<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialRequirementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_requirement', function (Blueprint $table) {
            $table->id();
            $table->integer('raw_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('requirement')->nullable();
            $table->integer('pending_po')->nullable();
            $table->integer('new_po')->nullable();
            $table->integer('wip')->nullable();
            $table->integer('fg')->nullable();
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
        Schema::dropIfExists('material_requirement');
    }
}
