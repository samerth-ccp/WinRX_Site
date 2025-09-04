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

use DB;
use Uploader;
use Stripe;

class StripeController extends Controller
{

    /**
	* Create a new controller instance.
	*
	* @return void
	*/

    public function __construct(){
		$this->EmailModel = new MailSenders();
        $this->SiteConfig = Session::get('ConfigData');
		$this->middleware('guest');
    }

    public function connectUserStripeAccount(Request $request){
        /** For Call Client */
        $stripe = new \Stripe\StripeClient($this->SiteConfig['stripe_secret_key']);

        /** Set Api on Stripe Request */
        \Stripe\Stripe::setApiKey($this->SiteConfig['stripe_secret_key']);

        try {
            $account = \Stripe\Account::create([
                'type' => 'express',
                'country' => 'US',
                'business_type'=>"individual",
                'settings'=>['payouts'=>['schedule'=>["interval"=>"manual"]]],
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
            ]);
        } catch (\Exception $e) {
            return redirect(route('frontend.profile'))->with('error',$e->getMessage());
        }

        if(!empty($account->id)){

            try {
                $account_links = \Stripe\AccountLink::create([
                    'account' => $account->id,
                    'refresh_url' => route('stripe.connectreturn'),
                    'return_url' => route('stripe.connectreturn'),
                    'type' => 'account_onboarding',
                ]);
            } catch (\Exception $e) {
                return redirect(route('frontend.profile'))->with('error',$e->getMessage());
            }

            if(!empty($account_links->url)){
                return redirect($account_links->url);
            }
        }

        return redirect(route('frontend.profile'));
    }

    public function connectUserRetrun(Request $request){
        $data = $request->json()->all();


        return redirect(route('frontend.profile'))->with('success','Your stripe account connected successfully.');
    }

    public function webhookResponse(Request $request){

        $data = $request->json()->all();
        if(!empty($data)){
            $logData['log_type'] = 'WebHook Response';
            $logData['log_response'] = json_encode($data);

            DB::table('logs')->insert($logData);
        }
        exit;
    }
}
