<?php

namespace App\Models;   

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\order;

class CartProduct extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'product_id',
        'user_id',
        'quantitty',
        'price',
        'status',
    ];

    // This should be a belongsTo, not belongsToMany, since each CartProduct references one Product by product_id.
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function order(){
        return $this->hasOne(order::class);
    }
}
