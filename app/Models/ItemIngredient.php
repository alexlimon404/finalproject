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

    public static function createItemIngredient($request, $store, $item)
    {
        $idIngredients = [];
        foreach ($request->ingredient as $ingredient){
            $itemIngredient =ItemIngredient::create([
                'store_id' => $store,
                'name' => $ingredient,
            ]);
            array_push($idIngredients, $itemIngredient->id);
            ItemIngredients::create([
                'item_id' => $item->id,
                'ingredient_id' => $itemIngredient->id
            ]);
        }
        return $idIngredients;
    }

}
