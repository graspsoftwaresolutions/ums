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

class AjaxController extends CommonController
{
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
                    ->orWhere('person_title', 'LIKE',"%{$search}%")
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

     //Company Deatils Starts 
     public function checkCompanyNameExists(Request $request)
     {
         $company_name =  $request->input('company_name');
         $company_id = $request->input('company_id'); 
       
         return $this->checkCompanyExists($company_name,$company_id);
     }

     //Form Type Deatils Start
    public function checkFormTypeNameExists(Request $request)
    {
        //dd($request->all());
        $formname =  $request->input('formname');
        $formtype_id = $request->input('formtype_id');   
        return $this->checkFormTyNameExists($formname,$formtype_id);
    }

    //Status Details Start  
    public function checkStatusNameExists(Request $request)
    {
        $status_name =  $request->input('status_name');
        $status_id = $request->input('status_id');   
        return $this->checkStatusExists($status_name,$status_id);
    }

    //Designation Details Start
    public function checkDesignationNameExists(Request $request)
    { 
        $designation_name =  $request->input('designation_name');
        $designation_id = $request->input('designation_id');
        
        return $this->checkDesignationExists($designation_name,$designation_id);
    }
    //Appform
    public function checkAppformExists(Request $request)
    {   
        $formname =  $request->input('formname');
       $formname_id = $request->input('formname_id');

       if(!empty($formname_id))
         {
               
           $formname_exists = AppForm::where([
                 ['formname','=',$formname],
                 ['id','!=',$formname_id],
                 ['status','=',1]
                 ])->count();
         }
         else
         {
           $formname_exists = AppForm::where([
               ['formname','=',$formname],
               ['status','=',1]
               ])->count(); 
         } 
         if($formname_exists > 0)
         {
             return "false";
         }
         else{
             return "true";
         }
   }

   //Reason Name Exists Check
   public function checkReasonNameExists(Request $request)
   { 
       $reason_name =  $request->input('reason_name');
       $reason_id = $request->input('reason_id');
       
       return $this->checkReasonExists($reason_name,$reason_id);
       //return $race_id;
   }

   //Role Name Exists Check
   public function checkRoleNameExists(Request $request)
   {
       $name =  $request->input('name');
       $role_id = $request->input('role_id');

       if(!empty($role_id))
         {
               
           $rolename_exists = Role::where([
                 ['name','=',$name],
                 ['id','!=',$role_id]
                 ])->count();
         }
         else
         {
           $rolename_exists = Role::where([
               ['name','=',$name]    
               ])->count(); 
         } 
         if($rolename_exists > 0)
         {
             return "false";
         }
         else{
             return "true";
         }
   }

   //Fee Name Exists Check
   public function checkFeeNameExists(Request $request)
   {
       $fee_name =  $request->input('fee_name');
       $fee_id = $request->input('fee_id');

       if(!empty($fee_id))
         {
               
                $feename_exists = Fee::where([
                 ['fee_name','=',$fee_name],
                 ['id','!=',$fee_id],
                 ['status','=','1']
                 ])->count();
         }
         else
         {
           $feename_exists = Fee::where([
               ['fee_name','=',$fee_name],
               ['status','=','1'],     
               ])->count(); 
         } 
         if($feename_exists > 0)
         {
             return "false";
         }
         else{
             return "true";
         }
   }

   //Race Name Exists Check
   public function checkRaceNameExists(Request $request)
   {
       
       $race_name =  $request->input('race_name');
       $race_id = $request->input('race_id');
       
       return $this->checkRaceExists($race_name,$race_id);
       //return $race_id;
   }

   //Relation Name Exists Check
   public function checkRelationNameExists(Request $request)
   {
       $relation_name =  $request->input('relation_name');
       $relation_id = $request->input('relation_id');
       return $this->checkRelationExists($relation_name,$relation_id);
   }

   //City Name Exists Check
   public function checkCityNameExists(Request $request)
   {
       $city_name =  $request->input('city_name');
       $city_id = $request->input('city_id');
       $country_id = $request->input('country_id');
       $state_id = $request->input('state_id');

       if(!empty($city_id))
         {
               
                $cityname_exists = City::where([
                 ['city_name','=',$city_name],
                 ['country_id','=',$country_id],
                 ['state_id','=',$state_id],
                 ['id','!=',$city_id],
                 ['status','=','1']
                 ])->count();
         }
         else
         {
           $cityname_exists = City::where([
               ['city_name','=',$city_name],
               ['country_id','=',$country_id],
               ['state_id','=',$state_id],
               ['status','=','1'],     
               ])->count(); 
         } 
         if($cityname_exists > 0)
         {
             return "false";
         }
         else{
             return "true";
         }
   }

   //State Name Exists Check
   public function checkStateNameExists(Request $request)
   {
       $state_name =  $request->input('state_name');
       $state_id = $request->input('state_id');
       $country_id = $request->input('country_id');

       if(!empty($state_id))
         {
               
                $statename_exists = State::where([
                 ['state_name','=',$state_name],
                 ['country_id','=',$country_id],
                 ['id','!=',$state_id],
                 ['status','=','1']
                 ])->count();
         }
         else
         {
           $statename_exists = State::where([
               ['state_name','=',$state_name],
               ['country_id','=',$country_id],
               ['status','=','1'],     
               ])->count(); 
         } 
         if($statename_exists > 0)
         {
             return "false";
         }
         else{
             return "true";
         }
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
    //Person Title Details Start
    public function checkTitleNameExists(Request $request)
    { 
        $person_title =  $request->input('person_title');
        $persontitle_id = $request->input('persontitle_id');
        
        return $this->checkPersonTitleExists($person_title,$persontitle_id);
    }

    public function checkFormTypeModuleExists(Request $request)
    {   
        $module =  $request->input('module');
        $formtype_id = $request->input('formtype_id');

        if(!empty($formtype_id))
        {   
        $module_exists = FormType::where([
                ['module','=',$module],
                ['id','!=',$formtype_id],
                ['status','=',1]
                ])->count();
        }
        else
        {
        $module_exists = FormType::where([
            ['module','=',$module],
            ['status','=',1]
            ])->count(); 
        } 
        if($module_exists > 0)
        {
            return "false";
        }
        else{
            return "true";
        }
    }
     //Form Type Deatils End

     public function checkCompanyBranchemailExists(Request $request){
		//return $request->all();
		$email =  $request->input('email');
        $db_autoid = $request->input('db_autoid');
		if($db_autoid=='' || $db_autoid==null)
        {
			return $branchexists = $this->CompanyBranchmailExists($email);
		}else{
			return $branchexists = $this->CompanyBranchmailExists($email,$db_autoid);
		}
		//return Response::json($return_status);
    }
}
