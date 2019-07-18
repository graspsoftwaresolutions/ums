<?php

use Illuminate\Database\Seeder;
use App\Model\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $new_status = new Status();
	    $new_status->status_name = 'New Member';
	    $new_status->status = 1;
		$new_status->save();
		
		$new_status = new Status();
	    $new_status->status_name = 'Active';
	    $new_status->status = 1;
		$new_status->save();
    }
}
