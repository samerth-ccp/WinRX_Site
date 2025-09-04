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
use DB;


class UserController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	private $admin;
	public function __construct(Admin $admin)
    {
		$this->admin = $admin;
		$this->EmailModel = new MailSenders();
		$this->middleware('admin');
    }

	/** Users  */
	public function users(Request $request){
		Session::put('PageHeading', 'Users');

		return view('Backend.user.users');
	}

	public function getusers(Request $request){

		if($request->ajax())
		{
			$aColumns = [
				'id',
				'id',
				'name',
				'email',
				'created_at',
				'status',
				'verify_status',
				'signup_gate'
			];

			$sTable = 'users';
			$sIndexColumn = 'id';

			/** { Query Bulider Start */

				$Query = DB::table($sTable)->select($aColumns);
				$iFilteredTotal = DB::table($sTable)->select(DB::Raw(`FOUND_ROWS`))->count();
				$iTotal = DB::table($sTable)->select(DB::Raw(`COUNT(`.$sIndexColumn.`)`));

				/*
				* Paging
				*/
				$sLimit = "";
				if (!empty($request->get('iDisplayLength')) &&  !empty($request->get('iDisplayStart')) != '-1' )
				{
					$Query->offset($request->get('iDisplayStart'))->limit($request->get('iDisplayLength'));
				}

				/*
				* Ordering
				*/
				$sOrder = "";
				if ( !empty($request->get('iSortCol_0')) || $request->get('iSortCol_0')!='')
				{
					for ( $i=0 ; $i<intval($request->get('iSortingCols')) ; $i++ )
					{
						if ($request->get('bSortable_'.intval($request->get('iSortCol_'.$i))) == "true")
						{
							$oColumns = $aColumns[intval($request->get('iSortCol_'.$i))];
							$oType = ($request->get('sSortDir_'.$i)==='asc' ? 'asc' : 'desc');
							$Query->orderBy($oColumns, $oType);
						}
					}
				}
				else{
					$Query->orderBy('created_at', 'desc');
				}

				/*
				* Filtering
				* NOTE this does not match the built-in DataTables filtering which does it
				* word by word on any field. It's possible to do here, but concerned about efficiency
				* on very large tables, and MySQL's regex functionality is very limited
				*/


				$sWhere = "";
				if (!empty($request->get('sSearch')) and $request->get('sSearch') != "" )
				{
					$keyword = $request->get('sSearch');
					$iTotal = $Query = $Query->where(function($query) use ($aColumns,$keyword) {
						for ( $i=0 ; $i<count($aColumns) ; $i++ )
						{
							$query->orWhere(DB::Raw('LOWER('.$aColumns[$i].')'), 'like', "%".strtolower(trim(addslashes($keyword)))."%");
						}
					});
				}

				/* Individual column filtering */
				for ( $i=0 ; $i<count($aColumns) ; $i++ ){
					if (!empty($request->get('bSearchable_'.$i)) and $request->get('bSearchable_'.$i) == "true" and $request->get('bSearchable_'.$i)!='')
					{
						if(!empty($request->get('sSearch_'.$i))){
							$iTotal = $Query->where($aColumns[$i],'like',"%".$request->get('sSearch_'.$i)."%");
						}
					}
				}

				$qry = $Query->get()->all();
				$iTotal = $iTotal->count();
			/* } Query Bulider End */

			$output = array(
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);

			$j=1;

			foreach($qry as $row1)
			{
				$row=array();

				switch ($row1->signup_gate) {
					case 'email':    $gate = '<i data-bs-toggle="tooltip" data-bs-placement="top" title="'.$row1->signup_gate.'" class="fas fa-at" style="color:#4c6ef5;"></i>';  break;
					case 'google':   $gate = '<i data-bs-toggle="tooltip" data-bs-placement="top" title="'.$row1->signup_gate.'" class="fab fa-google" style="color:#82c91e;"></i>';break;
					case 'facebook': $gate = '<i data-bs-toggle="tooltip" data-bs-placement="top" title="'.$row1->signup_gate.'" class="fab fa-facebook-f" style="color:#4c6ef5;"></i>'; break;
					case 'linkedin': $gate = '<i data-bs-toggle="tooltip" data-bs-placement="top" title="'.$row1->signup_gate.'" class="fab fa-linkedin-in" style="color:#0077b5;"></i>'; break;
					case 'twitter':  $gate = '<i data-bs-toggle="tooltip" data-bs-placement="top" title="'.$row1->signup_gate.'" class="fab fa-twitter" style="color:#f783ac;"></i>'; break;
				}

				$row[] = '<code>'.$j.'</code>';

				$row[] = '<div class="text-center"><input class="form-check-input elem_ids checkboxes" type="checkbox" name="'.$sTable.'['.$row1->$sIndexColumn.']" value="'.$row1->$sIndexColumn.'"></div>';

				$vImg = ($row1->verify_status == '1')?asset('/assets/img/shield-check.svg'):asset('/assets/img/shield-off.svg');
				$vStatus = ($row1->verify_status == '1')?'Verified':'Unverified';

				$row[] = $row1->name.' </br><img src="'.$vImg.'" width="12%" data-bs-toggle="tooltip" data-bs-placement="top" title="'.$vStatus.'"> '.$gate;

				$row[] = $row1->email;

				$row[] = date('M d, Y h:i A',strtotime($row1->created_at));

				$checked = ($row1->status=='1')?'checked':'';
				$row[] = '<input type="checkbox" class="status-'.(int)$row1->status.'"  id="'.$sTable."-".$row1->$sIndexColumn.'"  onChange="globalStatus(this);" switch="none" '.$checked.'><label for="'.$sTable."-".$row1->$sIndexColumn.'" data-on-label="On" data-off-label="Off"></label>';

				$accessUrl = route('backend.accessaccount',['uid'=>encrypt($row1->id)]);
				$resendEmailUrl = route('backend.resendverification',['uid'=>encrypt($row1->id)]);

				$accAcc = '<a href="'.$accessUrl.'" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Access Account"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-lock"></i></button></a>';
				$reVerifyEmail = '<a href="'.$resendEmailUrl.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Re-send Verification"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-paper-plane"></i></button></a>';

				$accessAccount = ($row1->verify_status == '1')?$accAcc:$reVerifyEmail;


				$uViewUrl = route('backend.viewusers',['uid'=>encrypt($row1->id)]);
				$row[] = '<a href="'.$uViewUrl.'" data-bs-toggle="tooltip" data-bs-placement="top" title="View User"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-eye"></i></button></a> '.$accessAccount;

				$output['aaData'][] = $row;
				$j++;
			}

			echo json_encode( $output );
			exit();
		}else{
			return redirect(route('backend.users'))->with('error','Unauthorized access.');
		}
	}

	public function manageuser(Request $request){

		Session::put('PageHeading', 'Manage User');
		$uid = $request->route('uid');

		return view('Backend.user.manageuser');
	}

	public function viewuser(Request $request, $uid){

		Session::put('PageHeading', 'Users Information');

		if(empty($uid)){
			return redirect(route('backend.users'))->with('error','Invalid Request!!');
		}

		$uid = decrypt($uid);
		$userData = DB::table('users')->where('id',$uid)->first();

		if(empty($userData)){
			return redirect(route('backend.users'))->with('error','Invalid User Request!!');
		}

		return view('Backend.user.viewuser',compact('userData'));
	}

	public function removeusers(Request $request){
		if($request->post()){

			$records = $request->all();
			$table = 'users';

			foreach($records[$table] as $k=>$record){

				DB::table($table)->where('id',$record)->delete();
			}

			return redirect(route('backend.users'))->with('success','Records deleted successfully.');
		}
		else{
			return redirect(route('backend.users'))->with('error','Invalid Request!!');
		}
	}
	/** End  */

	/** User Access Account */
	public function accessAccount(Request $request, $uid){

		if(!empty($uid)){
			$uid = decrypt($uid);
			$userData = DB::table('users')->where('id',$uid)->first();

			auth()->guard('user')->loginUsingId($uid);

			Session::put('UserData', $userData);
			return redirect()->intended(route('frontend.index.index'));
		}
		else{
			return redirect(route('backend.users'))->with('error','Invalid Request!!');
		}
	}

	/** Resend verification Mail */
	public function resendVerification(Request $request, $uid){
		if(!empty($uid)){
			$uid = decrypt($uid);
			$userData = DB::table('users')->where('id',$uid)->first();

			$activation_link = md5(generateRandomString(10));
			$verificationUrl = route('frontend.emailverification',['token'=>$activation_link]);

			$UpdateData['email_activation_link'] = $activation_link;
			$isUpdated = DB::table('users')->where('id',$uid)->update($UpdateData);

			if($isUpdated){
				$mailData = array(
					'user_name' =>  $userData->name,
					'user_email' => $userData->email,
					'confirm_link' => $verificationUrl,
					'mail_template' => 'registration_email',
				);

				$sendMail = $this->EmailModel->sendEmail($mailData);
				return redirect(route('backend.users'))->with('success','Email sended successfully.');
			}
			else{
				return redirect(route('backend.users'))->with('error','Some error occurred.');
			}
		}
		else{
			return redirect(route('backend.users'))->with('error','Invalid Request!!');
		}
	}
}
