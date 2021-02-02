<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMemberidToTdfUpdationTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tdf_updation_temp', function (Blueprint $table) {
            $table->integer('member_id')->nullable()->after('paid_date');
            $table->integer('status_id')->nullable()->after('cheque_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tdf_updation_temp', function (Blueprint $table) {
            //
        });
    }
}
