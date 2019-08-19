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
			$table->string('icno', 30)->nullable();  
			$table->string('icno_old', 30)->nullable();  
			$table->integer('relation_code')->nullable();   
			$table->integer('reason_code'); 
			$table->string('claimer_name',50)->nullable();   
			$table->integer('months_contributed')->nullable();   
			$table->float('accbf', 8, 2)->nullable();  
			$table->float('accbenefit', 8, 2)->nullable();   
			$table->string('chequeno',30)->nullable();   
			$table->dateTime('chequedate')->nullable();   
			$table->float('amount', 8, 2)->nullable();   
			$table->integer('totalarrears')->nullable();    
			$table->integer('user_code')->nullable(); 
			$table->dateTime('entry_date')->nullable();  
			$table->time('entry_time')->nullable();   
			$table->dateTime('currentdate')->nullable();  
			$table->dateTime('voucher_date')->nullable(); 
			$table->integer('service_year')->nullable();  
			$table->string('paymode')->nullable();   
			$table->float('insuranceamount', 8, 2)->nullable();   
			$table->float('unioncontribution', 8, 2)->nullable();   
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
