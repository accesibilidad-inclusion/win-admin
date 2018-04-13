<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');

            // sujeto que contesta
            $table->unsignedInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects');

            // aplicación del cuestionario
            $table->unsignedInteger('survey_id');
            $table->foreign('survey_id')->references('id')->on('surveys');

            // alternativa seleccionada
            $table->unsignedInteger('option_id');
            $table->foreign('option_id')->references('id')->on('options');

            $table->unsignedInteger('question_id');
            $table->foreign('question_id')->references('id')->on('options');

            // sólo puede haber 1 respuesta de 1 sujeto a 1 alternativa en 1 aplicación
            // pueden haber más respuestas en aplicaciones (survey_id) distintas
			$table->unique(['subject_id', 'survey_id', 'question_id']);

            $table->enum('specification', ['home', 'outside', 'both'])->nullable();

            // tiempo de respuesta, en segundos
			$table->unsignedInteger('response_time')->nullable();
            $table->index('response_time');

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
        Schema::dropIfExists('answers');
    }
}
