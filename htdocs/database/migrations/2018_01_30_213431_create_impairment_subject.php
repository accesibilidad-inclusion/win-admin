<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImpairmentSubject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impairment_subject', function (Blueprint $table) {
            $table->tinyInteger('impairment_id')->unsigned();
            $table->foreign('impairment_id')->references('id')->on('impairments');
            $table->unsignedInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->unique(['impairment_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('impairment_subject');
    }
}
