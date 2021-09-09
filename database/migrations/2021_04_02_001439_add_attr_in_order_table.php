<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttrInOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->integer('import_status')->default(0);
            $table->integer('global_sale_id')->nullable();
            $table->boolean('with_global_sales')->default(0);
            $table->float('total_sum' , 10,2)->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('total_sum');
            $table->dropColumn('global_sale_id');
            $table->dropColumn('with_global_sales');
            $table->dropColumn('import_status');
        });
    }
}
