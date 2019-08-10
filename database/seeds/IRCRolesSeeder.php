<?php

use Illuminate\Database\Seeder;
use App\Role;

class IRCRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $irc_role = new Role();
	    $irc_role->slug = 'irc-confirmation';
	    $irc_role->name = 'IRC Confirmation';
	    $irc_role->save();
		
		$irc_role_one = new Role();
	    $irc_role_one->slug = 'irc-branch-committee';
	    $irc_role_one->name = 'IRC Branch Committee';
	    $irc_role_one->save();
    }
}
