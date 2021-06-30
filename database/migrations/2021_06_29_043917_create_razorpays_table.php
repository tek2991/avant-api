<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRazorpaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('razorpays', function (Blueprint $table) {
            $table->id();

            $table->string('order_id');
            $table->string('payment_id')->nullable();
            $table->string('signature')->nullable();
            $table->string('attempts');

            $table->unsignedBigInteger('amount_in_cent');
            $table->unsignedBigInteger('amount_paid_in_cent');
            $table->unsignedBigInteger('amount_due_in_cent');

            $table->string('currency');
            $table->string('order_status');
            $table->string('payment_status')->nullable();

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
        Schema::dropIfExists('razorpays');
    }
}
