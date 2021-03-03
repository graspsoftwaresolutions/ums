<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ApiController extends Controller
{
    public function __construct() {
        ini_set('memory_limit', '-1');
    }

    public function storePrevilegeCard(Request $request)
    {
    	$membername = $request->input('membername');
    	$newicno = $request->input('newicno');
    	$memberno = $request->input('memberno');
    	$previlegecardno = $request->input('previlegecardno');

		$data = [
					'full_name' => $membername,
					'nric_new' => $newicno,
					'privilege_card_no' => $previlegecardno,
					'member_number' => $memberno,
				];
							
		$pcuserid = DB::table('privilege_card_users')->insertGetId($data);

    	if($request->hasfile('attachment'))

        {
         	$sno =1;

            foreach($request->file('attachment') as $file)

            {
            	$filenameWithExt = $file->getClientOriginalExtension();

            	$inputfilenames = $pcuserid.'_'.strtotime(date('Ymdhis')).'_'.$sno.'.'.$filenameWithExt;
               

                $file = $file->storeAs('privilege_card', $inputfilenames ,'local');

                $filedata = [
					'pc_user_id' => $pcuserid,
					'file_name' => $inputfilenames,
				];
				//dd($data);
				//dd($data);
				DB::table('privilege_card_files')->insert($filedata);
               
                $sno++;
            }

        }

        $return_data= ['message' => 'Card Registration Successfull' , 'status' => 1];

        return response()->json($return_data, 200);
    }

    public function validatePrevilegeCard(Request $request)
    {
    	$membername = $request->input('membername');
    	$newicno = $request->input('newicno');
    	$memberno = $request->input('memberno');
    	$previlegecardno = $request->input('previlegecardno');

    	$existcount=0;

    	if($newicno!='' && $previlegecardno!=''){
    		$existcount = DB::table('eco_park')->where('nric_new', $newicno)->orWhere('privilege_card_no', $previlegecardno)->count();
    	}

    	if($existcount!=0){
    		$return_data= ['message' => 'Data exists' , 'status' => 1];
    	}else{
    		$return_data= ['message' => 'You are not applicable for previlege card' , 'status' => 0];
    	}
        return response()->json($return_data, 200);
    }
}
