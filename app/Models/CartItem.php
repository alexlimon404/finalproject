<?php

namespace App\Models;

use App\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = [
        'user_id', 'item_id', 'amount'
    ];

    public $timestamps = false;

    public static function createCartItem($request, $item)
    {
        $cartItems = new CartItem();
        $cartItems->user_id = $request->user_id;
        $cartItems->item_id = $item;
        $cartItems->amount = $request->amount;
        $cartItems->save();
        return $cartItems;
    }

    public static function deleteItems($item)
    {
        $deletedRows = CartItem::where('item_id', $item)->delete();
        return $deletedRows;
    }

    public static function storeId($user)
    {
        $storeId = CartItem::where('user_id', $user->id)->first();
        return $storeId->store_id;
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

    public static function destroyCartItems($cartItems)
    {
        foreach ($cartItems as $cartItem) {
            CartItem::destroy([$cartItem->id]);
        }
    }

    public static function cartPrice (User $user)
    {
        $cartItems = CartItem::where('user_id', $user->id)->get();
        $price = 0;
        foreach ($cartItems as $cartItem) {
            $price += $cartItem->amount * $cartItem->item->getPrice();
        }
        return $price;
    }
}
