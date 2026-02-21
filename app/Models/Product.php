<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'title',
        'description',
        'price',
        'image',
    ];

    // The relationship should be hasMany. Each Product can have multiple CartProduct entries (for different users/carts), and CartProduct has a product_id (foreign key).
    public function cartProducts(){
        return $this->hasMany(CartProduct::class, 'product_id','id');
    }

    public function orderItem(){
        return $this->hasMany(OrderItems::class,'product_id','id');
    }
}
