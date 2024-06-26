<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('exam_schedule_id')->nullable()->constrained('exam_schedules');
            $table->integer('full_mark');
            $table->integer('pass_mark');
            $table->integer('negative_percentage');
            $table->foreignId('exam_subject_state_id')->constrained('exam_subject_states');
            $table->boolean('auto_start');
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
        Schema::dropIfExists('exam_subject');
    }
}
