<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawmaterialStockActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rawmaterial_stock_activity', function (Blueprint $table) {
            $table->id();
            $table->integer('raw_id')->nullable();
            $table->integer('issue_id')->nullable();
            $table->integer('stockIn')->nullable();
            $table->integer('stockOut')->nullable();
            $table->integer('oldstock')->nullable();
            $table->integer('newstock')->nullable();
            $table->integer('createdBy')->nullable();
            $table->dateTime('stock_date')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('rawmaterial_stock_activity');
    }
}
