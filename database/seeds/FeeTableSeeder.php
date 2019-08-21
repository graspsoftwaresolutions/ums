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
        $fee->fee_amount = '5';
        $fee->fee_shortcode = 'ENT';
	    $fee->status = 1;
	    $fee->save();
		
		$fee = new Fee();
	    $fee->fee_name = 'Insurance fee';
        $fee->fee_amount = '3';
        $fee->fee_shortcode = 'INS';
	    $fee->status = 1;
	    $fee->save();
    }
}
