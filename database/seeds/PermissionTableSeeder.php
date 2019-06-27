<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
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

	    $createUsers = new Permission();
	    $createUsers->slug = 'create-user';
	    $createUsers->name = 'Create User';
	    $createUsers->save();
	    $createUsers->roles()->attach($union_role);

	    /* $editUsers = new Permission();
	    $editUsers->slug = 'edit-users';
	    $editUsers->name = 'Edit Users';
	    $editUsers->save();
	    $editUsers->roles()->attach($manager_role); */
    }
}
