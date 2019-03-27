<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'password', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function createUser($request)
    {
        $user = new User();
        $user->full_name = $request->full_name ? : 'name - ' . Str::random(3);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role ? : 'Customer';
        $user->api_token = Str::random('10');
        $user->save();
        return $user;
    }

    public function cartItems()
    {
        return $this->hasMany('App\Models\CartItem');
    }

    public function itemsIngredients()
    {
        return $this->belongsToMany('App\Models\ItemIngredients');
    }

}
