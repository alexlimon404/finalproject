<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'store_id', 'user_id', 'status', 'total_price'
    ];

    public $timestamps = false;
}
