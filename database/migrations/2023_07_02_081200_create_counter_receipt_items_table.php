<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCounterReceiptItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counter_receipt_id')->constrained();
            $table->foreignId('counter_receipt_item_type_id')->constrained();
            $table->integer('amount_in_cents');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('counter_receipt_items');
    }
}
