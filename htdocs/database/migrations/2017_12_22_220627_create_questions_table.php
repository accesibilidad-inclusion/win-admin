<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
			$table->string('formulation')->index();
			$table->boolean('needs_specification')->default( false )->index();
			$table->string('specification')->nullable();
			$table->smallInteger('order')->default( 100 );
            // visible, hidden, review, deleted(?)...
			$table->string('status', 64)->default( 'review' );
			$table->index('order');
			$table->index('status');
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
        Schema::dropIfExists('questions');
    }
}
