<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\Color;
use App\Models\Size;
use App\Models\Product;

use DB;
use Uploader;

class ProductController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	*/
	public function __construct(){

		$this->color = new Color;
		$this->size = new Size;
		$this->product = new Product;

    }

	/** Size  */
	public function index(Request $request){
		Session::put('PageHeading', 'Products');

		return view('Backend.products.index');
	}

	public function get(Request $request){

		if($request->ajax())
		{
			$aColumns = [
				'product_id',
				'product_id',
				'product_name',
				'product_image',
				'product_status',
				'product_updated_at'
			];

			$sTable = 'products';
			$sIndexColumn = 'product_id';

			/** { Query Bulider Start */

				$Query = $this->product->select($aColumns);

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
					$Query->orderBy('product_id', 'desc');
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

				$row[] = $row1->product_name;

				$row[] = '<div style="width:200px;"><img src="'.asset('assets/storage/products/'.$row1->product_image).'" class="w-100" /></div>';

				$checked = ($row1->product_status=='1')?'checked':'';
				$row[] = '
					<input type="checkbox" class="status-'.(int)$row1->product_status.'"  id="'.$sTable."-".$row1->$sIndexColumn.'"  onChange="globalStatus(this);" switch="none" '.$checked.'>
					<label for="'.$sTable."-".$row1->$sIndexColumn.'" data-on-label="On" data-off-label="Off"></label>
				';

				$row[] = date('M d, Y h:i A',strtotime($row1->product_updated_at));

				$row[] = '
					<a href="'.route('manage.products', ['id'=>encrypt($row1->$sIndexColumn)]).'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
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

		Session::put('PageHeading', 'Manage Product');

		$Id = $request->route('id');

		$product = $this->product;
		if(!empty($Id)){
			$Id = decrypt($Id);

			$product = $product->find($Id);

			if(empty($product)){
				return redirect()->back()->with('error','Record not found.');
			}
		}

		if($request->isMethod('POST')){

			$data = $request->all();

			$validated = array();

			$validator = Validator::make($request->all(), $validated);

			if ($validator->fails()) {

				return redirect()->back()->withErrors($validator)->withInput();

			} else {

				$product->product_name      		= $data['product_name'];
				$product->product_description     	= $data['product_description'];
				$product->product_color       		= $data['product_color']??[];
				$product->product_size            	= $data['product_size']??[];
				$product->product_price            	= $data['product_price']??[];


				$product->product_helpful_answer    = $data['product_helpful_answer'];
				$product->product_in_box    		= $data['product_in_box'];
				$product->product_tech_insights    	= $data['product_tech_insights'];
				$product->product_faqs    			= (!empty($data['product_faqs'])?array_values($data['product_faqs']):[]);


				if(!empty($request->file())){

					$isUpload = Uploader::universalUpload(
						array(
							'directory'=>storage_path('app/public/products/'),
							'files'=>array_keys($request->file()),
							'multiple'=>false,
							'thumb'=>array(),
							'allowExtension' => array('jpg','png','JPG','PNG'),
						),
						$request
					);

					if(isset($isUpload['success']) && $isUpload['success']){
						foreach($isUpload['media_path'] as $k=>$value){

							$http_path = asset('assets/storage/products/');

							if(!empty($value['mediaPath'])){

								if(!empty($product->$k)){
									Uploader::universalUnlink($product->$k,storage_path('app/public/products/'));
								}

								$product->$k = $value['mediaPath'];
							}else{
								return redirect()->back()->with('error',$value['error']);
							}
						}
					}


					$RequestFiles = $request->file('product_specification');
					if(!empty($RequestFiles)){

						foreach($RequestFiles as $k=>$value){

							$response = Uploader::fileUpload($value['image'],storage_path('app/public/products/'),false,array('jpg','png','JPG','PNG'));
							if(!empty($response['mediaPath'])){
								$exist = $product->product_specification;
								$spe = $data['product_specification'];

								if(!empty($exist[$k]['image']) || !empty($exist['spe_'.$k]['image'])){
									Uploader::universalUnlink(($exist[$k]['image']??$exist['spe_'.$k]['image']),storage_path('app/public/products/'));
								}

								$spe[$k]['image'] = $response['mediaPath'];
								$data['product_specification'] = $spe;
							}
						}
					}
				}

				$product->product_specification = (!empty($data['product_specification'])?array_values($data['product_specification']):[]);

				$isaved = $product->save();

				if ($isaved) {
					return redirect(route('products'))
						->with('success', 'Product ' . ($Id ? 'updated' : 'created') . ' successfully.');
				} else {
					return redirect()->back()->with('error', 'Failed to save product.');
				}
			}
		}

		$sizes = $this->size->pluck('size','size_id');
		$colors = $this->color->pluck('color_name','color_id');

		$ediContent = view('Backend.products.editor_default')->render();

		return view('Backend.products.manage',compact('product','sizes','colors','ediContent'));
	}

	public function delete(Request $request){
		if($request->post()){

			$records = $request->all();

			$records = $this->product->find($records['products']);

			foreach($records as $k=>$record){

				if(!empty($record->product_image)){
					Uploader::universalUnlink($record->product_image,storage_path('app/public/products/'));
				}

				if(!empty($record->product_faq_image)){
					Uploader::universalUnlink($record->product_faq_image,storage_path('app/public/products/'));
				}


				if(!empty($record->product_in_box_image)){
					Uploader::universalUnlink($record->product_in_box_image,storage_path('app/public/products/'));
				}

				if(!empty($record->product_specification)){
					foreach ($record->product_specification as $key => $value) {
						Uploader::universalUnlink($value['image'],storage_path('app/public/products/'));
					}
				}

				$record->delete();
			}

			return redirect(route('products'))->with('success','Records deleted successfully.');
		}
		else{
			return redirect(route('products'))->with('error','Invalid Request!!');
		}
	}
	/** End  */

}
