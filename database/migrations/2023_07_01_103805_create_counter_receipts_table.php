<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCounterReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_receipts', function (Blueprint $table) {
            // ID starts from 10001
            $table->id()->startingValue(10001); // Receipt Number
            
            $table->foreignId('student_id')->constrained();
            $table->foreignId('standard_id')->constrained();
            $table->string('remarks')->nullable();

            $table->string('payment_mode')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('cheque_date')->nullable();
            $table->string('bank_name')->nullable();

            $table->string('created_by')->nullable();

            $table->boolean('completed')->default(false);
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
        Schema::dropIfExists('counter_receipts');
    }
}
