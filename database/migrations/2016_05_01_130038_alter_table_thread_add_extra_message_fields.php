<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableThreadAddExtraMessageFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('threads', function ($table) {
            $table->string('place');
            $table->string('subservice');
            $table->string('accepted')->default(0)->comment = "1 for accepted ,2 for not accepted";
            $table->string('date');
            $table->string('duration');
            $table->string('proficiency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('threads', function(Blueprint $table)
        {
            $table->dropColumn('rate_average');
            $table->dropColumn('place');
            $table->dropColumn('service');
            $table->dropColumn('subservice');
            $table->dropColumn('accepted');
            $table->dropColumn('date');
            $table->dropColumn('duration');
            $table->dropColumn('proficiency');
        });
    }
}
