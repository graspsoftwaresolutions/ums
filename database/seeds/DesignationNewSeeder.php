<?php

use Illuminate\Database\Seeder;

class DesignationNewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $groups = array(
				array(
					'designation_name' => 'Teller',
				),
				array(
					'designation_name' => 'Customer Service',
				),
				array(
					'designation_name' => 'Back office',
				),
				array(
					'designation_name' => 'Greeter',
				),
				array(
					'designation_name' => 'Customer Experience Assitant',
				),array(
					'designation_name' => 'Credit Dept',
				),array(
					'designation_name' => 'Hire Purchase',
				),array(
					'designation_name' => 'AutoFinance',
				),array(
					'designation_name' => 'CSB',
				),array(
					'designation_name' => 'CRR',
				),array(
					'designation_name' => 'CEA Alliance',
				),array(
					'designation_name' => 'Others',
				),
			);
        DB::table('designation_new')->insert($groups);
    }
}
