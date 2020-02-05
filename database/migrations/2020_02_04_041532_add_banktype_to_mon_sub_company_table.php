<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBanktypeToMonSubCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mon_sub_company', function (Blueprint $table) {
             $table->integer('banktype')->default(0)->after('CompanyCode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mon_sub_company', function (Blueprint $table) {
            //
        });
    }
}
