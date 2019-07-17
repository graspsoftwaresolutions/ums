<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;
use App\Model\Country;
use App\User;
use App\Model\Relation;
use App\Model\Race;
use App\Model\Reason;
use App\Model\Persontitle;
use App\Model\UnionBranch;
use App\Model\Designation;
use App\Mail\UnionBranchMailable;
use DB;
use View;
use Mail;
use App\Role;
use URL;


class MasterController extends CommonController
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Country = new Country;
        $this->User = new User;
        $this->Relation = new Relation;
        $this->Race = new Race;
        $this->Reason = new Reason;
        $this->Persontitle = new Persontitle;
        $this->Designation = new Designation;
    }
    public function countryList()
    {
        $data['country_view'] = Country::all();
        return view('master.country.country_list')->with('data',$data);
    }
    //Ajax Datatable Countries List //Users List 
    public function ajax_countries_list(Request $request){
        $columns = array( 
            0 => 'country_name', 
            1 => 'id',
        );

        $totalData = Country::count();

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
        
        
        $data = $this->CommonAjaxReturn($country, 'master.countrydestroy'); 
       
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
            'country_name'=>'required',
        ],
        [
            'country_name.required'=>'please enter Country name',
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
           
            if($saveCountry == true)
            {
                return  redirect($defdaultLang.'/country')->with('message','Country Name Added Succesfully');
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
    }

    //user Details Save and Update
    public function userSave(Request $request)
    {
        $request->validate([
            'name'=>'required',
        ],
        [
            'name.required'=>'Please enter User name',
        ]);

        $data = $request->all();
       
        $defdaultLang = app()->getLocale();
        
        if(!empty($request->id)){
            $data_exists = $this->mailExists($request->input('email'),$request->id);
        }else{
            $data_exists = $this->mailExists($request->input('email'));
        }

        if($data_exists>0)
        {
            return  redirect($defdaultLang.'/users')->with('error','User Email Already Exists'); 
        }
        else if(empty($request->id)) {
           if(($request->password == $request->confirm_password))
           {
                $data['password'] = Crypt::encrypt($request->password);
                //return $data;
                $saveUser = $this->User->saveUserdata($data);
            }
            
            if($saveUser == true)
            {
                return  redirect($defdaultLang.'/users')->with('message','User Added Succesfully');
            }
           
           else {
             return  redirect($defdaultLang.'/users')->with('error','passwords are mismatch');
           }
         }
         else{
            
            $updata['id'] = $request->id;
            $updata['email'] = $request->email;
            $updata['name'] = $request->name;
            
            $saveUser = $this->User->saveUserdata($updata);
            if($saveUser == true)
            {
                return  redirect($defdaultLang.'/users')->with('message','User Updated Succesfully');
            }
         }
    }
    //Users List 
    public function ajax_users_list(Request $request){
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
        $data = $this->CommonAjaxReturn($users, 'master.destroy');
    
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

    public function unionBranchList(Request $request){
        $columns = array( 
            0 => 'union_branch', 
            1 => 'is_head',
            2 => 'email',
            3 => 'id'
        );

        $totalData = UnionBranch::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $unionbranchs = UnionBranch::orderBy($order,$dir)
                ->get();
            }else{
                $unionbranchs = UnionBranch::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            }
                
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $unionbranchs =  UnionBranch::where('id','LIKE',"%{$search}%")
                            ->orWhere('union_branch', 'LIKE',"%{$search}%")
                            ->orWhere('is_head', 'LIKE',"%{$search}%")
                            ->orWhere('email', 'LIKE',"%{$search}%")
                            ->orderBy($order,$dir)
                            ->get();
        }else{
            $unionbranchs =  UnionBranch::where('id','LIKE',"%{$search}%")
                    ->orWhere('union_branch', 'LIKE',"%{$search}%")
                    ->orWhere('is_head', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }

             $totalFiltered = UnionBranch::where('id','LIKE',"%{$search}%")
                    ->orWhere('union_branch', 'LIKE',"%{$search}%")
                    ->orWhere('is_head', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->count();
          
    }
    $data = array();
        if(!empty($unionbranchs))
        {
            foreach ($unionbranchs as $unionbranch)
            {
                $enc_id = Crypt::encrypt($unionbranch->id);  
                $delete =  route('master.deleteunionbranch',[app()->getLocale(),$enc_id]);
                $edit =  route('master.editunionbranch',[app()->getLocale(),$enc_id]);
                $confirmAlert = __("Are you sure you want to delete?");

                $nestedData['union_branch'] = $unionbranch->union_branch;
                $nestedData['is_head'] = $unionbranch->is_head;
                $nestedData['email'] = $unionbranch->email;
                $unionbranchid = $unionbranch->id;
                
                $actions ="<a class='btn-small waves-effect waves-light cyan' href='$edit'>".trans('Edit')."</a>";  
                
                $actions .="&nbsp; <a class='btn-small waves-effect waves-light amber darken-4' href='$delete' onclick='if (confirm('{{ $confirmAlert }}')) return true; else return false;'>".trans('Delete')."</a>";

                

                $nestedData['options'] = $actions;
                $data[] = $nestedData;

            }
        }  
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
        $totalData = Relation::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Relation = Relation::orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }else{
                $Relation = Relation::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Relation =  Relation::where('id','LIKE',"%{$search}%")
                        ->orWhere('relation_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }else{
            $Relation =  Relation::where('id','LIKE',"%{$search}%")
                        ->orWhere('relation_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }
        $totalFiltered = Relation::where('id','LIKE',"%{$search}%")
                    ->orWhere('relation_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = array();
        if(!empty($Relation))
        {
        foreach ($Relation as $Relation)
        {
            $enc_id = Crypt::encrypt($Relation->id);  
            $delete =  route('master.relationdestroy',[app()->getLocale(),$Relation->id]) ;
            $edit =  "#modal_add_edit";
            $nestedData['relation_name'] = $Relation->relation_name;
            $relationid = $Relation->id;
            $actions ="<a style='float: left;' id='$edit' onClick='showeditForm($relationid);' class='btn-small waves-effect waves-light cyan modal-trigger' href='$edit'>".trans('Edit')."</a>";
            $actions .="<a><form style='float: left;margin-left:5px;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
            $actions .="<button  type='submit' class='btn-small waves-effect waves-light amber darken-4'  onclick='return ConfirmDeletion()'>".trans('Delete')."</button> </form>";
            $nestedData['options'] = $actions;
            $data[] = $nestedData;
        }
    }
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
        $totalData = Race::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Race = Race::orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }else{
                $Race = Race::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Race     =  Race::where('id','LIKE',"%{$search}%")
                        ->orWhere('race_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }else{
            $Race      =  Race::where('id','LIKE',"%{$search}%")
                        ->orWhere('race_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }
        $totalFiltered = Race::where('id','LIKE',"%{$search}%")
                    ->orWhere('race_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = array();
        if(!empty($Race))
        {
        foreach ($Race as $Race)
        {
            $enc_id = Crypt::encrypt($Race->id);  
            $delete =  route('master.racedestroy',[app()->getLocale(),$Race->id]) ;
            $edit =  "#modal_add_edit";
            $nestedData['race_name'] = $Race->race_name;
            $raceid = $Race->id;
            $actions ="<a style='float: left;' id='$edit' onClick='showeditForm($raceid);' class='btn-small waves-effect waves-light cyan modal-trigger' href='$edit'>".trans('Edit')."</a>";
            $actions .="<a><form style='float: left;margin-left:5px;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
            $actions .="<button  type='submit' class='btn-small waves-effect waves-light amber darken-4'  onclick='return ConfirmDeletion()'>".trans('Delete')."</button> </form>";
            $nestedData['options'] = $actions;
            $data[] = $nestedData;
        }
    }
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
        $totalData = Reason::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Reason = Reason::orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }else{
                $Reason = Reason::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Reason     =  Reason::where('id','LIKE',"%{$search}%")
                        ->orWhere('reason_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }else{
            $Reason      = Reason::where('id','LIKE',"%{$search}%")
                        ->orWhere('reason_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }
        $totalFiltered = Reason::where('id','LIKE',"%{$search}%")
                    ->orWhere('reason_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = array();
        if(!empty($Reason))
        {
        foreach ($Reason as $Reason)
        {
            $enc_id = Crypt::encrypt($Reason->id);  
            $delete =  route('master.reasondestroy',[app()->getLocale(),$Reason->id]) ;
            $edit =  "#modal_add_edit";
            $nestedData['reason_name'] = $Reason->reason_name;
            $relationid = $Reason->id;
            $actions ="<a style='float: left;' id='$edit' onClick='showeditForm($relationid);' class='btn-small waves-effect waves-light cyan modal-trigger' href='$edit'>".trans('Edit')."</a>";
            $actions .="<a><form style='float: left;margin-left:5px;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
            $actions .="<button  type='submit' class='btn-small waves-effect waves-light amber darken-4'  onclick='return ConfirmDeletion()'>".trans('Delete')."</button> </form>";
            $nestedData['options'] = $actions;
            $data[] = $nestedData;
        }
    }
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
        $totalData = Persontitle::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Persontitle = Persontitle::orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }else{
                $Persontitle = Persontitle::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Persontitle     =  Persontitle::where('id','LIKE',"%{$search}%")
                        ->orWhere('person_title', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }else{
            $Persontitle      = Persontitle::where('id','LIKE',"%{$search}%")
                        ->orWhere('person_title', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }
        $totalFiltered = Persontitle::where('id','LIKE',"%{$search}%")
                    ->orWhere('person_tilte', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = array();
        if(!empty($Persontitle))
        {
        foreach ($Persontitle as $Persontitle)
        {
           
            $enc_id = Crypt::encrypt($Persontitle->id);  
            $delete =  route('master.persontitledestroy',[app()->getLocale(),$Persontitle->id]) ;
            $edit =  "#modal_add_edit";
            $nestedData['person_title'] = $Persontitle->person_title;
            $Persontitle = $Persontitle->id;
            $actions ="<a style='float: left;' id='$edit' onClick='showeditForm($Persontitle);' class='btn-small waves-effect waves-light cyan modal-trigger' href='$edit'>".trans('Edit')."</a>";
            $actions .="<a><form style='float: left;margin-left:5px;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
            $actions .="<button  type='submit' class='btn-small waves-effect waves-light amber darken-4'  onclick='return ConfirmDeletion()'>".trans('Delete')."</button> </form>";
            $nestedData['options'] = $actions;
            $data[] = $nestedData;
        }
    }
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
        $totalData = Designation::count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if(empty($request->input('search.value')))
        {            
            if( $limit == -1){
                $Designation = Designation::orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }else{
                $Designation = Designation::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->where('status','=','1')
                ->get();
            }
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $Designation     =  Designation::where('id','LIKE',"%{$search}%")
                        ->orWhere('designation_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }else{
            $Designation      = Designation::where('id','LIKE',"%{$search}%")
                        ->orWhere('designation_name', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->where('status','=','1')
                        ->orderBy($order,$dir)
                        ->get();
        }
        $totalFiltered = Designation::where('id','LIKE',"%{$search}%")
                    ->orWhere('designation_name', 'LIKE',"%{$search}%")
                    ->where('status','=','1')
                    ->count();
        }
        $data = array();
        if(!empty($Designation))
        {
        foreach ($Designation as $Designation)
        { 
            $enc_id = Crypt::encrypt($Designation->id);  
            $delete =  route('master.designationdestroy',[app()->getLocale(),$Designation->id]) ;
            $edit =  "#modal_add_edit";
            $nestedData['designation_name'] = $Designation->designation_name;
            $Designation = $Designation->id;
            $actions ="<a style='float: left;' id='$edit' onClick='showeditForm($Designation);' class='btn-small waves-effect waves-light cyan modal-trigger' href='$edit'>".trans('Edit')."</a>";
            $actions .="<a><form style='float: left;margin-left:5px;' action='$delete' method='POST'>".method_field('DELETE').csrf_field();
            $actions .="<button  type='submit' class='btn-small waves-effect waves-light amber darken-4'  onclick='return ConfirmDeletion()'>".trans('Delete')."</button> </form>";
            $nestedData['options'] = $actions;
            $data[] = $nestedData;
        }
    }
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
}
