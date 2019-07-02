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
            $table->string('address_two');
            $table->string('phone');
            $table->string('email');
            $table->string('is_head');
            $table->timestamps();
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
