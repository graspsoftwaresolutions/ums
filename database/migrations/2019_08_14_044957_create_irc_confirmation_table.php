<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIrcConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('irc_confirmation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('resignedmemberno');
            $table->string('resignedmembername');
            $table->string('resignedmembericno')->nullable();
            $table->string('resignedmemberbankname')->nullable();
            $table->string('resignedmemberbranchname')->nullable();
            $table->string('resignedreason')->nullable();
            $table->string('ircmembershipno')->nullable();
            $table->string('ircname')->nullable();
            $table->string('ircposition')->nullable();
            $table->string('ircbank')->nullable();
            $table->string('ircbankaddress')->nullable();
            $table->string('irctelephoneno')->nullable();
            $table->string('ircmobileno')->nullable();
            $table->string('ircfaxno')->nullable();
            $table->string('promotedto')->nullable();
            $table->date('gradewef')->nullable();
            $table->integer('nameofperson')->nullable();
            $table->integer('waspromoted')->nullable();
            $table->integer('beforepromotion')->nullable();
            $table->integer('attached')->nullable();
            $table->integer('herebyconfirm')->nullable();
            $table->integer('filledby')->nullable();
            $table->string('nameforfilledby')->nullable();
            $table->integer('branchcommitteeverification1')->nullable();
            $table->integer('branchcommitteeverification2')->nullable();
            $table->string('branchcommitteeName')->nullable();
            $table->string('branchcommitteeZone')->nullable();
            $table->date('branchcommitteedate')->nullable();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->date('submitted_at')->nullable();

             //FOREIGN KEY CONSTRAINTS
           $table->foreign('resignedmemberno')->references('id')->on('membership')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('irc_confirmation');
    }
}
