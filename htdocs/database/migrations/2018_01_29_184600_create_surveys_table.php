<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->integer('event_id')->unsigned()->nullable();
            $table->foreign('event_id')->references('id')->on('events');

            $table->unsignedInteger('script_id');
            $table->foreign('script_id')->references('id')->on('scripts');

            // identificador del cliente del sujeto
            $table->string('hash')->unique();
            // ha completado todas las preguntas del guión?
            $table->boolean('is_completed')->default( false )->index();
            $table->datetime('last_answer_at')->nullable()->index();
            $table->softDeletes();
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
        Schema::dropIfExists('surveys');
    }
}
