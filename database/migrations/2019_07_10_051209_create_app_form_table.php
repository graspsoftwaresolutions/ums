<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_form', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('formname');
            $table->string('formtype');
            $table->decimal('orderno', 14,2);
            $table->string('route')->nullable();
            $table->string('isactive')->default(0);
            $table->string('isinsert')->default(0);
            $table->string('isupdate')->default(0);
            $table->string('isdelete')->default(0);
            $table->string('ismenu')->default(0);
            $table->string('description')->nullable();
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
        Schema::dropIfExists('app_form');
    }
}