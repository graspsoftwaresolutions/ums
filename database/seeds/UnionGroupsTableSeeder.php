<?php

use Illuminate\Database\Seeder;

class UnionGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = array(
				array(
					'group_name' => 'SMJ',
				),
				array(
					'group_name' => 'PKP',
				),
				array(
					'group_name' => 'PERAK',
				),
				array(
					'group_name' => 'KELANTAN TERENGGANU',
				),
				array(
					'group_name' => 'KLSP',
				),
				
			);
        DB::table('union_groups')->insert($groups);
    }
}
