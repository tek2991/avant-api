<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_question_id')->constrained('exam_questions');
            $table->foreignId('user_id')->constrained();
            $table->longText('description')->nullable();
            $table->foreignId('exam_answer_state_id')->constrained('exam_answer_states');
            $table->decimal('marks_secured', $precision = 6, $scale = 2);
            $table->foreignId('evaluated_by')->constrained('users', 'id');
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
        Schema::dropIfExists('exam_answers');
    }
}
