<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaidamtToAdvancePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advance_payments', function (Blueprint $table) {
             $table->float('paid_amount', 8, 2)->default(0);  
             $table->float('balance_amount', 8, 2)->default(0);  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advance_payments', function (Blueprint $table) {
            //
        });
    }
}
