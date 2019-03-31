<?php

namespace App\Http\Controllers;

use App\Models\StoreUser;
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
        $storeUser = StoreUser::updateOrCreate(
            ['store_id' => $store, 'user_id' => $request->user_id],
            ['store_id' => $store, 'user_id' => $request->user_id]
        );
        dd($storeUser);
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
