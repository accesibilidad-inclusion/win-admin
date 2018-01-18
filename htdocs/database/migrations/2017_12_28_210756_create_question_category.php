<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('questions', function (Blueprint $table) {
			$table->unsignedInteger('category_id')->nullable();
			$table->foreign('category_id')->references('id')->on('categories');
			$table->index('category_id');
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
			$table->dropColumn('category_id');
			$table->dropIndex('category_id');
			$table->dropForeign('questions_category_id_foreign');
		});
    }
}
