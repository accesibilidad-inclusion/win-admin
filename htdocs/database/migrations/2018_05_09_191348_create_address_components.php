<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressComponents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_components', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('institution_id')->references('id')->on('institutions');
            $table->string('type');
            $table->string('short_name');
            $table->string('long_name');
            $table->index(['type', 'short_name', 'long_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address_components');
    }
}
