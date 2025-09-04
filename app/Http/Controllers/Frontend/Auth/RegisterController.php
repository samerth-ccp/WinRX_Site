<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\User;
use Illuminate\Http\Request;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailSenders;

use DB;
use Hash;

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

    public function register(Request $request){

        $ConfigData = Session::get('ConfigData');
        if($request->post()){
            $data = $request->all();

            if(empty($data['hiddenRecaptcha'])){
                return redirect(route('frontend.register'))->with('error','Invalid captcha request!!.');
            }

            /*
            * Email Validation Rules
            | rfc: RFCValidation
            | strict: NoRFCWarningsValidation
            | dns: DNSCheckValidation
            | spoof: SpoofCheckValidation
            | filter: FilterEmailValidation
            */
            $passregx ="/^(?=.*\d)(?=.)(?=.*[a-zA-Z]).{8,30}$/";
            $validated = array();
			$validated['name'] = 'required|max:25';
            $validated['email'] = 'required|email:rfc,dns|max:250';
			$validated['password'] = ['required','regex:'.$passregx];

			$validator = Validator::make($data, $validated);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {

                /* check g-recaptcha-3 */
				$res = post_captcha($data['hiddenRecaptcha'],$ConfigData['recaptcha_secret_key']);

				if (!$res['success']) {
					// What happens when the reCAPTCHA is not properly set up
					// echo 'reCAPTCHA error: Check to make sure your keys match the registered domain and are in the correct locations. You may also want to doublecheck your code for typos or syntax errors.';
                    return redirect(route('frontend.register'))->with('error','Your Session expired. Please try again!!.');
				}else{

                    $email = strip_tags(trim(strtolower($data['email'])));

                    /** Check email address already exsit in our database */
                    $isCheckEmail = DB::table('users')->where('email',$email)->get()->first();

                    if(!empty($isCheckEmail)){
                        return redirect(route('frontend.register'))->with('error', $email.' - This email address already exist in our database. Please try with different.');
                    }

                    /*Gen Hash Password*/
                    $hashPassword = Hash::make($data['password']);
                    /** email vetifiction code */
                    $activation_link = md5(generateRandomString(10));
                    $verificationUrl = route('frontend.emailverification',['token'=>$activation_link]);

                    $InsertData['name'] = strip_tags($data['name']);
                    $InsertData['email'] = $email;
                    $InsertData['password'] = $hashPassword;
                    $InsertData['created_at'] = date('Y-m-d H:i:s');
                    $InsertData['email_activation_link'] = $activation_link;

                    $isInserted = DB::table('users')->insert($InsertData);

                    if($isInserted){


                        $mailData = array(
                            'user_name' =>  $InsertData['name'],
                            'user_email' => $InsertData['email'],
                            'confirm_link' => $verificationUrl,
                            'mail_template' => 'registration_email',
                        );

                        $sendMail = $this->EmailModel->sendEmail($mailData);

                        return redirect(route('frontend.register'))->with('success','You have successfully registered. Please check your email address for email verification.');
                    }
                    else{
                        return redirect(route('frontend.register'))->with('error','Some error occurred.');
                    }

                }
            }

        }
        return view('Frontend.auth.signup');
    }

    /** Account email verification*/
    public function emailverification(Request $request, $token){
        if(!empty($token)){

            $getUser = DB::table('users')->where('email_activation_link',$token)->get()->first();

            if(!empty($getUser)){

                $updateData['email_activation_link'] = NULL;
                $updateData['verify_status'] = '1';

                $isUpdated = DB::table('users')->where('id',$getUser->id)->update($updateData);

                if($isUpdated){
                    return redirect(route('frontend.login'))->with('success','Your email address successfully verified.');
                }
                else{
                    return redirect(route('frontend.login'))->with('error','Some error occurred.');
                }

            }
            else{
                return redirect(route('frontend.index.index'))->with('error','Invalid Request!');
            }
        }
        else{
            return redirect(route('frontend.login'))->with('error','Invalid Request!');
        }
    }

    /** Change email verification  */
    public function changeEmailVerification(Request $request, $token){
        if(!empty($token)){

            $getUser = DB::table('users')->where('email_activation_link',$token)->get()->first();

            if(!empty($getUser)){

                $updateData['email_activation_link'] = NULL;
                $updateData['change_email'] = NULL;
                $updateData['email'] = $getUser->change_email;

                $isUpdated = DB::table('users')->where('id',$getUser->id)->update($updateData);

                if($isUpdated){
                    return redirect(route('frontend.index.index'))->with('success','Your email address successfully verified.');
                }
                else{
                    return redirect(route('frontend.index.index'))->with('error','Some error occurred.');
                }

            }
            else{
                return redirect(route('frontend.index.index'))->with('error','Invalid Request!');
            }
        }
        else{
            return redirect(route('frontend.index.index'))->with('error','Invalid Request!');
        }
    }
}
