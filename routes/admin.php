<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Auth\ResetPasswordController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AjaxController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\StaticController;
use App\Http\Controllers\Backend\ColorController;
use App\Http\Controllers\Backend\SizeController;
use App\Http\Controllers\Backend\ProductController;

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
Route::group(['middleware' => ['guest'],'prefix' => config('app.backend')], function() {
    /* Befor Login Admin  */

    Route::get('/', [AdminController::class, 'getLoginForm'])->name('backend.login');
    Route::get('/login', [LoginController::class, 'getLoginForm'])->name('backend.login');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('backend.authenticate');

    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/resetpassword/{token}', [ResetPasswordController::class, 'resetpassword'])->name('password.update');
});


Route::group(['middleware' => ['admins'],'prefix' => config('app.backend')], function() {
    /* After Login Admin  */
    Route::get('/', [AdminController::class, 'index'])->name('backend.index');
    Route::get('/logout', [LoginController::class, 'getLogout'])->name('backend.logout');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('backend.dashboard');
    Route::get('/site-configuration/{key}', [AdminController::class, 'siteconfigs'])->name('backend.siteconfigs');
    Route::post('/update-site-configuration/{key}', [AdminController::class, 'updatesiteconfigs'])->name('backend.updatesiteconfigs');

    /** Admin Profile */
    Route::match(['get','post'],'/admin-profile', [AdminController::class, 'adminprofile'])->name('backend.adminprofile');
    Route::match(['get','post'],'/admin-reset-password', [AdminController::class, 'adminresetpassword'])->name('backend.resetpassword');

    /** Users */
    Route::get('/users', [UserController::class, 'users'])->name('backend.users');
    Route::get('/get-users', [UserController::class, 'getusers'])->name('backend.getusers');
    Route::match(['get','post'],'/manage-user/{uid?}', [UserController::class, 'manageuser'])->name('backend.manageuser');
    Route::get('/user-details/{uid}', [UserController::class, 'viewuser'])->name('backend.viewusers');
    Route::post('/remove-users', [UserController::class, 'removeusers'])->name('backend.removeusers');
    Route::get('/access-account/{uid}', [UserController::class, 'accessAccount'])->name('backend.accessaccount');
    Route::get('/resend-verification/{uid}', [UserController::class, 'resendVerification'])->name('backend.resendverification');

    /** Static - Email */
    Route::get('/email-template', [StaticController::class, 'emailtemplate'])->name('backend.emailtemplate');
    Route::get('/get-template', [StaticController::class, 'gettemplate'])->name('backend.gettemplate');
    Route::match(['get','post'],'/manage-template/{tid}', [StaticController::class, 'managetemplate'])->name('backend.managetemplate');
    Route::get('/view-template/{tid}', [StaticController::class, 'viewtemplate'])->name('backend.viewtemplate');

    /** Static - Pages */
    Route::get('/pages', [StaticController::class, 'pages'])->name('backend.pages');
    Route::get('/get-pages', [StaticController::class, 'getpages'])->name('backend.getpages');
    Route::match(['get','post'],'/manage-page/{pid}', [StaticController::class, 'managepages'])->name('backend.managepages');
    Route::get('/view-page/{pid}', [StaticController::class, 'viewpages'])->name('backend.viewpages');

    /** Home Page - Banner Section */
    Route::match(['get','post'],'/banner-section', [StaticController::class, 'bannersection'])->name('backend.bannersection');
    Route::match(['get','post'],'/slidercontent', [StaticController::class, 'slidercontent'])->name('backend.slidercontent');
    Route::get('/slider-section', [StaticController::class, 'slidersection'])->name('backend.slidersection');
    Route::get('/get-slidersection', [StaticController::class, 'getslidersection'])->name('backend.getslidersection');
    Route::match(['get','post'],'/manage-slider/{pid?}', [StaticController::class, 'manageslidersection'])->name('backend.manageslidersection');
    Route::post('/remove-slidersection', [StaticController::class, 'removeslidersection'])->name('backend.removeslidersection');
    Route::match(['get','post'],'/aboutsection', [StaticController::class, 'aboutsection'])->name('backend.aboutsection');
    Route::get('/about-content', [StaticController::class, 'aboutcontent'])->name('backend.aboutcontent');
    Route::get('/get-aboutcontent', [StaticController::class, 'getaboutcontent'])->name('backend.getaboutcontent');
    Route::match(['get','post'],'/manage-aboutcontent/{pid?}', [StaticController::class, 'manageaboutcontent'])->name('backend.manageaboutcontent');
    Route::post('/remove-aboutcontent', [StaticController::class, 'removeaboutcontent'])->name('backend.removeaboutcontent');
    Route::match(['get','post'],'/newerasection', [StaticController::class, 'newerasection'])->name('backend.newerasection');
    Route::get('/newera-content', [StaticController::class, 'neweracontent'])->name('backend.neweracontent');
    Route::get('/get-neweracontent', [StaticController::class, 'getneweracontent'])->name('backend.getneweracontent');
    Route::match(['get','post'],'/manage-neweracontent/{pid?}', [StaticController::class, 'manageneweracontent'])->name('backend.manageneweracontent');
    Route::post('/remove-neweracontent', [StaticController::class, 'removeneweracontent'])->name('backend.removeneweracontent');
    Route::match(['get','post'],'/smartsolutions', [StaticController::class, 'smartsolutions'])->name('backend.smartsolutions');
    Route::match(['get','post'],'/accuratesection', [StaticController::class, 'accuratesection'])->name('backend.accuratesection');

    Route::match(['get','post'],'/shop-banner-section', [StaticController::class, 'shopbannersection'])->name('backend.shopbannersection');
    Route::match(['get','post'],'/shop-smart-section', [StaticController::class, 'shopsmartsection'])->name('backend.shopsmartsection');
    Route::match(['get','post'],'/shop-complement-section', [StaticController::class, 'shopcomplementsection'])->name('backend.shopcomplementsection');
    Route::get('/shop-complement-content', [StaticController::class, 'shopcomplementcontent'])->name('backend.shopcomplementcontent');
    Route::get('/get-shopcomplementcontent', [StaticController::class, 'getshopcomplementcontent'])->name('backend.getshopcomplementcontent');
    Route::match(['get','post'],'/manage-shopcomplementcontent/{pid?}', [StaticController::class, 'manageshopcomplementcontent'])->name('backend.manageshopcomplementcontent');
    Route::post('/remove-shopcomplementcontent', [StaticController::class, 'removeshopcomplementcontent'])->name('backend.removeshopcomplementcontent');
    Route::get('/shop-tech-section', [StaticController::class, 'shoptechsection'])->name('backend.shoptechsection');
    Route::get('/get-shoptechsection', [StaticController::class, 'getshoptechsection'])->name('backend.getshoptechsection');
    Route::match(['get','post'],'/manage-shoptechsection/{pid?}', [StaticController::class, 'manageshoptechsection'])->name('backend.manageshoptechsection');
    Route::post('/remove-shoptechsection', [StaticController::class, 'removeshoptechsection'])->name('backend.removeshoptechsection');

    Route::prefix('products')->group(function () {
        /** Colors  */
        Route::get('/colors', [ColorController::class, 'index'])->name('colors');
        Route::get('/get-colors', [ColorController::class, 'get'])->name('get.colors');
        Route::match(['get','post'],'/manage-color/{id?}', [ColorController::class, 'manage'])->name('manage.colors');
        Route::post('/delete-color', [ColorController::class, 'delete'])->name('delete.colors');

        /** Sizes  */
        Route::get('/sizes', [SizeController::class, 'index'])->name('sizes');
        Route::get('/get-sizes', [SizeController::class, 'get'])->name('get.sizes');
        Route::match(['get','post'],'/manage-size/{id?}', [SizeController::class, 'manage'])->name('manage.sizes');
        Route::post('/delete-size', [SizeController::class, 'delete'])->name('delete.sizes');

        /** Products  */
        Route::get('/', [ProductController::class, 'index'])->name('products');
        Route::get('/get', [ProductController::class, 'get'])->name('get.products');
        Route::match(['get','post'],'/manage/{id?}', [ProductController::class, 'manage'])->name('manage.products');
        Route::post('/delete', [ProductController::class, 'delete'])->name('delete.products');
    });



    /** Change Status - Ajax */
    Route::get('/ajaxsetstatus/{type}/{id}/{status}', [AjaxController::class, 'ajaxsetstatus'])->name('backend.ajaxsetstatus');
    Route::get('ajax/request', [AjaxController::class, 'ajaxRequest'])->name('ajax.request');
    Route::get('ajax/navrequest', [AjaxController::class, 'navajaxRequest'])->name('ajax.navrequest');

    /** Ck Editor Media upload */
    Route::match(['get','post'],'/upload-media', [StaticController::class, 'uploadmedia'])->name('backend.uploadmedia');

    Route::get('/getchart', [AdminController::class, 'getchart'])->name('backend.getchart');
    /** 404 file put always this route in end of routing */
    Route::get('/{any}', [AdminController::class, 'any'])->name('backend.any');
});


