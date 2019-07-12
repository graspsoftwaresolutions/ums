<?php

use Illuminate\Database\Seeder;
use App\Model\UnionBranch;

class UnionBranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// union head
        $new_branch = new UnionBranch();
	    $new_branch->union_branch = 'Head union branch';
	    $new_branch->country_id = 130;
	    $new_branch->state_id = 15;
	    $new_branch->city_id = 33;
	    $new_branch->postal_code = '635204';
	    $new_branch->address_one = 'Default address';
	    $new_branch->phone = '04343';
	    $new_branch->mobile = '91111111';
	    $new_branch->email = 'unionbranch@gmail.com';
	    $new_branch->is_head = 1;
		$new_branch->status = 1;
		$new_branch->user_id = 1;
	    $new_branch->save();
    }
}
