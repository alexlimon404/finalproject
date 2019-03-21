<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->nullable(true)->unsigned();
            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade');
            $table->integer('user_id')->nullable(true)->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->enum('status', ['Canceled', 'Placed', 'Approved', 'Shipped', 'Received'])->default('Placed');
            $table->float('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
