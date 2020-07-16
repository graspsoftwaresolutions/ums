<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempMemberNomineesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_member_nominees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('member_id');
            $table->integer('relation_id');
            $table->string('nominee_name');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->string('postal_code');
            $table->integer('city_id');
            $table->string('address_one');
            $table->string('address_two')->nullable();
            $table->string('address_three')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender');
            $table->string('nric_n');
            $table->string('nric_o');
            $table->string('mobile');
            $table->string('phone')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->nullable();
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_member_nominees');
    }
}
