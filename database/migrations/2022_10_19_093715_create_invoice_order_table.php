<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_order', function (Blueprint $table) {
            $table->id();
            $table->integer('so_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('code')->nullable();
            $table->text('order_products')->nullable();
            $table->string('igst')->default('0');
            $table->string('sgst')->default('0');
            $table->string('cgst')->default('0');
            $table->string('base_grandtotal')->nullable()->comment('Total amount with tax');
            $table->string('base_subtotal')->nullable()->comment('Total amount without tax');
            $table->string('base_tax_amount')->nullable()->comment('Tax amount');
            $table->string('due_amount')->nullable()->comment('Due amount');
            $table->string('receivable_amount')->nullable()->comment('Total amount');
            $table->string('base_total_quantity')->nullable();
            $table->string('base_total_rate')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->enum('status', ['Open','Partial Paid','Overdue','Close'])->nullable();
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
        Schema::dropIfExists('invoice_order');
    }
}
