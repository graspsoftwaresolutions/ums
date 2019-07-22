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

    //Ajax Datatable Countries List //Users List 
    public function ajax_countries_list(Request $request){
        $columns = array( 
            0 => 'country_name', 
            1 => 'id',
        );

        $totalData = Country::where('status','=','1')
                     ->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $country = Country::select('id','country_name')->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }else{
                $country = Country::select('id','country_name')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $country =  Country::select('id','country_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('country_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $country =  Country::select('id','country_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('country_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = Country::where('id','LIKE',"%{$search}%")
                    ->orWhere('country_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        
        
        $data = $this->CommonAjaxReturn($country, 0, 'master.countrydestroy',0); 
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
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
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/country')->with('error','User Email Already Exists'); 
        }
        else{

            $saveCountry = $this->Country->saveCountrydata($data);

            if ($saveCountry == true) {
                return redirect($defdaultLang . '/country')->with('message', 'Country Name Added Succesfully');
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
		
		$data['country_view'] = Country::all();
        return view('master.state.state_list')->with('data',$data);
        //return view('master.state.state_list');

    }
	public function ajax_state_list(Request $request){
        $columns = array( 
            0 => 'country_name', 
            1 => 'state_name', 
            2 => 'id',
        );

        $totalData = State::where('status','=','1')
					->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
				
				$state = DB::table('country')->select('country.country_name','state.state_name','state.id','state.country_id','state.status')
                ->join('state','country.id','=','state.country_id')
                ->orderBy($order,$dir)
                ->where('state.status','=','1')
				->get()->toArray();
            }else{
                $state = DB::table('country')->select('country.country_name','state.state_name','state.id','state.country_id','state.status')
                ->join('state','country.id','=','state.country_id')
				->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('state.status','=','1')
                ->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
			$state = DB::table('country')->select('country.country_name','state.state_name','state.id','state.country_id','state.status')
					->join('state','country.id','=','state.country_id')
					->where('state.id','LIKE',"%{$search}%")
                    ->orWhere('country.country_name', 'LIKE',"%{$search}%")
                    ->orWhere('state.state_name', 'LIKE',"%{$search}%")
                    ->where('state.status','=','1')
                    ->orderBy($order,$dir)
                    ->get()->toArray();
        }else{
            $state 	=  DB::table('country')->select('country.country_name','state.state_name','state.id','state.country_id','state.status')
						->join('state','country.id','=','state.country_id')
						->where('state.id','LIKE',"%{$search}%")
                        ->orWhere('country.country_name', 'LIKE',"%{$search}%")
                        ->orWhere('state.state_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('state.status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = State::where('id','LIKE',"%{$search}%")
                    ->orWhere('state_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        
        
        $data = $this->CommonAjaxReturn($state, 0, 'master.statedestroy', 0); 
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
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
                return redirect($defdaultLang . '/state')->with('message', 'State Name Added Succesfully');
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
        $data['country_view'] = Country::all();
        $data['state_view'] = State::all();
        return view('master.city.city_list',compact('data',$data));
    }
	public function ajax_city_list(Request $request){
        $columns = array( 
            0 => 'country_name', 
            1 => 'state_name', 
            2 => 'city_name', 
            3 => 'id',
        );

        $totalData = City::where('status','=','1')
					->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
				$city = DB::table('country')->select('country.country_name','state.state_name','city.id','state.country_id','city.status','city.city_name')
                ->join('state','country.id','=','state.country_id')
                ->join('city','city.state_id','=','state.id')
                ->orderBy($order,$dir)
                ->where('city.status','=','1')
				->get()->toArray();
            }else{
               $city = DB::table('country')->select('country.country_name','state.state_name','city.id','state.country_id','city.status','city.city_name')
                ->join('state','country.id','=','state.country_id')
                ->join('city','city.state_id','=','state.id')
				->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('city.status','=','1')
                ->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
			$city = DB::table('country')->select('country.country_name','state.state_name','city.id','state.country_id','city.status','city.city_name')
					->join('state','country.id','=','state.country_id')
					->join('city','city.state_id','=','state.id')
					->where('city.id','LIKE',"%{$search}%")
                    ->orWhere('country.country_name', 'LIKE',"%{$search}%")
                    ->orWhere('state.state_name', 'LIKE',"%{$search}%")
                    ->orWhere('city.city_name', 'LIKE',"%{$search}%")
                    ->where('city.status','=','1')
                    ->orderBy($order,$dir)
                    ->get()->toArray();
        }else{
            $city = DB::table('country')->select('country.country_name','state.state_name','city.id','state.country_id','city.status','city.city_name')
						->join('state','country.id','=','state.country_id')
						->join('city','city.state_id','=','state.id')
						->where('city.id','LIKE',"%{$search}%")
						->orWhere('country.country_name', 'LIKE',"%{$search}%")
						->orWhere('state.state_name', 'LIKE',"%{$search}%")
						->orWhere('city.city_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('city.status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = City::where('id','LIKE',"%{$search}%")
                    ->orWhere('city_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        
        
        $data = $this->CommonAjaxReturn($city, 0, 'master.citydestroy', 0); 
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
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
                return redirect($defdaultLang . '/city')->with('message', 'City Name Added Succesfully');
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
                return redirect($defdaultLang . '/users')->with('message', 'User Updated Succesfully');
            }
        }
    }

    //Users List 
    public function ajax_users_list(Request $request) {
        $columns = array(
            0 => 'name',
            1 => 'email',
            2 => 'id',
        );

        $totalData = User::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');

        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $users = User::select('id','name','email')->orderBy($order,$dir)
                ->get()->toArray();
            }else{
                $users = User::select('id','name','email')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $users =  User::select('id','name','email')->where('id','LIKE',"%{$search}%")
                        ->orWhere('name', 'LIKE',"%{$search}%")
                        ->orWhere('email', 'LIKE',"%{$search}%")
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $users =  User::select('id','name','email')->where('id','LIKE',"%{$search}%")
                        ->orWhere('name', 'LIKE',"%{$search}%")
                        ->orWhere('email', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = User::where('id','LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->count();
        }
        $data = $this->CommonAjaxReturn($users, 0, 'master.destroy', 0);
    
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    }
    //User Destroy
    public function user_destroy($lang, $id)
    {
        $User = new User();
        $User = User::find($id);
        $User->delete();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/users')->with('message','User Details Deleted Successfully!!');
    }

    public function users_list()
    {
        return view('master.users.users');
    }

    // UNION BRANCH

    public function AjaxunionBranchList(Request $request){
        $columns = array( 
            0 => 'union_branch', 
            1 => 'is_head',
            2 => 'email',
            3 => 'id'
        );

        $totalData = UnionBranch::where('status','=','1')
                                ->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $unionbranchs = UnionBranch::select('id','union_branch','is_head','email')->where('status',1)->orderBy($order,$dir)
                ->get()->toArray();
            }else{
                $unionbranchs = UnionBranch::select('id','union_branch','is_head','email')->where('status',1)->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get()->toArray();
            }
                
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $unionbranchs =  UnionBranch::select('id','union_branch','is_head','email')->where('status',1)->where('id','LIKE',"%{$search}%")
                            ->orWhere('union_branch', 'LIKE',"%{$search}%")
                            ->orWhere('is_head', 'LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orderBy($order,$dir)
                            ->get()->toArray();
        }else{
            $unionbranchs =  UnionBranch::select('id','union_branch','is_head','email')->where('status',1)->where('id','LIKE',"%{$search}%")
                    ->orWhere('union_branch', 'LIKE',"%{$search}%")
                    ->orWhere('is_head', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get()->toArray();
        }

             $totalFiltered = UnionBranch::where('status',1)->where('id','LIKE',"%{$search}%")
                    ->orWhere('union_branch', 'LIKE',"%{$search}%")
                    ->orWhere('is_head', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->count();
          
    }
    $data = $this->CommonAjaxReturn($unionbranchs, 1, 'master.deleteunionbranch', 1, 'master.editunionbranch'); 
    
         $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
    } 
    //Relation Details 
    public function relationList()
    {
        return view('master.relation.relation_list');
    }
    //Ajax Datatable Relation List
    public function ajax_relation_list(Request $request){
        $columns = array( 
            0 => 'relation_name', 
            1 => 'id',
        );
        $totalData = Relation::where('status','=','1')
        ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Relation = Relation::select('id','relation_name')->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }else{
                $Relation = Relation::select('id','relation_name')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Relation =  Relation::select('id','relation_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('relation_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $Relation =  Relation::select('id','relation_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('relation_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = Relation::where('id','LIKE',"%{$search}%")
                    ->orWhere('relation_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = $this->CommonAjaxReturn($Relation, 0, 'master.relationdestroy', 0);
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
        echo json_encode($json_data); 
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
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/relation')->with('error','Relation Name Already Exists'); 
        }
        else{
            $saveRelation = $this->Relation->saveRelationdata($data);
           
            if($saveRelation == true)
            {
                return  redirect($defdaultLang.'/relation')->with('message','Relation Name Added Succesfully');
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
     //Ajax Datatable Race List
     public function ajax_race_list(Request $request){
        $columns = array( 
            0 => 'race_name', 
            1 => 'id',
        );
        $totalData = Race::where('status','=','1')
        ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Race = Race::select('id','race_name')->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }else{
                $Race = Race::select('id','race_name')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Race     =  Race::select('id','race_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('race_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $Race      =  Race::select('id','race_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('race_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = Race::where('id','LIKE',"%{$search}%")
                    ->orWhere('race_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = $this->CommonAjaxReturn($Race, 0, 'master.racedestroy', 0);
    
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
        echo json_encode($json_data); 
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
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/race')->with('error','Race Name Already Exists'); 
        }
        else{
            $saveRace = $this->Race->saveRacedata($data);
           
            if($saveRace == true)
            {
                return  redirect($defdaultLang.'/race')->with('message','Race Name Added Succesfully');
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
     //Ajax Datatable Race List
     public function ajax_reason_list(Request $request){
        $columns = array( 
            0 => 'reason_name', 
            1 => 'id',
        );
        $totalData = Reason::where('status','=','1')
        ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Reason = Reason::select('id','reason_name')->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }else{
                $Reason = Reason::select('id','reason_name')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Reason     =  Reason::select('id','reason_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('reason_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $Reason      = Reason::select('id','reason_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('reason_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = Reason::where('id','LIKE',"%{$search}%")
                    ->orWhere('reason_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = $this->CommonAjaxReturn($Reason, 0, 'master.reasondestroy', 0);
       
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
        echo json_encode($json_data); 
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
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/reason')->with('error','Reason Name Already Exists'); 
        }
        else{
            $saveReason = $this->Reason->saveReasondata($data);
           
            if($saveReason == true)
            {
                return  redirect($defdaultLang.'/reason')->with('message','Reason Name Added Succesfully');
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
    
    //Ajax Datatable Race List
    public function ajax_persontitle_list(Request $request){
        $columns = array( 
            0 => 'person_title', 
            1 => 'id',
        );
        $totalData = Persontitle::where('status','=','1')
        ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Persontitle = Persontitle::select('id','person_title')->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }else{
                $Persontitle = Persontitle::select('id','person_title')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Persontitle     =  Persontitle::select('id','person_title')->where('id','LIKE',"%{$search}%")
                        ->orWhere('person_title', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $Persontitle      = Persontitle::select('id','person_title')->where('id','LIKE',"%{$search}%")
                        ->orWhere('person_title', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = Persontitle::where('id','LIKE',"%{$search}%")
                    ->orWhere('person_tilte', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = $this->CommonAjaxReturn($Persontitle, 0, 'master.persontitledestroy', 0);
        
        $json_data = array(

            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
        echo json_encode($json_data); 
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
         }
         if($data_exists>0)
         {
             return  redirect($defdaultLang.'/persontitle')->with('error','Person Title Name Already Exists'); 
         }
         else{
             $savePersonTitle = $this->Persontitle->savePersonTitledata($data);
            
             if($savePersonTitle == true)
             {
                 return  redirect($defdaultLang.'/persontitle')->with('message','Person Title Added Succesfully');
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
    //Ajax Datatable Race List
    public function ajax_designation_list(Request $request){
        $columns = array( 
            0 => 'designation_name', 
            1 => 'id',
        );
        $totalData = Designation::where('status','=','1')
        ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Designation = Designation::select('id','designation_name')->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }else{
                $Designation = Designation::select('id','designation_name')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get()->toArray();
            }
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Designation     =  Designation::select('id','designation_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('designation_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $Designation      = Designation::select('id','designation_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('designation_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = Designation::where('id','LIKE',"%{$search}%")
                    ->orWhere('designation_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = $this->CommonAjaxReturn($Designation, 0, 'master.designationdestroy', 0);
   
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
        echo json_encode($json_data); 
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
         }
         if($data_exists>0)
         {
             return  redirect($defdaultLang.'/designation')->with('error','Designation Name Already Exists'); 
         }
         else{
             $saveDesignation = $this->Designation->saveDesignationdata($data);
            
             if($saveDesignation == true)
             {
                 return  redirect($defdaultLang.'/designation')->with('message','Designation Added Succesfully');
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
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
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
			$files->move('public/images',$image_name);
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
                $status = Mail::to($union['email'])->send(new UnionBranchMailable($mail_data));

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
                                            'union_branch.status','union_branch.address_three','union_branch.mobile','union_branch.logo','country.id','country.country_name','country.status','state.id','state.state_name','state.status','city.id','city.city_name','city.status')
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
	
    public function ajax_fees_list(Request $request) {
        $columns = array(
            0 => 'fee_name',
            1 => 'fee_amount',
            2 => 'id',
        );
        $select = array();
        $where = array();
        $or_where = array();
        $limit = $request->input('length');
        $offset = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $orderby = array($order, $dir);
        
        $select = array('fee.id', 'fee.fee_name', 'fee.fee_amount');
        if (isset($user_id) && !empty($user_id)) {
            $where['id'] = $user_id;
        }
        $search = $request->input('search.value');
        if (isset($search) && !empty($search)) {
            $or_where1 = array("fee_name", "Like", "%{$search}%");
            $or_where2 = array("fee_amount", "Like", "%{$search}%");
             $or_where = array($or_where1, $or_where2);
        }
        $feelist = new Fee();
        $overallfeedetail = $feelist->getFee($select, $where, $or_where, $orderby, $limit, $offset);
        $totalFiltered =$totalData=$overallfeedetail->count();
        $data = $this->CommonAjaxReturn($overallfeedetail->toArray(), 0, 'master.feedestroy', 0);
        
          $json_data = array(
          "draw" => intval($request->input('draw')),
          "recordsTotal" => intval($totalData),
          "recordsFiltered" => intval($totalFiltered),
          "data" => $data
          );

          echo json_encode($json_data);
    }
    
	public function saveFee(Request $request)
    {

        $request->validate([
            'fee_name' => 'required',
			'fee_amount' => 'required',
                ], [
            'fee_name.required' => 'please enter Fee name',
			'fee_amount.required' => 'please enter Fee Amount',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();

        if(!empty($request->id)){
            $data_exists = $this->mailExists($request->input('fee_name'),$request->id);
        }else{
            $data_exists = $this->mailExists($request->input('fee_name'));
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/fee')->with('error','User Email Already Exists'); 
        }
        else{
//dd($data);
            $saveFee = $this->Fee->saveFeedata($data);

            if ($saveFee == true) {
                return redirect($defdaultLang . '/fee')->with('message', 'Fee Name Added Succesfully');
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
	// App Form

    public function ajaxAppFormList(Request $request){
        $columns = array( 
            0 => 'formname', 
            1 => 'formtype_id',
            2 => 'route',
            3 => 'orderno',
            4 => 'id'
        );

        $totalData = AppForm::where('status','=','1')
                                ->count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $appforms = AppForm::select('id','formname','formtype_id','route','orderno')->where('status',1)->orderBy($order,$dir)
                ->get()->toArray();
            }else{
                $appforms = AppForm::select('id','formname','formtype_id','route','orderno')->where('status',1)->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get()->toArray();
            }
                
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $appforms =  AppForm::select('id','formname','formtype_id','route','orderno')->where('status',1)->where('id','LIKE',"%{$search}%")
                            ->orWhere('formname', 'LIKE',"%{$search}%")
                            ->orWhere('formtype_id', 'LIKE',"%{$search}%")
                            ->orWhere('route', 'LIKE',"%{$search}%")
                            ->orWhere('orderno', 'LIKE',"%{$search}%")
                            ->orderBy($order,$dir)
                            ->get()->toArray();
        }else{
            $appforms =  AppForm::select('id','formname','formtype_id','route','orderno')->where('status',1)->where('id','LIKE',"%{$search}%")
                    ->orWhere('formname', 'LIKE',"%{$search}%")
                            ->orWhere('formtype_id', 'LIKE',"%{$search}%")
                            ->orWhere('route', 'LIKE',"%{$search}%")
                            ->orWhere('orderno', 'LIKE',"%{$search}%")
							->offset($start)
							->limit($limit)
							->orderBy($order,$dir)
							->get()->toArray();
        }

             $totalFiltered = AppForm::where('status',1)->where('id','LIKE',"%{$search}%")
							->orWhere('formname', 'LIKE',"%{$search}%")
                            ->orWhere('formtype_id', 'LIKE',"%{$search}%")
                            ->orWhere('route', 'LIKE',"%{$search}%")
                            ->orWhere('orderno', 'LIKE',"%{$search}%")
							->count();
          
    }
    $data = $this->CommonAjaxReturn($appforms, 1, 'master.deleteappform', 1, 'master.editappform'); 
    
         $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
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
        // var_dump($data);
		// exit;
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
          //  dd($saveAppForm);
             if($saveAppForm == true)
             {
			//return  redirect back();
                 return  redirect($defdaultLang.'/appform')->with('message','AppForm Added Succesfully');
				 //return  redirect($defdaultLang.'/roles')->with('error','User Email Already Exists'); 
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
        return view('master.roles.roles');
    }
	public function ajax_roles_list(Request $request) {
        $columns = array( 
            0 => 'name', 
            1 => 'slug', 
            2 => 'id',
        );
        $totalData = Role::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Role = Role::select('id','name','slug')->orderBy($order,$dir)
                ->get()->toArray();
            }else{
                $Role = Role::select('id','name','slug')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get()->toArray();
            }
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Role     =  Role::select('id','name','slug')->where('id','LIKE',"%{$search}%")
                        ->orWhere('name', 'LIKE',"%{$search}%")
                        ->orWhere('slug', 'LIKE',"%{$search}%")
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }else{
            $Role      = Role::select('id','name','slug')->where('id','LIKE',"%{$search}%")
                        ->orWhere('name', 'LIKE',"%{$search}%")
                        ->orWhere('slug', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get()->toArray();
        }
        $totalFiltered = Role::where('id','LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    ->orWhere('slug', 'LIKE',"%{$search}%")
                    ->count();
        }
        $data = $this->CommonAjaxReturn($Role, 2, '', 0);
   
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
        echo json_encode($json_data); 
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
        $data = $request->all();   
        $defdaultLang = app()->getLocale();

        if(!empty($request->id)){
            $data_exists = $this->mailExists($request->input('name'),$request->id);
        }else{
            $data_exists = $this->mailExists($request->input('name'));
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/roles')->with('error','User Email Already Exists'); 
        }
        else{
//dd($data);
            $saveRole = $this->Role->saveRoledata($data);

            if ($saveRole == true) {
                return redirect($defdaultLang . '/roles')->with('message', 'Role Name Added Succesfully');
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
	
	public function checkBranchemailExists(Request $request){
		//return $request->all();
		$email =  $request->input('email');
        $db_autoid = $request->input('db_autoid');
		if($db_autoid=='' || $db_autoid==null)
        {
			return $branchexists = $this->BranchmailExists($email);
		}else{
			return $branchexists = $this->BranchmailExists($email,$db_autoid);
		}
		//return Response::json($return_status);
    }
    //Fee Details End

    //Status Details Start
    public function statusList()
    {
        return view('master.status.status_list');
    } 
    //Ajax Datatable Status List
    public function ajax_status_list(Request $request){

        $columns = array(
            0 => 'status_name', 
            1 => 'id',
        );
        $totalData = Status::where('status','=','1')
        ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Status = Status::select('id','status_name')->orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }else{
                $Status = Status::select('id','status_name')->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Status     =  Status::select('id','status_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('status_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }else{
            $Status      = Status::select('id','status_name')->where('id','LIKE',"%{$search}%")
                        ->orWhere('status_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }
        $totalFiltered = Status::where('id','LIKE',"%{$search}%")
                    ->orWhere('status_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = $this->CommonAjaxReturn($Status->toArray(), 0, 'master.statusdestroy', 0);
    
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );
        echo json_encode($json_data); 
    } 
    //Status Save and Update
    public function statusSave(Request $request)
    {   
        $request->validate([
            'status_name'=>'required',
        ],
        [
            'status_name.required'=>'please enter Status name',
        ]);
        $data = $request->all();   
        $defdaultLang = app()->getLocale();
        
        if(!empty($request->id)){
            $data_exists = $this->checkStatusExists($request->input('status_name'),$request->id);
        }else{
            $data_exists = $this->checkStatusExists($request->input('status_name'));
        }
        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/status')->with('error','Status Name Already Exists'); 
        }
        else{
            $saveStatus = $this->Status->saveStatusdata($data);
           
            if($saveStatus == true)
            {
                return  redirect($defdaultLang.'/status')->with('message','Status Added Succesfully');
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
     //Ajax Datatable FormType List
     public function ajax_formtype_list(Request $request){
 
         $columns = array(
             0 => 'formname', 
             1 => 'id',
         );
         $totalData = FormType::where('status','=','1')
         ->count();
         $totalFiltered = $totalData; 
         $limit = $request->input('length');
         
         $start = $request->input('start');
         $order = $columns[$request->input('order.0.column')];
         $dir = $request->input('order.0.dir');
         if(empty($request->input('search.value')))
         {            
             if( $limit == -1){
                 $FormType = FormType::select('id','formname','orderno')->orderBy($order,$dir)
                 ->where('status','=','1')
                 ->get();
             }else{
                 $FormType = FormType::select('id','formname','orderno')->offset($start)
                 ->limit($limit)
                 ->orderBy($order,$dir)
                 ->where('status','=','1')
                 ->get();
             }
         }
         else {
         $search = $request->input('search.value'); 
         if($limit == -1){
             $FormType     =  FormType::select('id','formname','orderno')->where('id','LIKE',"%{$search}%")
                         ->orWhere('formname', 'LIKE',"%{$search}%")
                         ->where('status','=','1')
                         ->orderBy($order,$dir)
                         ->get();
         }else{
             $FormType      = FormType::select('id','formname','orderno')->where('id','LIKE',"%{$search}%")
                         ->orWhere('formname', 'LIKE',"%{$search}%")
                         ->offset($start)
                         ->limit($limit)
                         ->where('status','=','1')
                         ->orderBy($order,$dir)
                         ->get();
         }
         $totalFiltered = FormType::where('id','LIKE',"%{$search}%")
                     ->orWhere('formname', 'LIKE',"%{$search}%")
                     ->where('status','=','1')
                     ->count();
         }
         $data = $this->CommonAjaxReturn($FormType->toArray(), 0, 'master.formTypedestroy', 0);
    
         $json_data = array(
             "draw"            => intval($request->input('draw')),  
             "recordsTotal"    => intval($totalData),  
             "recordsFiltered" => intval($totalFiltered), 
             "data"            => $data   
             );
         echo json_encode($json_data); 
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
         }
         if($data_exists>0)
         {
             return  redirect($defdaultLang.'/formtype')->with('error','Form Type Name Already Exists'); 
         }
         else{
             $saveFormType = $this->FormType->saveFormTypedata($data);
            
             if($saveFormType == true)
             {
                 return  redirect($defdaultLang.'/formtype')->with('message','Form Type Added Succesfully');
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
        $data['company_view'] = Company::all();
        return view('master.company.company_list')->with('data',$data);
     } 
    //Ajax Datatable FormType List
    public function ajax_company_list(Request $request){

    $columns = array(
        0 => 'company_name',
        1 => 'short_code',
        2 => 'id'
    );
    $totalData = Company::count();
    $totalFiltered = $totalData; 
    $limit = $request->input('length');
    
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');
    if(empty($request->input('search.value')))
    {            
        if( $limit == -1){
            $Company = Company::select('id','company_name','short_code')->orderBy($order,$dir)
            ->where('status','=','1')
            ->get();
        }else{
            $Company = Company::select('id','company_name','short_code')->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->where('status','=','1')
            ->get();
        }
    }
    else {
    $search = $request->input('search.value'); 
    if($limit == -1){
        $Company     = Company::select('id','company_name','short_code')->where('id','LIKE',"%{$search}%")
                    ->orWhere('company_name', 'LIKE',"%{$search}%")
                     ->orWhere('short_code', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->orderBy($order,$dir)
                    ->get();
    }else{
        $Company      = Company::select('id','company_name','short_code')->where('id','LIKE',"%{$search}%")
                    ->orWhere('company_name', 'LIKE',"%{$search}%")
                    ->orWhere('short_code', 'LIKE',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->where('status','=','1')
                    ->orderBy($order,$dir)
                    ->get();
    }
    $totalFiltered = Company::where('id','LIKE',"%{$search}%")
                ->orWhere('company_name', 'LIKE',"%{$search}%")
                ->orWhere('short_code', 'LIKE',"%{$search}%")
                ->where('status','=','1')
                ->count();
    }
    $data = $this->CommonAjaxReturn($Company->toArray(), 0, 'master.companydestroy', 0);
    
    $json_data = array(
        "draw"            => intval($request->input('draw')),  
        "recordsTotal"    => intval($totalData),  
        "recordsFiltered" => intval($totalFiltered), 
        "data"            => $data   
        );
    echo json_encode($json_data); 
}
//Company Save and Update
public function companySave(Request $request)
{  
    $request->validate([
        'company_name'=>'required',
    ],
    [
        'company_name.required'=>'Please enter Company name',
    ]);
    $data = $request->all();  
    
    $defdaultLang = app()->getLocale();
    
    if(!empty($request->id)){
        $data_exists = $this->checkCompanyExists($request->input('company_name'),$request->id);
    }else{
        $data_exists = $this->checkCompanyExists($request->input('company_name'));
    }
    if($data_exists>0)
    {
        return  redirect($defdaultLang.'/company')->with('error','Company Name Already Exists'); 
    }
    else{
        $saveCompany = $this->Company->saveCompanydata($data);
       
        if($saveCompany == true)
        {
            return  redirect($defdaultLang.'/company')->with('message','Company Added Succesfully');
        }
   }
} 
public function companyDestroy($lang,$id)
{
    $Company = new Company();
    $Company = Company::find($id);
    $Company->where('id','=',$id)->update(['status'=>'0']);
    $defdaultLang = app()->getLocale();
    return redirect($defdaultLang.'/company')->with('message','Company Details Deleted Successfully!!');
}
     //Company Details End
     public function AjaxCompanyBranchList(Request $request){
        DB::enableQueryLog();

        $columns = array( 
            0 => 'company_id', 
            1 => 'branch_name',
            2 => 'email',
            3 => 'is_head',
            4 => 'id'
        );

       
        $totalData = DB::table('company_branch as b')->where('b.status','=','1')
                                ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $companybranchs = DB::table('company_branch as b')
				->select('b.id','c.company_name','b.branch_name','b.email','b.is_head')
				->leftjoin('company as c','c.id','=','b.company_id')
                ->where('b.status','=','1')
                ->orderBy($order,$dir)
                ->get()->toArray();
            }else{
                $companybranchs = DB::table('company_branch as b')
				->select('b.id','c.company_name','b.branch_name','b.email','b.is_head')
				->leftjoin('company as c','c.id','=','b.company_id')
                ->where('b.status','=','1')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get()->toArray();
            }
                
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $companybranchs = DB::table('company_branch as b')
                            ->select('b.id','c.company_name','b.branch_name','b.email','b.is_head')
                            ->leftjoin('company as c','c.id','=','b.company_id')
                            ->where('b.status','=','1')
                            ->where(function($query) use ($search){
                                $query->orWhere('b.id','LIKE',"%{$search}%")
                                ->orWhere('c.company_name', 'LIKE',"%{$search}%")
                                ->orWhere('b.branch_name', 'LIKE',"%{$search}%")
                                ->orWhere('b.is_head', 'LIKE',"%{$search}%")
                                ->orWhere('b.email', 'LIKE',"%{$search}%");
                            })
                            ->orderBy($order,$dir)
                            ->get()->toArray();
        }else{
            $companybranchs =  DB::table('company_branch as b')
                            ->select('b.id','c.company_name','b.branch_name','b.email','b.is_head')
                            ->leftjoin('company as c','c.id','=','b.company_id')
                            ->where('b.status','=','1')
                            ->where(function($query) use ($search){
                                $query->orWhere('b.id','LIKE',"%{$search}%")
                                ->orWhere('c.company_name', 'LIKE',"%{$search}%")
                                ->orWhere('b.branch_name', 'LIKE',"%{$search}%")
                                ->orWhere('b.is_head', 'LIKE',"%{$search}%")
                                ->orWhere('b.email', 'LIKE',"%{$search}%");
                            })
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get()->toArray();
                            
        }

             $totalFiltered = DB::table('company_branch as b')
                            ->leftjoin('company as c','c.id','=','b.company_id')
                            ->where('b.status','=','1')
                            ->where(function($query) use ($search){
                                $query->orWhere('b.id','LIKE',"%{$search}%")
                                ->orWhere('c.company_name', 'LIKE',"%{$search}%")
                                ->orWhere('b.branch_name', 'LIKE',"%{$search}%")
                                ->orWhere('b.is_head', 'LIKE',"%{$search}%")
                                ->orWhere('b.email', 'LIKE',"%{$search}%");
                            })
                            ->count();
          
    }
    $data = $this->CommonAjaxReturn($companybranchs, 1, 'master.deletebranch', 1, 'master.editbranch'); 
    
         $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
            );

        echo json_encode($json_data); 
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
                ['email','=',$branch['email']]
                ])->count();
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
                $status = Mail::to($branch['email'])->send(new CompanyBranchMailable($mail_data));
    
                if( count(Mail::failures()) > 0 ) {
                    return redirect($redirect_url)->with('message','Company Account created successfully, Failed to send mail');
                }else{
                    return redirect($redirect_url)->with('message','Company Account created successfully, password sent to mail');
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

            return redirect($defdaultLang.'/branch')->with('message','Company Branch Details Updated Succesfully');
        }
    }

    public function deleteCompanyBranch($lang,$id)
	{
        //$id = Crypt::decrypt($id);
        $data = DB::table('company_branch')->where('id','=',$id)->update(['status'=>'0']);
        $defdaultLang = app()->getLocale();
		return redirect($defdaultLang.'/branch')->with('message','Company Branch Deleted Succesfully');
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
