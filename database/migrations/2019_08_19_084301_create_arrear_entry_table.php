<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrearEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrear_entry', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('membercode')->nullable(); 
            $table->integer('company_id')->nullable(); 
            $table->integer('branch_id')->nullable(); 
            $table->string('nric')->nullable(); 
            $table->date('arrear_date')->nullable(); 
            $table->string('arrear_amount')->nullable();
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
        Schema::dropIfExists('arrear_entry');
    }
}
