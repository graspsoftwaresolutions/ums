<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\UnionBranch;
use App\Model\Membership;
use App\Model\Company;
use App\Model\Branch;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\User;

class DashboardController extends Controller
{   
   public function unionBranchCount()
   {
    //     $union_branch_count = unionBranch::where('is_head',0)->count();
    //     echo '<pre>';
    //    $branch = User::find(1)->unionbranch;
    //    print_r($branch);
    //    die;
        //$union = UnionBranch::with('User')->get();
       // echo '<pre>';
        //$country_states= Country::find(130)->states;
       // $state_country= State::find(1)->country;
        // print_r($state_country);
       // die;
      
   }
}