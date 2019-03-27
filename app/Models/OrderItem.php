<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id', 'item_id', 'amount'
    ];

    public $timestamps = false;

    public static function makeOrderItems ($order, $cartItems)
    {
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cartItem->item_id,
                'amount' => $cartItem->amount
            ]);
        }
    }
}
