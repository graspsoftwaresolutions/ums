<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CustomerController extends Controller
{
    public function FormWizard()
    {
		$data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
		$data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
		$data['company_view'] = DB::table('company')->where('status','=','1')->get();
		$data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
		$data['race_view'] = DB::table('race')->where('status','=','1')->get();
		$data['status_view'] = DB::table('status')->where('status','=','1')->get();
		$data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
		$data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
		$data['user_type'] = 1;
       return view('membership.test_membership')->with('data',$data);  
    }
}
