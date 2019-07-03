<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\Model\Membership;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMemberMailable;
use URL;

class MemberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//$user = Auth::user();
		//dd($user->hasRole('developer'));
        return view('home');
    }
	
	public function register(Request $request)
    {
		$randompass = CommonHelper::random_password(5,true);
		
		$this->validate(request(), [
			'member_name' => 'required',
			'phone'=>'required',
			'company_id'=>'required',
			'branch_id'=>'required',
            'email' => 'required|email',
        ]);
		
		$member_role = Role::where('slug', 'member')->first();
		$status = 0;
		$new_user = new User();
	    $new_user->name = $request->member_name;
	    $new_user->email = $request->email;
	    $new_user->password = bcrypt($randompass);
		$new_user->save();
		$user_id =  $new_user->id;

		$New_member_user = new Membership();
		$New_member_user->name = $request->member_name;
		$New_member_user->phone = $request->phone;
		$New_member_user->branch_id = $request->branch_id;
		$New_member_user->email = $request->email;
		$New_member_user->user_id = $user_id;
		$New_member_user->status = 1;
		$New_member_user->status_id =1;
		$New_member_user->save();
		
	    $new_user->roles()->attach($member_role);
		
		$mail_data = array(
							'name' => $request->member_name,
							'email' => $request->email,
							'password' => $randompass,
							'site_url' => URL::to("/"),
						);
		
		if(!empty($new_user)){
			 $status = Mail::to($request->email)->send(new SendMemberMailable($mail_data));
		}
		if( count(Mail::failures()) > 0 ) {
			return redirect('/')->with('message','Account created successfully, Failed to send mail');
		}else{
			return redirect('/')->with('message','Account created successfully, please check your mail');
		}
    }
}
