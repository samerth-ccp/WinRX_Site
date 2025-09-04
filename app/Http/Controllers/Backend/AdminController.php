<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Mail\MailSenders;
use App\Models\Admin;
use App\Models\User;
use App\Models\Product;

use Exception;
use DB;
use Uploader;

class AdminController extends Controller{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	private $admin;
	public function __construct(Admin $admin){

		$this->admin = $admin;
		$this->EmailModel = new MailSenders();
        $this->product = new Product;
		$this->middleware('admin');
   }

	public function index(Request $request){
		if(empty(Session::get('AdminData'))){

			return redirect(route('backend.login'));
		}
        return view('Backend.index.index');
    }

	/** Dashboard */
	public function dashboard(Request $request){
		Session::put('PageHeading', 'Dashboard');


		$last_seven_dates = getlastsevenday();
		//$users = DB::table('users')->select('id',DB::raw('DATE(created_at) as created_at'))->whereIn(DB::raw('DATE(created_at)'),$last_seven_dates)->get()->all();
        $productCount = $this->product->where('product_status','1')->count();
		//prd($users);

		/*$user_Arr=array();
		foreach($last_seven_dates as $k=>$date)
		{
			$seven_dates[]=date("d, M", strtotime($date));
			$user_cnt = 0;
			foreach($users as $key=>$user)
			{
				//pr($earns->purchased_date."==".$date);
				if($user->created_at==$date){
				$user_cnt = $user_cnt+1;
				$user_Arr[$k]=$user_cnt;
				}
				else{ $user_Arr[$k]=$user_cnt;}
			}
		}
		$last_seven_users = $user_Arr;
		$last_seven_dates = $seven_dates;*/

		return view('Backend.index.index',compact('productCount'));

    }

	public function getchart(Request $request)
	{
		$type = $request->get('type');


		if($type=='chart_month')
		{
			$xaxis_dates = getmonthdates();

		}
		elseif($type=='chart_days')
		{
			$xaxis_dates = getlastsevenday();
		}
		else
		{
			for($m=1; $m<=12; $m++)
			{ $xaxis_dates[$m]=date('M', mktime(0, 0, 0, $m));}
     	}
		//$xaxis_dates = array("1"=>"Jan","2"=>"Feb","3"=>"Mar","4")
		//prd($earning);
		$user_Arr = array();
		if($type=='chart_month' or $type=='chart_days')
		{
			$users = DB::table('users')->select('id',DB::raw('DATE(created_at) as created_at'))->whereIn(DB::raw('DATE(created_at)'),$xaxis_dates)->get()->all();
			foreach($xaxis_dates as $k=>$date)
			{
				$seven_dates[]=date("d M", strtotime($date));
				$user_cnt = 0;
				foreach($users as $key=>$user)
				{
					if($user->created_at==$date){
					$user_cnt = $user_cnt+1;
					$user_Arr[$k]=$user_cnt;
					}
					else{ $user_Arr[$k]=$user_cnt;}

				}
			}
		}
		else
		{
			$users = DB::table('users')->select('id',DB::raw('MONTH(created_at) as created_at'))->where(DB::raw('YEAR(created_at)'),'=',$type)->get()->all();
			foreach($xaxis_dates as $k=>$date)
			{
				$seven_dates[]=$date;
				$user_cnt = 0;
				foreach($users as $key=>$user)
				{
					//pr($earns->purchased_date."==".$date);
					if($user->created_at==$k){
					$user_cnt = $user_cnt+1;
					$user_Arr[$k-1]=$user_cnt;
					}
					else{ $user_Arr[$k-1]=$user_cnt;}

				}
			}

		}

		$last_seven_users = $user_Arr;
		$last_seven_dates = $seven_dates;
		//pr($last_seven_dates);
		echo json_encode(array("last_seven_users"=>$last_seven_users,"last_seven_dates"=>$last_seven_dates));
		exit();

		//return view('Backend.index.getchart');
	}

	/** Site Configuration */
	public function siteconfigs(Request $request,$key){
		$group ="";
		switch ($key) {
			case 'configuration': $group = "SITE_CONFIG"; $title = 'Site'; break;
			case 'social': $group = "SOCIAL_CONFIG"; $title = 'Social'; break;
			case 'payment': $group = "PAYMENT_CONFIG"; $title = 'Payment'; break;
			case 'api': $group = "API_CONFIG"; $title = 'API'; break;
		}

		$configData = DB::table('site_configs')->where('config_groups',$group)->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray();
		if(empty($configData)){
			return redirect(route('backend.dashboard'))->with('error','No records found.');
		}

		Session::put('PageHeading', $title.' Configuration');
		return view('Backend.index.siteconfigs',compact('configData','key','title','group'));
	}

	public function updatesiteconfigs(Request $request,$key){
		$urlKey = $key;
		$group ="";
		switch ($key) {
			case 'configuration': $group = "SITE_CONFIG"; break;
			case 'social': $group = "SOCIAL_CONFIG"; break;
			case 'payment': $group = "PAYMENT_CONFIG"; break;
			case 'api': $group = "API_CONFIG"; break;
		}
		$configData = DB::table('site_configs')->where('config_groups',$group)->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray();
		if(empty($configData)){
			return redirect(route('backend.dashboard'))->with('error','No records found.');
		}
		if($request->post()){

			$exsitconfigData  = array_column($configData,'config_value','config_key');
			$validated = array();
			$customMessages = array();
			foreach($configData as $value){
				if($value->config_type=='file'){
					$validated[$value->config_key]='mimes:jpeg,jpg,png,gif,svg,ico|max:10000';
					$customMessages[$value->config_key.'.max'] = 'The :attribute may not be greater than 10 mb.';
				}
				else {
					if($group!='SOCIAL_CONFIG' && $value->config_key != 'facebook_url' && $value->config_key != 'twitter_url' && $value->config_key != 'instagram_url' && $value->config_key != 'youtube_url'){
						if($value->config_type!='checkbox')
						{
							$validated[$value->config_key] = 'required|max:'.$value->config_max_length;
						}
					}
					else{
					$validated[$value->config_key] = 'max:'.$value->config_max_length;
					}
					//$validated[$value->config_key] = 'required|max:'.$value->config_max_length;
				}
			}

			$validator = Validator::make($request->all(), $validated, $customMessages);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {

				$data = $request->all();
				unset($data['_token']);

				if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/logo/'),
							'files'=>array('site_fav_icon','site_icon'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','gif','bmp','webp','svg','ico','JPG','PNG','GIF','BMP','WebP','SVG'),
						),
						$request
					);

					if(isset($isUpload['success']) && $isUpload['success']){
						foreach($isUpload['media_path'] as $k=>$value){
							$http_path = asset('assets/storage/logo/');
							if(isset($value['mediaPath']) && !empty($value['mediaPath'])){
								//$data[$k] = $http_path.'/'.$value['mediaPath'];
								$data[$k] = $value['mediaPath'];
								$fileName = str_replace($http_path,'',Session::get('ConfigData')[$k]);
								$isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/logo/'));
							}else{
								unset($data[$k]);
								Session::put('error',$value['error']);
							}
						}
					}else{
						return redirect(route('backend.siteconfigs',['key'=>$urlKey]))->with('error',$isUpload['message']);
					}
				}

				foreach($data as $key=>$val){
					/* if(!empty($val)){
						$updateData['config_value'] = $val;
						DB::table('site_configs')->where('config_key',$key)->update($updateData);
					} */
					if($group=='SOCIAL_CONFIG' || $key == 'facebook_url' || $key == 'twitter_url' || $key == 'instagram_url' || $key == 'youtube_url'){
						$updateData['config_value'] = $val;
						DB::table('site_configs')->where('config_key',$key)->update($updateData);
					}
					else{
						if($val!=''){
							$updateData['config_value'] = $val;
							DB::table('site_configs')->where('config_key',$key)->update($updateData);
						}
					}
				}

				$configData = DB::table('site_configs')->select('config_key','config_name','config_value','config_type','config_max_length')->orderBy('config_order')->get()->toArray();
				$configData = array_column($configData,'config_value','config_key');

				Session::put('ConfigData', $configData);

				return redirect(route('backend.siteconfigs',['key'=>$urlKey]))->with('success','Configuration successfully updated.');
			}
		}
		else{
			return redirect(route('backend.siteconfigs',['key'=>$urlKey]))->with('error','Invalid Request!!');
		}
	}

	/** Admin Profile */
	public function adminprofile(Request $request){

		Session::put('PageHeading', 'Profile');

		if($request->post()){

			$validated = array();
			$validated['name'] = 'required|max:25';
			$validated['email'] = 'required|email|max:100';
			$validated['profile_image']='mimes:jpeg,jpg,png,gif,svg,ico,webp|max:100000';
			$customMessages['profile_image.max'] = 'The :attribute may not be greater than 10 mb.';

			$validator = Validator::make($request->all(), $validated,$customMessages);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {

				$data = $request->all();
				unset($data['_token']);

				$AdminData = DB::table('admins')->where('id',auth()->guard('admin')->id())->first();

				if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/avtar/'),
							'files'=>array('profile_image'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','gif','bmp','webp','svg','ico','JPG','PNG','GIF','BMP','WebP','SVG'),
						),
						$request
					);

					if(isset($isUpload['success']) && $isUpload['success']){
						foreach($isUpload['media_path'] as $k=>$value){
							$http_path = asset('assets/storage/avtar/');
							if(isset($value['mediaPath']) && !empty($value['mediaPath'])){
								//$data[$k] = $http_path.'/'.$value['mediaPath'];
								$data[$k] = $value['mediaPath'];
								$fileName = str_replace($http_path,'',Session::get('AdminData')->profile_image);
								$isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/avtar/'));
							}else{
								unset($data[$k]);
								Session::put('error',$value['error']);
							}
						}
					}else{
						return redirect(route('backend.siteconfigs'))->with('error',$isUpload['message']);
					}
				}

				if(empty($data['profile_image'])){
					unset($data['profile_image']);
				}

				$changeMail = false;
				if($data['email'] != $AdminData->email){
					$changeMail = true;
					if(empty($data['password'])){
						return redirect(route('backend.adminprofile'))->with('error','Password is required for change email.');
					}

					if(!Hash::check($data['password'], $AdminData->password)){
						return redirect(route('backend.adminprofile'))->with('error','Your password is invalid.');
					}

					$oldMailData['user_name'] = $AdminData->name;
					$oldMailData['user_email'] = $AdminData->email;
					$oldMailData['changed_email'] = $data['email'];
					$oldMailData['mail_template'] = 'admin_change_email_notification';
				}

				unset($data['password']);

				$isUpdated = DB::table('admins')->where('id',auth()->guard('admin')->id())->update($data);
				if($isUpdated){
					$AdminData = DB::table('admins')->where('id',auth()->guard('admin')->id())->first();
					Session::put('AdminData', $AdminData);

					if($changeMail){
						$sendMail = $this->EmailModel->sendEmail($oldMailData);
					}
					return redirect(route('backend.adminprofile'))->with('success','Profile successfully updated.');
				}
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.adminprofile'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.adminprofile'))->with('error','Some error occurred.');
				}

			}
		}

		return view('Backend.index.adminprofile');
	}

	public function adminresetpassword(Request $request){
		Session::put('PageHeading', 'Reset Password');

		if($request->post()){

			$passregx ="/^(?=.*\d)(?=.)(?=.*[a-zA-Z]).{8,30}$/";
			$validated = array();
			$validated['current_password'] = 'required|max:30';
			$validated['new_password'] = ['required','regex:'.$passregx];
			$validated['confirm_password'] = 'required|max:30';

			$validator = Validator::make($request->all(), $validated);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {

				$data = $request->all();
				unset($data['_token']);
				$AdminData = DB::table('admins')->where('id',auth()->guard('admin')->id())->first();

				$current_password = ($data['current_password']);
				$new_password = $data['new_password'];
				$confirm_password = $data['confirm_password'];

				if(!Hash::check($current_password, $AdminData->password)){
					return redirect(route('backend.resetpassword'))->with('error','Your current password is invalid.');
				}

				if($new_password!=$confirm_password){
					return redirect(route('backend.resetpassword'))->with('error','Your new password do not match to confirm password.');
				}

				$updatePassword['password'] = Hash::make($new_password);

				$isUpdated = DB::table('admins')->where('id',auth()->guard('admin')->id())->update($updatePassword);

				if($isUpdated){

					$MailData['user_name'] = $AdminData->name;
					$MailData['user_email'] = $AdminData->email;
					$MailData['mail_template'] = 'admin_change_password_notification';

					$sendMail = $this->EmailModel->sendEmail($MailData);

					return redirect(route('backend.resetpassword'))->with('success','Password successfully updated.');
				}
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.adminprofile'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.resetpassword'))->with('error','Some error occurred.');
				}

			}
		}

		return view('Backend.index.adminresetpassword');
	}

	public function any(Request $request, $any){
		if (view()->exists('Backend.starterpages.'.$any)) {
			return view('Backend.starterpages.'.$any);
        }
		return view('Backend.errors.pages-404');
   }
}
