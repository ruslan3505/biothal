<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RecreatedStockStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_status', function (Blueprint $table) {
            $table->string('name', 255);
            $table->integer('stock_status_id')->unsigned();
//            $table->foreign('stock_status_id')->references('stock_status_id')->on('products')->onDelete('cascade');
        });
        DB::statement("ALTER TABLE stock_status ADD PRIMARY KEY (stock_status_id)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::statement("DROP TABLE stock_status");
        Schema::dropIfExists('stock_status');
    }
}
