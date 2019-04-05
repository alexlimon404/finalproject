<?php

namespace Tests\Support;

use App\Models\CartItem;
use App\Models\Item;
use App\Models\Store;
use App\User;
use Illuminate\Support\Facades\Hash;

class FunctionsForTest
{
    public static function createUser($apiToken)
    {
        $user = new User();
        $user->full_name = 'ivan';
        $user->email = 'asd@asd.ru';
        $user->password = Hash::make(123);
        $user->api_token = $apiToken;
        $user->role = 'Admin';
        $user->save();
        return $user;
    }

    public static function deleteUser($user)
    {
        $user = User::find($user->id);
        $user->delete();
    }

    public static function createStore()
    {
        $store = new Store();
        $store->name = 'Кафе';
        $store->save();
        return $store;
    }

    public static function deleteStore($store)
    {
        $store = Store::find($store->id);
        $store->delete();
    }

    public static function createItem ($store)
    {
        $item = new Item();
        $item->store_id = $store->id;
        $item->name = 'Супчик';
        $item->save();
        return $item;
    }

    public static function deleteItem($item)
    {
        $item = Item::find($item->id);
        $item->delete();
    }

    public static function createCartItem($user, $store, $item)
    {
        $cartItem = new CartItem();
        $cartItem->user_id = $user->id;
        $cartItem->item_id = $item->id;
        $cartItem->store_id = $store->id;
        $cartItem->save();
        return $cartItem;
    }

    public static function deleteCartItem ($cartItem)
    {
        $cartItem = CartItem::find($cartItem->id);
        $cartItem->delete();
    }



}