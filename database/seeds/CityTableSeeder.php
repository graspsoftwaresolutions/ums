<?php

use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('city')->delete();
		DB::statement("
		INSERT INTO `city` (`id`, `country_id`, `state_id`, `city_name`, `updated_at`, `created_at`, `status`) VALUES
		(1, 130, 1, 'Sibu', '2019-07-11 16:30:41', NULL, 1),
		(2, 130, 15, 'Shah Alam', '2019-07-11 16:30:41', NULL, 1),
		(3, 130, 1, 'Bintulu', '2019-07-11 16:30:41', NULL, 1),
		(4, 130, 1, 'Kuching', '2019-07-11 16:30:41', NULL, 1),
		(5, 130, 2, 'Victoria', '2019-07-11 16:30:41', NULL, 1),
		(6, 130, 3, 'Taiping', '2019-07-11 16:30:41', NULL, 1),
		(7, 130, 4, 'Chukai', '2019-07-11 16:30:41', NULL, 1),
		(8, 130, 5, 'Kuala Lumpur', '2019-07-11 16:30:41', NULL, 1),
		(9, 130, 6, 'Kangar', '2019-07-11 16:30:41', NULL, 1),
		(10, 130, 7, 'Alor Setar', '2019-07-11 16:30:41', NULL, 1),
		(11, 130, 8, 'George Town', '2019-07-11 16:30:41', NULL, 1),
		(12, 130, 8, 'Butterworth', '2019-07-11 16:30:41', NULL, 1),
		(13, 130, 7, 'Sungai Petani', '2019-07-11 16:30:41', NULL, 1),
		(14, 130, 9, 'Sandakan', '2019-07-11 16:30:41', NULL, 1),
		(15, 130, 9, 'Lahad Datu', '2019-07-11 16:30:42', NULL, 1),
		(16, 130, 10, 'Kota Baharu', '2019-07-11 16:30:42', NULL, 1),
		(17, 130, 11, 'Johor Bahru', '2019-07-11 16:30:42', NULL, 1),
		(18, 130, 11, 'Keluang', '2019-07-11 16:30:42', NULL, 1),
		(19, 130, 4, 'Kuala Terengganu', '2019-07-11 16:30:42', NULL, 1),
		(20, 130, 3, 'Ipoh', '2019-07-11 16:30:42', NULL, 1),
		(21, 130, 12, 'Kuantan', '2019-07-11 16:30:42', NULL, 1),
		(22, 130, 9, 'Kota Kinabalu', '2019-07-11 16:30:42', NULL, 1),
		(23, 130, 9, 'Tawau', '2019-07-11 16:30:42', NULL, 1),
		(24, 130, 12, 'Kuala Lipis', '2019-07-11 16:30:42', NULL, 1),
		(25, 130, 3, 'Teluk Intan', '2019-07-11 16:30:42', NULL, 1),
		(26, 130, 13, 'Malacca', '2019-07-11 16:30:42', NULL, 1),
		(27, 130, 11, 'Muar', '2019-07-11 16:30:42', NULL, 1),
		(28, 130, 1, 'Miri', '2019-07-11 16:30:43', NULL, 1),
		(29, 130, 14, 'Seremban', '2019-07-11 16:30:43', NULL, 1),
		(30, 130, 14, 'Putrajaya', '2019-07-11 16:30:43', NULL, 1),
		(31, 130, 12, 'Raub', '2019-07-11 16:30:43', NULL, 1),
		(32, 130, 11, 'Batu Pahat', '2019-07-11 16:30:43', NULL, 1),
		(33, 130, 15, 'Kelang', '2019-07-11 16:30:43', NULL, 1)
		");
    }
}
