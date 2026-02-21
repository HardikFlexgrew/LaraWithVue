<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\order;
use App\Models\Product;

class OrderItems extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'tax_amount',
        'total'
    ];

    public function order(){
        return $this->belongsTo(order::class,'order_id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

}
