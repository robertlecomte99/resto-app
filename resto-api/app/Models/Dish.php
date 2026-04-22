<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dish extends Model
{
    //
    use HasFactory; //--trait
    protected $fillable = ['name', 'description', 'price','image', 'type_plat'];

    public function orders() {
        return $this->hasMany(Order::class);
    }
    
}
