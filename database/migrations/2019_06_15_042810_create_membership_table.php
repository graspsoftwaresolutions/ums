<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('member_number');
            $table->integer('member_title_id');
            $table->integer('old_member_number')->nullable();
            $table->string('name');
            $table->string('gender');
            $table->integer('designation_id');
            $table->string('email');
            $table->biginteger('phone');
            $table->biginteger('mobile');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->string('postal_code');
            $table->integer('city_id');
            $table->string('address_one');
            $table->string('address_two')->nullable();
            $table->string('address_three')->nullable();
            $table->integer('race_id');
            $table->string('old_ic')->nullable();
            $table->string('new_ic');
            $table->date('dob');
            $table->date('doe');
            $table->date('doj')->nullable();
            $table->integer('branch_id');
            $table->integer('status_id');
            $table->string('salary');
            $table->string('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('employee_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('user_type')->nullable();
            $table->string('is_request_approved')->nullable();   
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
        Schema::dropIfExists('membership');
    }
}
