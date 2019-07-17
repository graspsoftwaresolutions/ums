<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;
use App\Model\Country;
use App\User;
use App\Model\UnionBranch;
use App\Mail\UnionBranchMailable;
use DB;
use View;
use Mail;
use App\Role;
use URL;
use App\Model\Relation;

class MasterController extends CommonController
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Country = new Country;
        $this->User = new User;
        $this->Relation = new Relation;
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
}
