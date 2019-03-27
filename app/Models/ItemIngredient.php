<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemIngredient extends Model
{
    protected $table = 'item_ingredient';

    protected $fillable = [
        'store_id', 'name', 'price'
    ];

    public $timestamps = false;

}
