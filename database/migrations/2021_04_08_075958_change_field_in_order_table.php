<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldInOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->renameColumn('with_global_sales', 'with_sales');
            $table->renameColumn('global_sale_id', 'sale_id');
            $table->integer('sale_type')->nullable();
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
            $table->renameColumn('with_sales', 'with_global_sales');
            $table->renameColumn('sale_id', 'global_sale_id');
            $table->dropColumn('sale_type');
        });
    }
}
