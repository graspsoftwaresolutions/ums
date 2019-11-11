<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	 //    $union_permission = Permission::where('slug','create-user')->first();
	 //   // $manager_permission = Permission::where('slug', 'edit-users')->first();

	 //    $union_role = new Role();
	 //    $union_role->slug = 'union';
	 //    $union_role->name = 'Union';
	 //    $union_role->save();
	 //    //$union_role->permissions()->attach($union_permission);
		
		
		// $branch_role = new Role();
	 //    $branch_role->slug = 'union-branch';
	 //    $branch_role->name = 'union-branch';
	 //    $branch_role->save();
		
		// $company_role = new Role();
	 //    $company_role->slug = 'company';
	 //    $company_role->name = 'Company';
	 //    $company_role->save();
		
		// $cbranch_role = new Role();
	 //    $cbranch_role->slug = 'company-branch';
	 //    $cbranch_role->name = 'Company Branch';
	 //    $cbranch_role->save();
		
		// $member_role = new Role();
	 //    $member_role->slug = 'member';
	 //    $member_role->name = 'Member';
	 //    $member_role->save();

	    $official_role = new Role();
	    $official_role->slug = 'officials';
	    $official_role->name = 'Officials';
	    $official_role->save();
		
		

	   /*  $manager_role = new Role();
	    $manager_role->slug = 'manager';
	    $manager_role->name = 'Assistant Manager';
	    $manager_role->save();
	    $manager_role->permissions()->attach($manager_permission); */
    }
}
