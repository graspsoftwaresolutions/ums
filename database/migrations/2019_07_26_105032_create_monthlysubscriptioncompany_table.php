<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlySubscriptionCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('MonthlySubscriptionCompany', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('MonthlySubscriptionId')->nullable();
			$table->unsignedBigInteger('CompanyCode')->nullable();
            $table->integer('created_by')->nullable(); 
			$table->integer('updated_by')->nullable(); 
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('created_on')->nullable();
			
			$table->foreign('MonthlySubscriptionId')->references('id')->on('monthlysubscription')->onDelete('cascade');
		    $table->foreign('CompanyCode')->references('id')->on('company')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('MonthlySubscriptionCompany');
    }
}
