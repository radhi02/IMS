<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_material', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('material_category_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->text('code')->nullable();
            $table->text('HSN_CODE')->nullable();
            $table->text('GST')->nullable();
            $table->enum('status', ['Active', 'In-Active'])->default('Active');
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
        Schema::dropIfExists('raw_material');
    }
}
