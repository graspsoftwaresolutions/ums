<?php

use Illuminate\Database\Seeder;

class CardStatusTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $card_status = array(
				array(
					'card_status_name' => 'PC NOT FP',
				),
				array(
					'card_status_name' => 'PC INHAND',
				),
				array(
					'card_status_name' => 'PC RETURNED',
				),
				array(
					'card_status_name' => 'PC GS SIGN PENDING',
				),
				array(
					'card_status_name' => 'PC SEND OUT',
				),
				array(
					'card_status_name' => 'NO SPARE PC',
				),
				
			);
        DB::table('eco_park_card_status')->insert($card_status);
    }
}
