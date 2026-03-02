<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Stripe\StripeClient;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\CartProduct;
use App\Models\Country;
use League\ISO3166\ISO3166; 
use App\Models\User;
use CountryState;
use App\Models\order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Create a Stripe Checkout Session from cart items.
     */
    public function __construct(){
        Stripe::setApiKey(env('STRIPE_SECRET') ?? config('services.stripe.secret'));
    }

    public function createSession(Request $request)
    {
        try {
            $iso3166 = new ISO3166();
            $items = $request->input('items', []);
            $User = $request->user()->load('country');
            $country = $iso3166->name(Country::getCountryName($items[0]['country']));
            $states = CountryState::getStates('IN');
            $state_code = array_search($items[0]['state'],$states);
            
            if (empty($items)) {
                return response()->json(['error' => 'No items provided'], 400);
            }

            if($User->stripe_id){
                \Stripe\Customer::update($User->stripe_id,[
                    'name' => $items[0]['name'],
                    'email' => $items[0]['email'],
                    'shipping' => [
                        'name' => $items[0]['name'],
                        'address' => [
                            'line1' => $items[0]['address'], // From your DB
                            'city' => $items[0]['city'],
                            'state' => $state_code, 
                            'country' => $country['alpha2'],
                            'postal_code' => $items[0]['postal_code']
                        ],
                    ],
                ]);
            }

            // dd($country['alpha2']);
            $stripeCustomerObject = $User->createOrGetStripeCustomer([
                'name' => $items[0]['name'],
                'email' => $items[0]['email'],
                'shipping' => [
                    'name' =>  $items[0]['name'],
                    'address' => [
                        'line1' => $items[0]['address'], // From your DB
                        'city' => $items[0]['city'],
                        'state' => $state_code, 
                        'country' => $country['alpha2'],
                        'postal_code' => $items[0]['postal_code']
                    ],
                ],
            ]);

            $line_items = [];

            foreach ($items as $item) {
                $price = isset($item['price']) ? calculate_product_amount_with_tax( $item['price'],$item['tax_rate'] ) : 0;
                $quantity = isset($item['quantitty']) ? (int) $item['quantitty'] : (int) ($item['quantity'] ?? 1);
                $product = $item['product'] ?? [];
                $name = $product['title'] ?? 'Product';
                $description = $product['description'] ?? null;
                $cartId = isset($item['cartId']) ? $item['cartId'] : 0;
                if ($price <= 0) {
                    continue; // Skip items with invalid pricing
                }

                $line_items[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $name,
                            'description' => $description,
                        ],
                        'unit_amount' => $price,
                    ],
                    'quantity' => max(1, $quantity),
                ];
            }

            if (empty($line_items)) {   
                return response()->json(['error' => 'No valid items for checkout'], 400);
            }

            $secret = env('STRIPE_SECRET') ?? config('services.stripe.secret');

            if (empty($secret)) {
                return response()->json(['error' => 'Stripe secret not configured'], 500);
            }   

            $session = $User->checkout($line_items,[
                'customer' => $User->stripe_id,
                'customer_update' => ['shipping' => 'auto','name' => 'auto'],
                'payment_method_types' => ['card','crypto'],
                'mode' => 'payment',
                'metadata' => [
                    'cartIds' =>json_encode($cartId) 
                ], 
                'shipping_address_collection' => [  
                    'allowed_countries' => [$country['alpha2']], // Example: 'IN' for India
                ],
                'saved_payment_method_options' => [
                    'payment_method_save' => 'enabled'
                ],
                'phone_number_collection' => [
                    'enabled' => true,
                ],
                'success_url' => url('/checkout?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/checkout/cancel'),
            ]); 

            return response()->json(['url' => $session->url]);  
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function setStatus($sessionId,Request $request){
        try{
            if(!empty($sessionId)){
                $secret = env('STRIPE_SECRET') ?? config('services.stripe.secret');
                if (empty($secret)) {
                    return response()->json(['error' => 'Stripe secret not configured'], 500);
                }

                $stripe = new StripeClient($secret);
                
                $items = $stripe->checkout->sessions->retrieve($sessionId);
                if($items){
                    $payIntent = $stripe->paymentIntents->retrieve(
                        $items->payment_intent,
                        ['expand' => ['payment_method']]
                    );
                    if($payIntent->status == "succeeded"){
                        $cartId = json_decode($items->metadata->cartIds);
                        $cartItems = CartProduct::whereIn('id',$cartId)->update(['status' => 2]);
                        if($cartItems > 0){
                            $data = [
                                'user_id' => $request->user()->id,
                                'cart_id' => $cartId,
                                'order_date' => now()->toDateString(),
                                'total_amount' => $payIntent->amount/100,
                                'currency' => $payIntent->currency,
                                'payment_details' => [
                                    "stripe_payment_intent_id" => $payIntent->id,
                                    "payment_method" => $payIntent->payment_method->type,
                                    "card_brand" => $payIntent->payment_method->card->brand,
                                    "card_last4" => $payIntent->payment_method->card->last4,
                                ],
                                'billing_address' => [
                                    'address' => $request->tempCart['address'],
                                    'city' =>    $request->tempCart['city'],
                                    "state" =>   $request->tempCart['state'],
                                    "country" => Country::getCountryName($request->tempCart['country']),
                                    "postal_code" => $request->tempCart['postalCode'],
                                    "contact_number" => $items->customer_details->phone   
                                ],
                                'payment_status' => $payIntent->status,
                            ];

                            $order = order::create($data); 

                            $orderItems = collect($request->item)->map(function($item) use ($order){
                                return [
                                    'order_id' => $order->id,
                                    'product_id' =>$item['productId'],
                                    'quantity' => $item['quantity'],
                                    'price' =>  $item['price'],
                                    'tax_amount' => $item['tax_amount'],
                                    "total" => (($item['price'] *  $item['quantity'])  + $item['tax_amount'])
                                ];
                            });

                            $orderItemInsert = $order->orderItems()->createMany($orderItems);

                            if($orderItemInsert){
                               return response()->json([
                                'success' => true,
                                'message' => 'Order placed successfully'
                               ]); 
                            }
                        }   
                    }
                } 

                return response()->json([
                    'success' => false,
                    'message' => 'Order can\'t placed due to some issues!!!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function get_order_product(){
        try{
            $order = order::where('user_id',Auth::user()->id)->where('payment_status','succeeded')->get(); 
            $orderItems = OrderItems::with(['order','product'])->whereIn('order_id',$order->pluck('id'))->get()->groupBy('order_id');
            if($orderItems){
                return response()->json([
                    'success' => true,
                    'orderItems' => $orderItems
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'ordered product not found'
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
            ],500);
        }
    }
}
