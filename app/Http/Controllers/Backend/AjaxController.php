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
use Illuminate\Support\Facades\Mail;

use App\Models\Admin;
use App\Models\User;

use DB;
use Uploader;

class AjaxController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	private $admin;
	public function __construct(Admin $admin){
		$this->admin = $admin;
		$this->middleware('admin');
    }



	/** Change Status */
	public function ajaxsetstatus(Request $request,$type,$id,$status){
		if($request->ajax()){

			$data = array(
				'users'=>array(
					'table'      => 'users',
					'field_id'      => 'id',
					'field_status'      => 'status'),
				'products'=>array(
					'table'      => 'products',
					'field_id'      => 'product_id',
					'field_status'      => 'product_status'),
			);

			if(isset($data[$type]))
			{
				$updateData[$data[$type]['field_status']] = $status;
				$isUpadted = DB::table($data[$type]['table'])->where($data[$type]['field_id'],$id)->update($updateData);

				echo json_encode(array("success"=>true,"error"=>false,"message"=> 'Status updated successfully'));
			}
			else
			{
				echo json_encode(array("success"=>false,"error"=>true,"exception"=>false,"message"=>"Table Not Defined for the Current Request" ));
			}
		}
		exit();
    }

	public function ajaxRequest(Request $request)
	{
		$data['admin_theme']=$request->theme;

		$isUpadted = DB::table('admins')->where('id',auth()->guard('admin')->id())->update($data);

		if($isUpadted){
			$AdminData = DB::table('admins')->where('id',auth()->guard('admin')->id())->first();

			Session::put('AdminData', $AdminData);
		}

		echo json_encode(array("success"=>true,"error"=>false,"message"=> ''));
		exit();
	}

	public function navajaxRequest(Request $request)
	{
		//echo $request->navMode; die;
		$data['admin_sidebar_size']=$request->navMode;

		$isUpadted = DB::table('admins')->where('id',auth()->guard('admin')->id())->update($data);

		if($isUpadted){
			$AdminData = DB::table('admins')->where('id',auth()->guard('admin')->id())->first();

			Session::put('AdminData', $AdminData);
		}

		echo json_encode(array("success"=>true,"error"=>false,"message"=> ''));
		exit();
	}


}
