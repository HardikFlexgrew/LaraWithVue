<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use APP\Models\User;

class Country extends Model
{
    //
    protected $fillable = [
        'country_name',
        'country_iso3',
    ];

    public function users(){
        return $this->hasMany(User::class);
    }    
}
