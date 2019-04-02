<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Return_;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'store_id', 'user_id', 'status', 'total_price'
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem');
    }

    /**
     * @param $user
     * @param $store
     * @param $order
     * @param $request
     */
    public static function changeStatusForOrderUser ($user, $store, $order, $request)
    {
        if(Order::where('store_id', $store)->first()) {
            $order = Order::find($order);
            $order->status = $request->status;
            $order->save();
            return $order;
        }
        return abort(403, 'Вы можете редактировать только пользователей в своём магазине');
    }

    /**
     * @param $user
     * @param $store
     * @param $order
     * @param $request
     */
    public static function changeStatusForOrderCustomer ($user, $store, $order, $request)
    {
        if(Order::where('user_id', $user->id)->first()) {
            $order = Order::find($order);
            $order->status = $request->status;
            $order->save();
            return $order;
        }
        return abort(403, 'Вы можете редактировать статус только в своём заказе');
    }

    /**
     * @param $user
     * @param $store
     * @param $order
     * @param $request
     */
    public static function changeStatusForOrderAdmin ($user, $store, $order, $request)
    {
        if(Order::where('id', $order)->where('store_id', $store)->exists()) {
            $order = Order::find($order);
            $order->status = $request->status;
            $order->save();
            return $order;
        }
        return abort(403, "Такого заказа с id - $order и магазин с id - $store не существует");
    }
}
