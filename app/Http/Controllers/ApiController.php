<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Log;

class ApiController extends Controller
{
    public function __construct() {
        ini_set('memory_limit', '-1');
    }

    public function storePrevilegeCard(Request $request)
    {
    	$membername = $request->input('membername');
    	$hyphennewicno = $request->input('newicno');
    	$memberno = $request->input('memberno');
    	$previlegecardno = $request->input('previlegecardno');

        $newicno = str_replace("-", "", $hyphennewicno);

    	$existcount=0;

    	Log::channel('apilog')->info($request->all());


       // Log::channel('apilog')->info(DB::table('eco_park')->select('id','member_id')->whereRaw("replace(nric_new, '-', '')", $newicno)->orWhere('privilege_card_no', $previlegecardno)->dump()->get());

    	//return $request->all();

    	if($hyphennewicno!='' && $previlegecardno!=''){
    		$existcount = DB::table('eco_park')->where(DB::raw("replace(nric_new, '-', '')"),"=", $newicno)->orWhere('privilege_card_no', $previlegecardno)->count();
    	}

    	if($existcount!=0){
            $ecoparkdata = DB::table('eco_park')->select('id','member_id')->where(DB::raw("replace(nric_new, '-', '')"),"=", $newicno)->orWhere('privilege_card_no', $previlegecardno)->first();
           // dd(2);
            //Log::channel('apilog')->info($ecoparkdata->id);
    		$data = [
					'full_name' => $membername,
					'nric_new' => $newicno,
					'privilege_card_no' => $previlegecardno,
					'member_number' => $memberno,
                    'member_id' => $ecoparkdata->member_id,
                    'ecopark_id' => $ecoparkdata->id,
                    'status' => 0,
                    'created_at' => date('Y-m-d h:i:s'),
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
                        'created_at' => date('Y-m-d h:i:s'),
					];
					//dd($data);
					//dd($data);
					DB::table('privilege_card_files')->insert($filedata);
	               
	                $sno++;
	            }

	        }
	         $return_data= 1;
    	}else{
    		 $return_data= 0;
    		//return redirect('http://192.168.1.11/');
    	}

		

       Log::channel('apilog')->info('register status: '.$return_data);

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
