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

use App\Models\User;
use App\Models\Product;

use DB;
use Uploader;
use Artisan;

class IndexController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(){
		$this->middleware('guest');

		$this->product = new Product;
    }

	public function index(Request $request){
		$pageKey = 'home_page';
		$pageData = $this->getPageContents($pageKey);
		$pageMetaTitle = $pageData->meta_title_en;
		$pageMetaKeyword = $pageData->meta_keywords_en;
		$pageMetaDescription = $pageData->meta_desc_en;

        $bannerData = DB::table('banner_section')->first();
        $sliderMainData = DB::table('slider_content')->first();
        $sliderData = DB::table('slider_section')->get();
        $productData = $this->product->select('product_id','product_name','product_image','product_price')->where('product_status','1')->get()->random(2);

        $aboutMainData = DB::table('about_section')->first();
        $aboutContentData = DB::table('about_section_content')->get();
        $eraMainData = DB::table('newera_section')->first();
        $eraContentData = DB::table('newera_section_content')->get();
        $smartContentData = DB::table('smart_section')->first();
        $accuracyContentData = DB::table('accurate_section')->first();




        return view('Frontend.index.index',compact('pageData','pageMetaTitle','pageMetaKeyword','pageMetaDescription','bannerData','sliderData','sliderMainData','productData','aboutMainData','aboutContentData','eraMainData','eraContentData','smartContentData','accuracyContentData'));
    }

	public function getPageContents($key){
		$getPageData = DB::table('pages')->where('page_key',$key)->first();

		return $getPageData;
	}

	public function runarcmd(Request $request){
		Artisan::call('storage:link');
		// Artisan::call('cache:clear');
		// Artisan::call('config:clear');
		// Artisan::call('route:cache');
		return true;
	}

}
