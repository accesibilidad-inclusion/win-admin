<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssistanceQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assistance_question', function (Blueprint $table) {
			$table->unsignedInteger('question_id');
			$table->foreign('question_id')->references('id')->on('questions');
			$table->unsignedInteger('assistance_id');
			$table->foreign('assistance_id')->references('id')->on('assistances');
			$table->unique(['question_id', 'assistance_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assistance_question');
    }
}
