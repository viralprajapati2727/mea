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
    Route::get('page/faq', 'GeneralController@faq')->name('page.faq');
    Route::get('page/about-us', 'GeneralController@aboutUs')->name('page.about-us');
    Route::get('page/contact-us', 'GeneralController@contactUs')->name('page.contact-us');
    Route::post('page/contact-us', 'GeneralController@contactRequest')->name('contact-us-request');
    Route::get('page/resource', 'GeneralController@resource')->name('page.resource');
    Route::get('members', 'GeneralController@members')->name('page.members');
    Route::get('community', 'GeneralController@community')->name('page.community');
    Route::get('search-job', 'JobController@searchJob')->name('job.search-job');


    Route::get('profile/{slug}', 'GeneralController@viewProfile')->name('user.view-profile');

    Route::group(['middleware' => ['verified','auth']], function () {

        Route::get('change-password', 'GeneralController@changePassword')->name('user.change-password');
		Route::post('update-password', 'GeneralController@updatePassword')->name('user.update-password');
        
        Route::group(['middleware' => ['simpleuser-access']], function () {
            Route::get('user-fill-profile', 'GeneralController@fillProfile')->name('user.fill-profile');
            Route::post('user-store-profile', 'UserController@updateProfile')->name('user.store-profile');
        });

        Route::group(['middleware' => ['entrepreneur-access']], function () {
            Route::get('entrepreneur-fill-profile', 'GeneralController@fillProfile')->name('entrepreneur.fill-profile');
            Route::post('entrepreneur-store-profile', 'EntrepreneurController@updateProfile')->name('entrepreneur.store-profile');
        });

        Route::group(['middleware' => ['fill-profile-access']], function () {

            //post job
            Route::get('fill-job/{job_unique_id?}', 'JobController@fillJob')->name('job.fill-job');
            Route::post('update-job', 'JobController@updateJob')->name('job.update-job');
            Route::get('my-jobs', 'JobController@index')->name('job.my-jobs');
            
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

        //Job Title Management
        Route::resource('job-title','Admin\JobTitleController');
        Route::post('job-title-filter', 'Admin\JobTitleController@ajaxData')->name('job-title-filter');
        Route::post('change-job-title-status', 'Admin\JobTitleController@changeStatus')->name('admin.change-job-title-status');
        Route::post('check-unique-job-title','Admin\JobTitleController@checkUniqueJobTitle')->name('check_unique_job_title');

        //Currency Management
        Route::resource('currency','Admin\CurrencyController');
        Route::post('currency-filter', 'Admin\CurrencyController@ajaxData')->name('currency-filter');
        Route::post('change-currency-status', 'Admin\CurrencyController@changeStatus')->name('admin.change-currency-status');
        Route::post('check-unique-currency','Admin\CurrencyController@checkUniqueCurrency')->name('check_unique_currency');
        
        //Profile Question Management
        Route::resource('profile-question','Admin\ProfileQuestionController');
        Route::post('profile-question-filter', 'Admin\ProfileQuestionController@ajaxData')->name('profile-question-filter');
        Route::post('change-profile-question-status', 'Admin\ProfileQuestionController@changeStatus')->name('admin.change-profile-question-status');

       // entrepreneur
        Route::get('entrepreneur','Admin\EntrepreneurController@index')->name('admin.entrepreneur.index');
        Route::post('entrepreneur-filter','Admin\EntrepreneurController@ajaxData')->name('admin.entrepreneur.filter');
        Route::get('entrepreneur-details/{slug}','Admin\EntrepreneurController@viewDetails')->name('admin.entrepreneur.details');

        // simple user
        Route::get('user','Admin\UserController@index')->name('admin.user.index');
        Route::post('user-filter','Admin\UserController@ajaxData')->name('admin.user.filter');
        Route::get('user-details/{slug}','Admin\UserController@viewDetails')->name('admin.user.details');

        Route::post('remove-user','Admin\AdminController@removeUser')->name('remove-user');
        Route::post('user-status','Admin\AdminGeneralController@userStatus')->name('admin.user.status');
		
        // Email templates
        Route::resource('emails', 'Admin\EmailController');

        //FAQ Management
        Route::resource('faq', 'Admin\FaqController');
        Route::post('faq-change-status', 'Admin\FaqController@changeStatus')->name('faq-change-status');
        Route::post('faq-filter', 'Admin\FaqController@ajaxData')->name('faq-filter');
        Route::post('check-unique-faq-question','Admin\FaqController@checkUniqueQuestion')->name('check_unique_question');

        //job
		Route::get('job/pending', 'Admin\JobController@pendingJob')->name('admin.job.pending');
		Route::post('admin-pending-job-filter', 'Admin\JobController@pendingAjaxData')->name('admin.pending-job-filter');
		Route::get('job/active', 'Admin\JobController@activeJob')->name('admin.job.active');
		Route::post('admin-active-job-filter', 'Admin\JobController@activeAjaxData')->name('admin.active-job-filter');
		Route::get('job/sponsored', 'Admin\JobController@sponsoredJob')->name('admin.job.sponsored');
		Route::post('admin-sponsored-job-filter', 'Admin\JobController@sponsoredAjaxData')->name('admin.sponsored-job-filter');
		Route::post('job-sponsor-status', 'Admin\JobController@jobSponsorStatus')->name('admin.job.active-pending');
		Route::get('job/archived', 'Admin\JobController@archivedJob')->name('admin.job.archived');
		Route::post('admin-archived-job-filter', 'Admin\JobController@archivedAjaxData')->name('admin.archived-job-filter');
		Route::post('job-status', 'Admin\JobController@jobStatus')->name('admin.job.approve-reject');
		Route::post('job-destroy', 'Admin\JobController@destroy')->name('admin.job.destroy');
		Route::get('job/{status}/{id}', 'Admin\JobController@detail')->name('admin.job.detail');
		Route::get('job-translation/{status}/{id}', 'Admin\JobController@translation')->name('admin.job.translation');
		Route::post('job-translation-store', 'Admin\JobController@storeTranslation')->name('admin.job.translation.store');
		Route::get('edit-job/{status}/{uniqueId}/{language}', 'Admin\JobController@editJob')->name('admin.job.edit-job');
		Route::post('edit-job-store', 'Admin\JobController@updateJob')->name('admin.job.edit-job-store');
        Route::post('status-job-action', 'Admin\JobController@actionStatus')->name('admin.job.action-status');

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
Route::get('html-contact-us',function (){
    return view('html.contact-us');
});
Route::get('html-about-us',function (){
    return view('html.about-us');
});
Route::get('html-faq',function (){
    return view('html.faq');
});
Route::get('html-job-detail',function (){
    return view('html.job-detail');
});
Route::get('html-community',function (){
    return view('html.community');
});
Route::get('html-our-team',function (){
    return view('html.our-team');
});
Route::get('html-blog',function (){
    return view('html.blog');
});