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
use Illuminate\Support\Facades\Mail;
use App\Mail\MailSenders;
Use DB;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    /**
     * Where to redirect users after login / registration.
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
        $this->EmailModel = new MailSenders();
        $this->middleware('guest');
    }

    /**
    * Display the form to request a password reset link.
    *
    * @return \Illuminate\Http\Response
    */
    public function showLinkRequestForm()
    {
        /* logged in user can not see this page */
        $configData = DB::table('site_configs')->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray(); 
        $site_configs = array_column($configData,'config_value','config_key');
		
        if(!empty(auth()->guard('admin')->id())) {
            return redirect(route('backend.dashboard'))->with('error','Invalid Request');
        }
        return view('Backend.auth.passwords.email',array('pageHeading'=>'Forgot Password','site_configs'=>$site_configs));
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
		
		$email=$request->only('email');
        $userData = DB::table('admins')->where('email','=',$email['email'])->first();
		if(empty($userData)){
		  return redirect(route('backend.login'))->with('error',"Email does not exist");
		}
		$reset_key=md5(generateRandomString(10));
        $update = DB::table('admins')->where('email','=',$email['email'])->update(array('reset_key'=>$reset_key));
		$link= route('password.reset',['token'=>$reset_key]);

		$mailData = [
			'user_email'    => $email['email'],
			'user_name'     => $userData->name,
			'reset_link'          => $link,
			'mail_template' => 'forgot_password'
		];

		$sendMail = $this->EmailModel->sendEmail($mailData);

	 	return redirect()->intended(route('backend.login'))->with('success','Mail has been sent to your account to restore your password.');
		

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, 'success',$response)
                    : $this->sendResetLinkFailedResponse($request, 'error',$response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $status, $response)
    {
        return redirect(route('backend.login'))->with($status, trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
        ->withInput($request->only('email'))
        ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admins');
    }
}
