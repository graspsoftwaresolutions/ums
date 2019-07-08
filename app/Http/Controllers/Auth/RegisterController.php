<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Model\Company;
use App\Model\Branch;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function getBranchList(Request $request){
       
        $id = $request->company_id;
        $res = DB::table('branch')
        ->select('id','branch_name')
        ->where([
            ['company_id','=',$id],
            ['status','=','1']
        ])->get();
      
        return response()->json($res);
    }

    public function ShowRegistrationForm()
    {
		//return app()->getLocale();
		$data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
		$data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
		$data['company_view'] = DB::table('company')->where('status','=','1')->get();
		$data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
		$data['race_view'] = DB::table('race')->where('status','=','1')->get();
		$data['status_view'] = DB::table('status')->where('status','=','1')->get();
		$data['fee_list'] = DB::table('fee')->where('status','=','1')->get();
		$data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
		$data['user_type'] = 0;

		return view('membership.add_membership')->with('data',$data);  
		/* $data['company_view'] = DB::table('company')->where('status','=','1')->get();
		return view('auth.register')->with('data',$data); */
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
	
	public function redirectTo()
    {
        return app()->getLocale() . '/home';
    }

}
