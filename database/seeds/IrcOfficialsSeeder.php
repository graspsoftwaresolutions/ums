<?php

use Illuminate\Database\Seeder;
use App\Role;

class IrcOfficialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $irc_role = new Role();
	    $irc_role->slug = 'irc-confirmation-officials';
	    $irc_role->name = 'IRC Confirmation Officials';
	    $irc_role->save();
		
		$irc_role_one = new Role();
	    $irc_role_one->slug = 'irc-branch-committee-officials';
	    $irc_role_one->name = 'IRC Branch Committee Officials';
	    $irc_role_one->save();
    }
}
