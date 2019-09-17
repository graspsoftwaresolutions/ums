<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToReason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reason', function (Blueprint $table) {
           $table->integer('is_benefit_valid')->after('reason_name')->default(0); 
           $table->integer('minimum_year')->nullable()->after('is_benefit_valid'); 
           $table->float('minimum_refund')->nullable()->after('minimum_year'); 
           $table->float('maximum_refund')->nullable()->after('minimum_refund'); 
           $table->float('five_year_amount')->nullable()->after('maximum_refund'); 
           $table->float('fiveplus_year_amount')->nullable()->after('five_year_amount'); 
           $table->float('one_year_amount')->nullable()->after('fiveplus_year_amount'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reason', function (Blueprint $table) {
            //
        });
    }
}
