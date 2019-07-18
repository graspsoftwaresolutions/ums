<?php

use Illuminate\Database\Seeder;
use App\Model\Persontitle;

class PersonTitleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $persion_title = new Persontitle();
	    $persion_title->person_title = 'MR';
	    $persion_title->status = 1;
	    $persion_title->save();
		
		$persion_title = new Persontitle();
	    $persion_title->person_title = 'MISS';
	    $persion_title->status = 1;
	    $persion_title->save();
    }
}
