<?php

use Illuminate\Database\Seeder;

class CreatedByColumnUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table_names = ['app_form','city','company','company_branch','country','designation','fee','form_type','membership','member_fee','member_guardian'
						,'member_nominees','persontitle','race','reason','relation','state','status','union_branch','users'];
		foreach($table_names as $tablename){
			DB::table($tablename)->update(['created_by' => 1]);
		}
    }
}
