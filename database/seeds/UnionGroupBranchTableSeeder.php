<?php

use Illuminate\Database\Seeder;

class UnionGroupBranchTableSeeder extends Seeder
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
					'union_group_id' => '1', 'union_branch_id' => 9,
				),
				array(
					'union_group_id' => '1', 'union_branch_id' => 2,
				),
				array(
					'union_group_id' => '2', 'union_branch_id' => 8,
				),
				array(
					'union_group_id' => '2', 'union_branch_id' => 3,
				),
				array(
					'union_group_id' => '3', 'union_branch_id' => 1,
				),
				array(
					'union_group_id' => '4', 'union_branch_id' => 4,
				),
				array(
					'union_group_id' => '5', 'union_branch_id' => 6,
				),
				array(
					'union_group_id' => '5', 'union_branch_id' => 5,
				),
				array(
					'union_group_id' => '5', 'union_branch_id' => 7,
				),
				
			);
        DB::table('union_group_branches')->insert($groups);
    }
}
