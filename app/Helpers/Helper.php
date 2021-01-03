<?php

namespace App\Helpers;

use App;
use Validator;
use App\Models\UserProfile;
use Illuminate\Support\Str;
use App\User;
use Auth;
use App\Jobs\SendPushNotification;
use Illuminate\Support\Arr;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Image;
use Log;
use Route;
use Storage;
use File;
use Illuminate\Http\Request;
use stdClass;
use App\Http\Controllers\SendMailController;
use Carbon\CarbonPeriod;
use Mockery\Exception;
use Session;
use Illuminate\Http\Testing\MimeType;
use App\Models\PostJob;

class Helper
{
    // get full url of given path
    public static function assets($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
    public static function images($path, $secure = null)
    {
        return app('url')->asset($path)."/";
    }
    public static function uploaddynamicFile($path, $name, $data = null) {
        $path = public_path().$path;

        $data->move($path, $name);
    }
    public static function checkFileExists($path, $is_image = true, $is_deleted = false)
    {
        $return_path = $path;
        $path = public_path().$path;
        if (file_exists($path)) {
            if ($is_deleted) {
                unlink($path);
                return true;
            }
            return self::images($return_path);
        }
        if ($is_image) {
            return self::images($original_path . 'default.png');
        }
        return true;
    }
    public static function ageCalculate($dateOfBirth)
    {
        $years = Carbon::parse($dateOfBirth)->age;
        return ($years > 1) ? $years . " " . trans('page.Years') : $years . " " . trans('page.Year');
    }

    public static function staffAccessMenu($privileges = [], $currentroute = null)
    {
        if (is_null($currentroute)) {
            $currentroute = Route::currentRouteName();
        }
        $sideMenus = self::generateMenu();
        if (!empty($sideMenus)) {
            foreach ($sideMenus as $menu) {
                if (($menu['privilege_require'] == "0" || in_array($menu['privilege_key'], $privileges)) && in_array($currentroute, $menu['active_menu'])) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function generateMenu()
    {
        return array(
            "1" => array( // Dashboard
                "is_menu" => true,
                "url" => route('admin.index'),
                "is_access" => true,
                "privilege_key" => "1",
                "privilege_require" => "0",
                "full_title" => "Dashboard",
                "short_title" => "Dashboard",
                "icon" => "icon-home2",
                "active_menu" => array('admin.index'),
                "child" => array(),
            ),
            "2" => array( // user management
                "is_menu" => true,
                "url" => route('admin.user.index'),
                "is_access" => true,
                "privilege_key" => "2",
                "privilege_require" => "1",
                "full_title" => "Users",
                "short_title" => "Users",
                "icon" => "icon-users",
                "active_menu" => array('admin.user.index', 'admin.user.details'),
                "child" => array(),
            ),
            "3" => array( // entrepreneur management
                "is_menu" => true,
                "url" => route('admin.entrepreneur.index'),
                "is_access" => true,
                "privilege_key" => "3",
                "privilege_require" => "1",
                "full_title" => "Entrepreneur",
                "short_title" => "Entrepreneur",
                "icon" => "icon-collaboration",
                "active_menu" => array('admin.entrepreneur.index', 'admin.entrepreneur.details'),
                "child" => array(),

            ),
            "4" => array( // Job
                "is_menu" => TRUE,
                "url" => "javascript:;",
                "is_access" => TRUE,
                "privilege_key" => "4",
                "privilege_require" => "1",
                "full_title" => "Jobs Management",
                "short_title" => "Job",
                "icon" => "icon-graduation",
                "active_menu" => array('admin.job.pending','admin.job.active','admin.job.archived'),
                "child" => array(
                    "1" => array(
                        "is_menu" => TRUE,
                        "url" => route('admin.job.pending'),
                        "is_access" => TRUE,
                        "privilege_key" => "4",
                        "privilege_require" =>"1",
                        "full_title" => "Pending / Rejected",
                        "short_title" => "Pending / Rejected",
                        "icon" => "",
                        "active_menu" => array('admin.job.pending'),
                        "child" => array(),
                    ),
                    "2" => array(
                        "is_menu" => TRUE,
                        "url" => route('admin.job.active'),
                        "is_access" => TRUE,
                        "privilege_key" => "4",
                        "privilege_require" =>"1",
                        "full_title" => "Active",
                        "short_title" => "Active",
                        "icon" => "",
                        "active_menu" => array('admin.job.active'),
                        "child" => array(),
                    ),
                    "3" => array(
                        "is_menu" => TRUE,
                        "url" => route('admin.job.archived'),
                        "is_access" => TRUE,
                        "privilege_key" => "4",
                        "privilege_require" =>"1",
                        "full_title" => "Archived",
                        "short_title" => "Archived",
                        "icon" => "",
                        "active_menu" => array('admin.job.archived'),
                        "child" => array(),
                    ),
                ),
            ),
            "5" => array( // Staff
                "is_menu" => TRUE,
                "url" => '',//route('staff.index'),
                "is_access" => FALSE,
                "privilege_key" => "5",
                "privilege_require" => "1",
                "full_title" => "Staff Management",
                "short_title" => "Staff",
                "icon" => "icon-users4",
                "active_menu" => array(),//array('staff.index','staff.create','staff.edit'),
                "child" => array(),
            ),
            "6" => array( // Category (Ask a question)
                "is_menu" => TRUE,
                "url" => route('question-category.index'),
                "is_access" => TRUE,
                "privilege_key" => "6",
                "privilege_require" => "1",
                "full_title" => "Category Management (Ask Question)",
                "short_title" => "Categories (Ask Question)",
                "icon" => "icon-stack3",
                "active_menu" => array('question-category.index','question-category.create','question-category.edit'),
                "child" => array(),
            ),
            "7" => array( // Business Category
                "is_menu" => TRUE,
                "url" => route('business-category.index'),
                "is_access" => TRUE,
                "privilege_key" => "7",
                "privilege_require" => "1",
                "full_title" => "Business Category Management",
                "short_title" => "Business Category",
                "icon" => "icon-cogs",
                "active_menu" => array('business-category.index','business-category.create','business-category.edit'),
                "child" => array(),
            ),
            "8" => array( // Job Title
                "is_menu" => TRUE,
                "url" => route('job-title.index'),
                "is_access" => TRUE,
                "privilege_key" => "8",
                "privilege_require" => "1",
                "full_title" => "Job Title  Management",
                "short_title" => "Job Title",
                "icon" => "icon-graduation2",
                "active_menu" => array('job-title.index','job-title.create','job-title.edit'),
                "child" => array(),
            ),
            "9" => array( // Currency
                "is_menu" => TRUE,
                "url" => route('currency.index'),
                "is_access" => TRUE,
                "privilege_key" => "8",
                "privilege_require" => "1",
                "full_title" => "Currency  Management",
                "short_title" => "Currency",
                "icon" => "icon-coins",
                "active_menu" => array('currency.index','currency.create','currency.edit'),
                "child" => array(),
            ),
            "10" => array( // Questions
                "is_menu" => TRUE,
                "url" => route('profile-question.index'),
                "is_access" => TRUE,
                "privilege_key" => "10",
                "privilege_require" => "1",
                "full_title" => "Profile Questions",
                "short_title" => "Profile Questions",
                "icon" => "icon-question7",
                "active_menu" => array('profile-question.index','profile-question.create','profile-question.edit'),
                "child" => array(),
            ),
            "11" => array( // Ideas
                "is_menu" => TRUE,
                "url" => '',//route('job-title.index'),
                "is_access" => TRUE,
                "privilege_key" => "11",
                "privilege_require" => "1",
                "full_title" => "Ideas  Management",
                "short_title" => "Ideas",
                "icon" => "icon-folder-download",
                "active_menu" => array(),//array('job-title.index','job-title.create','job-title.edit'),
                "child" => array(),
            ),
            "12" => array( // Blog
                "is_menu" => TRUE,
                "url" => route('blog.index'),
                "is_access" => TRUE,
                "privilege_key" => "12",
                "privilege_require" => "1",
                "full_title" => "Blog Management",
                "short_title" => "Blog",
                "icon" => "icon-blog",
                "active_menu" => array('blog.index','blog.create','blog.edit'),
            ),
            "13" => array( // Resource
                "is_menu" => TRUE,
                "url" => route('resource.index'),
                "is_access" => TRUE,
                "privilege_key" => "132",
                "privilege_require" => "1",
                "full_title" => "Resource Management",
                "short_title" => "Resource",
                "icon" => "icon-blog",
                "active_menu" => array('resource.index','resource.create','resource.edit'),
            ),
            "14" => array( // Dynamic Email management
                "is_menu" => true,
                "url" => route('emails.index'),
                "is_access" => true,
                "privilege_key" => "14",
                "privilege_require" => "1",
                "full_title" => "Email Templates",
                "short_title" => "Email Templates",
                "icon" => "icon-envelop4",
                "active_menu" => array('emails.index', 'emails.create', 'emails.edit'),
                "child" => array(),
            ),
            "15" => array( // Dynamic Email management
                "is_menu" => true,
                "url" => route('faq.index'),
                "is_access" => true,
                "privilege_key" => "15",
                "privilege_require" => "1",
                "full_title" => "FAQ",
                "short_title" => "FAQ",
                "icon" => "icon-question7",
                "active_menu" => array('faq.index', 'faq.create', 'faq.edit'),
                "child" => array(),
            ),
            "16" => array( // Appointments
                "is_menu" => TRUE,
                "url" => '',//route('admin.settings'),
                "is_access" => TRUE,
                "privilege_key" => "16",
                "privilege_require" => "1",
                "full_title" => "Appointments",
                "short_title" => "Appointments",
                "icon" => "icon-cog2",
                "active_menu" => array(),//array('admin.settings'),
                "child" => array(),
            ),
        );
    }
    public static function explodeDate($date){
        $dates = [];
        $_date = str_replace(" ", "", $date);
        $_dateArr = explode('-',$_date);
        $from =  Carbon::createFromFormat('d/m/Y', $_dateArr[0])->format('Y-m-d');
        if(!isset($_dateArr[1])) $_dateArr[1] = $_dateArr[0];
        $to =  Carbon::createFromFormat('d/m/Y', $_dateArr[1])->format('Y-m-d');

        $dates['from'] = $from;
        $dates['to'] = $to;

        return $dates;
    }
    /**
     * concert array to object recursivly
     * @param #array Array
     * @return Object
     */
    public static function array_to_object($array)
    {
        $obj = new stdClass;
        foreach ($array as $k => $v) {
            if (strlen($k)) {
                if (is_array($v)) {
                    $obj->{$k} = self::array_to_object($v); //RECURSION
                } else {
                    $obj->{$k} = $v;
                }
            }
        }
        return $obj;
    }
    public static function timeAgo($timestamp){
  
        $time_ago        = strtotime($timestamp);
        $current_time    = time();
        $time_difference = $current_time - $time_ago;
        $seconds         = $time_difference;
        
        $minutes = round($seconds / 60); // value 60 is seconds  
        $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
        $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;  
        $weeks   = round($seconds / 604800); // 7*24*60*60;  
        $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
        $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
            
        
        if ($seconds <= 60){

            return "Just Now";

        } else if ($minutes <= 60){

            if ($minutes == 1){

            return "one minute ago";

            } else {

            return "$minutes minutes ago";

            }

        } else if ($hours <= 24){

            if ($hours == 1){

            return "an hour ago";

            } else {

            return "$hours hrs ago";

            }

        }
        
        else if ($days <= 7){

            if ($days == 1){

            return "yesterday";

            } else {

            return "$days days ago";

            }

        } else if ($weeks <= 4.3){

            if ($weeks == 1){

            return "a week ago";

            } else {

            return "$weeks weeks ago";

            }

        } else if ($months <= 12){

            if ($months == 1){

            return "a month ago";

            } else {

            return "$months months ago";

            }

        } else {
            
            if ($years == 1){

            return "one year ago";

            } else {

            return "$years years ago";

            }
        }

        // else{
        //     // if(1){
        //         $rDate =  Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone(env('APP_TIMEZONE'))->format('M d Y');
        //         $rDate .=  ' at '.Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone(env('APP_TIMEZONE'))->format('h:i A');
        //     // }else{
        //     //     $rDate =  Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format('M d Y');
        //     //     $rDate .=  ' at '.Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format('h:i A');
        //     // }
        //     return $rDate;
        // }
    }
    public static function userProfile($slug){
        $profile = User::where('slug',$slug)->where('is_profile_filled',1)
            ->select('id','name','logo','is_profile_filled','slug','email','type','is_active')
            ->with([
                'userProfile',
            ])
            ->first();

        return $profile;
    }
    public static function getJobData($user_id = null, $job_id = null, $job_status = null, $all = true, $paginate = null) {
        $selectedFields = ['*'];
        if(is_null($job_id)){
            $selectedFields = ['id','user_id','job_title_id','job_type','other_job_title','job_type_id','job_unique_id','job_status','location','job_count','created_at'];
        }

        $data = PostJob::select($selectedFields)->with([
            
            'jobTitle'=> function($query){

            },
            'currency'=> function($query){

            }
        ])->has('user');

        if(!is_null($job_id)){
            $data->where(function ($Query1) use ($job_id) {
                $Query1->where('id',$job_id);
                $Query1->orWhere('job_unique_id',$job_id);
            });
        }

        if(!is_null($job_status)){
            $data->where('job_status',$job_status);
        }

        if(!is_null($user_id)){
            $data->where('user_id',$user_id);
        }

        $data = $data->orderBy('id','DESC');

        if($all === false){
            return $data->first();
        }

        if(!is_null($paginate)){
            return $data->paginate($paginate);
        }
        return $data->get();
    }
}
