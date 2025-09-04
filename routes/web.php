<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Auth\LoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController;
use App\Http\Controllers\Frontend\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\Auth\ResetPasswordController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\StaticController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\SocialController;
use App\Http\Controllers\Frontend\StripeController;
use App\Http\Controllers\Frontend\CookieCartController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function() {
    /**Home Page */
    Route::get('/',[IndexController::class, 'index'])->name('frontend.index.index');

    /**Static Page */
    Route::get('/index',[StaticController::class, 'index'])->name('frontend.static.index');
    Route::get('/about-us',[StaticController::class, 'aboutUs'])->name('frontend.static.aboutus');
    Route::match(['get','post'],'/contact-us',[StaticController::class, 'contactUs'])->name('frontend.static.contactus');
    Route::get('/terms-and-condition',[StaticController::class, 'termsAndCondition'])->name('frontend.static.terms');
    Route::get('/privacy-policy',[StaticController::class, 'privacyPolicy'])->name('frontend.static.privacy');

    Route::get('/shop',[StaticController::class, 'shop'])->name('frontend.static.shop');
    Route::get('/product-detail/{pid?}',[StaticController::class, 'productDetail'])->name('frontend.static.productdetail');
    Route::get('/cart',[StaticController::class, 'cart'])->name('frontend.static.cart');


    /** Auth - Before Log in */
    Route::match(['get','post'],'/login',[LoginController::class, 'login'])->name('frontend.login');
    Route::match(['get','post'],'/sign-up',[RegisterController::class, 'register'])->name('frontend.register');
    Route::match(['get','post'],'/forgot-password',[ForgotPasswordController::class, 'forgotPassowrd'])->name('frontend.forgotpassowrd');
    Route::match(['get','post'],'/reset-password/{token}',[ResetPasswordController::class, 'resetPassword'])->name('frontend.resetpassword');

    /**Email Verification */
    Route::get('/email-verification/{token}',[RegisterController::class, 'emailverification'])->name('frontend.emailverification');
    /**Change Email Verification */
    Route::get('/change-email-verification/{token}',[RegisterController::class, 'changeEmailVerification'])->name('frontend.changeemailverification');

    /** Social Logins */
    /** google */
    Route::get('/google-auth-redirect',[SocialController::class, 'googleAuthRedirect'])->name('frontend.googleredirect');
    Route::get('/google-auth-callback',[SocialController::class, 'googleAuthCallback'])->name('frontend.googlecallback');
    /** facebook */
    Route::get('/facebook-auth-redirect',[SocialController::class, 'facebookAuthRedirect'])->name('frontend.facebookredirect');
    Route::get('/facebook-auth-callback',[SocialController::class, 'facebookAuthCallback'])->name('frontend.facebookcallback');
    /** linkedin */
    Route::get('/linkedin-auth-redirect',[SocialController::class, 'linkedinAuthRedirect'])->name('frontend.linkedinredirect');
    Route::get('/linkedin-auth-callback',[SocialController::class, 'linkedinAuthCallback'])->name('frontend.linkedincallback');
    /** twitter */
    Route::get('/twitter-auth-redirect',[SocialController::class, 'twitterAuthRedirect'])->name('frontend.twitterredirect');
    Route::get('/twitter-auth-callback',[SocialController::class, 'twitterAuthCallback'])->name('frontend.twittercallback');

    /** Stripe WebHook */
    Route::match(['get','post'],'/stripe-webhook-response', [StripeController::class, 'webhookResponse'])->name('stripe.webhookresponse');

    /* Add to Cart */
    Route::prefix('cart')->group(function(){
        Route::post('items', [CookieCartController::class, 'add'])->name('cart.add');
        Route::patch('items/{key?}', [CookieCartController::class, 'updateQty'])->name('cart.update');
        Route::delete('items/{key?}', [CookieCartController::class, 'remove'])->name('cart.delete');
        Route::get('/get', [CookieCartController::class, 'show'])->name('cart.get');
        Route::get('/count', [CookieCartController::class, 'count'])->name('cart.count');
    });

    /*** Create Symbolic Links */
    Route::get('/run-ar-cmd',[IndexController::class, 'runarcmd'])->name('frontend.runarcmd');
});


Route::group(['middleware' => ['user']], function() {
    /** Auth - After Log in */

    /** Profile Section */
    Route::match(['get','post'],'/profile', [ProfileController::class, 'index'])->name('frontend.profile');
    Route::post('/change-password', [ProfileController::class, 'resetPassword'])->name('frontend.changepassword');
    Route::post('/check-image', [ProfileController::class, 'checkImage'])->name('frontend.checkimage');
    Route::post('/upload-crop-image', [ProfileController::class, 'uploadCropImage'])->name('frontend.uploadimage');

    /** Stripe */
    Route::match(['get','post'],'/connect-user-stripe-account', [StripeController::class, 'connectUserStripeAccount'])->name('stripe.connectuser');
    Route::match(['get','post'],'/connect-user-return', [StripeController::class, 'connectUserRetrun'])->name('stripe.connectreturn');

    /** Log Out  */
    Route::get('/logout', [LoginController::class, 'getLogout'])->name('frontend.logout');
});


