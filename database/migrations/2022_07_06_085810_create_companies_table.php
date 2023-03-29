<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_code')->nullable();
            $table->string('company_name')->nullable();
            $table->string('reg_off_add')->nullable();
            $table->string('factory_add')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('cin_no')->nullable();
            $table->string('gst_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('otherdetails')->nullable();
            $table->string('logo')->nullable();
            $table->enum('status', ['Active', 'In-Active'])->default('Active');
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
        Schema::dropIfExists('companies');
    }
}
