<?php

use Illuminate\Database\Seeder;
use App\Model\MonthlySubMatchType;

class MonthlySubMatchTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'NRIC Matched';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();

        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'NRIC Not Matched';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();
        
        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'Mismatched Member Name';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();
        
        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'Mismatched Bank';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();
        
        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'Mismatched Previous Subscription';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();
        
        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'StruckOff Members';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();
        
        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'Resigned Members';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();
        
        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'NRIC Old Matched';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();
        
        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'NRIC By Bank Matched';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();
        
        $match_type = new MonthlySubMatchType();
        $match_type->match_name = 'Previous Subscription Unpaid';
        $match_type->created_by = 1;
	    $match_type->status = 1;
        $match_type->save();
        
    }
}
