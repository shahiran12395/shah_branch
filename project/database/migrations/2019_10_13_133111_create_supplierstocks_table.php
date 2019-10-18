<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierstocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplierstocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item')->nullable();
            $table->text('item_desc')->nullable();
            $table->integer('quantity_avail')->default(0);
            $table->string('item_img')->nullable();
            $table->string('item_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplierstocks');
    }
}
