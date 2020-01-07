<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToArrearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('arrear_entry', function (Blueprint $table) {
            $table->integer('no_of_rows')->nullable()->after('no_of_months');
            $table->integer('type')->after('membercode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('arrear_entry', function (Blueprint $table) {
            //
        });
    }
}
