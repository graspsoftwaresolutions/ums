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
	    $branch_role = Role::where('slug', 'branch')->first();
	    $member_role = Role::where('slug', 'member')->first();
		
	    $union_permission = Permission::where('slug','create-user')->first();
		
		/* master union user */
	    $union_user = new User();
	    $union_user->name = 'Union';
	    $union_user->email = 'union@gmail.com';
		$union_user->password = bcrypt('12345678');
		$union_user->branch_id = 1;
	    $union_user->save();
	    $union_user->roles()->attach($union_role);
	    $union_user->permissions()->attach($union_permission);

		/* master branch user */
	    $branch_user = new User();
	    $branch_user->name = 'Branch';
	    $branch_user->email = 'branch@gmail.com';
	    $branch_user->password = bcrypt('12345678');
	    $branch_user->save();
	    $branch_user->roles()->attach($branch_role);
	   // $manager->permissions()->attach($manager_perm);
	   
	   /* master memmber user */
	    $branch_user = new User();
	    $branch_user->name = 'Member';
	    $branch_user->email = 'member@gmail.com';
	    $branch_user->password = bcrypt('12345678');
	    $branch_user->save();
	    $branch_user->roles()->attach($member_role);
    }
}
