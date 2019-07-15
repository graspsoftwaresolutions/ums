<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Crypt;
use View;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$data = User::all();
        return view('users.users');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.add_users');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
            'confirm_password'=>'required',
        ],
        [
            'name.required'=>'Please enter User name',
            'email.required'=>'Please enter Valid Email',
            'password.required'=>'Please enter Password',
            'confirm_password.required'=>'Please enter Confirm Password',
        ]);
       
        $User = new User();
        $User->name = $request->name;
        $User->email = $request->email;
        $User->password = $request->password;
       
        if($User->password == $request->confirm_password)
        {
            $User->password = Crypt::encrypt($User->password);
            $User->save();
            $defdaultLang = app()->getLocale();
            return redirect($defdaultLang.'/users')->with('message','User Details Added Successfully!!');
        }
        else
        {
            $defdaultLang = app()->getLocale();
            return redirect($defdaultLang.'/add_users')->with('error','Paasword and Confirm password mismatch!!');
        }  
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang,$id)
    {
        $id = Crypt::decrypt($id);
        
        $User = new User();
        $data['users_edit'] = User::find($id);
       
        return view('users.edit_users')->with('data',$data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$lang, $id)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required',    
        ],
        [
            'name.required'=>'Please enter User name',
            'email.required'=>'Please enter Valid Email',
        ]);
       
        $User = new User();
        $User = User::find($id);
        $User->name = $request->name;
        $User->email = $request->email;
        $User->save();
        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/users')->with('message','User Details Updated Successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, $id)
    {
        $User = new User();
        $User = User::find($id);
        $User->delete();

        $defdaultLang = app()->getLocale();
        return redirect($defdaultLang.'/users')->with('message','User Details Deleted Successfully!!');
    }


    public function userList(Request $request){
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
        $users = User::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
        $search = $request->input('search.value'); 

        $users =  User::where('id','LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

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
            $delete =  route('users.destroy',[app()->getLocale(),$user->id]) ;
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

}
