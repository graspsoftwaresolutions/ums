<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchidToIrc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('irc_account', function (Blueprint $table) {
             $table->integer('union_branch_id')->nullable()->after('MemberCode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('irc_account', function (Blueprint $table) {
            $table->dropColumn('union_branch_id');
        });
    }
}
