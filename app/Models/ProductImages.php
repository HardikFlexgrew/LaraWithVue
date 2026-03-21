<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['product_id','path','is_default'])]
#[Hidden(['product_id'])]
class ProductImages extends Model
{
   public function product(){
    return $this->belongsTo(Product::class,'product_id');
   }

   protected $casts = [
    'is_default' => 'boolean'
   ];
}
