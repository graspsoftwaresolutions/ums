<?php

use Illuminate\Database\Seeder;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('state')->delete();
        DB::statement("INSERT INTO `state` (`id`, `country_id`, `state_name`, `updated_at`, `created_at`, `status`) VALUES
			(1, 130, 'Sarawak', '2019-07-11 16:03:26', NULL, 1),
			(2, 130, 'Labuan', '2019-07-11 16:03:26', NULL, 1),
			(3, 130, 'Perak', '2019-07-11 16:03:26', NULL, 1),
			(4, 130, 'Terengganu', '2019-07-11 16:03:26', NULL, 1),
			(5, 130, 'Kuala Lumpur ', '2019-07-11 16:03:26', NULL, 1),
			(6, 130, 'Perlis', '2019-07-11 16:03:26', NULL, 1),
			(7, 130, 'Kedah', '2019-07-11 16:03:26', NULL, 1),
			(8, 130, 'Pulau Pinang', '2019-07-11 16:03:26', NULL, 1),
			(9, 130, 'Sabah', '2019-07-11 16:10:55', NULL, 1),
			(10, 130, 'Kelantan', '2019-07-11 16:11:02', NULL, 1),
			(11, 130, 'Johor', '2019-07-11 16:11:06', NULL, 1),
			(12, 130, 'Pahang', '2019-07-11 16:11:09', NULL, 1),
			(13, 130, 'Melaka', '2019-07-11 16:11:13', NULL, 1),
			(14, 130, 'Negeri Sembilan', '2019-07-11 16:11:19', NULL, 1),
			(15, 130, 'Selangor', '2019-07-11 16:11:22', NULL, 1)"
			);																																
    }
}
