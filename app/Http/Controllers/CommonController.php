<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\Membership;
use App\Model\Country;
use App\Model\State;
use App\Model\Relation;
use App\Model\City;
use App\Model\Company;
use App\Model\Race;
use App\Model\Reason;
use App\Model\Persontitle;
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
    //Country Details 
    public function countryDetail(Request $request)
    {
        $id = $request->id;
        $country = new Country();
        $data = Country::find($id);
        return $data;
    }
    //Country Name Exists Check
    public function checkCountryNameExists(Request $request)
    {
        $country_name =  $request->input('country_name');
        $country_id = $request->input('country_id');

        if(!empty($country_id))
          {
                 $countryname_exists = Country::where([
                  ['country_name','=',$country_name],
                  ['id','!=',$country_id],
                  ['status','=','1']
                  ])->count();
          }
          else
          {
            $countryname_exists = Country::where([
                ['country_name','=',$country_name],
                ['status','=','1'],     
                ])->count(); 
          } 
          if($countryname_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    }

    //Relation Details Start
    //Relation Name Exists Check
    public function checkRelationNameExists(Request $request)
    {
        $relation_name =  $request->input('relation_name');
        $relation_id = $request->input('relation_id');
        return $this->checkRelationExists($relation_name,$relation_id);
    }
    public function checkRelationExists($relation_name,$relation_id=false)
    {

        if(!empty($relation_id))
          {
                 $relation_exists = Relation::where([
                  ['relation_name','=',$relation_name],
                  ['id','!=',$relation_id],
                  ['status','=','1']
                  ])->count();
          }
          else
          {
            $relation_exists = Relation::where([
                ['relation_name','=',$relation_name],
                ['status','=','1'],
                ])->count(); 
          } 
          if($relation_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    }
    //Relation Details
    public function relationDetail(Request $request)
    {
        $id = $request->id;
        $relation = new Relation();
        $data = Relation::find($id);
        return $data;
    }
    //Relation Details End

    // Race Details Start
     //Race Name Exists Check
     public function checkRaceNameExists(Request $request)
     {
         
         $race_name =  $request->input('race_name');
         $race_id = $request->input('race_id');
         
         return $this->checkRaceExists($race_name,$race_id);
         //return $race_id;
     }
     //checkRaceExists
     public function checkRaceExists($race_name,$race_id=false)
     {   
         if(!empty($race_id))
          { 
                 $race_exists = Race::where([
                  ['race_name','=',$race_name],
                  ['id','!=',$race_id],
                  ['status','=','1']
                  ])->count();
          }
          else
          {   
            $race_exists = Race::where([
                ['race_name','=',$race_name],
                ['status','=','1'],
                ])->count(); 
          } 
          if($race_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    }
    //Relation Details
    public function raceDetail(Request $request)
    {
        $id = $request->id;
        $race = new Race();
        $data = Race::find($id);
        return $data;
    }
    // Race Details End

    /*
        Input $result = query result (array)
        Input $deleteRoute = Delete Route Name (string)
    */
    public function CommonAjaxReturn($result, $deleteRoute){
        $data = array();
        if(!empty($result))
        {
            foreach ($result as $resultdata)
            {
                foreach($resultdata as $newkey => $newvalue){
                    if($newkey=='id'){
                        $autoid = $newvalue;
                    }else{
                        $nestedData[$newkey] = $newvalue;
                    }
                }
                
                $enc_id = Crypt::encrypt($autoid);  
                $delete =  route($deleteRoute, [app()->getLocale(),$autoid]) ;
                $edit =  "#modal_add_edit";

                $actions ="<a style='float: left;' id='$edit' onClick='showeditForm($autoid);' class='btn-small waves-effect waves-light cyan modal-trigger' href='$edit'>".trans('Edit')."</a>";
                $actions .="<a><form style='float: left;margin-left:5px;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
                $actions .="<button  type='submit' class='btn-small waves-effect waves-light amber darken-4'  onclick='return ConfirmDeletion()'>".trans('Delete')."</button> </form>";
                $nestedData['options'] = $actions;
                $data[] = $nestedData;

            }
        }
        return $data;
    }

    //Reason Details Start
    //Reason Name Exists Check
    public function checkReasonNameExists(Request $request)
    { 
        $reason_name =  $request->input('reason_name');
        $reason_id = $request->input('reason_id');
        
        return $this->checkReasonExists($reason_name,$reason_id);
        //return $race_id;
    }
     //checkReasonExists
     public function checkReasonExists($reason_name,$reason_id=false)
     {   
         if(!empty($reason_id))
          { 
                 $reason_exists = Reason::where([
                  ['reason_name','=',$reason_name],
                  ['id','!=',$reason_id],
                  ['status','=','1']
                  ])->count();
          }
          else
          {   
            $reason_exists = Reason::where([
                ['reason_name','=',$reason_name],
                ['status','=','1'],
                ])->count(); 
          } 
          if($reason_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    }
      //Reason Details
      public function reasonDetail(Request $request)
      {
          $id = $request->id;
          $reason = new Reason();
          $data = Reason::find($id);
          return $data;
      }
    //Reason Details End

    //Person Title Details Start
    public function checkTitleNameExists(Request $request)
    { 
        $person_title =  $request->input('person_title');
        $persontitle_id = $request->input('persontitle_id');
        
        return $this->checkPersonTitleExists($person_title,$persontitle_id);
        //return $race_id;
    }
     //checkPerson Title Exists
     public function checkPersonTitleExists($person_title,$persontitle_id=false)
     {   
         if(!empty($persontitle_id))
          { 
                 $persontitle_exists = Persontitle::where([
                  ['person_title','=',$person_title],
                  ['id','!=',$persontitle_id],
                  ['status','=','1']
                  ])->count();
          }
          else
          {   
            $persontitle_exists = Persontitle::where([
                ['person_title','=',$person_title],
                ['status','=','1'],
                ])->count(); 
          } 
          if($persontitle_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    }
    //Person Title Details
    public function personTitleDetail(Request $request)
    {
        $id = $request->id;
        $persontitle = new Persontitle();
        $data = Persontitle::find($id);
        return $data;
    }
    //Person Title Details End

    
}
