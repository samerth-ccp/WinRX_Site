<?php

namespace Illuminate\Foundation\Auth;
namespace App\Http\Controllers\Backend\Auth;

use App\Models\Backend\Admin;
use Illuminate\Http\Request;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\ResetsPasswords;

use DB;
use Hash;

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

   // use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
	
	public function showResetForm(Request $request, $token = null)
    {
        $configData = DB::table('site_configs')->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray(); 
        $site_configs = array_column($configData,'config_value','config_key');
		
		$userData = DB::table('admins')->where('reset_key','=',$token)->first();
		if(empty($userData)){
			 return redirect()->intended(route('backend.login'))->with('error','Invalid request');
		}
        
        if(empty($token)){
            return redirect()->intended(route('backend.login'))->with('error','Invalid request');
        }

        if (is_null($token)) {
            return $this->getEmail();
        }

        $email = $request->input('email');

		$pageHeading='Reset Password';
        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email','pageHeading','site_configs'));
        }

        if (view()->exists('Backend.auth.passwords.reset')) {
            return view('Backend.auth.passwords.reset')->with(compact('token', 'email','pageHeading','site_configs'));
        }
		
        return view('Backend.auth.reset')->with(compact('token', 'email','pageHeading','site_configs'));
    }
	
	protected function resetpassword(Request $request,$token)
    {
		$userData = DB::table('admins')->where('reset_key','=',$token)->first();
		if(empty($userData)){
			 return redirect()->intended(route('backend.login'))->with('error','Invalid request');
		}
                
        if(empty($token)){
            return redirect()->intended(route('backend.login'))->with('error','Invalid request');
        }
		$passregx ="/^(?=.*\d)(?=.)(?=.*[a-zA-Z]).{8,30}$/";                         
        $validated = array();
        $validated['password'] = ['required','regex:'.$passregx];
        $validated['confirm_password'] = 'required|same:password|min:8|max:30';
        
        $validator = Validator::make($request->all(), $validated);
        
        if ($validator->fails()) {
            return redirect()->intended(route('password.reset',['token'=>$token]))->with('error','Invalid Request!!')->withInput();
        }
       
        $input = $request->all();
		$password=Hash::make($input['password']);
		$update = DB::table('admins')->where('reset_key','=',$token)->update(array('password'=>$password,'reset_key'=>NULL));
		return redirect()->intended(route('backend.login'))->with('success','You have successfully changed your password.');
        
    }
}
