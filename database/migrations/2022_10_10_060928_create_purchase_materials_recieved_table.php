<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseMaterialsRecievedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_materials_recieved', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_order_id')->nullable();
            $table->integer('purchase_order_material_id')->nullable();
            $table->integer('raw_material_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('received_quantity')->nullable();
            $table->dateTime('date')->nullable();
            $table->enum('status', ['Complete', 'Pending','In Progress'])->nullable();
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
        Schema::dropIfExists('purchase_materials_recieved');
    }
}
