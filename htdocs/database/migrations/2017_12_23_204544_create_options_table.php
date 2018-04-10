<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
			$table->enum('type', ['yes', 'no']);
			$table->string('label');
            $table->unsignedTinyInteger('order');
            $table->unsignedTinyInteger('value');
			$table->unsignedInteger('question_id')->nullable();
			$table->foreign('question_id')->references('id')->on('questions');
			$table->index('type');
            $table->index('order');
            $table->index('value');
			$table->index('question_id');
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
        Schema::dropIfExists('options');
    }
}
