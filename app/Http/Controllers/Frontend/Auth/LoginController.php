<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;

use DB;
use Hash;

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
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request){

        if(auth()->guard('user')->id()){
            return redirect()->intended(route('frontend.index.index'));
        }

        if($request->post()){

            $email = $request->input('email');
            $password = $request->input('password');

            $userData = DB::table('users')->where(DB::RAW('LOWER(email)'),trim(strtolower($email)))->first();
            if(!empty($userData)){

                if($userData->status == '0'){
                    return redirect()->intended(route('frontend.login'))->with('error','Your account has been blocked by admin. Please contact to support.');
                }

                if($userData->verify_status == '0'){
                    return redirect()->intended(route('frontend.login'))->with('error','Your email is not verified. Please verify your email address to access account.');
                }

                if($userData->signup_type=="social"){

                    $socialMsg ='';
                    switch ($userData->signup_gate) {
                        case 'google':   $socialMsg = 'This email has been registered through Google Sign-in – <a href="'.route('frontend.googleredirect').'"> Continue with Google Sign-in</a>'; break;
                        case 'facebook': $socialMsg = 'This email has been registered through Facebook Sign-in – <a href="'.route('frontend.facebookredirect').'"> Continue with Facebook Sign-in</a>'; break;
                        case 'linkedin': $socialMsg = 'This email has been registered through LinkedIn Sign-in – <a href="'.route('frontend.linkedinredirect').'"> Continue with LinkedIn Sign-in</a>'; break;
                        case 'twitter':  $socialMsg = 'This email has been registered through Twitter Sign-in – <a href="'.route('frontend.twitterredirect').'"> Continue with Twitter Sign-in</a>'; break;
                    }

                    return redirect()->intended(route('frontend.login'))->with('error', $socialMsg);
                }

                if (auth()->guard('user')->attempt(['email' => $email, 'password' => $password]))
                {
                    $users = DB::table('users')
                            ->where('id', '=', auth()->guard('user')->id())
                            ->first();

                    $request->session()->put('logged_uid', auth()->guard('user')->id());

                    session()->regenerate();

                    Session::put('UserData', $users);

                    return redirect()->intended(route('frontend.index.index'));
                }
                else
                {
                    return redirect()->intended(route('frontend.login'))->with('error','Invalid Login Credentials !');
                }
            }else{
                return redirect()->intended(route('frontend.login'))->with('error','Invalid User Request !');
            }
        }

        return view('Frontend.auth.login');
    }

    public function getLogout(Request $request)
    {
        auth()->guard('user')->logout();
        Session::forget('UserData');
        return redirect()->intended(route('frontend.login'));
    }

}
