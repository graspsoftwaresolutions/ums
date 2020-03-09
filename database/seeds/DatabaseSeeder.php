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
        // factory(App\User::class, 5000)->create()->each(function ($user) {

        // });
		// $this->call(PermissionTableSeeder::class);
		 //$this->call(RoleTableSeeder::class);
		// $this->call(CountryTableSeeder::class);
        // $this->call(StateTableSeeder::class);
        // $this->call(CityTableSeeder::class);
		// $this->call(UserTableSeeder::class);
        // $this->call(CompanyTableSeeder::class);
        // $this->call(UnionBranchTableSeeder::class);
        // $this->call(CompanyBranchTableSeeder::class); 
		
		// new master seeds
    //    $this->call(RelationshipTableSeeder::class);
        //$this->call(FeeTableSeeder::class);
    //     $this->call(DesignationTableSeeder::class);
        // $this->call(RaceTableSeeder::class);
    //     $this->call(PersonTitleTableSeeder::class);
    //     $this->call(StatusTableSeeder::class);
    //     $this->call(FormTypeTableSeeder::class); 
    //     $this->call(CreatedByColumnUpdateSeeder::class);
        
        //$this->call(MonthlySubMatchTypeTableSeeder::class);
		//$this->call(IRCRolesSeeder::class);
        //$this->call(EntryRolesSeeder::class);
        $this->call(UnionGroupsTableSeeder::class);
    }
}
