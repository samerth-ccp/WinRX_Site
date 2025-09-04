<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Mail\MailSenders;
use App\Models\User;

use DB;
use Uploader;
use Socialite;

class SocialController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(){
		$this->middleware('guest');
    }

	/** Google Social Login/Signup */
	public function googleAuthRedirect(Request $request){
		$configData = Session::get('ConfigData');
		return Socialite::driver('google')->redirect();
    }

	public function googleAuthCallback(Request $request){
		
		try{
			$user = Socialite::driver('google')->user();
		} catch (\Laravel\Socialite\Two\InvalidStateException $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}catch (\GuzzleHttp\Exception\ClientException $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}
		catch (\Exception $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}

		// OAuth 2.0 providers...
		$token = $user->token;
		$refreshToken = $user->refreshToken;
		$expiresIn = $user->expiresIn;
		$userData = $user->user;
		
		if(!empty($userData)){

			$isCheckedUser = DB::table('users')->where('email',$userData['email'])->first();

			if(!empty($isCheckedUser)){

				if($isCheckedUser->status == '0'){
					return redirect()->intended(route('frontend.login'))->with('error','Your account has been blocked by admin. Please contact to support.');
				}
				
				auth()->guard('user')->loginUsingId($isCheckedUser->id);
				return redirect(route('frontend.profile'));

			} else {
				$addData['signup_type'] = 'social';
				$addData['signup_gate'] = 'google';
				$addData['name'] = $userData['name'];
				$addData['email'] = $userData['email'];
				$addData['password'] = md5('$**password***$$$');
				$addData['image'] = $this->getPicture($userData['picture']);
				$addData['verify_status'] = '1';
				$addData['created_at'] = date('Y-m-d H:i:s');
				$addData['updated_at'] = date('Y-m-d H:i:s');

				$isInserted = DB::table('users')->insert($addData);

				if($isInserted){

					$user = DB::table('users')->where('email',$userData['email'])->first();
					auth()->guard('user')->loginUsingId($user->id);
					
					return redirect(route('frontend.profile'))->with('success','You have successfully registered.');
				}
				else{
					return redirect(route('frontend.register'))->with('error','Some error occurred.');
				}
			}

		}else{
			return redirect()->intended(route('frontend.login'))->with('error','Some error occurred.');
		}
	}

	/** Facebook Social Login/Signup */
	public function facebookAuthRedirect(Request $request){
		$configData = Session::get('ConfigData');
		return Socialite::driver('facebook')->redirect();
	}

	public function facebookAuthCallback(Request $request){

		try{
			$user = Socialite::driver('facebook')->user();
		} catch (\Laravel\Socialite\Two\InvalidStateException $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}catch (\GuzzleHttp\Exception\ClientException $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}
		catch (\Exception $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}
		
		// OAuth 2.0 providers...
		$token = $user->token;
		$expiresIn = $user->expiresIn;
		$userData = $user->user;

		if(!empty($userData)){
			
			$isCheckedUser = DB::table('users')->where('email',$userData['email'])->first();

			if(!empty($isCheckedUser)){

				if($isCheckedUser->status == '0'){
					return redirect()->intended(route('frontend.login'))->with('error','Your account has been blocked by admin. Please contact to support.');
				}
				
				auth()->guard('user')->loginUsingId($isCheckedUser->id);
				return redirect(route('frontend.profile'));

			} else {

				$addData['signup_type'] = 'social';
				$addData['signup_gate'] = 'facebook';
				$addData['name'] = $userData['name'];
				$addData['email'] = $userData['email'];
				$addData['password'] = md5('$**password***$$$');
				$addData['image'] = $this->getPicture($user->avatar);
				$addData['verify_status'] = '1';
				$addData['created_at'] = date('Y-m-d H:i:s');
				$addData['updated_at'] = date('Y-m-d H:i:s');

				$isInserted = DB::table('users')->insert($addData);

				if($isInserted){

					$user = DB::table('users')->where('email',$userData['email'])->first();
					auth()->guard('user')->loginUsingId($user->id);
					
					return redirect(route('frontend.profile'))->with('success','You have successfully registered.');
				}
				else{
					return redirect(route('frontend.register'))->with('error','Some error occurred.');
				}
			}

		}else{
			return redirect()->intended(route('frontend.login'))->with('error','Some error occurred.');
		}
	}

	/** LinkedIn Social Login/Signup */
	public function linkedinAuthRedirect(Request $request){
		$configData = Session::get('ConfigData');
		return Socialite::driver('linkedin')->redirect();
	}

	public function linkedinAuthCallback(Request $request){

		try {
			$user = Socialite::driver('linkedin')->user();
		} catch (\Laravel\Socialite\Two\InvalidStateException $e) {

			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}catch (\GuzzleHttp\Exception\ClientException $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}
		catch (\Exception $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}
		
		// OAuth 2.0 providers...
		$token = $user->token;
		$expiresIn = $user->expiresIn;

		if(!empty($user)){
			
			$isCheckedUser = DB::table('users')->where('email',$user->email)->first();

			if(!empty($isCheckedUser)){

				if($isCheckedUser->status == '0'){
					return redirect()->intended(route('frontend.login'))->with('error','Your account has been blocked by admin. Please contact to support.');
				}
				
				auth()->guard('user')->loginUsingId($isCheckedUser->id);
				return redirect(route('frontend.profile'));

			} else {

				$addData['signup_type'] = 'social';
				$addData['signup_gate'] = 'linkedin';
				$addData['name'] = $user->name;
				$addData['email'] = $user->email;
				$addData['password'] = md5('$**password***$$$');
				$addData['image'] = $this->getPicture($user->avatar_original);
				$addData['verify_status'] = '1';
				$addData['created_at'] = date('Y-m-d H:i:s');
				$addData['updated_at'] = date('Y-m-d H:i:s');

				$isInserted = DB::table('users')->insert($addData);

				if($isInserted){

					$user = DB::table('users')->where('email',$user->email)->first();
					auth()->guard('user')->loginUsingId($user->id);
					
					return redirect(route('frontend.profile'))->with('success','You have successfully registered.');
				}
				else{
					return redirect(route('frontend.register'))->with('error','Some error occurred.');
				}
			}

		}else{
			return redirect()->intended(route('frontend.login'))->with('error','Some error occurred.');
		}
	}

	/** Twitter Social Login/Signup */
	public function twitterAuthRedirect(Request $request){
		$configData = Session::get('ConfigData');
		return Socialite::driver('twitter')->redirect();
	}

	public function twitterAuthCallback(Request $request){

		try{
			$user = Socialite::driver('twitter')->user();
		} catch (\Laravel\Socialite\Two\InvalidStateException $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}catch (\GuzzleHttp\Exception\ClientException $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}
		catch (\Exception $e) {
			$errorMsg = (!empty($e->getMessage()))?$e->getMessage():'Oops!! Your social login is cancelled, Please try again.';
			return redirect()->intended(route('frontend.login'))->with('error',$errorMsg);
		}

		// OAuth 2.0 providers...
		$token = $user->token;
		$tokenSecret = $user->tokenSecret;

		if(!empty($user)){
			
			$isCheckedUser = DB::table('users')->where('email',$user->email)->first();

			if(!empty($isCheckedUser)){

				if($isCheckedUser->status == '0'){
					return redirect()->intended(route('frontend.login'))->with('error','Your account has been blocked by admin. Please contact to support.');
				}
				
				auth()->guard('user')->loginUsingId($isCheckedUser->id);
				return redirect(route('frontend.profile'));

			} else {

				$addData['signup_type'] = 'social';
				$addData['signup_gate'] = 'twitter';
				$addData['name'] = $user->name;
				$addData['email'] = $user->email;
				$addData['password'] = md5('$**password***$$$');
				$addData['image'] = $this->getPicture($user->avatar_original);
				$addData['verify_status'] = '1';
				$addData['created_at'] = date('Y-m-d H:i:s');
				$addData['updated_at'] = date('Y-m-d H:i:s');

				$isInserted = DB::table('users')->insert($addData);

				if($isInserted){

					$user = DB::table('users')->where('email',$user->email)->first();
					auth()->guard('user')->loginUsingId($user->id);
					
					return redirect(route('frontend.profile'))->with('success','You have successfully registered.');
				}
				else{
					return redirect(route('frontend.register'))->with('error','Some error occurred.');
				}
			}

		}else{
			return redirect()->intended(route('frontend.login'))->with('error','Some error occurred.');
		}
	}

	/** Get Social Picture */
	public function getPicture($url){

		$image = file_get_contents($url);
		$image_name = rand().'-'.time().'.png';

		$path = storage_path('app/public/avtar/').$image_name;
		file_put_contents($path, $image);

		return $image_name;
	}
}
