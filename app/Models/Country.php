<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;

class Country extends Model
{
    use HasFactory, Notifiable;
    use HasRoles;
    //
    protected $fillable = [
        'country_name',
        'country_iso3',
    ];

    public function user(){
        return $this->hasMany(User::class);
    }
}
