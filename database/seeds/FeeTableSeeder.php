<?php

use Illuminate\Database\Seeder;
use App\Model\Fee;

class FeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fee = new Fee();
	    $fee->fee_name = 'Entrance fee';
	    $fee->fee_amount = '500';
	    $fee->status = 1;
	    $fee->save();
		
		$fee = new Fee();
	    $fee->fee_name = 'Subscribe fee';
	    $fee->fee_amount = '1000';
	    $fee->status = 1;
	    $fee->save();
    }
}
