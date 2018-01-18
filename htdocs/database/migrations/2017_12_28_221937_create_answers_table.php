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
			$table->unsignedInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->unsignedInteger('option_id');
			$table->enum('specification', ['home', 'outside', 'always']);
			$table->foreign('option_id')->references('id')->on('options');
			$table->unsignedInteger('response_time')->nullable();
			$table->unique(['user_id', 'option_id']);
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
