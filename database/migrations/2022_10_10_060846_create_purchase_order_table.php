<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('vendor_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('description')->nullable();
            $table->string('igst')->default('0');
            $table->string('sgst')->default('0');
            $table->string('cgst')->default('0');
            $table->string('base_subtotal')->nullable()->comment('Total amount without tax');
            $table->string('base_tax_amount')->nullable()->comment('Tax amount');
            $table->string('base_grandtotal')->nullable()->comment('Total amount with tax');
            $table->integer('created_by')->nullable();
            $table->enum('status', ['Pending', 'Complete', 'Partial Recieve', 'Cancelled', 'Refunded', 'Returned'])->nullable();
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
        Schema::dropIfExists('purchase_order');
    }
}
