<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResignationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resignation', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('member_code'); 
			$table->dateTime('resignation_date'); 
			$table->integer('resignstatus_code'); 
			$table->string('icno', 30); 
			$table->string('icno_old', 30); 
			$table->integer('relation_code'); 
			$table->integer('reason_code'); 
			$table->string('claimer_name',50); 
			$table->integer('months_contributed'); 
			$table->float('accbf', 8, 2); 
			$table->float('accbenefit', 8, 2); 
			$table->string('chequeno',30); 
			$table->dateTime('chequedate'); 
			$table->float('amount', 8, 2); 
			$table->float('totalarrears', 8, 2); 
			$table->integer('user_code'); 
			$table->dateTime('entry_date'); 
			$table->time('entry_time'); 
			$table->dateTime('currentdate'); 
			$table->dateTime('voucher_date'); 
			$table->integer('service_year'); 
			$table->string('paymode'); 
			$table->float('insuranceamount', 8, 2); 
			$table->float('unioncontribution', 8, 2); 
			$table->integer('created_by')->nullable(); 
			$table->integer('updated_by')->nullable(); 
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resignation');
    }
}
