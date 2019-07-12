<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$this->call(CountryTableSeeder::class);
        $this->call(StateTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(UnionBranchTableSeeder::class);
        $this->call(CompanyBranchTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
    }
}
