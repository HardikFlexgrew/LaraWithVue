<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Stripe\StripeClient;
use App\Models\CartProduct;
use League\ISO3166\ISO3166; 
use App\Models\User;
use CountryState;

class CheckoutController extends Controller
{
    /**
     * Create a Stripe Checkout Session from cart items.
     */
    public function createSession(Request $request)
    {
        try {
            $iso3166 = new ISO3166();
            $items = $request->input('items', []);
            $User = $request->user()->load('country');
            $country = $iso3166->alpha3($User->country->country_iso3);
            $states = CountryState::getStates('IN');
            $state_code = array_search($User->state,$states);

            if (empty($items)) {
                return response()->json(['error' => 'No items provided'], 400);
            }
            
            $User->createOrGetStripeCustomer([
                'name' => $User->name,
                'email' => $User->email,
                'shipping' => [
                    'name' => $User->name,
                    'address' => [
                        'line1' => $User->address, // From your DB
                        'city' => $User->city,
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

    public function setStatus($sessionId){
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
                    dd($payIntent,$items); 
                    if($payIntent->status == "succeeded"){
                        $cartId = json_decode($items->metadata->cartIds);
                        $cartItems = CartProduct::whereIn('id',$cartId)->update(['status' => 2]);
                        if($cartItems > 0){
                            
                        }
                    }
                } 

                return response()->json([
                    'success' => true,
                    'orderedItem' => $payIntent
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
