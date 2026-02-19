<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Stripe\StripeClient;
use App\Models\CartProduct;

class CheckoutController extends Controller
{
    /**
     * Create a Stripe Checkout Session from cart items.
     */
    public function createSession(Request $request)
    {
        try {
            $items = $request->input('items', []);

            if (empty($items)) {
                return response()->json(['error' => 'No items provided'], 400);
            } 

            $line_items = [];

            foreach ($items as $item) {
                $price = isset($item['price']) ? (int) round(floatval($item['price']) * 100) : 0;
                $quantity = isset($item['quantitty']) ? (int) $item['quantitty'] : (int) ($item['quantity'] ?? 1);
                $product = $item['product'] ?? [];
                $name = $product['title'] ?? 'Product';
                $description = $product['description'] ?? null;
                $cartId = isset($item['cartId']) ? $item['cartId'] : 0;
                $userId = isset($item['userId']) ? $item['userId'] : 0;
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

            $stripe = new StripeClient($secret);

            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'], 
                'line_items' => $line_items,
                'mode' => 'payment',
                'metadata' => [
                    'cartIds' =>json_encode($cartId) 
                ], 
                'success_url' => url('/checkout/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/checkout/cancel'),
            ]); 

            // The setStatus method is being called but its return value is not used or returned, 
            // and it's likely this will not work as intended because setStatus expects to return a response.
            // Instead, you should return the result of setStatus or call it separately after session completion.
            // Example fix: return the result of setStatus:
            return $this->setStatus($session->id);
            // return response()->json(['url' => $session->url]);  
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
                    $payIntent = $stripe->paymentIntents->retrieve($items->payment_intent);
                    dd($items->payment_intent);
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
