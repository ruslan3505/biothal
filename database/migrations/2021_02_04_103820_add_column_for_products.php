<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('ean');
            $table->integer('quantity');
            $table->integer('image_link')->nullable();
            $table->tinyInteger('status');
            $table->integer('minimum');
            $table->integer('sort_order');
            $table->integer('stock_status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('ean');
            $table->dropColumn('quantity');
            $table->dropColumn('image_link');
            $table->dropColumn('status');
            $table->dropColumn('minimum');
            $table->dropColumn('sort_order');
            $table->dropColumn('stock_status_id');
        });
    }
}
