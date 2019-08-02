<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonSubMemberMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mon_sub_member_match', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mon_sub_member_id')->nullable();
            $table->unsignedBigInteger('match_id')->nullable();
			$table->integer('created_by')->nullable(); 
			$table->integer('updated_by')->nullable(); 
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('created_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mon_sub_member_match');
    }
}
