<?php

use Illuminate\Database\Seeder;

class IncrementTypesTableDataSeeder extends Seeder
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
					'type_name' => 'Increment',
				),
				array(
					'type_name' => 'OT',
				),
				array(
					'type_name' => 'Bonus',
				),
				
			);
        DB::table('increment_types')->insert($groups);
    }
}
