<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRazorpayLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('razorpay_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fee_invoice_id')->constrained('fee_invoices');
            $table->string('order_id');
            $table->string('event');
            $table->longText('payload');


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
        Schema::dropIfExists('razorpay_logs');
    }
}
