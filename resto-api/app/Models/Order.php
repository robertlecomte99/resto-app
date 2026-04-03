<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['dish_id', 'status'];

    public function dish() {
        return $this->belongsTo(Dish::class);
    }
}
