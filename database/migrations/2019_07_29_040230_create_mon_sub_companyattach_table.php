<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonSubCompanyattachTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mon_sub_companyattach', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('MonthlySubscriptionCompanyId')->nullable();
			$table->unsignedBigInteger('MonthlySubscriptonFileTypeId')->nullable();
			$table->string('FilePath')->nullable();
			$table->string('FileContent')->nullable();
            $table->integer('created_by')->nullable(); 
			$table->integer('updated_by')->nullable(); 
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('created_on')->nullable();
			
			//$table->foreign('MonthlySubscriptionCompanyId')->references('id')->on('mon_sub_company')->onDelete('cascade');
		    //$table->foreign('MonthlySubscriptonFileTypeId')->references('id')->on('mon_sub_filetype')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mon_sub_companyattach');
    }
}
