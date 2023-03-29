<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_materials', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_order_id')->nullable();
            $table->integer('raw_material_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('tax_percentage')->nullable();
            $table->integer('remained_quantity')->nullable();
            $table->string('base_price')->nullable()->comment('Material amount');
            $table->string('base_tax')->nullable()->comment('Tax amount');
            $table->string('base_subtotal')->nullable()->comment('Total amount without tax');
            $table->string('base_total')->nullable()->comment('Total amount with tax');
            $table->enum('status', ['Complete', 'Pending'])->nullable();
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
        Schema::dropIfExists('purchase_order_materials');
    }
}
