<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeInvoiceFeeInvoiceItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_invoice_fee_invoice_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_invoice_id')->constrained('fee_invoices', 'id');
            $table->foreignId('fee_invoice_item_id')->constrained('fee_invoice_items', 'id');
            $table->unsignedBigInteger('set_amount_in_cent')->nullable();
            $table->unsignedBigInteger('set_tax_rate')->nullable();
            $table->unsignedBigInteger('set_gross_amount_in_cent')->nullable();
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
        Schema::dropIfExists('fee_invoice_fee_invoice_item');
    }
}
