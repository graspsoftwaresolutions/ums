<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;
use App\Model\Country;
use App\User;

class MasterController extends CommonController
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Country = new Country;
        $this->User = new User;
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
                    $country = Country::orderBy($order,$dir)
                    ->where('status','=','1')
                    ->get();
                }else{
                    $country = Country::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->where('status','=','1')
                    ->get();
                }
            
            }
            else {
            $search = $request->input('search.value'); 
            if( $limit == -1){
                $country =  Country::where('id','LIKE',"%{$search}%")
                            ->orWhere('country_name', 'LIKE',"%{$search}%")
                            ->where('status','=','1')
                            ->orderBy($order,$dir)
                            ->get();
            }else{
                $country =  Country::where('id','LIKE',"%{$search}%")
                            ->orWhere('country_name', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->where('status','=','1')
                            ->orderBy($order,$dir)
                            ->get();
            }
            $totalFiltered = Country::where('id','LIKE',"%{$search}%")
                        ->orWhere('country_name', 'LIKE',"%{$search}%")
                        ->where('status','=','1')
                        ->count();
            }
    
            $data = array();
            if(!empty($country))
            {
            foreach ($country as $country)
            {
                $enc_id = Crypt::encrypt($country->id);  
                $delete =  route('master.countrydestroy',[app()->getLocale(),$country->id]) ;
                $edit =  "#modal_add_edit";
    
                $nestedData['country_name'] = $country->country_name;
                $countryid = $country->id;
    
                $actions ="<a style='float: left;' id='$edit' onClick='showeditForm($countryid);' class='btn-small waves-effect waves-light cyan modal-trigger' href='$edit'>".trans('Edit')."</a>";
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
                $users = User::orderBy($order,$dir)
                ->get();
            }else{
                $users = User::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            }
        
        }
        else {
        $search = $request->input('search.value'); 
        if( $limit == -1){
            $users =  User::where('id','LIKE',"%{$search}%")
                        ->orWhere('name', 'LIKE',"%{$search}%")
                        ->orWhere('email', 'LIKE',"%{$search}%")
                        ->orderBy($order,$dir)
                        ->get();
        }else{
            $users =  User::where('id','LIKE',"%{$search}%")
                        ->orWhere('name', 'LIKE',"%{$search}%")
                        ->orWhere('email', 'LIKE',"%{$search}%")
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->get();
        }
        $totalFiltered = User::where('id','LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->count();
        }

        $data = array();
        if(!empty($users))
        {
        foreach ($users as $user)
        {
            $enc_id = Crypt::encrypt($user->id);  
            $delete =  route('master.destroy',[app()->getLocale(),$user->id]) ;
            $edit =  "#modal_add_edit";

            $nestedData['name'] = $user->name;
            $nestedData['email'] = $user->email;
            $userid = $user->id;

            $actions ="<a style='float: left;' id='$edit' onClick='showeditForm($userid);' class='btn-small waves-effect waves-light cyan modal-trigger' href='$edit'>".trans('Edit')."</a>";
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
        //$data = User::all();
        return view('master.users.users');
    }
}
