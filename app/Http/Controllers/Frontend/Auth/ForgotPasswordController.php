<?php

namespace Illuminate\Foundation\Auth;
namespace App\Http\Controllers\Frontend\Auth;

use App\Models\User;
use Illuminate\Http\Request;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailSenders;

use DB;
use Hash;

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
    public function __construct(){
        $this->EmailModel = new MailSenders();
        $this->middleware('guest');
    }

    public function forgotPassowrd(Request $request){

        $ConfigData = Session::get('ConfigData');

        if($request->post()){
            $data = $request->all();
            
            if(empty($data['hiddenRecaptcha'])){
                return redirect(route('frontend.forgotpassowrd'))->with('error','Invalid captcha request!!.');
            }

            $validated = array();
			$validated['email'] = 'required|email|max:250';

			$validator = Validator::make($data, $validated);
		
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {
            
                /* check g-recaptcha-3 */
				$res = post_captcha($data['hiddenRecaptcha'],$ConfigData['recaptcha_secret_key']);

				if (!$res['success']) {
					// What happens when the reCAPTCHA is not properly set up
					// echo 'reCAPTCHA error: Check to make sure your keys match the registered domain and are in the correct locations. You may also want to doublecheck your code for typos or syntax errors.';
                    return redirect(route('frontend.forgotpassowrd'))->with('error','Your Session expired. Please try again!!.');
				}else{

                    $email = strip_tags(trim(strtolower($data['email'])));
                    $checkUser = DB::table('users')->where('email',$email)->get()->first();
                    if(!empty($checkUser)){

                        if($checkUser->signup_type == 'social'){
                            return redirect(route('frontend.forgotpassowrd'))->with('error','It seems that you did social login, you cannot change your password!');
                        }

                        /** password reset code */
                        $reset_code = md5(generateRandomString(10));
                        $resetLink = route('frontend.resetpassword',['token'=>$reset_code]);
          
                        $updateData['password_reset_link'] = $reset_code;

                        $isUpdated = DB::table('users')->where('id',$checkUser->id)->update($updateData);
                        
                        if($isUpdated){

                            $mailData = [
                                'user_name'     => $checkUser->name,
                                'user_email'    => $email,
                                'reset_link'    => $resetLink,
                                'mail_template' => 'forgot_password'
                            ];

                            $sendMail = $this->EmailModel->sendEmail($mailData);
    
                            return redirect(route('frontend.login'))->with('success','Mail has been sent to your account to restore your password.');
                        }
                        else{
                            return redirect(route('frontend.forgotpassowrd'))->with('error','Some error occurred.');
                        }
                    }   
                    else{
                        return redirect(route('frontend.forgotpassowrd'))->with('error','Please make sure your email is registered with us, check your email to reset the password.');
                    }
                }
            }
        }

        return view('Frontend.auth.forgotpassowrd');
    }
}
