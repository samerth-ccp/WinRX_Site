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

use App\Mail\MailSenders;
use App\Models\User;
use App\Models\Product;

use DB;
use Uploader;

class StaticController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(){
		$this->EmailModel = new MailSenders();
		$this->product = new Product;
		$this->middleware('guest');
    }

	public function index(Request $request){
		$pageKey = 'home_page';
		$pageData = $this->getPageContents($pageKey);

		$pageMetaTitle = $pageData->meta_title_en;
		$pageMetaKeyword = $pageData->meta_keywords_en;
		$pageMetaDescription = $pageData->meta_desc_en;

        return view('Frontend.static.index',compact('pageData','pageMetaTitle','pageMetaKeyword','pageMetaDescription'));
    }

    public function aboutUs(Request $request){
		$pageKey = 'about_us';
		$pageData = $this->getPageContents($pageKey);

		$pageMetaTitle = $pageData->meta_title_en;
		$pageMetaKeyword = $pageData->meta_keywords_en;
		$pageMetaDescription = $pageData->meta_desc_en;

        return view('Frontend.static.index',compact('pageData','pageMetaTitle','pageMetaKeyword','pageMetaDescription'));
    }

    public function contactUs(Request $request){
		$pageKey = 'contact_us';
		$pageData = $this->getPageContents($pageKey);
        $configData = Session::get('ConfigData');
        if($request->post()){

            $data = $request->all();

			$validated = array();
			$validated['name'] = 'required|max:25';
			$validated['email'] = 'required|email|max:200';
            $validated['phone_number'] = 'required|min:8|max:16';
            $validated['message'] = 'required|min:10|max:2000';

			$validator = Validator::make($request->all(), $validated);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			} else {

                /* check g-recaptcha-3 */
				$res = post_captcha($data['hiddenRecaptcha'],$configData['recaptcha_secret_key']);

				if (!$res['success']) {
					// What happens when the reCAPTCHA is not properly set up
					// echo 'reCAPTCHA error: Check to make sure your keys match the registered domain and are in the correct locations. You may also want to doublecheck your code for typos or syntax errors.';
                    return redirect(route('frontend.static.contactus'))->with('error','Your Session expired. Please try again!!.');
				}else{

                    $data = $request->all();
                    unset($data['_token']);

                    $MailData['user_name'] = 'Admin';
                    $MailData['user_email'] = $configData['site_email'];
                    $MailData['name'] = $data['name'];
                    $MailData['email'] = $data['email'];
                    $MailData['number'] = $data['phone_number'];
                    $MailData['message'] = $data['message'];
                    $MailData['mail_template'] = 'contact_us_email';

                    $sendMail = $this->EmailModel->sendEmail($MailData);

                    return redirect(route('frontend.static.contactus'))->with('success','Your request successfully submitted.');
                }
            }
        }

        $pageMetaTitle = $pageData->meta_title_en;
		$pageMetaKeyword = $pageData->meta_keywords_en;
		$pageMetaDescription = $pageData->meta_desc_en;
        return view('Frontend.static.contact',compact('pageData','pageMetaTitle','pageMetaKeyword','pageMetaDescription'));
    }

    public function termsAndCondition(Request $request){
		$pageKey = 'terms_conditions';
		$pageData = $this->getPageContents($pageKey);

		$pageMetaTitle = $pageData->meta_title_en;
		$pageMetaKeyword = $pageData->meta_keywords_en;
		$pageMetaDescription = $pageData->meta_desc_en;

        return view('Frontend.static.index',compact('pageData','pageMetaTitle','pageMetaKeyword','pageMetaDescription'));
    }

    public function privacyPolicy(Request $request){
		$pageKey = 'privacy_policy';
		$pageData = $this->getPageContents($pageKey);

		$pageMetaTitle = $pageData->meta_title_en;
		$pageMetaKeyword = $pageData->meta_keywords_en;
		$pageMetaDescription = $pageData->meta_desc_en;

        return view('Frontend.static.index',compact('pageData','pageMetaTitle','pageMetaKeyword','pageMetaDescription'));
    }

	public function shop(Request $request){
		$pageKey = 'shop';
        $pageMetaTitle = "Shop";
		$pageMetaKeyword = "shop";
		$pageMetaDescription = "shop";
        $bannerContentData = DB::table('shop_banner')->first();

		$productData = $this->product->select('product_id','product_name','product_image','product_price')->where('product_status','1')->get();

        $smartContentData = DB::table('shop_smart')->first();
        $embedUrl = "";
        if(!empty($smartContentData->shop_smart_video_url)) {
            $youTube_link = $smartContentData->shop_smart_video_url;
            $videoUrl = getYouTubeEmbedUrl($youTube_link);
            $videoType = 1;
        } else {
            $videoUrl = asset('assets/storage/homeimages/'.$smartContentData->shop_smart_video);
            $videoType = 2;
        }
        $complementData = DB::table('shop_complement_section')->first();
        $complementContentData = DB::table('shop_complement_content')->get();
        $techContentData = DB::table('shop_tech_section')->get();
        //$productData = DB::table('products')->where('product_status','1')->limit(10)->latest('product_created_at')->get();
        return view('Frontend.static.shop',compact('pageMetaTitle','pageMetaKeyword','pageMetaDescription','bannerContentData','productData','smartContentData','complementData','complementContentData','techContentData','videoUrl','videoType'));
    }

    private function getYouTubeEmbedUrl($url) {
        // Match YouTube video ID from different URL formats
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches);

        if (!empty($matches[1])) {
            $videoId = $matches[1];
            return "https://www.youtube.com/embed/" . $videoId;
        }

        return false; // Invalid URL
    }

	public function productDetail(Request $request){
		$pageKey = 'product_detail';

		$Id = decrypt($request->route('pid'));

		$product = $this->product->where('product_status','1')->find($Id);

		if(empty($product)){
			return redirect()->back()->with('error','Product not found.');
		}

        return view('Frontend.static.productdetail',compact('product'));
    }

	public function cart(Request $request){
		$pageKey = 'cart';
        return view('Frontend.static.cart');
    }

    public function getPageContents($key){
		$getPageData = DB::table('pages')->where('page_key',$key)->first();

		return $getPageData;
	}
}
