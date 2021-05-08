<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->foreignId('session_id')->constrained();
            $table->date('bill_from_date');
            $table->date('bill_to_date');
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
        Schema::dropIfExists('bills');
    }
}
