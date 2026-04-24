<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['menu_date', 'is_published'];
    public function dishes() {
        return $this->belongsToMany(Dish::class, 'menu_dish', 'menu_id', 'dish_id');
    }
}
