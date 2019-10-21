<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonthEndController extends Controller
{
    public function index(){
        $data = [];
    	return view('monthend_update')->with('data',$data); 
    }

    public function getMonthendInfo($lang,Request $request)
    {
      return $request->all();
    }
}
