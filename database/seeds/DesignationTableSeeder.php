<?php

use Illuminate\Database\Seeder;
use App\Model\Designation;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designation = new Designation();
	    $designation->designation_name = 'Cashier';
	    $designation->status = 1;
	    $designation->save();
    }
}
