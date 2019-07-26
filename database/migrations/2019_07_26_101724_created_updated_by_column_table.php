<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatedUpdatedByColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$table_names = ['app_form','city','company','company_branch','country','designation','fee','form_type','membership','member_fee','member_guardian'
						,'member_nominees','persontitle','race','reason','relation','state','status','union_branch','users'];
		foreach($table_names as $tablename){
			Schema::table($tablename, function ($table) {
				$table->integer('created_by')->nullable()->after('created_at'); 
				$table->integer('updated_by')->nullable()->after('created_by'); 
			});
		}
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('app_form', function ($table) {
            $table->dropColumn(['created_by','updated_by']);
        });
    }
}
