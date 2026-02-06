<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartProduct;
use \Illuminate\Database\QueryException as queryException;
use Illuminate\Support\Facades\Auth;

class productController extends Controller
{
    public function show() {
        try{
            $data = Product::orderBy('id','desc')->get();
            return response()->json([
                'success' => true,
                'products' => $data
            ]);
        } catch(err){
            return response()->json([
                'success' => false,
                'message' => err,
            ]);
        }
    }

    public function create(Request $request){
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
        ],[
            'title.required' => 'Title is required',
            'price.required' => 'Price is required',
            'image.required' => 'Image is required'
        ]);

        try {
            
            $imagePath = null;
            
            if($request->hasFile('image')){
                $file = $request->file('image');
                $imagePath = $file->store('product','public'); 
            }

            $Product = Product::create([
                "title" => $request->title,
                "description" => $request->description,
                "price" => $request->price,
                "image" => $imagePath ?? null,
            ]);

            if($Product){
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully',
                    'data' => [
                        'id' => $Product->id,
                        'title' => $Product->title,
                        'description' => $Product->description,
                        'price' => $Product->price,
                        'image' => $Product->image
                    ],
                ], 201);
            }
        } catch(queryException $e)  {
            return response()->json([
                'success' => false,
                'message' => 'Insert encountered errors: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($productId){
    
        $data = Product::find($productId);

        return response()->json([
            "success" => true,
            'product' => $data
        ]);
        
    }

    public function update(Request $request, $productId)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'string',
                'price' => 'required|numeric|min:0',
            ], [
                'title' => 'Title is required',
                'price' => 'Price is required',
            ]);

            $imagePath = null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imagePath = $file->store('product', 'public');
                // dd($imagePath);

                $updatedProduct = Product::updateOrCreate(
                    ['id' => $productId],
                    [
                        'title' => $request->title,
                        'description' => $request->description,
                        'price' => $request->price,
                        'image' => $imagePath ?? null,
                    ]
                );
            } else {
                $updatedProduct = Product::updateOrCreate(
                    ['id' => $productId],
                    [
                        'title' => $request->title,
                        'description' => $request->description,
                        'price' => $request->price,
                    ]
                );
            }

            if ($updatedProduct) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully!!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not updated!!'
                ]);
            }

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update encountered errors : ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($productId){
      
        $product = Product::find($productId);
        $product->delete();

        if($product->trashed()){
            return response()->json([
                'success' => true,
                'message' => 'Product Deleted!!'
            ]);
        } else {
            return response()->json([
                "success" => false,
                'message' => 'Problem in delete'
            ]); 
        }
        
    }

    public function addToCart($productId){
        $productPrice = Product::find($productId);
        $checkExist = CartProduct::with('product')->where('product_id', $productId)
            ->where('user_id', Auth::user()->id)
            ->where('price',$productPrice->price)
            ->first();

        if ($checkExist) {
            $checkExist->quantitty = $checkExist->quantitty + 1;
            $checkExist->save();
            $cartProduct = $checkExist; 
        } else {
            $cartProduct = CartProduct::create([
                'product_id' => $productId,
                'user_id' => Auth::user()->id, 
                'quantitty' => 1, 
                'price' => $productPrice->price,
            ]);
        }

        if($cartProduct){
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_details' => $cartProduct,
                'cart_product' => $cartProduct->product,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart.'
            ], 500);
        }
        
    }

    public function showCartProduct(){
        $data = CartProduct::with('product')->orderBy('id','desc')->where('user_id', Auth::user()->id)->get()->toArray();
        return response()->json([
            'success' => true,
            'products' => $data
        ]);
    }

    public function removeCartProduct($cartId){
        try {
            $cartProduct = CartProduct::find($cartId);
            $cartProduct->delete();
            if($cartProduct->trashed()){
                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from the cart'
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Remove encountered errors : ' . $e->getMessage()
            ], 500);
        }
    }

    public function cartOperation(Request $request,$productId){
        
        $cartProduct = CartProduct::find($productId);
        $cartProduct->quantitty = $request->quantitty ;
        $cartProduct->save();
            
    }
}
