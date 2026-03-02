<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CartProduct;
use App\Models\User;
use App\Models\OrderItems;

class order extends Model
{
    use SoftDeletes,HasFactory;

    protected $fillable = [
        'user_id',
        'cart_id',
        'order_date',
        'order_status',
        'total_amount',
        'currency',
        'payment_details', // store stripe_payment_intent_id,payment_method,payment_status,card_brand,card_last4
        'payment_status',
        'billing_address',
    ];

    public function cartProduct(){
        return $this->belongsTo(cartProduct::class,'cart_product_id');
    }

    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function orderItems(){
        return $this->hasMany(OrderItems::class);
    }

    protected $casts = [
        'order_date' => 'date',
        'payment_details' => 'object',
        'billing_address' => 'object',
        'cart_id' => 'object',
    ];
}
