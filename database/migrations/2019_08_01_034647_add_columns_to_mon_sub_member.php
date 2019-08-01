<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMonSubMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mon_sub_member', function (Blueprint $table) {
            $table->integer('company_branch_id')->nullable()->after('MonthlySubscriptionCompanyId'); 
			$table->integer('update_status')->default(0)->after('StatusId'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mon_sub_member', function (Blueprint $table) {
            $table->dropColumn(['company_branch_id','update_status']);
        });
    }
}
