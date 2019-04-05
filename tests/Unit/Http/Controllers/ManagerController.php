<?php

namespace Tests\Unit\Http\Controllers;

use Tests\Support\FunctionsForTest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagerController extends TestCase
{
    /**
     * Start Test
     * .\vendor\bin\phpunit ./tests/Unit/Http/Controllers/ManagerController.php
     * */
    const API_TOKEN = 'zyiyVhWjZy';

    /**
     * 3. POST
     * */
    public function test_AddItem_WithItem_Success()
    {
    $user = FunctionsForTest::createUser(self::API_TOKEN);
    $store = FunctionsForTest::createStore();
    $item = FunctionsForTest::createItem($store);
    $response = $this->post("http://finalproject/api/v1/cart/items/$item->id?api_token=zyiyVhWjZy&amount=213");
    $response->assertStatus(200);
    $response->assertJson(["success" => true]);
    FunctionsForTest::deleteUser($user);
    FunctionsForTest::deleteStore($store);
    }

    public function test_AddItem_WithoutItem_NotFound()
    {
        $fakeItem = 234;
        $user = FunctionsForTest::createUser(self::API_TOKEN);
        $response = $this->post("http://finalproject/api/v1/cart/items/$fakeItem?api_token=zyiyVhWjZy");
        $response->assertStatus(404);
        $response->assertJson(['message' => 'такого товара не существует']);
        FunctionsForTest::deleteUser($user);
    }

    /**
     * 4. DELETE
     * */
    public function test_DelItem_WithCartItem_Success()
    {
        $user = FunctionsForTest::createUser(self::API_TOKEN);
        $store = FunctionsForTest::createStore();
        $item = FunctionsForTest::createItem($store);
        $cartItem = FunctionsForTest::createCartItem($user, $store, $item);

        $response = $this->delete("http://finalproject/api/v1/cart/item/$item->id?api_token=zyiyVhWjZy&amount=213");
        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
        FunctionsForTest::deleteUser($user);
        FunctionsForTest::deleteStore($store);
    }

    public function test_DelItem_WithoutItem_Success()
    {
        $fakeItem = 234;
        $user = FunctionsForTest::createUser(self::API_TOKEN);
        $response = $this->delete("http://finalproject/api/v1/cart/item/$fakeItem?api_token=zyiyVhWjZy&amount=213");
        $response->assertStatus(404);
        $response->assertJson(['message' => 'такого товара нет в корзине']);

    }
}
