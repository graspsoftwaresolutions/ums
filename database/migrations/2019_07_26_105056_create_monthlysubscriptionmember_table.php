<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlysubscriptionmemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('MonthlySubscriptionMember', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('MonthlySubscriptionCompanyId')->nullable();
			$table->string('MemberCode')->nullable();
			$table->string('NRIC')->nullable();
			$table->string('Name')->nullable();
			$table->float('Amount', 8, 2);
            $table->integer('StatusId')->nullable(); 
            $table->integer('created_by')->nullable(); 
			$table->integer('updated_by')->nullable(); 
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('created_on')->nullable();
			
			$table->foreign('MonthlySubscriptionCompanyId')->references('id')->on('monthlysubscriptioncompany')->onDelete('cascade');
		    $table->foreign('MemberCode')->references('member_number')->on('membership')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('MonthlySubscriptionMember');
    }
}
