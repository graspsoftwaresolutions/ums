<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Fee;
use App\User;
use App\Model\Relation;
use App\Model\Race;
use App\Model\Reason;
use App\Model\Persontitle;
use App\Model\UnionBranch;
use App\Model\AppForm;
use App\Model\CompanyBranch;
use App\Model\Designation;
use App\Model\Status;
use App\Model\FormType;
use App\Model\Company;
use App\Mail\UnionBranchMailable;
use App\Mail\CompanyBranchMailable;
use DB;
use View;
use Mail;
use App\Role;
use URL;
use Response;


class MasterController extends CommonController {

    public function __construct() {
        ini_set('memory_limit', '-1');
        $this->middleware('auth');
        $this->middleware('module:master');
        $this->Country = new Country;
        $this->state = new state;
        $this->City = new City;
        $this->User = new User;
        $this->Relation = new Relation;
        $this->Race = new Race;
        $this->Fee = new Fee;
        $this->Role = new Role;
        $this->Reason = new Reason;
        $this->UnionBranch = new UnionBranch;
        $this->AppForm = new AppForm;
        $this->Persontitle = new Persontitle;
        $this->Designation = new Designation;
        $this->Status = new Status; 
        $this->FormType = new FormType;
        $this->CompanyBranch = new CompanyBranch;
        $this->Company = new Company;
    }

    public function countryList() {
        $data['country_view'] = Country::all();
        return view('master.country.country_list')->with('data', $data);
    }


    public function countrySave(Request $request)
    {

        $request->validate([
            'country_name' => 'required',
                ], [
            'country_name.required' => 'please enter Country name',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();

        if(!empty($request->id)){
            $data_exists = $this->mailExists($request->input('country_name'),$request->id);
        }else{
            $data_exists = $this->mailExists($request->input('country_name'));
            $data_exists = Country::where([
                ['country_name','=',$request->country_name],['status','=','1']
                ])->count();
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/country')->with('error','Country Already Exists'); 
        }
        else{

            $saveCountry = $this->Country->saveCountrydata($data);

            if ($saveCountry == true) {

                if(!empty($request->id))
                {
                    return redirect($defdaultLang . '/country')->with('message', 'Country Name Updated Succesfully');
                }
                else
                {
                    return redirect($defdaultLang . '/country')->with('message', 'Country Name Added Succesfully');
                }
                
            }
        }
    }

    public function countrydestroy($lang,$id)
	{
        $Country = new Country();
        $Country = Country::find($id);
        $Country->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/country')->with('message','Country Details Deleted Successfully!!');
	}

    public function stateList()
    {	
		$data['country_view'] = Country::where('status','=','1')->get();
        return view('master.state.state_list')->with('data',$data);
    }
	
	
	public function stateSave(Request $request)
    {

        $request->validate([
            'country_id' => 'required',
			'state_name' => 'required',
                ], [
            'country_id.required' => 'please enter Country name',
			'state_name.required' => 'please enter State name',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();

        if(!empty($request->id)){
            $data_exists = $this->mailExists($request->input('state_name'),$request->id);
        }else{
            $data_exists = $this->mailExists($request->input('state_name'));       
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/state')->with('error','User Email Already Exists'); 
        }
        else{

            $saveState = $this->state->saveStatedata($data);
            
            if ($saveState == true) {
                if(!empty($request->id))
                {
                    return redirect($defdaultLang . '/state')->with('message', 'State Name Updated Succesfully');
                }
                else
                {
                    return redirect($defdaultLang . '/state')->with('message', 'State Name Added Succesfully');
                }
            }
        }
    }
	public function statedestroy($lang,$id)
	{
        $State = new state();
        $State = state::find($id);
        $State->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/state')->with('message','State Details Deleted Successfully!!');
	}
	
		
	// City List
	
	public function cityList()
    {
        $data['country_view'] = Country::where('status','=','1')->get();
        $data['state_view'] = State::where('status','=','1')->get();
        return view('master.city.city_list',compact('data',$data));
    }
	
	public function citySave(Request $request)
    {

        $request->validate([
            'country_id' => 'required',
			'state_id' => 'required',
			'city_name' => 'required',
                ], [
            'country_id.required' => 'please enter Country name',
			'state_id.required' => 'please enter State name',
			'city_name.required' => 'please enter City name',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();

        if(!empty($request->id)){
            $data_exists = $this->mailExists($request->input('city_name'),$request->id);
        }else{
            $data_exists = $this->mailExists($request->input('city_name'));
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/city')->with('error','User Email Already Exists'); 
        }
        else{

            $saveCity = $this->City->saveCitydata($data);

            if ($saveCity == true) {
                if(!empty($request->id))
                {
                    return redirect($defdaultLang . '/city')->with('message', 'City Name Updated Succesfully');
                }
                else
                {
                    return redirect($defdaultLang . '/city')->with('message', 'City Name Added Succesfully');
                }
            }
        }
    }
	public function citydestroy($lang,$id)
	{
        $City = new City();
        $City = City::find($id);
        $City->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/city')->with('message','City Details Deleted Successfully!!');
	}
    //user Details Save and Update
    public function userSave(Request $request) {
        $request->validate([
            'name' => 'required',
                ], [
            'name.required' => 'Please enter User name',
        ]);

        $data = $request->all();

        $defdaultLang = app()->getLocale();

        if (!empty($request->id)) {
            $data_exists = $this->mailExists($request->input('email'), $request->id);
        } else {
            $data_exists = $this->mailExists($request->input('email'));
        }

        if ($data_exists > 0) {
            return redirect($defdaultLang . '/users')->with('error', 'User Email Already Exists');
        } else if (empty($request->id)) {
            if (($request->password == $request->confirm_password)) {
                $data['password'] = Crypt::encrypt($request->password);
                //return $data;
                $saveUser = $this->User->saveUserdata($data);
            }

            if ($saveUser == true) {
                return redirect($defdaultLang . '/users')->with('message', 'User Added Succesfully');
            } else {
                return redirect($defdaultLang . '/users')->with('error', 'passwords are mismatch');
            }
        } else {

            $updata['id'] = $request->id;
            $updata['email'] = $request->email;
            $updata['name'] = $request->name;

            $saveUser = $this->User->saveUserdata($updata);
            if ($saveUser == true) {
                if(!empty($request->id))
                {
                    return redirect($defdaultLang . '/users')->with('message', 'User Updated Succesfully');
                }
                else{
                    return redirect($defdaultLang . '/users')->with('message', 'User Added Succesfully');
                }
            }
        }
    }
    
    //User Destroy
    public function user_destroy($lang, $id)
    {
        $User = new User();
        $User = User::find($id);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/users')->with('error','user cannot be deleted!!');
        if($id!=1){
            $User->delete();
            return redirect($defdaultLang.'/users')->with('message','User Details Deleted Successfully!!');
        }else{
            return redirect($defdaultLang.'/users')->with('error','Union user cannot be deleted!!');
        }
       
       
    }

    public function users_list()
    {
        return view('master.users.users');
    }
    
    //Relation Details 
    public function relationList()
    {
        return view('master.relation.relation_list');
    }
    //Relation Save and Update
    public function Relationsave(Request $request)
    {   
        $request->validate([
            'relation_name'=>'required',
        ],
        [
            'relation_name.required'=>'please enter Relation name',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();
        
        if(!empty($request->id)){
            $data_exists = $this->checkRelationExists($request->input('relation_name'),$request->id);
        }else{
            $data_exists = $this->checkRelationExists($request->input('relation_name'));
            $data_exists = Relation::where([
                ['relation_name','=',$request->relation_name],['status','=','1']
                ])->count();
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/relation')->with('error','Relation Name Already Exists'); 
        }
        else{
            $saveRelation = $this->Relation->saveRelationdata($data);
           
            if($saveRelation == true)
            {
                if(!empty($request->id))
                {
                 return  redirect($defdaultLang.'/relation')->with('message','Relation Name Updated Succesfully');
                }
                else{
                    return  redirect($defdaultLang.'/relation')->with('message','Relation Name Added Succesfully');
                }
            }
        }
    }
    public function relationDestroy($lang,$id)
	{
        $Relation = new Relation();
        $Relation = Relation::find($id);
        $Relation->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/relation')->with('message','Relation Details Deleted Successfully!!');
    }

    //Race Details Start
    public function raceList()
    {
        return view('master.race.race_list');
    }
    
    //Race Save and Update
    public function raceSave(Request $request)
    {   
        $request->validate([
            'race_name'=>'required',
        ],
        [
            'race_name.required'=>'please enter Race name',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();
        
        if(!empty($request->id)){
            $data_exists = $this->checkRaceExists($request->input('race_name'),$request->id);
        }else{
            $data_exists = $this->checkRaceExists($request->input('race_name'));
            $data_exists = Race::where([
                ['race_name','=',$request->input('race_name')],['status','=','1']
            ])->count();
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/race')->with('error','Race Name Already Exists'); 
        }
        else{
            $saveRace = $this->Race->saveRacedata($data);
           
            if($saveRace == true)
            {
                if(!empty($request->id))
                {
                    return  redirect($defdaultLang.'/race')->with('message','Race Name Updated Succesfully');
                }
                else{
                    return  redirect($defdaultLang.'/race')->with('message','Race Name Added Succesfully');
                }
            }
        }
    }
    public function raceDestroy($lang,$id)
	{
        $Race = new Race();
        $Race = Race::find($id);
        $Race->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/race')->with('message','Race Details Deleted Successfully!!');
    }
    // Race Details End

    //Reason Details Start
    public function reasonList()
    {
        return view('master.reason.reason_list');
    }
     
    //Reason Save and Update
    public function reasonSave(Request $request)
    {   
        $request->validate([
            'reason_name'=>'required',
        ],
        [
            'reason_name.required'=>'please enter Reason name',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();
        
        if(!empty($request->id)){
            $data_exists = $this->checkReasonExists($request->input('reason_name'),$request->id);
        }else{
            $data_exists = $this->checkReasonExists($request->input('reason_name'));
            $data_exists = Reason::where([
                ['reason_name','=',$request->input('reason_name')],['status','=','1']
            ])->count();
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/reason')->with('error','Reason Name Already Exists'); 
        }
        else{
            $saveReason = $this->Reason->saveReasondata($data);
           
            if($saveReason == true)
            {
                if(!empty($request->id))
                {
                     return  redirect($defdaultLang.'/reason')->with('message','Reason Name Updated Succesfully');
                }
                else{
                    return  redirect($defdaultLang.'/reason')->with('message','Reason Name Added Succesfully');
                }
            }
        }
    }
    public function reasonDestroy($lang,$id)
	{
        $Reason = new Reason();
        $Reason = Reason::find($id);
        $Reason->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/reason')->with('message','Reason Details Deleted Successfully!!');
    }
    //Reason Details End

    //Person Title Details Starts
    public function titleList()
    {
        return view('master.persontitle.persontitle_list');
    }
    
     //Person Title Save and Update
     public function personTileSave(Request $request)
     {   
         $request->validate([
             'person_title'=>'required',
         ],
         [
             'person_title.required'=>'please enter Person Title name',
         ]);
         $data = $request->all();   
         $defdaultLang = app()->getLocale();
         
         if(!empty($request->id)){
             $data_exists = $this->checkPersonTitleExists($request->input('person_title'),$request->id);
         }else{
             $data_exists = $this->checkPersonTitleExists($request->input('person_title'));
             $data_exists = Persontitle::where([
                ['person_title','=',$request->input('person_title')],['status','=','1']
            ])->count();
         }
         if($data_exists>0)
         {
             return  redirect($defdaultLang.'/persontitle')->with('error','Person Title Name Already Exists'); 
         }
         else{
             $savePersonTitle = $this->Persontitle->savePersonTitledata($data);
            
             if($savePersonTitle == true)
             {
                if(!empty($request->id))
                {
                     return  redirect($defdaultLang.'/persontitle')->with('message','Person Title Updated Succesfully');
                }
                else{
                    return  redirect($defdaultLang.'/persontitle')->with('message','Person Title Added Succesfully');
                }
             }
         }
     }
    public function personTiteDestroy($lang,$id)
	{
        $Persontitle = new Persontitle();
        $Persontitle = Persontitle::find($id);
        $Persontitle->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/persontitle')->with('message','Person Title Details Deleted Successfully!!');
    }
    //Person title Details End

    //Designation Details Start
    public function designationList()
    {
        return view('master.designation.designation_list');
    }
    
     //Designation Save and Update
     public function designationSave(Request $request)
     {   
         $request->validate([
             'designation_name'=>'required',
         ],
         [
             'designation_name.required'=>'please enter Designation name',
         ]);
         $data = $request->all();   
         $defdaultLang = app()->getLocale();
         
         if(!empty($request->id)){
             $data_exists = $this->checkDesignationExists($request->input('designation_name'),$request->id);
         }else{
             $data_exists = $this->checkDesignationExists($request->input('designation_name'));
             $data_exists = Designation::where([
                ['designation_name','=',$request->input('designation_name')],['status','=','1']
            ])->count();
         }
         if($data_exists>0)
         {
             return  redirect($defdaultLang.'/designation')->with('error','Designation Name Already Exists'); 
         }
         else{
             $saveDesignation = $this->Designation->saveDesignationdata($data);
            
             if($saveDesignation == true)
             {
                if(!empty($request->id))
                {
                    return  redirect($defdaultLang.'/designation')->with('message','Designation Updated Succesfully');
                }
                else
                {
                    return  redirect($defdaultLang.'/designation')->with('message','Designation Added Succesfully');
                }
             }
         }
     }
     public function designationDestroy($lang,$id)
     {
         $Designation = new Designation();
         $Designation = Designation::find($id);
         $Designation->where('id','=',$id)->update(['status'=>'0']);
         $defdaultLang = app()->getLocale();
         return redirect($defdaultLang.'/designation')->with('message','Person Title Details Deleted Successfully!!');
     }
    //Designation Details End

    //Union Branch Details Start
    public function  unionBranchList()
    {
        //$data['union_view'] = DB::table('union_branch')->where('status','=','1')->get();
        return view('master.unionbranch.unionbranch');
    }
	public function addUnionBranch()
    {
        $Defcountry = CommonHelper::DefaultCountry();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['state_view'] = State::where('status','=','1')->where('country_id',$Defcountry)->get();
        return view('master.unionbranch.unionbranch_details')->with('data',$data);
    }

    public function UnionBranchsave(Request $request)
    {
        $redirect_failurl = app()->getLocale().'/unionbranch';
        $redirect_url = app()->getLocale().'/unionbranch';
        $auto_id = $request->input('auto_id');
        $request->validate([
            'branch_name'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'postal_code'=>'required',
            'address_one'=>'required',
        ],
        [
            'branch_name.required'=>'Please Enter Branch Name',
            'phone.required'=>'Please Enter Mobile Number',
            'email.required'=>'Please Enter Email Address',
            'country_id.required'=>'Please choose  your Country',
            'state_id.required'=>'Please choose  your State',
            'city_id.required'=>'Please choose  your city',
            'postal_code.required'=>'Please Enter postal code',
            'address_one.required'=>'Please Enter your Address',
        ]);
		$union['union_branch'] = $request->input('branch_name');
		$union['phone'] = $request->input('phone');
		$union['mobile'] = $request->input('mobile');
		$union['email'] = $request->input('email');
		$union['postal_code'] = $request->input('postal_code');
		$union['country_id'] = $request->input('country_id');
		$union['state_id'] = $request->input('state_id');
		$union['city_id'] = $request->input('city_id');
		$union['address_one'] = $request->input('address_one');
		$union['address_two'] = $request->input('address_two');
		$union['address_three'] = $request->input('address_three');
		$is_head = $request->input('is_head');
		if(isset($is_head)){
			$union['is_head'] = 1;
		}else{
			$union['is_head'] = 0;
		}
		$files = $request->file('logo');
            
		if(!empty($files))
		{
			$image_name = time().'.'.$files->getClientOriginalExtension();
			$files->move('public/assets/images/logo/',$image_name);
			$union['logo'] = $image_name;
		}
		$defaultLanguage = app()->getLocale();
		
        if($auto_id==""){
            $union_head_role = Role::where('slug', 'union')->first();
            $union_branch_role = Role::where('slug', 'union-branch')->first();
            $randompass = CommonHelper::random_password(5,true);
            $redirect_failurl = app()->getLocale().'/unionbranch';
            $redirect_url = app()->getLocale().'/unionbranch';
			
            
            //Data Exists
            $data_exists_unionemail = DB::table('union_branch')->where([
                                        ['email','=',$union['email']]
                                        ])->count();
										echo 'br';
            $data_exists_usersemail = DB::table('users')->where('email','=',$union['email'])->count();
            if($data_exists_unionemail > 0 ||  $data_exists_usersemail > 0)
            {
                return redirect($defaultLanguage.'/save-unionbranch')->with('error','Email Already Exists');
            }
            else
            {
				$member_user = new User();
				$member_user->name = $request->input('branch_name');
				$member_user->email = $request->input('email');
				$member_user->password = bcrypt($randompass);
				$member_user->save();
                $union_type =2;
                DB::connection()->enableQueryLog();
                if($union['is_head']==1){
                    $rold_id_1 = DB::table('users_roles')->where('role_id','=','1')->update(['role_id'=>'2']);
                    $rold_id_2 = DB::table('union_branch')->where('is_head','=','1')->update(['is_head'=>'0']);
                    //$queries = DB::getQueryLog();
                   // dd($queries);
                    $member_user->roles()->attach($union_head_role);
                   
                    $union_type =1;
                }else{
                    $member_user->roles()->attach($union_branch_role);
                }
                $user_id = $member_user->id;
                $union['user_id'] = $user_id;
                $id = $this->UnionBranch->StoreUnionBranch($union);
                $status =1;
                
            }
            if($status == 1){
                $mail_data = array( 
                    'name' => $union['union_branch'],
                    'email' => $union['email'],
                    'password' => $randompass,
                    'site_url' => URL::to("/"),
                    'union_type' => $union_type,
                );
                $cc_mail = CommonHelper::getCCTestMail();
                $status = Mail::to($union['email'])->cc([ $cc_mail ])->send(new UnionBranchMailable($mail_data));

                if( count(Mail::failures()) > 0 ) {
                    return redirect($redirect_url)->with('message','Union Account created successfully, Failed to send mail');
                }else{
                    return redirect($redirect_url)->with('message','Union Account created successfully, password sent to mail');
                }
            }
            if($status == 0)
            {
                return redirect()->back()->with('error','please fill  valid data');
            }
        }else{
            $auto_id = $request->input('auto_id');
            $user_id = UnionBranch::where('id',$auto_id)->pluck('user_id')[0];
            $rold_id_21 = DB::table('users')->where('id','=',$user_id)->update(['name'=> $request->input('branch_name')]);
            if($union['is_head'] == 0)
            {
                $id = DB::table('union_branch')->where('id','=',$auto_id)->update($union);
                $rold_id_2 = DB::table('users_roles')->where('role_id','=','1')->where('user_id','=',$user_id)->update(['role_id'=>'2']);
               
            }else{
                $data = DB::table('union_branch')->where('is_head','=','1')->update(['is_head'=>'0']);
                $rold_id_2 = DB::table('users_roles')->where('role_id','=','1')->update(['role_id'=>'2']);
                $rold_id_2 = DB::table('users_roles')->where('user_id','=',$user_id)->update(['role_id'=>'1']);
                $id = DB::table('union_branch')->where('id','=',$auto_id)->update($union);
            }
			return redirect($defaultLanguage.'/unionbranch')->with('message','Union Branch Name Updated Succesfully');
        }
       
    }

    public function EditUnionBranch($lang,$id)
    {
        DB::connection()->enableQueryLog();
        $id = Crypt::decrypt($id);
        $data['union_branch'] = DB::table('union_branch')->select('union_branch.id as branchid','union_branch.id','union_branch.union_branch','union_branch.is_head','union_branch.country_id','union_branch.state_id','union_branch.city_id','union_branch.postal_code','union_branch.address_one','union_branch.address_two','union_branch.phone','union_branch.email','union_branch.is_head',
                                            'union_branch.status','union_branch.address_three','union_branch.mobile','union_branch.logo','country.id','country.country_name','country.status','state.id','state.state_name','state.status','city.id','city.city_name','city.status','union_branch.user_id')
                                ->leftjoin('country','union_branch.country_id','=','country.id')
                                ->leftjoin('state','union_branch.state_id','=','state.id')
                                ->leftjoin('city','union_branch.city_id','=','city.id')
                                ->where([
                                        ['union_branch.status','=','1'],
                                        ['union_branch.id','=',$id]
                                    ])->get();
        
        $country_id = $data['union_branch'][0]->country_id;
        $state_id = $data['union_branch'][0]->state_id;
        $city_id = $data['union_branch'][0]->city_id;
        
        $queries = DB::getQueryLog();
        $defaultLanguage = app()->getLocale();
        //dd($queries);
        //return $data['union_branch'];
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('status','=','1')->where('country_id','=',$country_id)->get();
        $data['city_view'] = DB::table('city')->select('id','city_name')->where('status','=','1')->where('state_id','=',$state_id)->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        return view('master.unionbranch.unionbranch_details')->with('data',$data);

    }
    //Union BRanch List End

    //Fee Details Start
	
	public function fees_list() {
        return view('master.fee.fee');
    }

	public function saveFee(Request $request)
    {
        //return $request->all();
        $request->validate([
            'fee_name' => 'required',
			'fee_amount' => 'required | numeric',
                ], [
            'fee_name.required' => 'please enter Fee name',
			'fee_amount.required' => 'please enter Fee Amount',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();
       
        if(($request->is_monthly_payment)=='on' || ($request->is_monthly_payment)=='1')
        {
            $data['is_monthly_payment'] = 1;
        }
        else{
            $data['is_monthly_payment'] = 0;
        }
        if(!empty($request->id)){
            $data_exists = $this->mailExists($request->input('fee_name'),$request->id);
        }else{
            $data_exists = $this->mailExists($request->input('fee_name'));
            $data_exists = Fee::where([
                ['fee_name','=',$request->input('fee_name')],['status','=','1']
            ])->count();
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/fee')->with('error','User Email Already Exists'); 
        }
        else{
//dd($data);
            $saveFee = $this->Fee->saveFeedata($data);

            if ($saveFee == true) {
                if(!empty($request->id))
                {
                    return redirect($defdaultLang . '/fee')->with('message', 'Fee Name Updated Succesfully');
                }
                else{
                    return redirect($defdaultLang . '/fee')->with('message', 'Fee Name Added Succesfully');
                }
            }
        }
    }
	
	public function feedestroy($lang,$id)
	{
    
        $Fee = new Fee();
        $Fee = Fee::find($id);
        $Fee->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/fee')->with('message','Fee Details Deleted Successfully!!');
	}
	
	//App Form Details Start
    public function  appFormList()
    {
        //$data['union_view'] = DB::table('union_branch')->where('status','=','1')->get();
        return view('master.appform.appform');
    }
	
	public function addAppForm()
    {
       $data['form_type'] = FormType::all();
        return view('master.appform.add_appform')->with('data',$data);
    }
	
	public function AppFormsave(Request $request)
     {   
         $request->validate([
             'formname'=>'required'
         ],
         [
             'formname.required'=>'please enter Form name'
             
         ]);
         $data = $request->all();   
         $defdaultLang = app()->getLocale();
       
         if(!empty($request->id)){
             $data_exists = $this->checkDesignationExists($request->input('formname'),$request->id);
         }else{
             $data_exists = $this->checkDesignationExists($request->input('formname'));
         }
         if($data_exists>0)
         {
             return  redirect($defdaultLang.'/appform')->with('error','Form Name Already Exists'); 
         }
         else{
             $saveAppForm = $this->AppForm->saveAppFormdata($data);
             if($saveAppForm == true)
             {
                if(!empty($request->id))
                {
                    return  redirect($defdaultLang.'/appform')->with('message','AppForm Updated Succesfully');
                }
                else{
                    return  redirect($defdaultLang.'/appform')->with('message','AppForm Added Succesfully');
                }
             }
         }
     }
	public function EditAppForm($lang,$id)
    {
        //DB::connection()->enableQueryLog();
        //$id = Crypt::decrypt($id);
		$auto_id = Crypt::decrypt($id);
        $data['form_type'] = FormType::all();
        $AppForm = new AppForm();
        $data['appform_edit'] = AppForm::find($auto_id);
		$defaultLanguage = app()->getLocale();
		return view('master.appform.add_appform')->with('data',$data);

    }
	
	public function deleteAppForm($lang,$id)
	{
        //return $id = Crypt::decrypt($id);
		$data = AppForm::where('id','=',$id)->update(['status'=>'0']);
		return redirect($lang.'/appform')->with('message','App Form  Deleted Succesfully');
    }
	
	// Roles section
	
	public function roles_list() {

        $data['roles_modules'] = DB::table('roles_modules')->get();
        $data['form_type'] = FormType::where('status','=','1')->get();
        return view('master.roles.roles')->with('data',$data);
    }
	public function saveRole(Request $request)
    {
        $request->validate([
            'name' => 'required',
			'slug' => 'required',
                ], [
            'name.required' => 'please enter name',
			'slug.required' => 'please enter slug',
        ]);
       // $data[]
        
        $data = $request->all();  
        $data['module'] = $request->module_id;  
        $defdaultLang = app()->getLocale(); 
        if(!empty($request->id)){
            $data_exists = $this->mailExists($request->input('name'),$request->id);
        }else{
            $data_exists = $this->mailExists($request->input('name'));
            $data_exists = Role::where('name','=',$request->input('name'))->count();
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/roles')->with('error','User Email Already Exists'); 
        }
        else{
            
            $saveRole = $this->Role->saveRoledata($data);

            if(!empty($request->id))
            {
                $roles = Role::find($request->id);
                $roles->formTypes()->sync($data['module']);
            }
            if ($saveRole == true) {
                if(!empty($request->id))
                {
                     return redirect($defdaultLang . '/roles')->with('message', 'Role Name Updated Succesfully');
                }
                else{
                    return redirect($defdaultLang . '/roles')->with('message', 'Role Name Added Succesfully');
                }
            }
            else{
                return redirect($defdaultLang . '/roles')->with('error', 'Union Role Cannot be modified!');
            }
        }
    }
	public function roledestroy($lang,$id)
	{
        $Role = new Role();
        $Role = Role::find($id);
        $Role->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/roles')->with('message','Role Details Deleted Successfully!!');
	}
	
    //Fee Details End

    //Status Details Start
    public function statusList()
    {
        return view('master.status.status_list');
    } 
   
    //Status Save and Update
    public function statusSave(Request $request)
    {   
        $request->validate([
            'status_name'=>'required',
            'font_color'=>'required',
        ],
        [
            'status_name.required'=>'please enter Status name',
            'font_color.required'=>'please enter Font Color',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();
        
        if(!empty($request->id)){
            $data_exists = $this->checkStatusExists($request->input('status_name'),$request->id);
        }else{       
            $data_exists = $this->checkStatusExists($request->input('status_name'));
            $data_exists = Status::where([
                ['status_name','=',$request->input('status_name')],['status','=','1']
            ])->count();
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/status')->with('error','Status Name Already Exists'); 
        }
        else{
            $saveStatus = $this->Status->saveStatusdata($data);
           
            if($saveStatus == true)
            {
                if(!empty($request->id))
                {
                   return  redirect($defdaultLang.'/status')->with('message','Status Updated Succesfully');
                }
                else{
                    return  redirect($defdaultLang.'/status')->with('message','Status Added Succesfully');
                }
            }
        }
    }
    public function statusDestroy($lang,$id)
    {
        $Status = new Status();
        $Status = Status::find($id);
        $Status->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/status')->with('message','Status Details Deleted Successfully!!');
    }
    ////Status Details End
    public function deleteUnionBranch($lang,$id)
	{
        //return $id = Crypt::decrypt($id);
		$data = DB::table('union_branch')->where('id','=',$id)->update(['status'=>'0']);
		return redirect($lang.'/unionbranch')->with('message','Union Branch Deleted Succesfully');
    }
     //FormType Details Start 
     public function formTypeList()
     {
         return view('master.formtype.formtype_list');
     } 
    
     //Status Save and Update
     public function formTypeSave(Request $request)
     {   
         $request->validate([
             'formname'=>'required',
         ],
         [
             'formname.required'=>'Please enter Form name',
         ]);
         $data = $request->all();   
         
         $defdaultLang = app()->getLocale();
         
         if(!empty($request->id)){
             $data_exists = $this->checkFormTyNameExists($request->input('formname'),$request->id);
         }else{
             $data_exists = $this->checkFormTyNameExists($request->input('formname'));
             $data_exists = FormType::where([
                ['formname','=',$request->input('formname')],['status','=','1']
            ])->count();
         }
         if($data_exists>0)
         {
             return  redirect($defdaultLang.'/formtype')->with('error','Form Type Name Already Exists'); 
         }
         else{
             $saveFormType = $this->FormType->saveFormTypedata($data);
            
             if($saveFormType == true)
             {
                if(!empty($request->id))
                {
                  return  redirect($defdaultLang.'/formtype')->with('message','Form Type Updated Succesfully');
                }
                else
                {
                    return  redirect($defdaultLang.'/formtype')->with('message','Form Type Added Succesfully');
                }
             }
        }
     }
     public function formTypeDestroy($lang,$id)
     {
         $FormType = new FormType();
         $FormType = FormType::find($id);
         $FormType->where('id','=',$id)->update(['status'=>'0']);
         $defdaultLang = app()->getLocale();
         return redirect($defdaultLang.'/formtype')->with('message','Form Type Details Deleted Successfully!!');
     }
     //FormType Details End

     //Company Details Starts
     public function companyList()
     {
        $data['company_view'] = Company::where('status','=','1')->get();
        return view('master.company.company_list')->with('data',$data);
     }
    
//Company Save and Update
public function companySave(Request $request)
{
    $request->validate([
        'company_name'=>'required',
        'short_code'=>'required',
    ],
    [
        'company_name.required'=>'Please enter Company name',
        'short_code.required'=>'Please enter Short Code',
    ]);
    $data = $request->all();  
    
    $defdaultLang = app()->getLocale();
    
    if(!empty($request->id)){
        $data_exists = $this->checkCompanyExists($request->input('company_name'),$request->id);
    }else{
        $data_exists = $this->checkCompanyExists($request->input('company_name'));
        $data_exists = Company::where([
            ['company_name','=',$request->company_name],
            ['status','=','1']
            ])->count();
    }
    if($data_exists>0)
    {
        return  redirect($defdaultLang.'/company')->with('error','Company Name Already Exists'); 
    }
    else{

        $saveCompany = $this->Company->saveCompanydata($data);
       
        if($saveCompany == true)
        {
            if(!empty($request->id))
            {
                return  redirect($defdaultLang.'/company')->with('message','Bank Updated Succesfully');
            }
            else
            {
                return  redirect($defdaultLang.'/company')->with('message','Bank Added Succesfully');
            }
        }
   }
} 
public function companyDestroy($lang,$id)
{
    $Company = new Company();
    $Company = Company::find($id);
    $Company->where('id','=',$id)->update(['status'=>'0']);
    $defdaultLang = app()->getLocale();
    return redirect($defdaultLang.'/company')->with('message','Bank Deleted Successfully!!');
}
     
    public function CompanyBranchList(){
        return view('master.companybranch.branch');
    }
    public function addCompanyBranch(){
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $data['union_view'] = DB::table('union_branch')->where('status','=','1')->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('country_id','=',130)->where('status','=','1')->get();
        $data['city_view'] = [];
        return view('master.companybranch.companybranch_details')->with('data',$data);
    }

    public function CompanyBranchsave(Request $request){
        
        $redirect_failurl = app()->getLocale().'/branch';
        $redirect_url = app()->getLocale().'/branch';
        $defdaultLang = app()->getLocale();
        $request->validate([
            'company_id'=>'required',
            'union_branch_id'=>'required',
            'branch_name'=>'required',
            'address_one'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'postal_code'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'mobile'=>'required',
            'branch_shortcode'=>'required',
        ],
        [
            'company_id.required'=>'please Choose Company name',
            'union_branch_id.required'=>'Please Choose union Banch',
            'branch_name.required'=>'please Enter branch name',
            'address_one.required'=>'please Enter address one name',
            'country_id.required'=>'please Enter country name',
            'state_id.required'=>'please Enter state name',
            'city_id.required'=>'please Enter city name',
            'postal_code.required'=>'please Enter postal code',
            'email.required'=>'please Enter email address',
            'phone.required'=>'please Enter phone number',
            'mobile.required'=>'please Enter mobile number',
            'branch_shortcode.required'=>'please Enter Short Code',
        ]);
        $auto_id = $request->input('auto_id');
        $branch['company_id'] = $request->input('company_id');
        $branch['union_branch_id'] = $request->input('union_branch_id');
        $branch['branch_name'] = $request->input('branch_name');
        $branch['country_id'] = $request->input('country_id');
        $branch['state_id'] = $request->input('state_id');
        $branch['city_id'] = $request->input('city_id');
        $branch['postal_code'] = $request->input('postal_code');
        $branch['address_one'] = $request->input('address_one');
        $branch['address_two'] = $request->input('address_two');
        $branch['address_three'] = $request->input('address_three');
        $branch['phone'] = $request->input('phone');
        $branch['mobile'] = $request->input('mobile');
        $branch['email'] = $request->input('email');
        $branch['branch_shortcode'] = $request->input('branch_shortcode');

        $is_head = $request->input('is_head');
        $companyid = $request->input('company_id');
		if(isset($is_head)){
			$branch['is_head'] = 1;
		}else{
			$branch['is_head'] = 0;
        }
        if($auto_id==""){
            $company_head_role = Role::where('slug', 'company')->first();
            $company_branch_role = Role::where('slug', 'company-branch')->first();
            $randompass = CommonHelper::random_password(5,true);

            $data_exists_branchemail = DB::table('company_branch')->where([
                ['email','=',$branch['email']],
                ['status','=','1'] ])->count();
            $data_exists_usersemail = DB::table('users')->where('email','=',$branch['email'])->count();
            if($data_exists_branchemail > 0 ||  $data_exists_usersemail > 0)
            {
                return redirect($defdaultLang.'/branch')->with('error','Email Already Exists');
            }
            else
            {
                $company_type =2;
                $member_user = new User();
                $member_user->name = $request->input('branch_name');
                $member_user->email = $request->input('email');
                $member_user->password = bcrypt($randompass);
                $member_user->save();
                $branch['user_id'] = $member_user->id;
                $company_type =2;
                if($branch['is_head'] == 0)
                {
                    $member_user->roles()->attach($company_branch_role);
                }else{
                    $company_type = 1;
                    $data = DB::table('company_branch')->where('is_head','=','1')->where('company_id','=',$companyid)->update(['is_head'=>'0']);
                    $rold_id_1 = DB::statement("UPDATE users_roles LEFT JOIN company_branch ON users_roles.user_id = company_branch.user_id SET users_roles.role_id = 4 WHERE users_roles.role_id = 3 AND company_branch.company_id = '$companyid'");
                    $member_user->roles()->attach($company_head_role);
                }
                $id = $this->CompanyBranch->StoreBranch($branch);
                $mail_data = array( 
                    'name' => $request->input('branch_name'),
                    'email' => $branch['email'],
                    'password' => $randompass,
                    'site_url' => URL::to("/"),
                    'company_type' => $company_type,
                );
                $cc_mail = CommonHelper::getCCTestMail();
                $status = Mail::to($branch['email'])->cc([$cc_mail])->send(new CompanyBranchMailable($mail_data));
    
                if( count(Mail::failures()) > 0 ) {
                    return redirect($redirect_url)->with('message','Bank Account created successfully, Failed to send mail');
                }else{
                    return redirect($redirect_url)->with('message','Bank Account created successfully, password sent to mail');
                }
            }
        }else{
             $user_id = CompanyBranch::where('id',$auto_id)->pluck('user_id')[0];
             $rold_id_21 = DB::table('users')->where('id','=',$user_id)->update(['name'=> $request->input('branch_name')]);
             if($branch['is_head']==0){
                $upid = DB::table('company_branch')->where('id','=',$auto_id)->update($branch);
                $rold_id_2 = DB::table('users_roles')->where('role_id','=','3')->where('user_id','=',$user_id)->update(['role_id'=>'4']);
             }else{
                $data = DB::table('company_branch')->where('is_head','=','1')->where('company_id','=',$companyid)->update(['is_head'=>'0']);
                $rold_id_1 = DB::statement("UPDATE users_roles LEFT JOIN company_branch ON users_roles.user_id = company_branch.user_id SET users_roles.role_id = 4 WHERE users_roles.role_id = 3 AND company_branch.company_id = '$companyid'");
                
                $upid = DB::table('company_branch')->where('id','=',$auto_id)->update($branch);
                $rold_id_2 = DB::table('users_roles')->where('role_id','=','4')->where('user_id','=',$user_id)->update(['role_id'=>'3']);
             }

            return redirect($defdaultLang.'/branch')->with('message','Bank Branch Details Updated Succesfully');
        }
    }

    public function deleteCompanyBranch($lang,$id)
	{
        //$id = Crypt::decrypt($id);
        $data = DB::table('company_branch')->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
		return redirect($defdaultLang.'/branch')->with('message','Bank Branch Deleted Succesfully');
	} 

    public function EditCompanyBranch($lang,$id){
        $id = Crypt::decrypt($id);
        $data['branch_view'] = DB::table('company')->select('company_branch.*', 'company.company_name','company_branch.branch_name','company_branch.id as branchid','company_branch.company_id','company_branch.status','company.status','union_branch.union_branch','company_branch.union_branch_id')
                ->join('company_branch','company.id','=','company_branch.company_id')
                ->join('union_branch','company_branch.union_branch_id','=','union_branch.id')
                ->where([
                    ['company_branch.status','=','1'],
                    ['company.status','=','1'],
                    ['company_branch.id','=',$id]
                    ])->get();
        $company_id = $data['branch_view'][0]->company_id;
        $union_branch_id = $data['branch_view'][0]->union_branch_id;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
        $data['union_view'] = DB::table('union_branch')->where('status','=','1')->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('status','=','1')->get();
        $data['city_view'] = DB::table('city')->select('id','city_name')->where('status','=','1')->get();
        return view('master.companybranch.companybranch_details')->with('data',$data);
    }
    
}
