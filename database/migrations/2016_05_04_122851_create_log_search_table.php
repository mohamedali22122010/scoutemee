<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogSearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('search_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment = "search or advanced search";
            $table->string('search_key');
            $table->string('geners');
            $table->string('role');
            $table->string('influenced_by');
            $table->string('gender');
            $table->string('location');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('live_performance');
            $table->string('music_lessons');
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
        Schema::dropIfExists('search_log');
    }
}
