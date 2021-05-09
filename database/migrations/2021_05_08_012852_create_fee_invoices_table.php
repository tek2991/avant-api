<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('bill_fee_id')->constrained('bill_fee');
            $table->foreignId('standard_id')->constrained();
            $table->unsignedBigInteger('amount_in_cent')->nullable();
            $table->unsignedBigInteger('tax_in_cent')->nullable();
            $table->unsignedBigInteger('gross_amount_in_cent')->nullable();
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
        Schema::dropIfExists('fee_invoices');
    }
}
