<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemIngredients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->nullable(true)->unsigned();
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
            $table->integer('ingredient_id')->nullable(true)->unsigned();
            $table->foreign('ingredient_id')
                ->references('id')
                ->on('item_ingredient')
                ->onDelete('cascade');
            $table->float('amount');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_ingredients');
    }
}
