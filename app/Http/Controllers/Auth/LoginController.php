<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Artisan;
use Illuminate\Http\Request;
use Auth;
use DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        Artisan::call('config:clear');
        $this->middleware('guest')->except('logout');
    }
	
	public function custom_login()
    {
        return view('auth.login');
    }
	
	public function redirectTo()
    {
        return app()->getLocale() . '/home';
    }

    public function login(Request $request)
    {
        $membernumber = $request->input('email');

        if(strpos($membernumber, '@') !== false){
           $email = $membernumber;
        }else{
            $email = DB::table('membership')->where('member_number','=',$membernumber)->pluck('email')->first();
        }
        

        // Validate the form data
          $this->validate($request, [
            'email'   => 'required',
            'password' => 'required|min:6'
          ]);
          
          // Attempt to log the user in
          if (Auth::attempt(['email' => $email, 'password' => $request->password])) {
            // if successful, then redirect to their intended location
            return redirect()->intended('en/home');
          } 
          // if unsuccessful, then redirect back to the login with the form data
          return redirect()->back()->withInput($request->only('email', 'remember'));
    }
}
