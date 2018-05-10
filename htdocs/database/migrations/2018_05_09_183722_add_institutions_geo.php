<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstitutionsGeo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->double('lat', 11, 8)->nullable();
            $table->double('lng', 11, 8)->nullable();
            $table->json('location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng', 'location']);
        });
    }
}
