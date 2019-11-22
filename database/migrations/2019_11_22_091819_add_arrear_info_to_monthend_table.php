<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArrearInfoToMonthendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membermonthendstatus', function (Blueprint $table) {
            $table->date('arrear_date')->nullable()->after('CURRENT_YDTINSURANCE');
            $table->integer('arrear_status')->after('arrear_date')->default(0); 
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
