<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained();
            $table->foreignId('section_standard_id')->constrained('section_standard');
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('chapter_id')->nullable()->constrained();
            $table->string('name');
            $table->string('description');
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->dateTime('homework_from_date');
            $table->dateTime('homework_to_date');
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
        Schema::dropIfExists('homeworks');
    }
}
