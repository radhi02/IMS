<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('BName')->nullable();
            $table->string('BIFSC')->nullable();
            $table->string('BSWIFTCODE')->nullable();
            $table->string('Branch')->nullable();
            $table->string('Baccount')->nullable();
            $table->string('BMICR')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->integer('comp_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->auditableWithDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
