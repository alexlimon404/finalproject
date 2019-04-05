<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthController extends TestCase
{
    /**
     * Start Test
     * .\vendor\bin\phpunit ./tests/Unit/Http/Controllers/AuthController.php
     */

    /**
     * 1. GET
     */
    public function test_RegisteredUser_WithUser_Success()
    {
        $response = $this->get("api/v1/auth/register?email=asd@asd.ru&password=123");
        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
    }
    public function test_RegisteredUser_WithUser_Double()
    {
        $response = $this->get('api/v1/auth/register?email=asd@asd.ru&password=123');
        $response->assertStatus(403);
        $response->assertJson(['message' => 'данный email занят']);
    }

    /**
     * 2. GET
     * */
    public function test_GetUser_WithUser_Success()
    {
        $response = $this->get("api/v1/auth/login?email=asd@asd.ru&password=123");
        $response->assertStatus(200);
        $response->assertJson(["success" => true]);
    }

    public function test_GetUser_WithoutUser_NotFound()
    {
        $response = $this->get("api/v1/auth/login?email=asd@asd.ru&password=1231");
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthorized, нет такой комбинации email & pass']);
    }
}
