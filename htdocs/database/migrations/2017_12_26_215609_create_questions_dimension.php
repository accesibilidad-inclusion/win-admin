<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsDimension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('questions', function (Blueprint $table) {
			$table->unsignedInteger('dimension_id')->nullable()->index();
			$table->foreign('dimension_id')->references('id')->on('dimensions');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('questions', function (Blueprint $table) {
			$table->dropColumn('dimension_id');
			$table->dropForeign('questions_dimension_id_foreign');
		});
    }
}
