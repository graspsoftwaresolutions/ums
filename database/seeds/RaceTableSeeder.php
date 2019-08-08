<?php

use Illuminate\Database\Seeder;
use App\Model\Race;

class RaceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $race = new Race();
        $race->race_name = 'Chineese';
        $race->short_code = 'CHI';
	    $race->status = 1;
	    $race->save();
    }
}
