<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumns1ToIrcConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('irc_confirmation', function (Blueprint $table) {
            $table->integer('retiredbox')->default(0)->after('memberstoppedboxfive');
            $table->integer('applicanttwo')->default(0)->after('retiredbox');
            $table->integer('committieverificationboxone')->default(0)->after('applicanttwo');
            $table->string('committiename')->nullable()->after('committieverificationboxone');
            $table->string('committieverifyname')->nullable()->after('committiename');
            $table->integer('committieverificationboxtwo')->default(0)->after('committieverifyname');
            $table->integer('committieverificationboxthree')->default(0)->after('committieverificationboxtwo');
            $table->string('committieremark')->nullable()->after('committieverificationboxthree');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('irc_confirmation', function (Blueprint $table) {
            //
        });
    }
}
