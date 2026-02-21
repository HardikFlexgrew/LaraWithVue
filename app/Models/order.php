<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CartProduct;

class order extends Model
{
    use SoftDeletes,HasFactory;

    protected $fillable = [
        'cart_id',
        'order_date',
        'order_status',
        'total_amount',
        'currency',
        'payment_details', // store stripe_payment_intent_id,payment_method,payment_status,card_brand,card_last4
        'billing_address',
    ];

    public function cartProduct(){
        return $this->belongsTo(cartProduct::class,'cart_id');
    }

    protected $casts = [
        'order_date' => 'date',
        'payment_details' => 'object',
    ];
}
