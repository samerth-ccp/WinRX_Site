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
use Uploader;

class StaticController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	private $admin;
	public function __construct(Admin $admin){
		$this->admin = $admin;
		$this->EmailModel = new MailSenders();
		$this->middleware('admin');
    }

	/** Email Template */
	public function emailtemplate(Request $request){
		Session::put('PageHeading', 'Email Templates');
		return view('Backend.static.emailtemplate');
	}

	public function gettemplate(Request $request){

		if($request->ajax())
		{
			$aColumns = [
				'template_id',
				'template_title',
				'template_subject',
				'last_update_on',
			];

			$sTable = 'email_template';
			$sIndexColumn = 'template_id';

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
					$Query->orderBy('last_update_on', 'desc');
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

				$row[] = '<code>'.$j.'</code>';

				$row[] = $row1->template_title;

				$row[] = $row1->template_subject;

				$row[] = '<span class="d-none">'.strtotime($row1->last_update_on).'</span>'.date('M d, Y h:i A',strtotime($row1->last_update_on));

				$editUrl = route('backend.managetemplate', ['tid'=>encrypt($row1->template_id)]);
				$uViewUrl = route('backend.viewtemplate', ['tid'=>encrypt($row1->template_id)]);
				$row[] = '
					<a href="'.$editUrl.'"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-edit"></i></button></a>
					&nbsp;&nbsp;
					<a href="'.$uViewUrl.'"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-eye"></i></button></a>
				';

				$output['aaData'][] = $row;
				$j++;
			}

			echo json_encode( $output );
			exit();
		}else{
			return redirect()->back()->with('error','Unauthorized access.');
		}
	}

	public function managetemplate(Request $request, $tid){

		Session::put('PageHeading', 'Manage Template');

		if(empty($tid)){
			return redirect(route('backend.emailtemplate'))->with('error','Invalid Request!!');
		}

		$tid = decrypt($tid);
		$templateData = DB::table('email_template')->where('template_id',$tid)->first();

		if(empty($templateData)){
			return redirect(route('backend.emailtemplate'))->with('error','Invalid Template Request!!');
		}

		$templateData->template_content = str_ireplace(array('{logo_url}','{site_url}'),array(asset('assets/storage/logo/').'/'.Session::get('ConfigData')['site_icon'],config('app.url')),$templateData->template_content);

		if($request->post()){


			$validated = array();
			$validated['template_title'] = 'required|max:100';
			$validated['template_subject'] = 'required|max:100';
			$validated['template_content'] = 'required';

			$validator = Validator::make($request->all(), $validated);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {
				$data = $request->all();
				//prd($data);
				unset($data['_token']);
				try{
				$data['template_content'] = str_ireplace(array(asset('assets/storage/logo/').'/'.Session::get('ConfigData')['site_icon'],config('app.url')),array('{logo_url}','{site_url}'),$data['template_content']);
				}
				catch(\Exception $e)
				{
					prd($e->getMessage());
				}

				$data['last_update_on'] = date('Y-m-d H:i:s');

				$isUpdated = DB::table('email_template')->where('template_id',$tid)->update($data);

				if($isUpdated){
					return redirect(route('backend.emailtemplate'))->with('success','Email template successfully updated.');
				}
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.emailtemplate'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.emailtemplate'))->with('error','Some error occurred.');
				}
			}
		}

		return view('Backend.static.managetemplate',compact('templateData'));
	}

	public function viewtemplate(Request $request, $tid){

		Session::put('PageHeading', 'View Template');

		if(empty($tid)){
			return redirect(route('backend.emailtemplate'))->with('error','Invalid Request!!');
		}

		$tid = decrypt($tid);
		$templateData = DB::table('email_template')->where('template_id',$tid)->first();

		if(empty($templateData)){
			return redirect(route('backend.emailtemplate'))->with('error','Invalid Template Request!!');
		}

		return view('Backend.static.viewtemplate',compact('templateData'));
	}
	/** End */

	/** Pages */
	public function pages(Request $request){
		Session::put('PageHeading', 'Pages');
		return view('Backend.static.pages');
	}

	public function getpages(Request $request){
		if($request->ajax())
		{
			$aColumns = [
				'page_id',
				'title_en',
				'updated_at',
			];

			$sTable = 'pages';
			$sIndexColumn = 'page_id';

			/** { Query Bulider Start */

				$Query = DB::table($sTable)->select($aColumns);
				$iFilteredTotal = DB::table($sTable)->select(DB::Raw(`FOUND_ROWS`))->count();
				$iTotal = DB::table($sTable)->select(DB::Raw(`COUNT(`.$sIndexColumn.`)`));

				/*
				* Paging
				*/
				$sLimit = "";
				if (!empty($request->get('iDisplayLength')) &&  !empty($request->get('iDisplayStart')) != '-1' ){
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
					$Query->orderBy('updated_at', 'desc');
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

				$row[] = '<code>'.$j.'</code>';

				$row[] = $row1->title_en;

				$row[] = date('M d, Y h:i A',strtotime($row1->updated_at));

				$editUrl = route('backend.managepages', ['pid'=>encrypt($row1->page_id)]);
				$uViewUrl = route('backend.viewpages', ['pid'=>encrypt($row1->page_id)]);
				$row[] = '
					<a href="'.$editUrl.'"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-edit"></i></button></a>
					&nbsp;&nbsp;
					<a href="'.$uViewUrl.'"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-eye"></i></button></a>
				';

				$output['aaData'][] = $row;
				$j++;
			}

			echo json_encode( $output );
			exit();
		}else{
			return redirect()->back()->with('error','Unauthorized access.');
		}
	}

    public function slidersection(Request $request) {
        Session::put('PageHeading', 'Manage Slider Section');
        $slidermainData = DB::table('slider_content')->first();
		return view('Backend.static.slidersection',compact('slidermainData'));
    }

    public function getslidersection(Request $request){
		if($request->ajax())
		{
			$aColumns = [
				'slider_section_id',
				'slider_section_tagline',
				'slider_section_heading',
                'slider_section_sub_heading'
			];

			$sTable = 'slider_section';
			$sIndexColumn = 'slider_section_id';

			/** { Query Bulider Start */

				$Query = DB::table($sTable)->select($aColumns);
				$iFilteredTotal = DB::table($sTable)->select(DB::Raw(`FOUND_ROWS`))->count();
				$iTotal = DB::table($sTable)->select(DB::Raw(`COUNT(`.$sIndexColumn.`)`));

				/*
				* Paging
				*/
				$sLimit = "";
				if (!empty($request->get('iDisplayLength')) &&  !empty($request->get('iDisplayStart')) != '-1' ){
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
					$Query->orderBy('slider_section_id', 'desc');
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

				$row[] = '<code>'.$j.'</code>';

                $row[] = '<div class="text-center"><input class="form-check-input elem_ids checkboxes" type="checkbox" name="'.$sTable.'['.$row1->$sIndexColumn.']" value="'.$row1->$sIndexColumn.'"></div>';

				$row[] = $row1->slider_section_tagline;

                $row[] = $row1->slider_section_heading;

                $row[] = $row1->slider_section_sub_heading;


				$editUrl = route('backend.manageslidersection', ['pid'=>encrypt($row1->slider_section_id)]);
				$row[] = '
					<a href="'.$editUrl.'"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-edit"></i></button></a>
					&nbsp;
				';

				$output['aaData'][] = $row;
				$j++;
			}

			echo json_encode( $output );
			exit();
		}else{
			return redirect()->back()->with('error','Unauthorized access.');
		}
	}

    public function manageslidersection(Request $request, $pid = ''){

		Session::put('PageHeading', 'Manage Slider Section');
        $sliderData = array();
        if(!empty($pid)) {
		    $pid = decrypt($pid);
		    $sliderData = DB::table('slider_section')->where('slider_section_id',$pid)->first();
            if(empty($sliderData)){
			    return redirect(route('backend.slidersection'))->with('error','Invalid Page Request!!');
		    }
        }

		if($request->post()){
            $validated = array();
            $validated['slider_section_tagline'] = 'required|max:40';
            $validated['slider_section_tagline_image']='mimes:jpeg,jpg,png,svg';
            $validated['slider_section_heading'] = 'required|max:40';
            $validated['slider_section_sub_heading'] = 'required|max:60';
            $validated['slider_section_para'] = 'required|max:255';
            $validated['slider_section_background_image']='mimes:jpeg,jpg,png';
            $validated['slider_section_image']='mimes:jpeg,jpg,png';
            $validated['slider_section_reviewer_image'] = 'mimes:jpeg,jpg,png';
            $validated['slider_section_reviewer_name'] = 'max:150';
            $validated['slider_section_reviewer_info'] = 'max:150';
            $validated['slider_section_review'] = 'max:255';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                if(!empty($request->file())){
                    try {
                        $isUpload = Uploader::universalUpload(
                            array(
                                'directory'=>storage_path('app/public/homeimages/'),
                                'files'=>array('slider_section_tagline_image','slider_section_background_image','slider_section_image','slider_section_reviewer_image'),
                                'multiple'=>false,
                                'thumb'=>array(), //array('w'=>60,'h'=>60)
                                'allowExtension' => array('jpg','png','JPG','PNG','svg','SVG'),
                            ),
                            $request
                        );
                    } catch (\Exception | Error $e) {
                        prd($e->getMessage());
                    }
                }
                $sliderDataInsert['slider_section_tagline'] = $data['slider_section_tagline'];
				$sliderDataInsert['slider_section_heading'] = $data['slider_section_heading'];
                $sliderDataInsert['slider_section_sub_heading'] = $data['slider_section_sub_heading'];
                $sliderDataInsert['slider_section_para'] = $data['slider_section_para'];
                $sliderDataInsert['slider_section_reviewer_name'] = $data['slider_section_reviewer_name'];
                $sliderDataInsert['slider_section_reviewer_info'] = $data['slider_section_reviewer_info'];
                $sliderDataInsert['slider_section_review'] = $data['slider_section_review'];
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["slider_section_tagline_image"]["mediaPath"])) {
                    $sliderDataInsert['slider_section_tagline_image'] = $isUpload["media_path"]["slider_section_tagline_image"]["mediaPath"];
                    if(!empty($sliderData->slider_section_tagline_image)) {
                        $fileName = str_replace($http_path,'',$sliderData->slider_section_tagline_image);
                        $filePath = storage_path('app/public/homeimages/') . $fileName;
                        if (file_exists($filePath)) {
                            $isDelete = Uploader::universalUnlink($fileName, storage_path('app/public/homeimages/'));
                        } else {
                            $isDelete = false; // or handle this however you need
                        }
                    }
                } else {
                    if(!empty($sliderData->slider_section_tagline_image)) {
                        $sliderDataInsert['slider_section_tagline_image'] = $sliderData->slider_section_tagline_image;
                    }
                }
                if(!empty($isUpload["media_path"]["slider_section_background_image"]["mediaPath"])) {
                    $sliderDataInsert['slider_section_background_image'] = $isUpload["media_path"]["slider_section_background_image"]["mediaPath"];
                    if(!empty($sliderData->slider_section_background_image)) {
                        $fileName = str_replace($http_path,'',$sliderData->slider_section_background_image);
					    $filePath = storage_path('app/public/homeimages/') . $fileName;
                        if (file_exists($filePath)) {
                            $isDelete = Uploader::universalUnlink($fileName, storage_path('app/public/homeimages/'));
                        } else {
                            $isDelete = false; // or handle this however you need
                        }
                    }
                } else {
                    if(!empty($sliderData->slider_section_background_image)) {
                        $sliderDataInsert['slider_section_background_image'] = $sliderData->slider_section_background_image;
                    }
                }
                if(!empty($isUpload["media_path"]["slider_section_image"]["mediaPath"])) {
                    $sliderDataInsert['slider_section_image'] = $isUpload["media_path"]["slider_section_image"]["mediaPath"];
                    if(!empty($sliderData->slider_section_image)) {
                        $fileName = str_replace($http_path,'',$sliderData->slider_section_image);
					    $filePath = storage_path('app/public/homeimages/') . $fileName;
                        if (file_exists($filePath)) {
                            $isDelete = Uploader::universalUnlink($fileName, storage_path('app/public/homeimages/'));
                        } else {
                            $isDelete = false; // or handle this however you need
                        }
                    }
                }
                if(!empty($isUpload["media_path"]["slider_section_reviewer_image"]["mediaPath"])) {
                    $sliderDataInsert['slider_section_reviewer_image'] = $isUpload["media_path"]["slider_section_reviewer_image"]["mediaPath"];
                    if(!empty($sliderData->slider_section_reviewer_image)) {
                        $fileName = str_replace($http_path,'',$sliderData->slider_section_reviewer_image);
					    $filePath = storage_path('app/public/homeimages/') . $fileName;
                        if (file_exists($filePath)) {
                            $isDelete = Uploader::universalUnlink($fileName, storage_path('app/public/homeimages/'));
                        } else {
                            $isDelete = false; // or handle this however you need
                        }
                    }
                }

                if(!empty($sliderData)) {
				    $isUpdated = DB::table('slider_section')->where('slider_section_id',$pid)->update($sliderDataInsert);
                } else {
                    $isUpdated = DB::table('slider_section')->insert($sliderDataInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.slidersection'))->with('success','Banner Section successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.slidersection'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.slidersection'))->with('error','Some error occurred.');
				}
            }
		}

		return view('Backend.static.manageslidersection',compact('sliderData'));
	}

    public function removeslidersection(Request $request){
		if($request->post()){

			$records = $request->all();
			$table = 'slider_section';

			foreach($records[$table] as $k=>$record){

				DB::table($table)->where('slider_section_id',$record)->delete();
			}

			return redirect(route('backend.slidersection'))->with('success','Records deleted successfully.');
		}
		else{
			return redirect(route('backend.slidersection'))->with('error','Invalid Request!!');
		}
	}

    public function bannersection(Request $request) {
        Session::put('PageHeading', 'Manage Banner Section');
        $bannerData = DB::table('banner_section')->first();
        if($request->post()){
            $validated = array();
            $validated['banner_first_heading'] = 'required|max:30';
            $validated['banner_second_heading'] = 'required|max:30';
            $validated['banner_image']='mimes:jpeg,jpg,png';
            $validated['banner_background_image']='mimes:jpeg,jpg,png';
            $validated['banner_para'] = 'required|max:255';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('banner_image','banner_background_image'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);
                }
                $bannerDataInsert['banner_first_heading'] = $data['banner_first_heading'];
				$bannerDataInsert['banner_second_heading'] = $data['banner_second_heading'];
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["banner_image"]["mediaPath"])) {
                    $bannerDataInsert['banner_image'] = $isUpload["media_path"]["banner_image"]["mediaPath"];
                    $fileName = str_replace($http_path,'',$bannerData->banner_image);
					$isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                }
                if(!empty($isUpload["media_path"]["banner_background_image"]["mediaPath"])) {
                    $bannerDataInsert['banner_background_image'] = $isUpload["media_path"]["banner_background_image"]["mediaPath"];
                    $fileName = str_replace($http_path,'',$bannerData->banner_background_image);
					$isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                }
				$bannerDataInsert['banner_para'] = $data['banner_para'];
                if(!empty($bannerData)) {
				    $isUpdated = DB::table('banner_section')->where('banner_section_id',1)->update($bannerDataInsert);
                } else {
                    $isUpdated = DB::table('banner_section')->insert($bannerDataInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.bannersection'))->with('success','Banner Section successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.bannersection'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.bannersection'))->with('error','Some error occurred.');
				}
            }
        }
        return view('Backend.static.bannersection',compact('bannerData'));
    }

    public function slidercontent(Request $request) {
        Session::put('PageHeading', 'Slider Main Section');
        $sliderContent = DB::table('slider_content')->first();
        if($request->post()){
            $validated = array();
            $validated['slider_content_heading'] = 'required|max:200';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                $mainContentInsert['slider_content_heading'] = $data['slider_content_heading'];
                if(!empty($sliderContent)) {
				    $isUpdated = DB::table('slider_content')->where('slider_content_id',1)->update($mainContentInsert);
                } else {
                    $isUpdated = DB::table('slider_content')->insert($mainContentInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.slidercontent'))->with('success','Slider Section Main Heading successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.slidercontent'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.slidercontent'))->with('error','Some error occurred.');
				}
            }
        }
        return view('Backend.static.slidercontent',compact('sliderContent'));
    }

    public function smartsolutions(Request $request) {
        Session::put('PageHeading', 'Smart Solutions Section');
        $smartContent = DB::table('smart_section')->first();
        if($request->post()){
            $validated = array();
            $validated['smart_section_heading'] = 'required|max:100';
            $validated['smart_section_subheading'] = 'required|max:100';
            $validated['smart_section_para'] = 'required|max:400';
            $validated['smart_section_first_heading'] = 'required|max:60';
            $validated['smart_section_first_para'] = 'required|max:100';
            $validated['smart_section_second_heading'] = 'required|max:60';
            $validated['smart_section_second_para'] = 'required|max:100';
            $validated['smart_section_third_heading'] = 'required|max:60';
            $validated['smart_section_third_para'] = 'required|max:100';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                $smartContentInsert['smart_section_heading'] = $data['smart_section_heading'];
                $smartContentInsert['smart_section_subheading'] = $data['smart_section_subheading'];
                $smartContentInsert['smart_section_para'] = $data['smart_section_para'];
                $smartContentInsert['smart_section_first_heading'] = $data['smart_section_first_heading'];
                $smartContentInsert['smart_section_first_para'] = $data['smart_section_first_para'];
                $smartContentInsert['smart_section_second_heading'] = $data['smart_section_second_heading'];
                $smartContentInsert['smart_section_second_para'] = $data['smart_section_second_para'];
                $smartContentInsert['smart_section_third_heading'] = $data['smart_section_third_heading'];
                $smartContentInsert['smart_section_third_para'] = $data['smart_section_third_para'];
                if(!empty($smartContent)) {
				    $isUpdated = DB::table('smart_section')->where('smart_section_id',1)->update($smartContentInsert);
                } else {
                    $isUpdated = DB::table('smart_section')->insert($smartContentInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.smartsolutions'))->with('success','Smart Solutions Section successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.smartsolutions'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.smartsolutions'))->with('error','Some error occurred.');
				}
            }
        }
        return view('Backend.static.smartsolutions',compact('smartContent'));
    }

    public function accuratesection(Request $request) {
        Session::put('PageHeading', 'Accurate Section');
        $accurateContent = DB::table('accurate_section')->first();
        if($request->post()){
            $validated = array();
            $validated['accurate_section_heading'] = 'required|max:60';
            $validated['accurate_section_sub_heading'] = 'required|max:100';
            $validated['accurate_section_para'] = 'required|max:255';
            $validated['accurate_section_first_heading'] = 'required|max:50';
            $validated['accurate_section_first_sub_heading'] = 'required|max:50';
            $validated['accurate_section_first_para'] = 'required|max:200';
            $validated['accurate_section_second_heading'] = 'required|max:50';
            $validated['accurate_section_second_sub_heading'] = 'required|max:50';
            $validated['accurate_section_second_para'] = 'required|max:200';
            $validated['accurate_section_third_heading'] = 'required|max:50';
            $validated['accurate_section_third_sub_heading'] = 'required|max:50';
            $validated['accurate_section_third_para'] = 'required|max:200';
            $validated['accurate_section_fourth_heading'] = 'required|max:50';
            $validated['accurate_section_fourth_sub_heading'] = 'required|max:50';
            $validated['accurate_section_fourth_para'] = 'required|max:200';
            $validated['accurate_section_background_image']='mimes:jpeg,jpg,png';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('accurate_section_background_image'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);
                }
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["accurate_section_background_image"]["mediaPath"])) {
                    $accurateInsert['accurate_section_background_image'] = $isUpload["media_path"]["accurate_section_background_image"]["mediaPath"];
                    if(!empty($accurateContent->accurate_section_background_image)) {
                        $fileName = str_replace($http_path,'',$accurateContent->accurate_section_background_image);
					    //$isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                }

                $accurateInsert['accurate_section_heading'] = $data['accurate_section_heading'];
                $accurateInsert['accurate_section_sub_heading'] = $data['accurate_section_sub_heading'];
                $accurateInsert['accurate_section_para'] = $data['accurate_section_para'];
                $accurateInsert['accurate_section_first_heading'] = $data['accurate_section_first_heading'];
                $accurateInsert['accurate_section_first_sub_heading'] = $data['accurate_section_first_sub_heading'];
                $accurateInsert['accurate_section_first_para'] = $data['accurate_section_first_para'];
                $accurateInsert['accurate_section_second_heading'] = $data['accurate_section_second_heading'];
                $accurateInsert['accurate_section_second_sub_heading'] = $data['accurate_section_second_sub_heading'];
                $accurateInsert['accurate_section_second_para'] = $data['accurate_section_second_para'];
                $accurateInsert['accurate_section_third_heading'] = $data['accurate_section_third_heading'];
                $accurateInsert['accurate_section_third_sub_heading'] = $data['accurate_section_third_sub_heading'];
                $accurateInsert['accurate_section_third_para'] = $data['accurate_section_third_para'];
                $accurateInsert['accurate_section_fourth_heading'] = $data['accurate_section_fourth_heading'];
                $accurateInsert['accurate_section_fourth_sub_heading'] = $data['accurate_section_fourth_sub_heading'];
                $accurateInsert['accurate_section_fourth_para'] = $data['accurate_section_fourth_para'];
                if(!empty($accurateContent)) {
				    $isUpdated = DB::table('accurate_section')->where('accurate_section_id',1)->update($accurateInsert);
                } else {
                    $isUpdated = DB::table('accurate_section')->insert($accurateInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.accuratesection'))->with('success','Accurate Section successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.accuratesection'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.accuratesection'))->with('error','Some error occurred.');
				}
            }
        }
        return view('Backend.static.accuratesection',compact('accurateContent'));
    }

    public function shopcomplementsection(Request $request) {
        Session::put('PageHeading', 'Shop Complement Section');
        $complementContent = DB::table('shop_complement_section')->first();
        if($request->post()){
            $validated = array();
            $validated['shop_complement_section_heading'] = 'required|max:30';
            $validated['shop_complement_section_sub_heading'] = 'required|max:50';
            $validated['shop_complement_section_description'] = 'required|max:400';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                $complementInsert['shop_complement_section_heading'] = $data['shop_complement_section_heading'];
                $complementInsert['shop_complement_section_sub_heading'] = $data['shop_complement_section_sub_heading'];
                $complementInsert['shop_complement_section_description'] = $data['shop_complement_section_description'];
                if(!empty($complementContent)) {
				    $isUpdated = DB::table('shop_complement_section')->where('shop_smart_id',1)->update($complementInsert);
                } else {
                    $isUpdated = DB::table('shop_complement_section')->insert($complementInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.shopcomplementsection'))->with('success','Shop Complement section successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.shopcomplementsection'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.shopcomplementsection'))->with('error','Some error occurred.');
				}
            }
        }
        return view('Backend.static.shopcomplementsection',compact('complementContent'));
    }

    public function shopsmartsection(Request $request) {
        Session::put('PageHeading', 'Shop Smart Section');
        $smartContent = DB::table('shop_smart')->first();
        if($request->post()){
            $validated = array();
            $rules = [
                'shop_smart_heading' => 'required|max:100',
                'shop_smart_sub_heading' => 'required|max:150',
                'shop_smart_description' => 'required|max:500',
                'shop_smart_video_heading' => 'required|max:50',
                'shop_smart_video_sub_heading' => 'required|max:70',
                'shop_smart_video_tagline' => 'required|max:50',
                'shop_smart_video_image' => 'mimes:jpeg,jpg,png',
                'shop_smart_video_type' => 'required|in:1,2',
                'shop_smart_video_url'  => 'nullable|url|required_if:shop_smart_video_type,1',
                'shop_smart_video'      => 'nullable|mimes:mp4|required_if:shop_smart_video_type,2',
            ];

            $messages = [
                'shop_smart_video_url.required_if' => 'The YouTube URL is required when video type is set to YouTube.',
                'shop_smart_video_url.url'         => 'Please enter a valid YouTube URL.',
                'shop_smart_video.required_if'     => 'The video file is required when video type is set to Upload Video.',
                'shop_smart_video.mimes'           => 'Only MP4 files are allowed for uploaded videos.',
                'shop_smart_video_type.in'         => 'Video type must be either YouTube or Upload Video.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('shop_smart_video_image'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);
                    $isVideoUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('shop_smart_video'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('mp4','MP4'),
						),
						$request
					);
                }
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["shop_smart_video_image"]["mediaPath"])) {
                    $smartInsert['shop_smart_video_image'] = $isUpload["media_path"]["shop_smart_video_image"]["mediaPath"];
                    if(!empty($smartContent->shop_smart_video_image)) {
                        $fileName = str_replace($http_path,'',$smartContent->shop_smart_video_image);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                }
                if(!empty($isVideoUpload["media_path"]["shop_smart_video"]["mediaPath"])) {
                    $smartInsert['shop_smart_video'] = $isVideoUpload["media_path"]["shop_smart_video"]["mediaPath"];
                    $smartInsert['shop_smart_video_url'] = '';
                    $smartInsert['shop_smart_video_type'] = '2';
                    if(!empty($smartContent->shop_smart_video)) {
                        $fileName = str_replace($http_path,'',$smartContent->shop_smart_video);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else if(empty($data['shop_smart_video_url']) && !(!empty($smartContent->shop_smart_video))) {
                     $smartInsert['shop_smart_video'] = $smartContent->shop_smart_video;
                     $smartInsert['shop_smart_video_url'] = '';
                     $smartInsert['shop_smart_video_type'] = '2';
                }
                $smartInsert['shop_smart_heading'] = $data['shop_smart_heading'];
                $smartInsert['shop_smart_sub_heading'] = $data['shop_smart_sub_heading'];
                $smartInsert['shop_smart_description'] = $data['shop_smart_description'];
                $smartInsert['shop_smart_video_heading'] = $data['shop_smart_video_heading'];
                $smartInsert['shop_smart_video_sub_heading'] = $data['shop_smart_video_sub_heading'];
                $smartInsert['shop_smart_video_tagline'] = $data['shop_smart_video_tagline'];
                if(!empty($data['shop_smart_video_type']) && $data["shop_smart_video_type"] == '1') {
                    $smartInsert['shop_smart_video_url'] = $data['shop_smart_video_url'];
                    $smartInsert['shop_smart_video'] = '';
                    $smartInsert['shop_smart_video_type'] = '1';
                }
                if(!empty($smartContent)) {
				    $isUpdated = DB::table('shop_smart')->where('shop_smart_id',1)->update($smartInsert);
                } else {
                    $isUpdated = DB::table('shop_smart')->insert($smartInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.shopsmartsection'))->with('success','Shop Smart section successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.shopsmartsection'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.shopsmartsection'))->with('error','Some error occurred.');
				}
            }
        }
        return view('Backend.static.shopsmartsection',compact('smartContent'));
    }

    public function shopbannersection(Request $request) {
        Session::put('PageHeading', 'Shop Banner Section');
        $bannerContent = DB::table('shop_banner')->first();
        if($request->post()){
            $validated = array();
            $validated['shop_banner_title'] = 'required|max:250';
            $validated['shop_banner_description'] = 'required|max:400';
            $validated['shop_banner_image'] = 'mimes:jpeg,jpg,png';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('shop_banner_image'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);
                }
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["shop_banner_image"]["mediaPath"])) {
                    $bannerInsert['shop_banner_image'] = $isUpload["media_path"]["shop_banner_image"]["mediaPath"];
                    if(!empty($bannerContent->shop_banner_image)) {
                        $fileName = str_replace($http_path,'',$bannerContent->shop_banner_image);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                }

                $bannerInsert['shop_banner_title'] = $data['shop_banner_title'];
                $bannerInsert['shop_banner_description'] = $data['shop_banner_description'];
                if(!empty($bannerContent)) {
				    $isUpdated = DB::table('shop_banner')->where('shop_banner_id',1)->update($bannerInsert);
                } else {
                    $isUpdated = DB::table('shop_banner')->insert($bannerInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.shopbannersection'))->with('success','Shop Banner section successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.shopbannersection'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.shopbannersection'))->with('error','Some error occurred.');
				}
            }
        }
        return view('Backend.static.shopbannersection',compact('bannerContent'));
    }

    public function newerasection(Request $request) {
        Session::put('PageHeading', 'New ERA Section');
        $neweraContent = DB::table('newera_section')->first();
        if($request->post()){
            $validated = array();
            $validated['newera_section_heading'] = 'required|max:40';
            $validated['newera_section_subheading'] = 'required|max:70';
            $validated['newera_section_para'] = 'required|max:255';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                $eraContentInsert['newera_section_heading'] = $data['newera_section_heading'];
                $eraContentInsert['newera_section_subheading'] = $data['newera_section_subheading'];
                $eraContentInsert['newera_section_para'] = $data['newera_section_para'];
                if(!empty($neweraContent)) {
				    $isUpdated = DB::table('newera_section')->where('newera_section_id',1)->update($eraContentInsert);
                } else {
                    $isUpdated = DB::table('newera_section')->insert($eraContentInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.newerasection'))->with('success','New ERA Section successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.newerasection'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.newerasection'))->with('error','Some error occurred.');
				}
            }
        }
        return view('Backend.static.newerasection',compact('neweraContent'));
    }

    public function aboutsection(Request $request) {
        Session::put('PageHeading', 'About Section');
        $aboutContent = DB::table('about_section')->first();
        if($request->post()){
            $validated = array();
            $validated['about_section_main_heading'] = 'required|max:200';
            $validated['about_section_heading'] = 'required|max:50';
            $validated['about_section_sub_heading'] = 'required|max:50';
            $validated['about_section_para'] = 'required|max:200';
            //$validated['about_section_keypoints'] = 'required';
            //$validated['about_section_img1'] = 'required|mimes:jpeg,jpg,png';
            //$validated['about_section_img2'] = 'required|mimes:jpeg,jpg,png';
            //$validated['about_section_img3'] = 'mimes:jpeg,jpg,png';
            //$validated['about_section_img4'] = 'mimes:jpeg,jpg,png';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                /*if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('about_section_img1','about_section_img2','about_section_img3','about_section_img4'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);
                }
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["about_section_img1"]["mediaPath"])) {
                    $bannerDataInsert['about_section_img1'] = $isUpload["media_path"]["about_section_img1"]["mediaPath"];
                    if(!empty($aboutContent->about_section_img1)) {
                        $fileName = str_replace($http_path,'',$aboutContent->about_section_img1);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                }
                if(!empty($isUpload["media_path"]["about_section_img2"]["mediaPath"])) {
                    $bannerDataInsert['about_section_img2'] = $isUpload["media_path"]["about_section_img2"]["mediaPath"];
                    if(!empty($aboutContent->about_section_img2)) {
                        $fileName = str_replace($http_path,'',$aboutContent->about_section_img2);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                }
                if(!empty($isUpload["media_path"]["about_section_img3"]["mediaPath"])) {
                    $bannerDataInsert['about_section_img3'] = $isUpload["media_path"]["about_section_img3"]["mediaPath"];
                    if(!empty($aboutContent->about_section_img3)) {
                        $fileName = str_replace($http_path,'',$aboutContent->about_section_img3);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                }
                if(!empty($isUpload["media_path"]["about_section_img4"]["mediaPath"])) {
                    $bannerDataInsert['about_section_img4'] = $isUpload["media_path"]["about_section_img4"]["mediaPath"];
                    if(!empty($aboutContent->about_section_img4)) {
                        $fileName = str_replace($http_path,'',$aboutContent->about_section_img1);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                }*/

                $aboutContentInsert['about_section_main_heading'] = $data['about_section_main_heading'];
                $aboutContentInsert['about_section_heading'] = $data['about_section_heading'];
                $aboutContentInsert['about_section_sub_heading'] = $data['about_section_sub_heading'];
                $aboutContentInsert['about_section_para'] = $data['about_section_para'];
                //$aboutContentInsert['about_section_keypoints'] = $data['about_section_keypoints'];
                if(!empty($aboutContent)) {
				    $isUpdated = DB::table('about_section')->where('about_section_id',1)->update($aboutContentInsert);
                } else {
                    $isUpdated = DB::table('about_section')->insert($aboutContentInsert);
                }
                if($isUpdated){
                    return redirect(route('backend.aboutsection'))->with('success','About Section successfully updated.');
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.aboutsection'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.aboutsection'))->with('error','Some error occurred.');
				}
            }
        }
        return view('Backend.static.aboutsection',compact('aboutContent'));
    }

    public function aboutcontent(Request $request) {
        Session::put('PageHeading', 'Manage About Section Content');
		return view('Backend.static.aboutcontent');
    }

    public function getaboutcontent(Request $request){
		if($request->ajax())
		{
			$aColumns = [
				'about_section_content_id',
				'about_section_content_title',
				'about_section_content_img1'
			];

			$sTable = 'about_section_content';
			$sIndexColumn = 'about_section_content_id';

			/** { Query Bulider Start */

				$Query = DB::table($sTable)->select($aColumns);
				$iFilteredTotal = DB::table($sTable)->select(DB::Raw(`FOUND_ROWS`))->count();
				$iTotal = DB::table($sTable)->select(DB::Raw(`COUNT(`.$sIndexColumn.`)`));

				/*
				* Paging
				*/
				$sLimit = "";
				if (!empty($request->get('iDisplayLength')) &&  !empty($request->get('iDisplayStart')) != '-1' ){
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
					$Query->orderBy('about_section_content_id', 'desc');
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

				$row[] = '<code>'.$j.'</code>';

                $row[] = '<div class="text-center"><input class="form-check-input elem_ids checkboxes" type="checkbox" name="'.$sTable.'['.$row1->$sIndexColumn.']" value="'.$row1->$sIndexColumn.'"></div>';

				$row[] = $row1->about_section_content_title;

                if(!empty($row1->about_section_content_img1)) {
                    $src = asset('assets/storage/homeimages/'.$row1->about_section_content_img1);
                    $row[] = '<img class="img-thumbnail mb-3" alt="Image" style="max-width:200px;background:#d0d0d0" src="'.$src.'" data-holder-rendered="true">';
                } else {
                    $row[] = '';
                }


				$editUrl = route('backend.manageaboutcontent', ['pid'=>encrypt($row1->about_section_content_id)]);
				$row[] = '
					<a href="'.$editUrl.'"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-edit"></i></button></a>
					&nbsp;
				';

				$output['aaData'][] = $row;
				$j++;
			}

			echo json_encode( $output );
			exit();
		}else{
			return redirect()->back()->with('error','Unauthorized access.');
		}
	}

    public function manageaboutcontent(Request $request, $pid = ''){

		Session::put('PageHeading', 'Manage About Content');
        $aboutContentData = array();
        if(!empty($pid)) {
		    $pid = decrypt($pid);
		    $aboutContentData = DB::table('about_section_content')->where('about_section_content_id',$pid)->first();
            if(empty($aboutContentData)){
			    return redirect(route('backend.aboutcontent'))->with('error','Invalid Page Request!!');
		    }
        }

		if($request->post()){
            $validated = array();
            $validated['about_section_content_title'] = 'required|max:100';
            $validated['about_section_content_img1']='mimes:jpeg,jpg,png';
            $validated['about_section_content_img2']='mimes:jpeg,jpg,png';
            $validated['about_section_content_img3']='mimes:jpeg,jpg,png';
            $validated['about_section_content_img4']='mimes:jpeg,jpg,png';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('about_section_content_img1','about_section_content_img2','about_section_content_img3','about_section_content_img4'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);
                }
                $aboutDataInsert['about_section_content_title'] = $data['about_section_content_title'];
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["about_section_content_img1"]["mediaPath"])) {
                    $aboutDataInsert['about_section_content_img1'] = $isUpload["media_path"]["about_section_content_img1"]["mediaPath"];
                    if(!empty($aboutContentData->about_section_content_img1)) {
                        $fileName = str_replace($http_path,'',$aboutContentData->about_section_content_img1);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($aboutContentData->about_section_content_img1)) {
                        $aboutDataInsert['about_section_content_img1'] = $aboutContentData->about_section_content_img1;
                    }
                }
                if(!empty($isUpload["media_path"]["about_section_content_img2"]["mediaPath"])) {
                    $aboutDataInsert['about_section_content_img2'] = $isUpload["media_path"]["about_section_content_img2"]["mediaPath"];
                    if(!empty($aboutContentData->about_section_content_img2)) {
                        $fileName = str_replace($http_path,'',$aboutContentData->about_section_content_img2);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($aboutContentData->about_section_content_img2)) {
                        $aboutDataInsert['about_section_content_img2'] = $aboutContentData->about_section_content_img2;
                    }
                }
                if(!empty($isUpload["media_path"]["about_section_content_img3"]["mediaPath"])) {
                    $aboutDataInsert['about_section_content_img3'] = $isUpload["media_path"]["about_section_content_img3"]["mediaPath"];
                    if(!empty($aboutContentData->about_section_content_img3)) {
                        $fileName = str_replace($http_path,'',$aboutContentData->about_section_content_img3);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($aboutContentData->about_section_content_img3)) {
                        $aboutDataInsert['about_section_content_img3'] = $aboutContentData->about_section_content_img3;
                    }
                }
                if(!empty($isUpload["media_path"]["about_section_content_img4"]["mediaPath"])) {
                    $aboutDataInsert['about_section_content_img4'] = $isUpload["media_path"]["about_section_content_img4"]["mediaPath"];
                    if(!empty($aboutContentData->about_section_content_img4)) {
                        $fileName = str_replace($http_path,'',$aboutContentData->about_section_content_img4);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($aboutContentData->about_section_content_img4)) {
                        $aboutDataInsert['about_section_content_img4'] = $aboutContentData->about_section_content_img4;
                    }
                }

                if(!empty($aboutContentData)) {
				    $isUpdated = DB::table('about_section_content')->where('about_section_content_id',$pid)->update($aboutDataInsert);
                } else {
                    $isUpdated = DB::table('about_section_content')->insert($aboutDataInsert);
                }
                if($isUpdated){
                    if(!empty($aboutContentData)) {
                        return redirect(route('backend.aboutcontent'))->with('success','About Content successfully updated.');
                    } else {
                        return redirect(route('backend.aboutcontent'))->with('success','About Content successfully added.');
                    }
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.aboutcontent'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.aboutcontent'))->with('error','Some error occurred.');
				}
            }
		}

		return view('Backend.static.manageaboutcontent',compact('aboutContentData'));
	}

    public function removeaboutcontent(Request $request){
		if($request->post()){

			$records = $request->all();
			$table = 'about_section_content';

			foreach($records[$table] as $k=>$record){

				DB::table($table)->where('about_section_content_id',$record)->delete();
			}

			return redirect(route('backend.aboutcontent'))->with('success','Records deleted successfully.');
		}
		else{
			return redirect(route('backend.aboutcontent'))->with('error','Invalid Request!!');
		}
	}

    public function neweracontent(Request $request) {
        Session::put('PageHeading', 'Manage New ERA Content');
		return view('Backend.static.neweracontent');
    }

    public function getneweracontent(Request $request){
		if($request->ajax())
		{
			$aColumns = [
				'newera_section_content_id',
				'newera_section_content_title',
				'newera_section_content_tagline',
                'newera_section_content_background_image',
                'newera_section_content_image'
			];

			$sTable = 'newera_section_content';
			$sIndexColumn = 'newera_section_content_id';

			/** { Query Bulider Start */

				$Query = DB::table($sTable)->select($aColumns);
				$iFilteredTotal = DB::table($sTable)->select(DB::Raw(`FOUND_ROWS`))->count();
				$iTotal = DB::table($sTable)->select(DB::Raw(`COUNT(`.$sIndexColumn.`)`));

				/*
				* Paging
				*/
				$sLimit = "";
				if (!empty($request->get('iDisplayLength')) &&  !empty($request->get('iDisplayStart')) != '-1' ){
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
					$Query->orderBy('newera_section_content_id', 'desc');
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

				$row[] = '<code>'.$j.'</code>';

                $row[] = '<div class="text-center"><input class="form-check-input elem_ids checkboxes" type="checkbox" name="'.$sTable.'['.$row1->$sIndexColumn.']" value="'.$row1->$sIndexColumn.'"></div>';

				$row[] = $row1->newera_section_content_title;

                $row[] = $row1->newera_section_content_tagline;

				$editUrl = route('backend.manageneweracontent', ['pid'=>encrypt($row1->newera_section_content_id)]);
				$row[] = '
					<a href="'.$editUrl.'"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-edit"></i></button></a>
					&nbsp;
				';

				$output['aaData'][] = $row;
				$j++;
			}

			echo json_encode( $output );
			exit();
		}else{
			return redirect()->back()->with('error','Unauthorized access.');
		}
	}

    public function manageneweracontent(Request $request, $pid = ''){

		Session::put('PageHeading', 'Manage New ERA Content');
        $neweraCount = DB::table('newera_section_content')->count();
        $neweraContentData = array();
        if(!empty($pid)) {
		    $pid = decrypt($pid);
		    $neweraContentData = DB::table('newera_section_content')->where('newera_section_content_id',$pid)->first();
            if(empty($neweraContentData)){
			    return redirect(route('backend.neweracontent'))->with('error','Invalid Page Request!!');
		    }
        } else {
            if($neweraCount >= 3) {
                return redirect(route('backend.neweracontent'))->with('error','You can add up to 3 items in this section.');
            }
        }

		if($request->post()){
            $validated = array();
            $validated['newera_section_content_title'] = 'required|max:200';
            $validated['newera_section_content_tagline'] = 'required|max:60';
            $validated['newera_section_content_background_image']='mimes:jpeg,jpg,png';
            $validated['newera_section_content_image']='mimes:jpeg,jpg,png';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('newera_section_content_background_image','newera_section_content_image'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);
                }
                $eraDataInsert['newera_section_content_title'] = $data['newera_section_content_title'];
                $eraDataInsert['newera_section_content_tagline'] = $data['newera_section_content_tagline'];
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["newera_section_content_background_image"]["mediaPath"])) {
                    $eraDataInsert['newera_section_content_background_image'] = $isUpload["media_path"]["newera_section_content_background_image"]["mediaPath"];
                    if(!empty($neweraContentData->newera_section_content_background_image)) {
                        $fileName = str_replace($http_path,'',$neweraContentData->newera_section_content_background_image);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($neweraContentData->newera_section_content_background_image)) {
                        $eraDataInsert['newera_section_content_background_image'] = $neweraContentData->newera_section_content_background_image;
                    }
                }
                if(!empty($isUpload["media_path"]["newera_section_content_image"]["mediaPath"])) {
                    $eraDataInsert['newera_section_content_image'] = $isUpload["media_path"]["newera_section_content_image"]["mediaPath"];
                    if(!empty($neweraContentData->newera_section_content_image)) {
                        $fileName = str_replace($http_path,'',$neweraContentData->newera_section_content_image);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($neweraContentData->newera_section_content_image)) {
                        $eraDataInsert['newera_section_content_image'] = $neweraContentData->newera_section_content_image;
                    }
                }


                if(!empty($neweraContentData)) {
				    $isUpdated = DB::table('newera_section_content')->where('newera_section_content_id',$pid)->update($eraDataInsert);
                } else {
                    $isUpdated = DB::table('newera_section_content')->insert($eraDataInsert);
                }
                if($isUpdated){
                    if(!empty($neweraContentData)) {
                        return redirect(route('backend.neweracontent'))->with('success','About Content successfully updated.');
                    } else {
                        return redirect(route('backend.neweracontent'))->with('success','About Content successfully added.');
                    }
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.neweracontent'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.neweracontent'))->with('error','Some error occurred.');
				}
            }
		}

		return view('Backend.static.manageneweracontent',compact('neweraContentData'));
	}

    public function removeneweracontent(Request $request){
		if($request->post()){

			$records = $request->all();
			$table = 'newera_section_content';

			foreach($records[$table] as $k=>$record){

				DB::table($table)->where('newera_section_content_id',$record)->delete();
			}

			return redirect(route('backend.neweracontent'))->with('success','Records deleted successfully.');
		}
		else{
			return redirect(route('backend.neweracontent'))->with('error','Invalid Request!!');
		}
	}

    public function shoptechsection(Request $request) {
        Session::put('PageHeading', 'Manage Shop Tech Section');
		return view('Backend.static.shoptechsection');
    }

    public function getshoptechsection(Request $request){
		if($request->ajax())
		{
			$aColumns = [
				'shop_tech_section_id',
				'shop_tech_section_title',
				'shop_tech_section_description',
                'shop_tech_section_image'
			];

			$sTable = 'shop_tech_section';
			$sIndexColumn = 'shop_tech_section_id';

			/** { Query Bulider Start */

				$Query = DB::table($sTable)->select($aColumns);
				$iFilteredTotal = DB::table($sTable)->select(DB::Raw(`FOUND_ROWS`))->count();
				$iTotal = DB::table($sTable)->select(DB::Raw(`COUNT(`.$sIndexColumn.`)`));

				/*
				* Paging
				*/
				$sLimit = "";
				if (!empty($request->get('iDisplayLength')) &&  !empty($request->get('iDisplayStart')) != '-1' ){
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
					$Query->orderBy('shop_tech_section_id', 'desc');
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

				$row[] = '<code>'.$j.'</code>';

                $row[] = '<div class="text-center"><input class="form-check-input elem_ids checkboxes" type="checkbox" name="'.$sTable.'['.$row1->$sIndexColumn.']" value="'.$row1->$sIndexColumn.'"></div>';

				$row[] = $row1->shop_tech_section_title;

                $row[] = $row1->shop_tech_section_description;

                if(!empty($row1->shop_tech_section_image)) {
                    $src = asset('assets/storage/homeimages/'.$row1->shop_tech_section_image);
                    $row[] = '<img class="img-thumbnail mb-3" alt="Image" style="max-width:200px;background:#d0d0d0" src="'.$src.'" data-holder-rendered="true">';
                } else {
                    $row[] = '';
                }

				$editUrl = route('backend.manageshoptechsection', ['pid'=>encrypt($row1->shop_tech_section_id)]);
				$row[] = '
					<a href="'.$editUrl.'"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-edit"></i></button></a>
					&nbsp;
				';

				$output['aaData'][] = $row;
				$j++;
			}

			echo json_encode( $output );
			exit();
		}else{
			return redirect()->back()->with('error','Unauthorized access.');
		}
	}

    public function manageshoptechsection(Request $request, $pid = ''){

		Session::put('PageHeading', 'Manage Shop Tech Section');
        $shoptechData = array();
        if(!empty($pid)) {
		    $pid = decrypt($pid);
		    $shoptechData = DB::table('shop_tech_section')->where('shop_tech_section_id',$pid)->first();
            if(empty($shoptechData)){
			    return redirect(route('backend.shoptechsection'))->with('error','Invalid Page Request!!');
		    }
        }

		if($request->post()){
            $validated = array();
            $validated['shop_tech_section_title'] = 'required|max:30';
            $validated['shop_tech_section_description'] = 'required|max:150';
            $validated['shop_tech_section_image'] = 'mimes:jpeg,jpg,png';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('shop_tech_section_image'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);
                }
                $shoptechDataInsert['shop_tech_section_title'] = $data['shop_tech_section_title'];
                $shoptechDataInsert['shop_tech_section_description'] = $data['shop_tech_section_description'];
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["shop_tech_section_image"]["mediaPath"])) {
                    $shoptechDataInsert['shop_tech_section_image'] = $isUpload["media_path"]["shop_tech_section_image"]["mediaPath"];
                    if(!empty($shoptechData->shop_tech_section_image)) {
                        $fileName = str_replace($http_path,'',$shoptechData->shop_tech_section_image);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($shoptechData->shop_tech_section_image)) {
                        $shoptechDataInsert['shop_tech_section_image'] = $shoptechData->shop_tech_section_image;
                    }
                }

                if(!empty($shoptechData)) {
				    $isUpdated = DB::table('shop_tech_section')->where('shop_tech_section_id',$pid)->update($shoptechDataInsert);
                } else {
                    $isUpdated = DB::table('shop_tech_section')->insert($shoptechDataInsert);
                }
                if($isUpdated){
                    if(!empty($shoptechData)) {
                        return redirect(route('backend.shoptechsection'))->with('success','Shop Tech section successfully updated.');
                    } else {
                        return redirect(route('backend.shoptechsection'))->with('success','Shop Tech section successfully added.');
                    }
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.shoptechsection'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.shoptechsection'))->with('error','Some error occurred.');
				}
            }
		}

		return view('Backend.static.manageshoptechsection',compact('shoptechData'));
	}

    public function removeshoptechsection(Request $request){
		if($request->post()){

			$records = $request->all();
			$table = 'shop_tech_section';

			foreach($records[$table] as $k=>$record){

				DB::table($table)->where('shop_tech_section_id',$record)->delete();
			}

			return redirect(route('backend.shoptechsection'))->with('success','Records deleted successfully.');
		}
		else{
			return redirect(route('backend.shoptechsection'))->with('error','Invalid Request!!');
		}
	}

    public function shopcomplementcontent(Request $request) {
        Session::put('PageHeading', 'Manage Shop Complement Content');
		return view('Backend.static.shopcomplementcontent');
    }

    public function getshopcomplementcontent(Request $request){
		if($request->ajax())
		{
			$aColumns = [
				'shop_complement_content_id',
				'shop_complement_content_title',
				'shop_complement_content_first_image',
                'shop_complement_content_second_image',
                'shop_complement_content_third_image',
                'shop_complement_content_fourth_image'
			];

			$sTable = 'shop_complement_content';
			$sIndexColumn = 'shop_complement_content_id';

			/** { Query Bulider Start */

				$Query = DB::table($sTable)->select($aColumns);
				$iFilteredTotal = DB::table($sTable)->select(DB::Raw(`FOUND_ROWS`))->count();
				$iTotal = DB::table($sTable)->select(DB::Raw(`COUNT(`.$sIndexColumn.`)`));

				/*
				* Paging
				*/
				$sLimit = "";
				if (!empty($request->get('iDisplayLength')) &&  !empty($request->get('iDisplayStart')) != '-1' ){
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
					$Query->orderBy('shop_complement_content_id', 'desc');
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

				$row[] = '<code>'.$j.'</code>';

                $row[] = '<div class="text-center"><input class="form-check-input elem_ids checkboxes" type="checkbox" name="'.$sTable.'['.$row1->$sIndexColumn.']" value="'.$row1->$sIndexColumn.'"></div>';

				$row[] = $row1->shop_complement_content_title;

                if(!empty($row1->shop_complement_content_first_image)) {
                    $src = asset('assets/storage/homeimages/'.$row1->shop_complement_content_first_image);
                    $row[] = '<img class="img-thumbnail mb-3" alt="Image" style="max-width:200px;background:#d0d0d0" src="'.$src.'" data-holder-rendered="true">';
                } else {
                    $row[] = '';
                }

                if(!empty($row1->shop_complement_content_second_image)) {
                    $src = asset('assets/storage/homeimages/'.$row1->shop_complement_content_second_image);
                    $row[] = '<img class="img-thumbnail mb-3" alt="Image" style="max-width:200px;background:#d0d0d0" src="'.$src.'" data-holder-rendered="true">';
                } else {
                    $row[] = '';
                }

				$editUrl = route('backend.manageshopcomplementcontent', ['pid'=>encrypt($row1->shop_complement_content_id)]);
				$row[] = '
					<a href="'.$editUrl.'"><button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-edit"></i></button></a>
					&nbsp;
				';

				$output['aaData'][] = $row;
				$j++;
			}

			echo json_encode( $output );
			exit();
		}else{
			return redirect()->back()->with('error','Unauthorized access.');
		}
	}

    public function manageshopcomplementcontent(Request $request, $pid = ''){

		Session::put('PageHeading', 'Manage Shop Complement Content');
        $shopcomplementContentData = array();
        if(!empty($pid)) {
		    $pid = decrypt($pid);
		    $shopcomplementContentData = DB::table('shop_complement_content')->where('shop_complement_content_id',$pid)->first();
            if(empty($shopcomplementContentData)){
			    return redirect(route('backend.shopcomplementcontent'))->with('error','Invalid Page Request!!');
		    }
        }

		if($request->post()){
            $validated = array();
            $validated['shop_complement_content_title'] = 'required|max:60';
            $validated['shop_complement_content_first_image'] = 'mimes:jpeg,jpg,png';
            $validated['shop_complement_content_second_image']='mimes:jpeg,jpg,png';
            $validated['shop_complement_content_third_image']='nullable|mimes:jpeg,jpg,png';
            $validated['shop_complement_content_fourth_image']='nullable|mimes:jpeg,jpg,png';
            $validator = Validator::make($request->all(), $validated);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $data = $request->all();
                unset($data['_token']);

                if(!empty($request->file())){
					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/homeimages/'),
							'files'=>array('shop_complement_content_first_image','shop_complement_content_second_image','shop_complement_content_third_image','shop_complement_content_fourth_image'),
							'multiple'=>false,
							'thumb'=>array(), //array('w'=>60,'h'=>60)
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);
                }
                $shopcomplementDataInsert['shop_complement_content_title'] = $data['shop_complement_content_title'];
                $http_path = asset('assets/storage/homeimages/');
                if(!empty($isUpload["media_path"]["shop_complement_content_first_image"]["mediaPath"])) {
                    $shopcomplementDataInsert['shop_complement_content_first_image'] = $isUpload["media_path"]["shop_complement_content_first_image"]["mediaPath"];
                    if(!empty($shopcomplementContentData->shop_complement_content_first_image)) {
                        $fileName = str_replace($http_path,'',$shopcomplementContentData->shop_complement_content_first_image);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($shopcomplementContentData->shop_complement_content_first_image)) {
                        $shopcomplementDataInsert['shop_complement_content_first_image'] = $shopcomplementContentData->shop_complement_content_first_image;
                    }
                }
                if(!empty($isUpload["media_path"]["shop_complement_content_second_image"]["mediaPath"])) {
                    $shopcomplementDataInsert['shop_complement_content_second_image'] = $isUpload["media_path"]["shop_complement_content_second_image"]["mediaPath"];
                    if(!empty($shopcomplementContentData->shop_complement_content_second_image)) {
                        $fileName = str_replace($http_path,'',$shopcomplementContentData->shop_complement_content_second_image);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($shopcomplementContentData->shop_complement_content_second_image)) {
                        $shopcomplementDataInsert['shop_complement_content_second_image'] = $shopcomplementContentData->shop_complement_content_second_image;
                    }
                }
                if(!empty($isUpload["media_path"]["shop_complement_content_third_image"]["mediaPath"])) {
                    $shopcomplementDataInsert['shop_complement_content_third_image'] = $isUpload["media_path"]["shop_complement_content_third_image"]["mediaPath"];
                    if(!empty($shopcomplementContentData->shop_complement_content_third_image)) {
                        $fileName = str_replace($http_path,'',$shopcomplementContentData->shop_complement_content_third_image);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($shopcomplementContentData->shop_complement_content_third_image)) {
                        $shopcomplementDataInsert['shop_complement_content_third_image'] = $shopcomplementContentData->shop_complement_content_third_image;
                    }
                }
                if(!empty($isUpload["media_path"]["shop_complement_content_fourth_image"]["mediaPath"])) {
                    $shopcomplementDataInsert['shop_complement_content_fourth_image'] = $isUpload["media_path"]["shop_complement_content_fourth_image"]["mediaPath"];
                    if(!empty($shopcomplementContentData->shop_complement_content_fourth_image)) {
                        $fileName = str_replace($http_path,'',$shopcomplementContentData->shop_complement_content_fourth_image);
					    $isDelete = Uploader::universalUnlink($fileName,storage_path('app/public/homeimages/'));
                    }
                } else {
                    if(!empty($shopcomplementContentData->shop_complement_content_fourth_image)) {
                        $shopcomplementDataInsert['shop_complement_content_fourth_image'] = $shopcomplementContentData->shop_complement_content_fourth_image;
                    }
                }

                if(!empty($shopcomplementContentData)) {
				    $isUpdated = DB::table('shop_complement_content')->where('shop_complement_content_id',$pid)->update($shopcomplementDataInsert);
                } else {
                    $isUpdated = DB::table('shop_complement_content')->insert($shopcomplementDataInsert);
                }
                if($isUpdated){
                    if(!empty($shopcomplementContentData)) {
                        return redirect(route('backend.shopcomplementcontent'))->with('success','Shop Complement Content successfully updated.');
                    } else {
                        return redirect(route('backend.shopcomplementcontent'))->with('success','Shop Complement Content successfully added.');
                    }
                }
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.shopcomplementcontent'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.shopcomplementcontent'))->with('error','Some error occurred.');
				}
            }
		}

		return view('Backend.static.manageshopcomplementcontent',compact('shopcomplementContentData'));
	}

    public function removeshopcomplementcontent(Request $request){
		if($request->post()){

			$records = $request->all();
			$table = 'shop_complement_content';

			foreach($records[$table] as $k=>$record){

				DB::table($table)->where('shop_complement_content_id',$record)->delete();
			}

			return redirect(route('backend.shopcomplementcontent'))->with('success','Records deleted successfully.');
		}
		else{
			return redirect(route('backend.shopcomplementcontent'))->with('error','Invalid Request!!');
		}
	}

	public function managepages(Request $request, $pid){

		Session::put('PageHeading', 'Manage Pages');

		if(empty($pid)){
			return redirect(route('backend.pages'))->with('error','Invalid Request!!');
		}

		$pid = decrypt($pid);
		$pageData = DB::table('pages')->where('page_id',$pid)->first();
		$pageKey = $pageData->page_key;
		$pageDataContents = DB::table('pages_contents')->where('page_key',$pageKey)->orderBy('ordering')->get()->all();

		if(empty($pageData)){
			return redirect(route('backend.pages'))->with('error','Invalid Page Request!!');
		}

		if($request->post()){


			$validated = array();
			$validated['meta_title_'.app()->getLocale()] = 'required|max:100';
			$validated['meta_keywords_'.app()->getLocale()] = 'required|max:250';
			$validated['meta_desc_'.app()->getLocale()] = 'required|max:2000';
			$validated['title_'.app()->getLocale()] = 'required|max:100';

			foreach($pageDataContents as $k=>$value){
				if($value->page_content_type=='file'){
					$validated[$value->page_content_key]='mimes:jpeg,jpg,png,gif,svg,ico|max:10000';
					$customMessages[$value->page_content_key.'.max'] = 'The :attribute may not be greater than 10 mb.';
				}
				else {
					$validated[$value->page_content_key] = 'required|max:'.$value->max_limit;
				}
			}

			$validator = Validator::make($request->all(), $validated);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {
				$data = $request->all();
				unset($data['_token']);

				$pageDataInsert['meta_title_en'] = $data['meta_title_en'];
				$pageDataInsert['meta_keywords_en'] = $data['meta_keywords_en'];
				$pageDataInsert['meta_desc_en'] = $data['meta_desc_en'];
				$pageDataInsert['title_en'] = $data['title_en'];
				if($pageData->page_type=='static'){
					$pageDataInsert['page_content'] = $data['page_content'];
				}

				$data['updated_at'] = date('Y-m-d H:i:s');
				$isUpdated = DB::table('pages')->where('page_id',$pid)->update($pageDataInsert);

				if($isUpdated){
					foreach($pageDataContents as $k=>$value){
						$pageContentData['page_content_value'] = $data[$value->page_content_key];
						$pageContentData['updated_at'] = date('Y-m-d H:i:s');
						$isUpdated = DB::table('pages_contents')->where('page_content_key',$value->page_content_key)->update($pageContentData);
					}
					return redirect(route('backend.pages'))->with('success','Page successfully updated.');
				}
				else{
					if($isUpdated=='0'){
						return redirect(route('backend.pages'))->with('warning','Your submitted information same as previous information.');
					}
					return redirect(route('backend.pages'))->with('error','Some error occurred.');
				}
			}
		}

		return view('Backend.static.managepages',compact('pageData','pageDataContents'));
	}

	public function viewpages(Request $request, $pid){

		Session::put('PageHeading', 'View Page');

		if(empty($pid)){
			return redirect(route('backend.pages'))->with('error','Invalid Request!!');
		}

		$pid = decrypt($pid);
		$pageData = DB::table('pages')->where('page_id',$pid)->first();

		if(empty($pageData)){
			return redirect(route('backend.pages'))->with('error','Invalid Page Request!!');
		}

		return view('Backend.static.viewpages',compact('pageData'));
	}
	/** End */

	/** Ck Editor upload media */
	public function uploadmedia(Request $request){

		$validated['upload'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024';

		$validator = Validator::make($request->all(), $validated);

		$keyName='upload';
		if ($request->hasFile($keyName) && $request->file($keyName)->isValid()) {

			$isUpload = Uploader::universalUpload(
				array(
					'directory'=>storage_path('app/public/mediafiles/'),
					'files'=>array($keyName),
					'multiple'=>false,
					'thumb'=>array(),//array('w'=>60,'h'=>60)
					'allowExtension' => array('jpg','png','gif','bmp','webp','svg','ico','JPG','PNG','GIF','BMP','WebP','SVG'),
				),
				$request
			);

			if(isset($isUpload['success']) && $isUpload['success']){
				foreach($isUpload['media_path'] as $k=>$value){
					$http_path = asset('assets/storage/mediafiles/');
					if(!empty($value['mediaPath'])){
						$url = $http_path.$value['mediaPath'];
					}else{
						echo json_encode(array("uploaded"=>false,"error"=>array("message"=>$value['error'])));exit();
					}
				}
			}else{
				echo json_encode(array("uploaded"=>false,"error"=>array("message"=>$isUpload['message'])));exit();
			}

			echo json_encode(array("uploaded"=>true,"url"=>$url));exit();
		}
		else{
			$message = 'Please upload valid image.';
			echo json_encode(array("uploaded"=>false,"error"=>array("message"=>$message)));exit();
		}
 		exit();
	}

}
