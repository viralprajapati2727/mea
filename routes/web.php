<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return 'View cache cleared';
});
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return 'Routes cache cleared';
});
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:clear');
    return 'Config cache cleared';
});

Route::get("/checkout", "PaymentController@index");
Route::get("/create-account", "PaymentController@create")->name('create-account');
Route::get("/success", "PaymentController@success");
Route::post('/payment', "PaymentController@payment")->name('payment');
Route::get("/bank-account", "PaymentController@bankAccount")->name('bank-account');
Route::get("/bank-account/{action?}", "PaymentController@success");

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', 'GeneralController@index')->name('home');
Route::get('refresh-csrf', function(){ return csrf_token(); });

Route::group(['middleware' => 'prevent-back-history'] , function () {
    Auth::routes(['verify' => true]);
    
    /**Social login urls */
    Route::get('login/{provider?}', 'Auth\LoginController@redirectToProvider');
    Route::get('login/{provider?}/callback', 'Auth\LoginController@socialLoginHandler');

    Route::post('email-exists', 'Auth\RegisterController@emailExists')->name('email-exists');
    Route::post('check-email', 'Auth\RegisterController@checkEmail')->name('check-email');
    Route::get('/', 'GeneralController@index')->name('index');
    Route::get('page/faq', 'GeneralController@faq')->name('page.faq');
    Route::get('page/about-us', 'GeneralController@aboutUs')->name('page.about-us');
    Route::get('page/team', 'GeneralController@team')->name('page.team');
    Route::get('page/contact-us', 'GeneralController@contactUs')->name('page.contact-us');
    Route::post('page/contact-us', 'GeneralController@contactRequest')->name('contact-us-request');
    Route::get('blogs', 'GeneralController@blogs')->name('page.blogs');
    Route::get('blog/{slug}', 'GeneralController@blogDetail')->name('page.blog-detail');
    
    Route::get('search', 'JobController@globalSearch')->name('job.global-search');
    Route::get('questions','CommunityController@questions')->name('page.questions');
    Route::get('questions/{question_id}/{like?}','CommunityController@detail')->name('community.questions-details');
    
    Route::post('subscribe-email', 'GeneralController@subscriptionEmail')->name('subscribe-email');

    
    Route::group(['middleware' => ['verified','auth']], function () {
        /** Nav bar urls */
        Route::get('members', 'GeneralController@members')->name('page.members');
        Route::get('search-job', 'JobController@searchJob')->name('job.search-job');
        Route::get('startup-portal','GeneralController@getStartupPortal')->name('page.startup-portal');
        Route::get('profile/{slug}', 'GeneralController@viewProfile')->name('user.view-profile');
        /** Nav bar url end */

        Route::get('page/resource/{id}', 'GeneralController@resource')->name('page.resources');
        Route::get('resource/{slug}', 'GeneralController@resourceDetail')->name('page.resource-detail');

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

        Route::get('members/message/{user?}','MessageController@index')->name('member.message');
        Route::post('members/send-message','MessageController@sendMessage')->name('member.send-message');
        Route::post('get_message_list', 'MessageController@getMessageList')->name('get_message_list');

        // fund request 
        Route::get('fund-requests','GeneralController@getFundRequests')->name('page.fund-requests');
        Route::get('fund-requests/view/{id?}/{status?}','GeneralController@viewFundRequest')->name('page.fund-requests.view');

        Route::group(['middleware' => ['fill-profile-access']], function () {

            //post job
            Route::get('fill-job/{job_unique_id?}', 'JobController@fillJob')->name('job.fill-job');
            Route::post('update-job', 'JobController@updateJob')->name('job.update-job');
            Route::get('my-jobs', 'JobController@index')->name('job.my-jobs');
            Route::get('job/detail/{id}', 'JobController@detail')->name('job.job-detail');
            Route::get('applied-job', 'JobController@appliedJob')->name('job.applied-job');
            Route::post('cancel-job/{id}', 'JobController@cancelJob')->name('job.cancel-job');

            Route::post('apply-job', 'JobController@applyJob')->name('job.apply-job');
            Route::post('check-apply-job', 'JobController@checkAppliedJob')->name('job.check-apply-job');
            Route::get('view-applicant/{job_unique_id}', 'JobController@viewApplicant')->name('job.view-applicant');
            Route::post('change-applicant-status', 'JobController@changeApplicantStatus')->name('job.change-applicant-status');


            //appointments
            Route::get('appointments', 'AppointmentController@index')->name('appointment.index');
            Route::get('appointments/sent', 'AppointmentController@sent')->name('appointment.sent');
            Route::post('update-appointment', 'AppointmentController@updateAppointment')->name('appointment.update-appointment');
            Route::post('appointment-detail', 'AppointmentController@detail')->name('appointment.detail');
            Route::post('appointment-delete', 'AppointmentController@destroy')->name('appointment.delete');
            Route::post('appointment-status', 'AppointmentController@appointmentStatus')->name('appointment.approve-reject');
            
            //community
            Route::get('community', 'CommunityController@index')->name('community.index');
            Route::post('update-community', 'CommunityController@updateCommunity')->name('community.update-community');
            
            Route::get('drop-your-idea','GeneralController@idea')->name('page.drop-idea');
            Route::post('drop-your-ideas', 'GeneralController@sendIdea')->name('idea.send-idea');
            
            // Startup portal team member reqyest approve/reject
            Route::get('startup-portal-request','StartupPortalController@startupPortalRequest')->name('startup-portal-request');
            Route::post('startup-portal-request-action','StartupPortalController@startupPortalRequestAction')->name('startup-portal-request-action');

            Route::group(['middleware' => ['simpleuser-access']], function () {
            
            });
    
            Route::group(['middleware' => ['entrepreneur-access']], function () {
                // Startup portal
                Route::get('create-startup-portal','StartupPortalController@index')->name('startup-portal');
                Route::get('startup-portal/{action?}/{portal_id?}','StartupPortalController@create')->name('start-statup-portal');
                Route::post('store-startup-portal','StartupPortalController@store')->name('startup-portal.store');
                Route::post('store-appointment','StartupPortalController@storeAppoinment')->name('store-appoinment');
                Route::post('delete-portal','StartupPortalController@deletePortal')->name('delete-portal');
                
                Route::get('raise-fund','FundController@index')->name('startup.raise-fund');
                Route::get('raise-fund/{action?}/{id?}','FundController@create')->name('startup.raise-fund.create');
                Route::get('raise-fund/view/{id}/donor-list','FundController@viewDonorList')->name('startup.donor-list');
                Route::post('store-raise-fund','FundController@store')->name('startup-raise-fund.store');
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
		Route::get('job/archived', 'Admin\JobController@archivedJob')->name('admin.job.archived');
		Route::post('admin-archived-job-filter', 'Admin\JobController@archivedAjaxData')->name('admin.archived-job-filter');
		Route::post('job-status', 'Admin\JobController@jobStatus')->name('admin.job.approve-reject');
		Route::post('job-destroy', 'Admin\JobController@destroy')->name('admin.job.destroy');
		Route::get('job/{status}/{id}', 'Admin\JobController@detail')->name('admin.job.detail');
		Route::get('edit-job/{status}/{uniqueId}/{language}', 'Admin\JobController@editJob')->name('admin.job.edit-job');
		Route::post('edit-job-store', 'Admin\JobController@updateJob')->name('admin.job.edit-job-store');
        Route::post('status-job-action', 'Admin\JobController@actionStatus')->name('admin.job.action-status');

        //Blog
		Route::resource('blog','Admin\BlogController');
        Route::post('blog-filter', 'Admin\BlogController@ajaxData')->name('blog-filter');
        Route::post('change-blog-status', 'Admin\BlogController@changeStatus')->name('admin.change-blog-status');

        //Resource
		Route::resource('resource','Admin\ResourceController');
        Route::post('resource-filter', 'Admin\ResourceController@ajaxData')->name('resource-filter');
        Route::post('change-resource-status', 'Admin\ResourceController@changeStatus')->name('admin.change-resource-status');

        //appointments
        Route::get('appointments', 'Admin\AppointmentController@index')->name('admin.appointments.index');
        Route::post('admin-appointment-filter', 'Admin\AppointmentController@ajaxData')->name('admin.appointment-filter');
        Route::get('appointment/{id}', 'Admin\AppointmentController@detail')->name('admin.appointment.detail');

        // Community Question
		Route::get('question','Admin\CommunityController@index')->name('admin.question.index');
        Route::post('community-filter', 'Admin\CommunityController@ajaxData')->name('question-filter');
        Route::get('question/{question_id}','Admin\CommunityController@detail')->name('question.details');
        
        // Startup Portal
        Route::get('startup-portal','Admin\StartupPortalController@index')->name('admin.startup-portal.index');
        Route::post('startup-filter', 'Admin\StartupPortalController@ajaxData')->name('admin.startup-filter');
        Route::post('startup-status', 'Admin\StartupPortalController@startupStatus')->name('admin.startup.approve-reject');
        Route::get('startup-details/{portal_id}','Admin\StartupPortalController@detail')->name("admin.startup.detail");
        Route::post('update-appoinment','Admin\StartupPortalController@updateAppoinment')->name("admin.appoinment.update");
        
        // fund raise
        Route::get('fund-request','Admin\FundController@index')->name('admin.fund.index');
        Route::post('fund-filter', 'Admin\FundController@ajaxData')->name('admin.fund-filter');
        Route::post('fund-status', 'Admin\FundController@fundStatus')->name('admin.fund.approve-reject');
        Route::get('fund-details/{id}','Admin\FundController@detail')->name("admin.fund.detail");
        Route::post('fund-donor-list','Admin\FundController@donorList')->name('admin.fund-donor-list');

        //Topic Management
        Route::resource('topic','Admin\TopicController');
        Route::post('topic-filter', 'Admin\TopicController@ajaxData')->name('topic-filter');
        Route::post('change-topic-status', 'Admin\TopicController@changeStatus')->name('admin.change-topic-status');
        Route::post('check-unique-topic','Admin\TopicController@checkUniqueTopic')->name('check_unique_topic');
        
        
        Route::get('topic-detail/{id}','Admin\SubTopicController@index')->name("sub-topic.index");
        Route::post('sub-topic-store','Admin\SubTopicController@store')->name("sub-topic.store");
        Route::post('sub-topic-filter', 'Admin\SubTopicController@ajaxData')->name('sub-topic-filter');
        Route::post('change-sub-topic-status', 'Admin\SubTopicController@changeStatus')->name('admin.change-sub-topic-status');
        Route::post('check-unique-sub-topic','Admin\SubTopicController@checkUniqueSubTopic')->name('check_unique_sub_topic');
        Route::delete('topic-detail/sub-topic-delete/{id?}', 'Admin\SubTopicController@destroy')->name('sub-topic.delete');

        /** Subscription Emails */
        Route::get('email-subscriptions','Admin\SubscriptionController@index')->name('admin.email-subscriptions');
        Route::post('email-subscriptions-filter', 'Admin\SubscriptionController@ajaxData')->name('admin.email-subscriptions-filter');
        Route::post('send-subscription-mail', 'Admin\SubscriptionController@sendEmail')->name('admin.send-subscription-mail');

        Route::get('messages','Admin\MessageController@index')->name('admin.messages');
        Route::post('messages-filter', 'Admin\MessageController@ajaxData')->name('admin.messages-filter');
        Route::get('messages/detail/{id}', 'Admin\MessageController@detail')->name('admin.message.detail');
        Route::get('messages/download/{type}/{id}', 'Admin\MessageController@download')->name('admin.message.download');
        
    });
});


//html only
// Route::get('html-all-pages',function () {
//     return view('html.all-pages');
// })->name('html.all-pages');

// Route::get('html-members',function (){
//     return view('html.members');
// });
// Route::get('html-profile',function (){
//     return view('html.profile');
// });
// Route::get('html-edit-profile',function (){
//     return view('html.edit-profile');
// });
// Route::get('html-business-ideas',function (){
//     return view('html.business-ideas');
// });
// Route::get('html-job-portal',function (){
//     return view('html.job-portal');
// });
// Route::get('html-resources',function (){
//     return view('html.resources');
// });
// Route::get('html-contact-us',function (){
//     return view('html.contact-us');
// });
// Route::get('html-about-us',function (){
//     return view('html.about-us');
// });
// Route::get('html-faq',function (){
//     return view('html.faq');
// });
// Route::get('html-job-detail',function (){
//     return view('html.job-detail');
// });
// Route::get('html-community',function (){
//     return view('html.community');
// });
// Route::get('html-our-team',function (){
//     return view('html.our-team');
// });
// Route::get('html-blog',function (){
//     return view('html.blog');
// });
// Route::get('html-questions',function (){
//     return view('pages.questions');
// });
// Route::get('html-fund',function (){
//     return view('pages.fund');
// });
// Route::get('html-resources-new',function (){
//     return view('html.resources-new');
// });