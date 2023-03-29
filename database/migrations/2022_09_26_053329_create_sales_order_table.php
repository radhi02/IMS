<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->dateTime('order_date')->nullable();
            $table->text('description')->nullable();
            $table->text('order_products')->nullable();
            $table->text('delivery_mode')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('igst')->default('0');
            $table->string('sgst')->default('0');
            $table->string('cgst')->default('0');
            $table->string('base_grandtotal')->nullable()->comment('Total amount with tax');
            $table->string('base_subtotal')->nullable()->comment('Total amount without tax');
            $table->string('base_tax_amount')->nullable()->comment('Tax amount');
            $table->string('base_total_quantity')->nullable();
            $table->string('base_total_rate')->nullable();
            $table->string('code')->nullable();
            $table->integer('created_by')->nullable();
            $table->enum('status', ['Pending','Approve','In Manufacturing','Ready to be Invoiced','Closed'])->nullable();
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
        Schema::dropIfExists('sales_order');
    }
}
