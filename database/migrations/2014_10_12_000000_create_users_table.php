<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('comp_id')->nullable()->default(1);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->enum('user_status', ['Active', 'In-Active'])->default('Active');
            $table->integer('Role')->nullable();
            $table->string('Image')->nullable();
            $table->string('Phone')->nullable();
            $table->integer('Country')->nullable();
            $table->integer('State')->nullable();
            $table->integer('city')->nullable();
            $table->unsignedMediumInteger('pincode')->length(5);
            $table->text('Address')->nullable();
            $table->date('doj')->nullable();
            $table->string('emp_code')->nullable();
            $table->integer('Per_Country')->nullable();
            $table->integer('Per_State')->nullable();
            $table->integer('Per_city')->nullable();
            $table->unsignedMediumInteger('Per_pincode')->length(5);
            $table->text('Per_Address')->nullable();
            $table->integer('department_id')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Transgender']);
            $table->enum('marital_status', ['Married', 'Unmarried']);
            $table->string('bank_name')->nullable();
            $table->string('bank_swiftcode')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('acc_number')->nullable();
            $table->string('acc_name')->nullable();
            $table->string('acc_ifsccode')->nullable();
            $table->enum('blood_group', ['O+','O-','A+','A-','B+','B-','AB+','AB-'])->nullable();
            $table->string('user_PAN')->nullable();
            $table->string('user_ADHAR')->nullable();
            $table->rememberToken();    
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
        Schema::dropIfExists('users');
    }
}
