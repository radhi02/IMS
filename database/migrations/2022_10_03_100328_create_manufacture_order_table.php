<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufactureOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacture_order', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('uniqueid')->nullable();
            $table->text('bom_detail')->nullable();
            $table->integer('sales_order_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('approved_quantity')->nullable();
            $table->integer('rejected_quantity')->nullable();
            $table->integer('check_quantity')->nullable(); 
            $table->enum('status', ['Pending','Open','Indemand','Redemand','Inissue','WIP','Finish','QCPending','Instore','Inprocess'])->default('Pending');
            $table->enum('demand_status', ['Not Started','In Progress','Completed'])->nullable();
            $table->enum('issue_status', ['Not Started','In Progress','Completed'])->nullable();
            $table->enum('consume_status', ['Not Started','In Progress','Completed'])->nullable();
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
        Schema::dropIfExists('manufacture_order');
    }
}
