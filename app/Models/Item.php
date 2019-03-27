<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'store_id', 'name'
    ];

    public $timestamps = false;

    public function itemIngredients()
    {
        return $this->hasMany('App\Models\ItemIngredients');
    }

    public function getPrice()
    {
        $itemIngredients = $this->itemIngredients()->get();
        $sum = 0;
        foreach ($itemIngredients as $itemIngredient) {
            $ingredientAmount = $itemIngredient->amount;
            $ingredientPrice = $itemIngredient->itemIngredient->price;
            $sum += $ingredientAmount * $ingredientPrice;
        }
        return $sum;
    }
}
