<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberNomineesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_nominees', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('member_id');
            $table->string('nominee_name');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->string('postal_code');
            $table->integer('city_id');
            $table->string('address_one');
            $table->string('address_two')->nullable();
            $table->string('address_three')->nullable();
            $table->string('years');
            $table->string('gender');
            $table->string('nric_n');
            $table->string('nric_o');
            $table->string('mobile');
            $table->string('phone');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('member_nominees');
    }
}
