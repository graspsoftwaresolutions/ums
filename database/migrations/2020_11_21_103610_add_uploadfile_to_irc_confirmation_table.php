<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUploadfileToIrcConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('irc_confirmation', function (Blueprint $table) {
            $table->string('attachment_fullform')->nullable()->after('samebranchothers');
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
