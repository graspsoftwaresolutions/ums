<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberGuardianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_guardian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('member_id')->nullable() ;
            $table->string('guardian_name')->nullable();
            $table->integer('relationship_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->string('postal_code')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('address_one')->nullable();
            $table->string('address_two')->nullable();
            $table->string('address_three')->nullable();
			$table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('nric_n')->nullable();
            $table->string('nric_o')->nullable();
            $table->string('mobile')->nullable();
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
        Schema::dropIfExists('member_guardian');
    }
}
