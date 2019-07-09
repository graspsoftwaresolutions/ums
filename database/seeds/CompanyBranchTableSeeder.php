<?php

use Illuminate\Database\Seeder;
use App\Model\Branch;

class CompanyBranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = new Branch();
	    $branch->company_id = 1;
	    $branch->union_branch_id = 1;
		$branch->branch_name = 'Default branch';
		$branch->country_id = 1;
		$branch->state_id = 1;
		$branch->city_id = 1;
		$branch->postal_code = '635204';
		$branch->address_one = 'test address';
		$branch->address_two = 'test address';
		$branch->address_three = 'test address';
		$branch->phone = '9988888';
		$branch->mobile = '88888888';
		$branch->email = 'companybranch@gmail.com';
		$branch->status = 1;
	    $branch->save();
    }
}
