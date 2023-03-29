<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_products', function (Blueprint $table) {
            $table->id();
            $table->integer('sales_order_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('base_price')->nullable();
            $table->string('base_total')->nullable();
            $table->enum('status', ['Complete', 'Processing', 'Cancelled', 'Refunded', 'Returned'])->nullable();
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
        Schema::dropIfExists('sales_order_items');
    }
}
