<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name');
            $table->string('owner_name');
            $table->integer('phone');
            $table->string('email');
            $table->string('address_one')->nullable();
            $table->string('address_two')->nullable();
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
        Schema::dropIfExists('company');
    }
}
