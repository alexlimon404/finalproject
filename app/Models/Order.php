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

    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem');
    }

    public static function changeStatusForStoreUser ($user, $store, $order, $request)
    {
        if(Order::where('store_id', $store)->first()) {
            $order = Order::find($order);
            $order->status = $request->status;
            $order->save();
            return $order;
        }
        return abort(403, 'Вы можете редактировать только пользователей в своём магазине');
    }

    public static function changeStatusForStoreCustomer ($user, $store, $order, $request)
    {
        if(Order::where('user_id', $user->id)->first()) {
            $order = Order::find($order);
            $order->status = $request->status;
            $order->save();
            return $order;
        }
        return abort(403, 'Вы можете редактировать статус только в своём заказе');
    }


}
