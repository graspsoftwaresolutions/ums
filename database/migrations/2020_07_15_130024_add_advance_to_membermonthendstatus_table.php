<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvanceToMembermonthendstatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membermonthendstatus', function (Blueprint $table) {
            $table->float('advance_amt')->default(0)->after('arrear_status');
            $table->float('advance_balamt')->default(0)->after('advance_amt');
            $table->float('advance_totalmonths')->default(0)->after('advance_balamt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membermonthendstatus', function (Blueprint $table) {
            //
        });
    }
}
