<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->string('phone');
            $table->string('phone_alternate')->nullable();
            $table->date('dob')->nullable();
            $table->foreignId('gender_id')->constrained();
            $table->foreignId('blood_group_id')->constrained('blood_groups');
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('address')->nullable();
            $table->string('pincode')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('dl_no')->nullable();
            $table->string('voter_id')->nullable();
            $table->string('passport_no')->nullable();
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
        Schema::dropIfExists('user_details');
    }
}
