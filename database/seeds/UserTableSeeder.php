<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	    $union_role = Role::where('slug','union')->first();
	    $union_branch_role = Role::where('slug', 'union-branch')->first();
	    $member_role = Role::where('slug', 'member')->first();
	    $company_role = Role::where('slug', 'company')->first();
	    $company_branch_role = Role::where('slug', 'company-branch')->first();
		
	    $union_permission = Permission::where('slug','create-user')->first();
		
		/* master union user */
	    $union_user = new User();
	    $union_user->name = 'Union';
	    $union_user->email = 'union@gmail.com';
		$union_user->password = bcrypt('12345678');
	    $union_user->save();
	    $union_user->roles()->attach($union_role);
	    //$union_user->permissions()->attach($union_permission);

		/* master union branch user */
	    $branch_user = new User();
	    $branch_user->name = 'Default union Branch';
	    $branch_user->email = 'unionbranch@gmail.com';
		$branch_user->union_branch_id = 1;
	    $branch_user->password = bcrypt('12345678');
	    $branch_user->save();
	    $branch_user->roles()->attach($union_branch_role);
	   // $manager->permissions()->attach($manager_perm);
	   
	   /* master memmber user */
	    $branch_user = new User();
	    $branch_user->name = 'Member';
	    $branch_user->email = 'member@gmail.com';
	    $branch_user->password = bcrypt('12345678');
	    $branch_user->save();
	    $branch_user->roles()->attach($member_role);
		
	   /* Company user */
	    $company_user = new User();
	    $company_user->name = 'Default Company';
	    $company_user->email = 'company@gmail.com';
	    $company_user->password = bcrypt('12345678');
	    $company_user->save();
	    $company_user->roles()->attach($company_role);
		
		 /* Company company branch user */
	    $company_user = new User();
	    $company_user->name = 'Default Company Branch';
	    $company_user->email = 'companybranch@gmail.com';
		$company_user->branch_id = 1;
		$company_user->union_branch_id = 1;
	    $company_user->password = bcrypt('12345678');
	    $company_user->save();
	    $company_user->roles()->attach($company_branch_role);
    }
}
