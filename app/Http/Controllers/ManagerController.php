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
use App\Enums\StatusType;
use App\Http\Transformers\Transformer;
use App\Support\OtherFunc;

class ManagerController extends Controller
{
    /**
     * 3.POST
     * @param Request $request
     * @param $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function addItem (Request $request, $item)
    {
        $user = User::where('api_token', $request->api_token)->first();
        if(!Item::where('id', $item)->exists()) {
            return response(array(
                'message' => 'такого товара не существует',
            ), 403);
        }
        $itemCollection = Item::where('id', $item)->first();
        CartItem::createCartItem($request, $itemCollection, $user);
        return response()->json([
            "success" => true
        ]);
    }

    /**
     * 4.DELETE
     * @param $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function delItem(Request $request, $item)
    {
        $user = User::where('api_token', $request->api_token)->first();
        if(!CartItem::where('item_id', $item)->exists()) {
            return response(array(
                'message' => 'такого товара не существует',
            ), 403);
        }
        CartItem::deleteItems($user, $item);
        return response()->json([
            "success" => true
        ]);
    }

    /**
     * 5.POST
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * todo: сделать трансформер
     */
    public function getMeOrders(Request $request)
    {
        $user = User::where('api_token', $request->get('api_token'))->first();
        $order = Order::where('user_id', $user->id)->where('status', $request->get('status'))->first();
        $minTotalAmount = $request->min_total_amount ? : 9999999999;
        $maxTotalAmount = $request->max_total_amount ? : 0;
        $items = CartItem::where('user_id', $user->id)->
            where('amount', '<', $minTotalAmount)->
            where('amount', '>', $maxTotalAmount)->get();
        return response()->json([
            "success" => true,
            "data" => [
                "order" => [
                    "id" => $order->id,
                    "user_id" => $order->user_id,
                    "store_id" => $order->store_id,
                    "total_amount" => $items->sum('amount'),
                    "items" =>  OtherFunc::paginate($items, $request->page)
                ]
            ],
        ]);
    }

    /**
     * Store_user & Admin
     * 8.POST
     * @param Request $request
     * @param $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function addItemAndArrIngredients(Request $request, $store)
    {
        $item = Item::makeNewItem($request, $store);
        $idIngredients = ItemIngredient::createItemIngredient($request, $store, $item);
        return response()->json([
            "success" => true,
            "data" => [
                "item" => [
                    "id" => $item->id,
                    "store_id" => $store,
                    "name" => $request->name,
                    "ingredients" => ItemIngredient::find([$idIngredients])
                ]
            ],
        ]);
    }

    /**
     * 9.PATCH
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateItemAndIngredients (Request $request, $store, $item)
    {
        $item = Item::updateOrCreate(
            ['store_id' => $store, 'id' => $item],
            ['name' => $request->name]
        );
        $item->itemIngredients()->delete();
        $idIngredients = ItemIngredient::createItemIngredient($request, $store, $item);
        return response()->json([
            "success" => true,
            "data" => [
                "item" => [
                    "id" => $item->id,
                    "store_id" => $store,
                    "name" => $request->name,
                    "ingredients" => ItemIngredient::find([$idIngredients])
                ]
            ],
        ]);
    }

    /**
     * 10.DELETE
     * @param Request $request
     * @param $store
     * @param $item
     */
    public function deleteItemAndIngredients(Request $request, $store, $item)
    {
        $cartItem = CartItem::where('item_id', $item)->where('store_id', $store)->firstOrFail();
        if(!$cartItem){
            return response()->json([
                "success" => "Используется в cart_items id - $cartItem->id",
                "data" => [
                    "delete_item" => [
                        "id" => $cartItem->id,
                        "user_id" => $cartItem->user_id,
                        "item_id" => $cartItem->item_id,
                        "store_id" => $cartItem->store_id,
                    ]
                ],
            ]);
        }
        $deleteItem = Item::find($item);
        $deleteItem->itemIngredients()->delete();
        $deleteItem->delete();
        return response()->json([
            "success" => true,
            "data" => [
                "delete_item" => [
                    "id" => $cartItem->id,
                    "user_id" => $cartItem->user_id,
                    "item_id" => $cartItem->item_id,
                    "store_id" => $cartItem->store_id,
                ]
            ],
        ]);
    }

    /**
     * 11. GET
     * @param $store
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllOrderInStore($store, Request $request)
    {
        $minTotalAmount = $request->min_total_amount ? : 9999999999;
        $maxTotalAmount = $request->max_total_amount ? : 0;
        $orders = Order::where('store_id', $store)->
            where('total_price', '<', $minTotalAmount)->
            where('total_price', '>', $maxTotalAmount)->
            where('status', $request->status)->first();
        $orderItems = $orders->orderItems()->get();
        return response()->json([
            "success" => true,
            "data" => [
                "order" => [
                    "id" => $orders->id,
                    "user_id" => $orders->user_id,
                    "store_id" => $orders->store_id,
                    "total_price" => $orders->sum('total_price'),
                    "items" =>  OtherFunc::paginate($orderItems)
                ]
            ],
        ]);
    }

    /**
     * 12.
     * */
    public function updateStatusOrder(Request $request, $store, $order)
    {
        $user = User::where('api_token', $request->api_token)->firstOrFail();
        //StoreUser
        if ($user->role === UserType::StoreUser) {
            if ($request->status === StatusType::Canceled or
                $request->status === StatusType::Placed or
                $request->status === StatusType::Approved or
                $request->status === StatusType::Shipped){
                Order::changeStatusForOrderUser($user, $store, $order, $request);
                return response()->json([
                    "success" => true,
                    "message" => "id - $user->id c ролью '$user->role' поменял статус на $request->status"
                ]);
            }
            return abort(403, "У user id-> $user->id - $user->full_name нет прав менять статус этого заказа");
        }
        //Customer
        if ($user->role === UserType::Customer) {
            if ($request->status === StatusType::Shipped or
                $request->status === StatusType::Received){
                Order::changeStatusForOrderCustomer($user, $store, $order, $request);
                return response()->json([
                    "success" => true,
                    "message" => "id - $user->id c ролью '$user->role' поменял статус на $request->status"
                ]);
            }
            return abort(403, "У user id-> $user->id - $user->full_name нет прав менять статус этого заказа");
        }
        //Admin
        Order::changeStatusForOrderAdmin($user, $store, $order, $request);
        return response()->json([
            "success" => true,
            "message" => "id - $user->id c ролью '$user->role' поменял статус на $request->status"
        ]);
    }
}
