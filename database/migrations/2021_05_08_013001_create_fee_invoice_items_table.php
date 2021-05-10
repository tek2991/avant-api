<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->foreignId('bill_id')->constrained();
            $table->foreignId('chargeable_id')->constrained();
            $table->unsignedBigInteger('amount_in_cent')->nullable();
            $table->unsignedBigInteger('tax_rate')->nullable();
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
        Schema::dropIfExists('fee_invoice_items');
    }
}
