<?php

namespace App\Http\Controllers;
use App\Role;
use App\User;
use Illuminate\Http\Request;
class IrcController extends Controller
{
    public function __construct() {
        
    }

    public function ircIndex()
    {
        return view('IRC.irc');
    }
	
	public function index() {
		$irc = env("IRC",'Not set');
		$irc = $irc=='' ? 0 : $irc;
        echo 'IRC:'.$irc;
    }
	
	public function ListIrcAccount() {
		return view('irc.users');
    }
	
	public function AddIrcAccount() {
		return view('irc.add_user');
    }
	public function SaveUserAccount(Request $request) {
		$member_name = $request->input('name');
        $member_email = $request->input('email');
		$password = $request->input('password');
		
		$account_type = $request->input('account_type');
		$user_role = Role::where('slug', $account_type)->first();
		$request->validate([
            'name' => 'required',
                ], [
            'name.required' => 'Please enter User name',
		]);
		$member_user = new User();
		$member_user->name = $member_name;
		$member_user->email = $member_email;
		$member_user->password = bcrypt($password);
		$member_user->save();
		$member_user->roles()->attach($user_role);
		if($member_user){
			return redirect( app()->getLocale().'/list_irc_account')->with('message','User account added successfully'); 
		}else{
			return redirect( app()->getLocale().'/list_irc_account')->with('error','Failed to add account'); 
		}
    }
}