<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Item;
use App\Models\ItemIngredient;
use App\Models\OrderItem;
use App\User;
use App\Models\Order;
use App\Models\ItemIngredients;
use Illuminate\Http\Request;
use App\Enums\UserType;
use App\Http\Transformers\Transformer;
use App\Support\OtherFunc;

class ManagerController extends Controller
{
    /**
     * 3.POST
     * */
    public function addItem (Request $request, $item)
    {
        CartItem::createCartItem($request, $item);
        return response()->json([
            "success" => true
        ]);
    }

    /**
     * 4.DELETE
     * */
    public function delItem($item)
    {
        CartItem::deleteItems($item);
        return response()->json([
            "success" => true
        ]);
    }

    /**
     * 5.POST
     * */

    public function makeOrder(Request $request)
    {
        $user = User::where('api_token', $request->api_token)->first();
        $cartItems = CartItem::where('user_id', $user->id)->get();
        $order = Order::create([
            'store_id' => CartItem::storeId($user),
            'user_id' => $user->id,
            'total_price' => CartItem::cartPrice($user)
        ]);
        OrderItem::makeOrderItems($order, $cartItems);
        CartItem::destroyCartItems($cartItems);
        return response()->json([
            "success" => true
        ]);
    }

    /**
     * 6.GET
     * */
    public function getMeInfo (Request $request)
    {
        $data = User::where('api_token', $request->get('api_token'))->first();
        return response()->json([
            "success" => true,
            "data" => [
                "id" => $data->id,
                "email" => $data->email,
                "created_at" => $data->get('created_at')
            ],
        ]);
    }

    /**
     * 7.GET
     * */
    //todo: сделать трансформер
    public function getMeOrders(Request $request)
    {
        $user = User::where('api_token', $request->get('api_token'))->first();
        $order = Order::where('user_id', $user->id)->where('status', $request->get('status'))->first();
        $items = CartItem::where('user_id', $user->id)->where('total_price', '<', 20)->where('total_price', '>', 0)->get();
        return response()->json([
            "success" => true,
            "data" => [
                "order" => [
                    "id" => $order->id,
                    "user_id" => $order->user_id,
                    "store_id" => $order->store_id,
                    "total_amount" => 123,
                    "items" =>  OtherFunc::paginate($items, $request->page)

                ]
            ],
        ]);
    }

}
