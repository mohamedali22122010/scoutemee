<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('name')->unique();
            $table->text('services');
            $table->string('facebook_page');
            $table->string('sound_cloud_page');
            $table->string('youtube_channel');
            $table->string('location');
            $table->string('profile_image');
            $table->string('profile_video');
            $table->string('gender');
            $table->string('theme');
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
        Schema::drop('profiles');
    }
}
