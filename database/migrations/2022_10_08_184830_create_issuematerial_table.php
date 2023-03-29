<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuematerialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuematerial', function (Blueprint $table) {
            $table->id();
            $table->text('code')->nullable();
            $table->integer('dn_id')->nullable();
            $table->integer('mo_id')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->text('materialnote')->nullable();
            $table->enum('status', ['Pending', 'Complete'])->nullable();
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
        Schema::dropIfExists('issuematerial');
    }
}
