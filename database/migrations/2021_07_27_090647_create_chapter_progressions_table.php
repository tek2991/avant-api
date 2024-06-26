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
            $table->foreignId('started_by')->nullable()->constrained('teachers', 'id');
            $table->foreignId('completed_by')->nullable()->constrained('teachers', 'id');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('complete_before');
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
