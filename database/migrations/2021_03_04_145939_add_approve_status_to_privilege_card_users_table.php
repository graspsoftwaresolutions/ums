<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproveStatusToPrivilegeCardUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('privilege_card_users', function (Blueprint $table) {
            $table->integer('pc_status_id')->nullable()->after('status');
            $table->date('approval_reject_date')->nullable()->after('pc_status_id');
            $table->integer('approval_reject_by')->nullable()->after('approval_reject_date');
            $table->string('approval_reject_reason')->nullable()->after('approval_reject_by');
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
