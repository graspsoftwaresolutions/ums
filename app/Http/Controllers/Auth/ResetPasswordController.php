<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
	
	public function showResetForm($lang,Request $request, $token = null)
	{
		return view('auth.passwords.reset')->with(
			['token' => $token, 'email' => $request->email]
		);
	}
	
	public function toMail(Request $request)
    {
		print_r(11);die;
		//return $request->all();
		return $reseturl = app()->getLocale().'password/reset/'.$this->token.'?email=murugan@gmail.com'; exit;
        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url($reseturl)) 
            ->line('If you did not request a password reset, no further action is required.');
    }
	
	public function showLinkRequestForm(){
		die;
	}
	
	public function sendResetLinkEmail(){
		print_r(11);die;
		return 1;
	}
	public function redirectTo()
    {
        return app()->getLocale() . '/home';
    }
}
