<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['dish_id','menu_id','user_id', 'status'];

    public function dish() {
        return $this->belongsTo(Dish::class);
    }

    public function menu() {
        return $this->belongsTo(Menu::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
