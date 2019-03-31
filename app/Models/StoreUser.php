<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreUser extends Model
{
    protected $table = 'store_users';

    protected $fillable = [
        'store_id', 'user_id'
    ];

    public $timestamps = false;
}
