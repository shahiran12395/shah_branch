<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillplzToGeneralsettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('generalsettings', function($table) {
//            $table->string('billplz_key');
//            $table->string('billplz_x_signature');
//            $table->string('billplz_callback_url');
//            $table->string('billplz_mode');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('generalsettings', function($table) {
//            $table->dropColumn('billplz_key');
//            $table->dropColumn('billplz_x_signature');
//            $table->dropColumn('billplz_callback_url');
//            $table->dropColumn('billplz_mode');
//        });
    }
}
