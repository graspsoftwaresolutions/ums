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
            $table->string('formname')->nullable();
            $table->string('formtype')->nullable();
            $table->decimal('orderno', 14,2)->nullable();
            $table->string('route')->nullable();
            $table->string('isactive')->nullable();
            $table->string('isinsert')->nullable();
            $table->string('isupdate')->nullable();
            $table->string('isdelete')->nullable();
            $table->string('ismenu')->nullable();
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