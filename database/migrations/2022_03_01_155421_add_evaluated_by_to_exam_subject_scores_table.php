<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvaluatedByToExamSubjectScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_subject_scores', function (Blueprint $table) {
            $table->foreignId('evaluated_by')->nullable()->constrained('users');
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
            $table->dropForeign(['evaluated_by']);
            $table->dropColumn('evaluated_by');
        });
    }
}
