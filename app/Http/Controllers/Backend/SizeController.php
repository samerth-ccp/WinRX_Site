<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Size;
use DB;


class SizeController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	*/
	public function __construct(){

		$this->size = new Size;

    }

	/** Size  */
	public function index(Request $request){
		Session::put('PageHeading', 'Size Chart');

		return view('Backend.sizes.index');
	}

	public function get(Request $request){

		if($request->ajax())
		{
			$aColumns = [
				'size_id',
				'size_id',
				'size'
			];

			$sTable = 'product_size';
			$sIndexColumn = 'size_id';

			/** { Query Bulider Start */

				$Query = $this->size->select($aColumns);


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
					$Query->orderBy('size_id', 'desc');
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

				$iTotal = $Query->count();

				/*
				* Paging
				*/
				$sLimit = "";
				if (!empty($request->get('iDisplayLength')) && 	($request->get('iDisplayStart')) != '-1' )
				{
					$Query->offset($request->get('iDisplayStart'))->limit($request->get('iDisplayLength'));
				}

				$qry = $Query->get();

			/* } Query Bulider End */

			$output = array(
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iTotal,
				"aaData" => array()
			);

			$j=1;

			foreach($qry as $row1)
			{
				$row=array();

				$row[] = '<code>'.$j.'</code>';

				$row[] = '<div class="text-center"><input class="form-check-input elem_ids checkboxes" type="checkbox" name="'.$sTable.'['.$row1->$sIndexColumn.']" value="'.$row1->$sIndexColumn.'"></div>';

				$row[] = $row1->size;

				$row[] = '
					<a href="'.route('manage.sizes', ['id'=>encrypt($row1->size_id)]).'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
						<button type="button" class="btn btn-soft-primary waves-effect waves-light"><i class="fas fa-edit"></i></button>
					</a>
				';

				$output['aaData'][] = $row;
				$j++;
			}

			echo json_encode( $output );
			exit();
		}else{
			return redirect(route('backend.users'))->with('error','Unauthorized access.');
		}
	}

	public function manage(Request $request){

		Session::put('PageHeading', 'Manage Size');

		$Id = $request->route('id');

		$size = $this->size;
		if(!empty($Id)){
			$Id = decrypt($Id);

			$size = $size->find($Id);

			if(empty($size)){
				return redirect()->back()->with('error','Record not found.');
			}
		}

		if($request->isMethod('POST')){

			$data = $request->all();

			$validated = array();
			$validated['size'] 	= ['required','integer', Rule::unique((new Size)->getTable(), 'size')->ignore($Id, 'size_id')];

			$validator = Validator::make($request->all(), $validated);

			if ($validator->fails()) {

				return redirect()->back()->withErrors($validator)->withInput();

			} else {

				$this->size->updateOrCreate(['size_id' => $Id],['size' => $data['size']]);

				return redirect(route('sizes'))->with('success','Size '.($Id?'updated':'added').' successfully.');
			}
		}

		return view('Backend.sizes.manage',compact('size'));
	}

	public function delete(Request $request){
		if($request->post()){

			$records = $request->all();

			$records = $this->size->find($records['product_size']);

			foreach($records as $k=>$record){

				$record->delete();
			}

			return redirect(route('sizes'))->with('success','Records deleted successfully.');
		}
		else{
			return redirect(route('sizes'))->with('error','Invalid Request!!');
		}
	}
	/** End  */

}
