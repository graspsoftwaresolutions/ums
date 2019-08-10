<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIrcAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('irc_account', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('MemberCode')->nullable();
			$table->string('account_type')->nullable();
			$table->unsignedBigInteger('user_id')->nullable();
			$table->integer('created_by')->nullable(); 
			$table->integer('updated_by')->nullable(); 
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->nullable();
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('irc_account');
    }
}
