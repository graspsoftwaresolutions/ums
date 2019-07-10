<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnionBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('union_branch', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('union_branch');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->integer('city_id');
            $table->string('postal_code');
            $table->string('address_one');
            $table->string('address_two')->nullable();
            $table->string('address_three')->nullable();
            $table->string('logo')->nullable();
            $table->string('phone');
            $table->string('mobile')->nullable();
            $table->string('email');
            $table->string('is_head');
			$table->integer('user_id');
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
        Schema::dropIfExists('union_branch');
    }
}
