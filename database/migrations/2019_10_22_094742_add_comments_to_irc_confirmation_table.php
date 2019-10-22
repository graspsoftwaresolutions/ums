<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentsToIrcConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('irc_confirmation', function (Blueprint $table) {
             $table->text('comments')->after('remarks')->nullable(); 
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
            //$table->integer('is_benefit_valid')->after('reason_name')->default(0); 
        });
    }
}
