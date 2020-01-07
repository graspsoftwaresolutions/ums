<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrearEntryRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrear_entry_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('arrear_id');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->float('subscription_amount', 8, 2);
            $table->float('bf_amount', 8, 2);
            $table->float('insurance_amount', 8, 2);
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
        Schema::dropIfExists('arrear_entry_records');
    }
}
