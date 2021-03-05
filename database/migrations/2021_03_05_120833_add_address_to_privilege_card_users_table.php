<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressToPrivilegeCardUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('privilege_card_users', function (Blueprint $table) {
            $table->string('home_address')->nullable()->after('member_number');
            $table->string('home_tel_no',25)->nullable()->after('home_address');
            $table->string('office_address')->nullable()->after('home_tel_no');
            $table->string('office_tel_no',25)->nullable()->after('office_address');
            $table->string('handphone_no',25)->nullable()->after('office_tel_no');
            $table->string('email_id',100)->nullable()->after('handphone_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('privilege_card_users', function (Blueprint $table) {
            //
        });
    }
}
