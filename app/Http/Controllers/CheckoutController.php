<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    /**
     * Create a Stripe Checkout Session from cart items.
     */
    public function createSession(Request $request): JsonResponse
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

                if ($price <= 0) {
                    continue; // Skip items with invalid pricing
                }

                $line_items[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'id' => $product['id'],
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

            $secret = env('STRIPE_SECRET');
            if (empty($secret)) {
                return response()->json(['error' => 'Stripe secret not configured'], 500);
            }

            $stripe = new StripeClient($secret);

            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => url('/checkout/success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/checkout/cancel'),
            ]);

            return response()->json(['url' => $session->url]);
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
