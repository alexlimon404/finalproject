<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemIngredients extends Model
{
    protected $table = 'item_ingredients';

    protected $fillable = [
        'item_id', 'ingredient_id', 'amount'
    ];

    public $timestamps = false;

    public function itemIngredient()
    {
        return $this->belongsTo('App\Models\ItemIngredient', 'ingredient_id');
    }


}
