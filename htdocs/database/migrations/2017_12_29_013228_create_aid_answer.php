<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAidAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('aid_answer', function( Blueprint $table ){
			$table->bigIncrements('id');
			$table->unsignedInteger('aid_id');
			$table->foreign('aid_id')->references('id')->on('aids');
			$table->unsignedBigInteger('answer_id');
			$table->foreign('answer_id')->references('id')->on('answers');
			$table->unique(['aid_id', 'answer_id']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aid_answer');
    }
}
