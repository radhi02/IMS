<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejectedManufacturedOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_qc_activity', function (Blueprint $table) {
            $table->id();
            $table->integer('mo_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['Approve', 'Reject'])->nullable();
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
        Schema::dropIfExists('product_qc_activity');
    }
}
