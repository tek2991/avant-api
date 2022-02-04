<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExamSubjectStateIdToExamSubjectScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_subject_scores', function (Blueprint $table) {
            $table->foreignId('exam_subject_state_id')->constrained('exam_subject_states', 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_subject_scores', function (Blueprint $table) {
            $table->dropForeign(['exam_subject_state_id']);
            $table->dropColumn('exam_subject_state_id');
        });
    }
}
