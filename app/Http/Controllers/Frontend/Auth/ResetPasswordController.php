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

    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('guest');
    }
	
    public function resetPassword(Request $request,$token){
        if(!empty($token)){

            $ConfigData = Session::get('ConfigData');

            $getUser = DB::table('users')->where('password_reset_link',$token)->get()->first();
            
            if(!empty($getUser)){

                if($request->post()){

                    $data = $request->all();
             
                    if(empty($data['hiddenRecaptcha'])){
                        return redirect()->back()->with('error','Invalid captcha request!!.');
                    }
                    $passregx ="/^(?=.*\d)(?=.)(?=.*[a-zA-Z]).{8,30}$/";
                    $validated = array();
                    $validated['password'] = ['required','regex:'.$passregx];
                    $validated['confirm_password'] = 'required|same:password';
                    
                    $validator = Validator::make($data, $validated);
            
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    } else {
                        /* check g-recaptcha-3 */
                        $res = post_captcha($data['hiddenRecaptcha'],$ConfigData['recaptcha_secret_key']);
        
                        if (!$res['success']) {
                            // What happens when the reCAPTCHA is not properly set up
                            // echo 'reCAPTCHA error: Check to make sure your keys match the registered domain and are in the correct locations. You may also want to doublecheck your code for typos or syntax errors.';
                            return redirect(route('frontend.resetpassword'))->with('error','Your Session expired. Please try again!!.');
                        }else{

                            if($data['password']!=$data['confirm_password']){
                                return redirect()->back()->with('error','Password and confirm password is not matched.');
                            }
                            
                            $hashPassword = Hash::make($data['password']);

                            $updateData['password_reset_link'] = NULL;
                            $updateData['password'] = $hashPassword;

                            $isUpdated = DB::table('users')->where('id',$getUser->id)->update($updateData);

                            if($isUpdated){
                                return redirect(route('frontend.login'))->with('success','Your password successfully updated.');
                            }
                            else{
                                return redirect(route('frontend.login'))->with('error','Some error occurred.');
                            }
                        }
                    }
                }
            }
            else
            {
                return redirect(route('frontend.index.index'))->with('error','Invalid User Request!');
            }

            return view('Frontend.auth.resetpassword');
        }
        else{
            return redirect(route('frontend.login'))->with('error','Invalid Request!');
        }
    }
    
}
