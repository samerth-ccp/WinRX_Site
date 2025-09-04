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


class ProfileController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	*/
	public function __construct(){
		$this->EmailModel = new MailSenders();
		$this->middleware('user');
    }

	public function index(Request $request){

		$userData = DB::table('users')->where('id',auth()->guard('user')->id())->get()->first();
		$httpPath = asset('assets/storage/avtar/');

		if($request->post()){
			$data = $request->all();

			$validated = array();
			$validated['name'] = 'required|max:25';
			$validated['email'] = 'required|email|max:100';

			$validator = Validator::make($request->all(), $validated);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {

				$data = $request->all();
				unset($data['_token'],$data['image']);

				$changeMail = false;
				if($data['email'] != $userData->email){
					$changeMail = true;

					if(empty($data['password'])){
						return redirect(route('frontend.profile'))->with('error','Password is required for change email.');
					}

					if(!Hash::check($data['password'], $userData->password)){
						return redirect(route('frontend.profile'))->with('error','Your password is invalid.');
					}


					$oldMailData['user_name'] = $userData->name;
					$oldMailData['user_email'] = $userData->email;
					$oldMailData['changed_email'] = $data['email'];
					$oldMailData['mail_template'] = 'change_email_notification';


					/** email vetifiction code */
					$activation_link = md5(generateRandomString(10));
					$verificationUrl = route('frontend.changeemailverification',['token'=>$activation_link]);


					$newMailData['user_name'] = $userData->name;
					$newMailData['user_email'] = $data['email'];
					$newMailData['confirm_link'] = $verificationUrl;
					$newMailData['mail_template'] = 'change_email_verification';

					$data['change_email'] = $data['email'];
					$data['email'] = $userData->email;
					$data['email_activation_link'] = $activation_link;
				}
				unset($data['password']);

				$isUpdated = DB::table('users')->where('id',$userData->id)->update($data);

				if($isUpdated){
					$UserData = DB::table('users')->where('id',$userData->id)->first();
					Session::put('UserData', $UserData);

					$sMsg = 'Profile successfully updated.';
					if($changeMail){

						$this->EmailModel->sendEmail($oldMailData);
						$this->EmailModel->sendEmail($newMailData);

						$sMsg = 'Profile updated successfully And Email verification link has been sent to the updated email address. Please verify to update it.';
					}
					return redirect(route('frontend.profile'))->with('success',$sMsg);
				}
				else{
					if($isUpdated=='0'){
						return redirect(route('frontend.profile'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('frontend.profile'))->with('error','Some error occurred.');
				}

			}
		}

        return view('Frontend.profile.index',compact('userData','httpPath'));
    }

	public function resetPassword(Request $request){
		if($request->post()){

			$passregx ="/^(?=.*\d)(?=.)(?=.*[a-zA-Z]).{8,30}$/";
			$validated = array();
			$validated['current_password'] = 'required|max:30';
			$validated['password'] = ['required','regex:'.$passregx];
			$validated['confirm_password'] = 'required|max:30';

			$validator = Validator::make($request->all(), $validated);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {

				$data = $request->all();

				unset($data['_token']);
				$UserData = DB::table('users')->where('id',auth()->guard('user')->id())->first();

				$current_password = ($data['current_password']);
				$new_password = $data['password'];
				$confirm_password = $data['confirm_password'];

				if(!Hash::check($current_password, $UserData->password)){
					return redirect(route('frontend.profile'))->with('error','Your current password is invalid.');
				}

				if($new_password!=$confirm_password){
					return redirect(route('frontend.profile'))->with('error','Your new password do not match to confirm password.');
				}

				$updatePassword['password'] = Hash::make($new_password);

				$isUpdated = DB::table('users')->where('id',auth()->guard('user')->id())->update($updatePassword);

				if($isUpdated){
					return redirect(route('frontend.profile'))->with('success','Password successfully updated.');
				}
				else{
					if($isUpdated=='0'){
						return redirect(route('frontend.profile'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('frontend.profile'))->with('error','Some error occurred.');
				}

			}
		}
	}

	public function checkImage(Request $request){

		$postData = $request->only('file');
		$file = $postData['file'];

		// Build the input for validation
		$fileArray = array('image' => $file);

		// Tell the validator that this file should be an image
		$rules = array(
			'image' => 'mimes:jpeg,jpg,png|max:10000' // max 10000kb
		);
		$customMessages['image.max'] = 'The :attribute may not be greater than 10 mb.';

		$validator = Validator::make($fileArray, $rules, $customMessages);
		if ($validator->fails())
		{
				// Redirect or return json to frontend with a helpful message to inform the user
				// that the provided file was not an adequate type
				return response()->json(['error' => $validator->errors()->getMessages()], 200);
		} else
		{
			// Store the File Now
			// read image from temporary file
			return response()->json(['success' => true], 200);
		};

		exit;
	}

	public function uploadCropImage(Request $request){

		if (!empty(auth()->guard('user')->id())) {
			$userData = DB::table('users')->where('id',auth()->guard('user')->id())->get()->first();
			$image = $request->image;

			$image = $request->image; // your base64 encoded
			@list($type, $image) = explode(';', $image);
			@list(, $image) = explode(',', $image);
			$imageName = rand().'-'.time().'.png';

			Storage::disk('public')->put('/avtar/'.$imageName, base64_decode($image));

			if(!empty($userData->image)){
				$fileName = $userData->image;
				$isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/avtar/'));
			}

			//Save new image in DB
			DB::table('users')->where('id',$userData->id)->update(['image' => $imageName]);

			return response()->json(['success'=>true,'image'=>getUserImage($imageName)]);
		}
		exit;
	}

}
