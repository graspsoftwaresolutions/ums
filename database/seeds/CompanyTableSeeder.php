<?php

use Illuminate\Database\Seeder;
use App\Model\Company;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = new Company();
	    $company->company_name = 'Head Company';
	    $company->short_code = 'DEF';
		$company->head_of_company = 1;
		$company->status = 1;
	    $company->save();
    }
}
