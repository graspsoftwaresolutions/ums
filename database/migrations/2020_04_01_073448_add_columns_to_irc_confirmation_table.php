<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToIrcConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('irc_confirmation', function (Blueprint $table) {
            $table->integer('posfilledbytype')->nullable();  
            $table->integer('posfilledmemberid')->nullable();  
            $table->string('messengertype')->nullable();  
            $table->string('attachment_file')->nullable();  
            $table->string('attachfourtype')->nullable();  
            $table->string('committiecode')->nullable();  
            $table->string('replacestafftype')->nullable();  
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
