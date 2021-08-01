<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChapterProgressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_progressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained();
            $table->foreignId('chapter_id')->constrained();
            $table->foreignId('section_id')->constrained();
            $table->foreignId('started_by')->constrained('teachers', 'id')->nullable();
            $table->foreignId('completed_by')->constrained('teachers', 'id')->nullable();
            $table->dateTime('complete_before_date');
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
        Schema::dropIfExists('chapter_progressions');
    }
}
