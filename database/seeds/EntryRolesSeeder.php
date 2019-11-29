<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class EntryRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $enry_role = new Role();
	    $enry_role->slug = 'data-entry';
	    $enry_role->name = 'Data Entry';
	    $enry_role->save();

	    /* master union branch user */
	    $branch_user = new User();
	    $branch_user->name = 'Zul hq';
	    $branch_user->email = 'zul_hq@nube.org.my';
	    $branch_user->password = bcrypt('nube12345');
	    $branch_user->save();
	    $branch_user->roles()->attach($enry_role);
    }
}
