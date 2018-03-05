<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionScriptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_script', function (Blueprint $table) {
            $table->unsignedInteger('script_id');
            $table->foreign('script_id')->references('id')->on('scripts');
            $table->unsignedInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->unique(['script_id', 'question_id']);
            $table->unsignedInteger('order')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_script');
    }
}
