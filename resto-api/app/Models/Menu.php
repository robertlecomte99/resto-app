<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['menu_date', 'is_published', 'order_deadline']; 
    
    public function dishes() {
        return $this->belongsToMany(Dish::class, 'menu_dish')
                        ->withPivot('sort_order')
                        ->orderByPivot('sort_order', 'asc');    }
}
