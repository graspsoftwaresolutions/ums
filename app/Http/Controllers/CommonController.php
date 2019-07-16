<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\Membership;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Company;
use DB;
use View;
use Mail;
use App\Role;
use App\User;
use App\Model\MemberNominees;
use App\Model\MemberGuardian;
use App\Model\MemberFee;
use App\Helpers\CommonHelper;
use App\Mail\SendMemberMailable;
use URL;
use Auth;

use Carbon\Carbon;

class CommonController extends Controller
{
	public function __construct()
    {
		
	}
	public function userDetail(Request $request)
    {
        $id = $request->id;
        $User = new User();
        $data = User::find($id);
        return $data;
    }
    public function getStateList(Request $request)
    {
        $id = $request->country_id;
        $res = DB::table('state')
                ->select('id','state_name')
                ->where([
                    ['country_id','=',$id],
                    ['status','=','1']
                ])->get();
        
                return response()->json($res);
    }
    public function getCitiesList(Request $request){
      
        $id = $request->State_id;
        $res = DB::table('city')
        ->select('id','city_name')
        ->where([
            ['state_id','=',$id],
            ['status','=','1']
        ])->get();
       
        return response()->json($res);
    }

    public function getAge(Request $request)
    {
         $dob1 = $request->dob;
           
         $fmm_date = explode("/",$dob1);					
         $dob2 = $fmm_date[2]."-".$fmm_date[1]."-".$fmm_date[0];
         $dob = date('Y-m-d', strtotime($dob2));

         $years = Carbon::parse($dob)->age;

         echo $years;
    }
    public function getBranchList(Request $request){
       
         $id = $request->company_id;
         $res = DB::table('company_branch')
         ->select('id','branch_name')
         ->where([
             ['company_id','=',$id],
             ['status','=','1']
         ])->get();
       
         return response()->json($res);
     }
	 public function getFeesList(){
      
        $res = DB::table('fee')->where('status','=','1')->get();
       
        return response()->json($res);
    }
    public function mailExists($email,$userid=false){
          //  return $userid;
          if(!empty($userid))
          {
              $useremail_exists = User::where([
                  ['email','=',$email],
                  ['id','!=',$userid]
                  ])->count(); 
          }
          else
          {
              $useremail_exists = User::where('email','=',$email)->count(); 
          } 
          if($useremail_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    }

    //user exists check 
    public function checkemailExists(Request $request)
    {
        $email =  $request->input('email');
        $userid = $request->input('login_userid');
        return $this->mailExists($email,$userid);
      
    }
}
