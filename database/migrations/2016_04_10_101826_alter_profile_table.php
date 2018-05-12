<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('full_name');
            $table->string('role');
            $table->renameColumn('name', 'profile_url')->change();
            $table->string('tagline');
            $table->renameColumn('theme', 'profile_background')->change();
            $table->text('influnced_by');
            $table->boolean('youtube_subscribe')->default(0);
            $table->boolean('soundcloud_subscribe')->default(0);
            $table->boolean('facebook_subscribe')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function(Blueprint $table)
        {
            $table->dropColumn('full_name');
            $table->renameColumn('profile_url', 'name')->change();
            $table->renameColumn('profile_background', 'theme')->change();
            $table->dropColumn('role');
            $table->dropColumn('tagline');
            $table->dropColumn('influnced_by');
            $table->dropColumn('youtube_subscribe');
            $table->dropColumn('soundcloud_subscribe');
            $table->dropColumn('facebook_subscribe');
        });
    }
}
