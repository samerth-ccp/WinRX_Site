<?php

namespace App\Http\Controllers\Backend\Auth;
use App\Models\Backend\Admin;
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
    public function __construct(){
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function getLoginForm(Request $request){

		$url = $request->get('url');

        if(!empty($url)){
            $request->session()->put('url', $url);
        }

        /* logged in user can not see this page */
        if(!empty(Session::get('AdminData'))) {
            return redirect()->intended(route('backend.dashboard'));
        }

        $configData = DB::table('site_configs')->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray();
        $site_configs = array_column($configData,'config_value','config_key');

        return view('Backend.auth.login',array('pageHeading'=>'Login','site_configs'=>$site_configs));
    }

    public function authenticate(Request $request){

        $url = $request->session()->pull('url');

        $email = $request->input('email');
        $password = $request->input('password');

        if (auth()->guard('admin')->attempt(['email' => $email, 'password' => $password ]))
        {

            /* Set admin session in "AdminData" variable after login */
			$mm=DB::table('admins')->where('id', '=', auth()->guard('admin')->id())->update(array('last_logged_in_on'=>date('Y-m-d H:i:s')));

            $users = DB::table('admins')
                     ->select('id','name','email','profile_image','last_logged_in_on')
                     ->where('id', '=', auth()->guard('admin')->id())
                     ->first();

            $request->session()->put('logged_aid', auth()->guard('admin')->id());

            $configData = DB::table('site_configs')->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray();
            $configData = array_column($configData,'config_value','config_key');

            Session::put('AdminData', $users);
            Session::put('ConfigData', $configData);

            if(!empty($url)){
                return redirect()->intended($url);
            }

            return redirect()->intended(route('backend.dashboard'));
        }
        else
        {
            return redirect()->intended(route('backend.login'))->with('error','Invalid Login Credentials !');
        }
    }

    public function getLogout(Request $request) {
        auth()->guard('admin')->logout();
        Session::forget('AdminData');
        Session::forget('ConfigData');
        return redirect()->intended(route('backend.login'));
    }
}
