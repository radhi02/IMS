<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumption', function (Blueprint $table) {
            $table->id();
            $table->text('code')->nullable();
            $table->integer('issue_id')->nullable();
            $table->integer('mo_id')->nullable();
            $table->text('consumptionnote')->nullable();
            $table->enum('status', ['Pending','Complete'])->nullable();
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
        Schema::dropIfExists('consumption');
    }
}
