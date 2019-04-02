<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreUser;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * 13.POST
     * @param Request $request
     * @param $store
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStoreUser(Request $request, $store)
    {
        if(!Store::where('id', $store)->exists()) {
            return response(array(
                'message' => "Магазина с id - $store не существует",
            ), 403);
        }
        if(!User::where('id', $request->user_id)->exists()){
            return response(array(
                'message' => "Пользователя с id $request->user_id не существует",
            ), 403);
        }
        StoreUser::updateOrCreate(
            ['store_id' => $store, 'user_id' => $request->user_id],
            ['store_id' => $store, 'user_id' => $request->user_id]
        );
        return response()->json([
            "success" => true,
        ]);
    }

    /**
     * 14.DELETE
     * @param $store
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function delStoreUser($store, $user)
    {
        $storeUser = StoreUser::where('store_id', $store)->where('user_id', $user)->firstOrFail();
        $storeUser->delete();
        return response()->json([
            "success" => true,
        ]);
    }
}
