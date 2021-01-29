<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTdfUpdationTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tdf_updation_temp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tdf_date_id')->nullable();
            $table->date('paid_date')->nullable();
            $table->bigInteger('member_number');
            $table->string('name');
            $table->string('icno');
            $table->float('amount', 10, 2)->default(0);
            $table->string('cheque_no');
            $table->integer('status')->nullable();
            $table->integer('created_by')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tdf_updation_temp');
    }
}
