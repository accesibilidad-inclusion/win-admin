<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('personal_id', 32)->nullable()->index();
            $table->string('given_name')->nullable();
            $table->string('family_name')->nullable();
            $table->enum('sex', ['female', 'male', 'other'])->nullable();
            $table->datetime('consent_at')->nullable();
            $table->date('birthday')->nullable();
            $table->boolean('works')->default( false )->nullable();
            $table->string('works_at')->nullable();
            $table->boolean('studies')->default( false )->nullable();
            $table->string('studies_at')->nullable();
            $table->datetime('last_connection_at')->nullable();
            $table->softDeletes();
            $table->index(['given_name', 'family_name']);
            $table->index(['works', 'studies']);
            $table->index('birthday');
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
        Schema::dropIfExists('subjects');
    }
}
