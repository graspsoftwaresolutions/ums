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
   public function __construct() {
        ini_set('memory_limit', '-1');
   }
   public function unionBranchCount()
   {
        echo '<pre>';
        $branch = State::with('country')->get();
        foreach($branch as $keyone => $countryone){
        print_r($countryone); die;
        }
    //     $union_branch_count = unionBranch::where('is_head',0)->count();
    //     echo '<pre>';
    //    $branch = User::find(1)->unionbranch;
    //    print_r($branch);
    //    die;
        //$union = UnionBranch::with('User')->get();
        echo '<pre>';
        $country_states= Country::select('country.country_name','state.id','state.state_name')->with('states')->get()->toArray();
        $country_cities= Country::with('cities')->get();
       //return $country_states->states;
        //$state_country= State::find(1)->country;
        //return $country_states;
        foreach($country_states as $keyone => $countryone){
                
                foreach($countryone['states'] as $key => $value){
                        $data['country'] = $countryone['country_name'];
                        foreach($value as $statecol => $stateval){
                                $data[$statecol] =$value[$statecol];
                                print_r($statecol);
                        }
                        // $data['country'] = $countryone['country_name'];
                        // $data[$key] =$countryone['states'][$key];
                        //print_r($key);
                }
        }
        print_r($data);
        die;
      
   }
}