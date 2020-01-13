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
use App\Model\Status;
use App\Model\Persontitle;
use App\Model\Designation;
use App\Model\UnionBranch;
use App\Model\CompanyBranch;
use App\Model\Fee;
use App\Model\FormType;
use App\Model\AppForm;
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
        ini_set('memory_limit', '-1');
        $this->membermonthendstatus_table = "membermonthendstatus";
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
        //dd($dob);
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
    
	
	//State Details 
    public function stateDetail(Request $request)
    {
        $id = $request->id;
        $state = new State();
        $data = State::find($id);
        return $data;
    }
	
	
	//City Details 
    public function cityDetail(Request $request)
    {
        $id = $request->id;
        $city = new City();
        $data = City::find($id);
        return $data;
    }
	
	
    //Relation Details Start
    
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
	
	//Fee Details 
    public function feeDetail(Request $request)
    {
        $id = $request->id;
        $fee = new Fee();
        $data = Fee::find($id);
        return $data;
    }
	
	
	//Role Details 
    public function roleDetail(Request $request)
    {
        $id = $request->id;
        $role = new Role();
        $roles = Role::find($request->id);
        $data['roles'] = $roles;
        $data['roles_module'] =  $roles->formTypes;
        return $data;
    }
	
    /*
        Input $result = query result (array)
        Input $deleteRoute = Delete Route Name (string)
        deletetype : 0 => post , 1 => get, 2 => hide
        edittype : 0 => popup , 1 => page
    */
    public function CommonAjaxReturn($result, $deletetype, $deleteRoute,$edittype,$table,$editRoute=false){
        $data = array();
        $get_roles = Auth::user()->roles;
		$user_role = $get_roles[0]->slug;
		$user_id = Auth::user()->id;
        if(!empty($result))
        {
            //$memberscount = CommonHelper::mastersMembersCount($table,$autoid, $user_role, $user_id);
            //dd($table);

            foreach ($result as $resultdata)
            {
                $serial = 0;
                foreach($resultdata as $newkey => $newvalue){
                   
                    if($newkey=='id'){
                        $autoid = $newvalue;
                        
                    }else{
                        $memberscount = 0;
                        if($table!='reason'){
                            $memberscount = CommonHelper::mastersMembersCount($table,$autoid, $user_role, $user_id);
                        }
                      
                        
                        if($table=='state' || $table=='company_branch')
                        {
                            if($serial == 1)
                            {
                                $nestedData[$newkey] = $newvalue."&nbsp;&nbsp;&nbsp;".'<span class="badge badge pill light-blue mr-10">'.$memberscount.'</span>';
                            }
                            else{
                                $nestedData[$newkey] = $newvalue;
                            }
                            $serial++;
                        }
                        else{

                            if($serial == 0)
                            {
                                if($table!='reason'){
                                    $nestedData[$newkey] = $newvalue."&nbsp;&nbsp;&nbsp;".'<span class="badge badge pill light-blue mr-10">'.$memberscount.'</span>';
                                }else{
                                    $nestedData[$newkey] = $newvalue;
                                }
                            }
                            else{
                                $nestedData[$newkey] = $newvalue;
                            }
                            $serial++;
                        }
                    }
                }
                
                $enc_id = Crypt::encrypt($autoid);
                
                //dd($nestedData[$newkey]);
               // $memberscount = CommonHelper::countryMembersCount($table,$autoid, $user_role, $user_id);
               // $nestedData[$newkey] = "|".$memberscount;
                //dd($nestedData[$newkey]);
				if($deleteRoute == "")
				{
					$delete = "";
				}else{
                $delete =  route($deleteRoute, [app()->getLocale(),$autoid]) ;
				}
                if($edittype==0){
                    $edit =  "#";
                }
				else if($edittype==2){
                    $edit =  "#";
                }else{
                    $edit =  route($editRoute,[app()->getLocale(),$enc_id]);
                }
				$actions ='';
				
				if($edittype!=2){
					$actions ="<label style='width:100% !important;float:left;text-align:center;'><a style='' id='$edit' onClick='showeditForm($autoid);' class='' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";
				}
               

                
                if($deletetype==0){
                    $actions .="<a><form style='display:inline-block;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
                    $actions .="<button  type='submit' class='' style='background:none;border:none;'  onclick='return ConfirmDeletion()'><i class='material-icons' style='color:red;'>delete</i></button> </form>";
                }
				else if($deletetype==2){
                    $actions .="";
                }
				else{
                    if($user_role=='union'){
                        $actions .="&nbsp; <a href='$delete' onclick='return ConfirmDeletion()' ><i class='material-icons' style='color:red;'>delete</i></a></label>";
                    }
                    
                }
                
               
              
                $nestedData['options'] = $actions;
                $data[] = $nestedData;

            }
        }
        return $data;
    }

    //Reason Details Start
    
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

     
     //checkDesignation Exists
     public function checkDesignationExists($designation_name,$designation_id=false)
     {   
         if(!empty($designation_id))
          { 
                 $designation_exists = Designation::where([
                  ['designation_name','=',$designation_name],
                  ['id','!=',$designation_id],
                  ['status','=','1']
                  ])->count();
          }
          else
          {   
            $designation_exists = Designation::where([
                ['designation_name','=',$designation_name],
                ['status','=','1'],
                ])->count(); 
          } 
          if($designation_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    }
    public function designationDetail(Request $request)
    {
        $id = $request->id;
        $Designation = new Designation();
        $data = Designation::find($id);
        return $data;
    } 
    //Designation Details End
    
    //Check branch starts
	public function BranchmailExists($email,$autoid=false){
          //  return $userid;
          if(!empty($autoid))
          {
              $useremail_exists = UnionBranch::where([
                  ['email','=',$email],
                  ['id','!=',$autoid]
                  ])->count(); 
          }
          else
          {
              $useremail_exists = UnionBranch::where('email','=',$email)->count(); 
          } 
          if($useremail_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    }
    //Branch End

    public function CompanyBranchmailExists($email,$autoid=false){
        //  return $userid;
        if(!empty($autoid))
        {
            $useremail_exists = CompanyBranch::where([
                ['email','=',$email],
                ['id','!=',$autoid]
                ])->count(); 
        }
        else
        {
            $useremail_exists = CompanyBranch::where('email','=',$email)->count(); 
        } 
        if($useremail_exists > 0)
        {
            return "false";
        }
        else{
            return "true";
        }
  }

    
	public function checkStatusExists($status_name,$status_id=false)
     {   
         if(!empty($status_id))
          { 
                 $status_exists = Status::where([
                  ['status_name','=',$status_name],
                  ['id','!=',$status_id],
                  ['status','=','1']
                  ])->count();
          }
          else
          {   
            $status_exists = Status::where([
                ['status_name','=',$status_name],
                ['status','=','1'],
                ])->count(); 
          } 
          if($status_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    }
    public function statusDetail(Request $request)
    {
        $id = $request->id;
        $Status = new Status();
        $data = Status::find($id);
        return $data;
    } 
    //Status Details End

    
    public function checkFormTyNameExists($formname,$formtype_id=false)
    {   
         if(!empty($formtype_id))
          { 
                 $formtype_exists = FormType::where([
                  ['formname','=',$formname],
                  ['id','!=',$formtype_id],
                  ['status','=','1']
                  ])->count();
          }
          else
          { 
            $formtype_exists = FormType::where([
                ['formname','=',$formname],
                ['status','=','1'],
                ])->count(); 
          } 
          if($formtype_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    } 
    public function formTypeDetail(Request $request)
    {
        $id = $request->id;
        $FormType = new FormType();
        $data = FormType::find($id);
        return $data;
    } 
   

    
    public function checkCompanyExists($company_name,$company_id=false)
    {   
         if(!empty($company_id))
          { 
            $companyname_exists = Company::where([
            ['company_name','=',$company_name],
            ['id','!=',$company_id],
            ['status','=','1']
            ])->count();
          }
          else
          {
            $companyname_exists = Company::where([
            ['company_name','=',$company_name],
            ['status','=','1'],
            ])->count(); 
          } 
          if($companyname_exists > 0)
          {
              return "false";
          }
          else{
              return "true";
          }
    } 
    public function companyDetail(Request $request)
    {
        $id = $request->id;
        $Company = new Company();
        $data['company'] = Company::find($id);
        $data['head_company'] = Company::where([
            ['id','!=',$id],
            ['status','=',1]
            ])->get();
           // print_r($data['head_company']); exit;
        return $data;
    } 
    public function saveCompanyDetail(Request $request)
    {
        $Company = new Company();
        $data['company'] = Company::where('status','=',1)->get();
        return $data;
    }
      //Company Deatils End 
    public function getConditionalBranchList(Request $request){
       
        $companyid = $request->company_id;
        $unionbranch_id = $request->unionbranch_id;
        $branch_id = $request->branch_id;

        $res = CommonHelper::getCompanyBranchList($companyid, $branch_id, $unionbranch_id);
      
        return response()->json($res);
    }

    public function membermailExists($email,$autoid=false){
        //  return $userid;
        if(!empty($autoid))
        {
            $useremail_exists = Membership::where([
                ['email','=',$email],
                ['id','!=',$autoid]
                ])->count(); 
        }
        else
        {
            $useremail_exists = Membership::where('email','=',$email)->count(); 
        } 
        if($useremail_exists > 0)
        {
            return "false";
        }
        else{
            return "true";
        }
  }
   /*
        Input $result = query result (array)
        Input $deleteRoute = Delete Route Name (string)
        deletetype : 0 => post , 1 => get, 2 => hide
        edittype : 0 => popup , 1 => page
    */
    public function CommonAjaxReturnold($result, $deletetype, $deleteRoute,$edittype,$editRoute=false){
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
				if($deleteRoute == "")
				{
					$delete = "";
				}else{
                $delete =  route($deleteRoute, [app()->getLocale(),$autoid]) ;
				}
                if($edittype==0){
                    $edit =  "#";
                }
				else if($edittype==2){
                    $edit =  "#";
                }else{
                    $edit =  route($editRoute,[app()->getLocale(),$enc_id]);
                }
				$actions ='';
				
				if($edittype!=2){
					$actions ="<label style='width:100% !important;float:left;text-align:center;'><a style='' id='$edit' onClick='showeditForm($autoid);' class='' href='$edit'><i class='material-icons' style='color:#2196f3'>edit</i></a>";
				}
               
                
                if($deletetype==0){
                    $actions .="<a><form style='display:inline-block;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
                    $actions .="<button  type='submit' class='' style='background:none;border:none;'  onclick='return ConfirmDeletion()'><i class='material-icons' style='color:red;'>delete</i></button> </form>";
                }
				else if($deletetype==2){
                    $actions .="";
                }
				else{
                    $actions .="&nbsp; <a href='$delete' onclick='return ConfirmDeletion()' ><i class='material-icons' style='color:red;'>delete</i></a></label>";
                }
                
                $nestedData['options'] = $actions;
                $data[] = $nestedData;
            }
        }
        return $data;
    }
    public static function getCompanyList(Request $request)
    {
        
        $id = $request->unionbranch_id;
        $res = DB::table('company as c')
                ->select('c.id','c.company_name as company_name')
                ->leftjoin('company_branch as cb','c.id','=','cb.company_id')
                ->leftjoin('union_branch as ub','cb.union_branch_id','=','ub.id')
                ->where([
                    ['ub.id','=',$id]
                ])
                ->distinct('company_name')
                ->get();
        
                return response()->json($res);
    }

    public function getCompanyBranchesList(Request $request)
    {
        $id = $request->company_id;
        $res = DB::table('company_branch as cb')->select('cb.id as branchid','cb.branch_name')
                ->leftjoin('company as c','c.id','=','cb.company_id')
                ->where([
                    ['cb.company_id','=',$id]
                ])
                ->distinct('branch_name')
                ->get();
        return response()->json($res);
    } 
    
    public function getUnionBranchesList(Request $request)
    {
        $id = $request->unionbranch_id;
        $res = DB::table('company_branch as cb')
            ->select('cb.id','cb.branch_name')
                ->leftjoin('union_branch as ub','ub.id','=','cb.union_branch_id')
                ->where([
                    ['cb.union_branch_id','=',$id]
                ])
                ->distinct('cb.branch_name')
                ->get();
        return response()->json($res);
    }
}
