<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', 'HomeController@index')->name('home');
Route::get('refresh-csrf', function(){ return csrf_token(); });

Route::group(['middleware' => ['prevent-back-history']], function () {
    Auth::routes(['verify' => true]);
    Route::post('email-exists', 'Auth\RegisterController@emailExists')->name('email-exists');
    Route::post('check-email', 'Auth\RegisterController@checkEmail')->name('check-email');
    Route::get('/', 'GeneralController@index')->name('index');


    Route::get('profile/{slug}', 'GeneralController@viewProfile')->name('user.view-profile');

    Route::group(['middleware' => ['verified','auth']], function () {
        
        Route::group(['middleware' => ['simpleuser-access']], function () {
            Route::get('user-fill-profile', 'GeneralController@fillProfile')->name('user.fill-profile');
            Route::post('user-store-profile', 'UserController@updateProfile')->name('user.store-profile');
        });

        Route::group(['middleware' => ['entrepreneur-access']], function () {
            Route::get('entrepreneur-fill-profile', 'GeneralController@fillProfile')->name('entrepreneur.fill-profile');
            Route::post('entrepreneur-store-profile', 'EntrepreneurController@updateProfile')->name('entrepreneur.store-profile');
        });

        Route::group(['middleware' => ['fill-profile-access']], function () {
            Route::group(['middleware' => ['simpleuser-access']], function () {
            
            });
    
            Route::group(['middleware' => ['entrepreneur-access']], function () {
                
            }); 
        }); 
    }); 
    

    // Admin urls
	Route::get('admin/login', 'Auth\AdminLoginController@index')->name('admin');
    Route::post('admin/login', 'Auth\AdminLoginController@login')->name('admin.login');
    Route::post('admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

	Route::get('admin/forgot-password', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('admin/forgot-password', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    
    Route::group(['prefix' => 'admin','middleware' => ['admin-access']], function () {
        Route::get('/', 'Admin\AdminController@dashboard')->name('admin.index');

        //admin profile
        Route::get('admin-fill-profile', 'Admin\AdminController@fillProfile')->name('admin.fill-profile');
        Route::post('admin-store-profile', 'Admin\AdminController@updateProfile')->name('admin.store-profile');

        // Change Password
        Route::get('change-password', 'Admin\AdminController@changePassword')->name('admin.change-password');
        Route::post('update-password', 'Admin\AdminController@updatePassword')->name('admin.update-password');

        //Question Category Management
        Route::resource('question-category','Admin\QuestionCategoryController');
        Route::post('question-category-filter', 'Admin\QuestionCategoryController@ajaxData')->name('question-category-filter');
        Route::post('change-question-category-status', 'Admin\QuestionCategoryController@changeStatus')->name('admin.change-question-category-status');
        Route::post('check-unique-q-category','Admin\QuestionCategoryController@checkUniqueCategory')->name('check_unique_category');

        //Business Category Management
        Route::resource('business-category','Admin\BusinessCategoryController');
        Route::post('business-category-filter', 'Admin\BusinessCategoryController@ajaxData')->name('business-category-filter');
        Route::post('change-business-category-status', 'Admin\BusinessCategoryController@changeStatus')->name('admin.change-business-category-status');
        Route::post('check-unique-business-category','Admin\BusinessCategoryController@checkUniqueCategory')->name('check_unique_b_category');
        
        //Profile Question Management
        Route::resource('profile-question','Admin\ProfileQuestionController');
        Route::post('profile-question-filter', 'Admin\ProfileQuestionController@ajaxData')->name('profile-question-filter');
        Route::post('change-profile-question-status', 'Admin\ProfileQuestionController@changeStatus')->name('admin.change-profile-question-status');

       // entrepreneur
        Route::get('entrepreneur','Admin\EntrepreneurController@index')->name('admin.entrepreneur.index');
        Route::post('entrepreneur-filter','Admin\EntrepreneurController@ajaxData')->name('admin.entrepreneur.filter');
        Route::get('entrepreneur-details/{slug','Admin\EntrepreneurController@viewDetails')->name('admin.entrepreneur.details');

        Route::post('remove-user','Admin\AdminController@removeUser')->name('remove-user');
        Route::post('user-status','Admin\AdminGeneralController@userStatus')->name('admin.user.status');
		
        // Email templates
        Route::resource('emails', 'Admin\EmailController');

    });        
});




//html only
Route::get('html-all-pages',function () {
    return view('html.all-pages');
})->name('html.all-pages');

Route::get('html-members',function (){
    return view('html.members');
});
Route::get('html-profile',function (){
    return view('html.profile');
});
Route::get('html-edit-profile',function (){
    return view('html.edit-profile');
});
Route::get('html-business-ideas',function (){
    return view('html.business-ideas');
});
Route::get('html-job-portal',function (){
    return view('html.job-portal');
});
Route::get('html-resources',function (){
    return view('html.resources');
});