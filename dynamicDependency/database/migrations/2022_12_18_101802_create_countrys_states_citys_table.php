<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countrys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('CountryName');
            $table->string('sortname');
            $table->string('phonecode');
            $table->timestamps();
        });
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stateName');
            $table->integer('country_id');
            $table->timestamps();
        });
        Schema::create('citys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cityName');
            $table->integer('state_id'); 
            $table->integer('country_id');
            $table->timestamps();
        });

        Schema::rename('countries','countrys');
        Schema::rename('cities','citys');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countrys');
        Schema::dropIfExists('states');
        Schema::dropIfExists('citys');
    }
};
