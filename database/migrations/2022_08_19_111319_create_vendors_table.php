<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->string('vendor_code')->nullable();
            $table->string('vendor_contactperson')->nullable();
            $table->string('vendor_email')->nullable();
            $table->string('vendor_phone')->nullable();
            $table->string('Location')->nullable();
            $table->string('vendor_street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->unsignedMediumInteger('vendor_zipcode')->nullable();
            $table->string('vendor_GST')->nullable();
            $table->string('vendor_PAN')->nullable();
            $table->string('vendor_type')->nullable();
            $table->boolean('vendor_MSME');
            $table->string('vendor_MSME_number')->nullable();
            $table->enum('vendor_status', ['Active', 'In-Active'])->default('Active');
            $table->unsignedBigInteger('company_id')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
