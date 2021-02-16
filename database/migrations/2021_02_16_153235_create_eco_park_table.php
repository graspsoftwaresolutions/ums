<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcoParkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eco_park', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('eco_park_type_id')->nullable();
			$table->unsignedBigInteger('member_id')->nullable();
			$table->unsignedBigInteger('bank_id')->nullable();
            $table->string('privilege_card_no')->nullable();
            $table->string('reissue_pv_card_no')->nullable();
            $table->string('full_name')->nullable();
            $table->string('nric_old')->nullable();
            $table->string('nric_new')->nullable();
            $table->string('bank')->nullable();
            $table->bigInteger('member_number')->nullable();
            $table->string('telephone_no')->nullable();
            $table->text('address')->nullable();
            $table->text('updated_home_address')->nullable();
			$table->float('original_fee', 10, 2)->default(0);
			$table->float('payment_fee', 10, 2)->default(0);
            $table->string('date_joined')->nullable();
            $table->text('updated_bank_address')->nullable();
            $table->string('remarks')->nullable();
            $table->string('date_call')->nullable();
            $table->string('date_updated_on')->nullable();
            $table->string('card_dispatched')->nullable();
            $table->string('card_issue_date')->nullable();
            $table->string('card_status')->nullable();
            $table->string('ack_of_card_received')->nullable();
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
        Schema::table('eco_park', function (Blueprint $table) {
            //
        });
    }
}
