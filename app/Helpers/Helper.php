<?php

namespace App\Helpers;

use App;
use Validator;
use App\Models\City;
use App\Models\CmsPages;
use App\Models\Country;
use App\Models\DanceType;
use App\Models\EventType;
use App\Models\UserProfile;
use App\Models\NotificationUserType;
use Illuminate\Support\Facades\Redis;
use App\Models\ProfessionalType;
use App\Models\MetaNotification;
use App\Models\ChallengeEntry;
use App\Models\Challenge;
use App\Models\EntryHashtag;
use App\Models\HashTag;
use App\Models\Event;
use App\Models\NotificationReceiver;
use App\Models\MessageReceiver;
use App\Models\Feed;
use App\Models\FeedImage;
use App\Models\FeedLike;
use App\Models\FeedWith;
use App\Models\FeedComment;
use Illuminate\Support\Str;
use App\Models\WalletLog;
use App\User;
use Auth;
use App\Jobs\SendPushNotification;
use App\Models\Notification;
use App\Models\NotificationSettings;
use Illuminate\Support\Arr;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use Image;
use Log;
use Route;
use Storage;
use App\Models\UserGallery;
use App\Models\EventGallery;
use App\Models\Setting;
use App\Models\SponsorEvent;
use File;
use Illuminate\Http\Request;
use App\Models\EventBooking;
use App\Models\EventBookingTicketType;
use App\Models\EventAttendee;
use App\Models\EntryVote;
use stdClass;
use App\Http\Controllers\SendMailController;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use App\Jobs\ImageMoveDraftToOriginalDestination;
use App\Jobs\VideoMoveDraftToOrignalDestination;
use App\Jobs\VideoCompression;
use App\Models\UserFollower;
use Carbon\CarbonPeriod;
use Mockery\Exception;
use Session;
use Illuminate\Http\Testing\MimeType;
use App\Events\EntryVote as EntryVoteSocket;
use App\Events\LikePostEvent as LikePostEventSocket;
use App\Events\DeletePostEvent as DeletePostEventSocket;
use App\Events\DeletePostCommentEvent as DeletePostCommentEventSocket;
use App\Events\CommentPostEvent as CommentPostEventSocket;
use App\Events\AddPostEvent as AddPostEventSocket;
use App\Events\EditPostEvent as EditPostEventSocket;
use FFMpeg\Coordinate\Dimension;
use Illuminate\Console\Scheduling\Schedule;



class Helper
{
    // get full url of given path
    public static function assets($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
    public static function images($path, $secure = null)
    {
        $url = Storage::disk('s3')->url("/");
            $url = rtrim($url, '/');
            return $url . '/' . $path;

        // if (env('APP_ENV') != "local") {
        //     $url = Storage::disk('s3')->url("/");
        //     $url = rtrim($url, '/');
        //     return $url . '/' . $path;
        // } else {
        //     $path = str_replace('local', '', $path);
        //     /*
        //      * change by kaushik ::When we give path of static image than "//" issue occured thats why
        //      * dothis "#/#" and replace this, if we do direct replace"//" with "/" than http:// also effected thats why
        //      * */
        //     $path = app('url')->asset($path, $secure) . '#/#';

        //     $path = str_replace('#/#', '/', str_replace('/#/#', '/', $path));
        //     return $path;
        // }
    }

    public static function checkFileExists($path, $is_image = true, $is_deleted = false)
    {
        $original_path = config('constant.profile_url');
        // if (env('APP_ENV') != "local") {
            if (Storage::disk('s3')->exists($path)) {
                $original_path = config('constant.profile_url');
                if ($is_deleted) {
                    Storage::disk('s3')->delete($path);
                    return true;
                }
                return self::images($path);
            }
        // } else {
        //     $return_path = str_replace('local', '', $path);
        //     $original_path = str_replace('local', '', config('constant.profile_url'));
        //     $path = str_replace('local', public_path(), $path);
        //     if (file_exists($path)) {
        //         if ($is_deleted) {
        //             unlink($path);
        //             return true;
        //         }
        //         return self::images($return_path);
        //     }
        // }
        if ($is_image) {
            return self::images($original_path . 'default.png');
        }
        return true;
    }
    public static function uploaddynamicFile($path, $name, $data = null, $thumbnail = false, $thumbnail_path = '')
    {
            Storage::disk('s3')->put($path . $name, file_get_contents($data), 'public');
            if ($thumbnail) {
                $imgDdata = Storage::disk('s3')->get($path . $name);
                $thumbnailImage = Image::make($imgDdata)->resize(config('constant.thumbnail_image_width'), config('constant.thumbnail_image_height'), function ($constraint) {
            //                $constraint->aspectRatio();
                    $constraint->upsize();
                })->stream();
                $thumbnail_path = $thumbnail_path . $name;
                Storage::disk('s3')->put($thumbnail_path, $thumbnailImage->__toString(), 'public');
            }

        // if (env('APP_ENV') != "local") {
//             if($thumbnail){
//                 $thumbnailImage = Image::make($data->getRealPath())->resize(config('constant.thumbnail_image_width'), config('constant.thumbnail_image_height'), function ($constraint) {
// //                $constraint->aspectRatio();
//                     $constraint->upsize();
//                 })->stream();
//                 $thumbnail_path = $thumbnail_path . $name;
//                 Storage::disk('s3')->put($thumbnail_path, $thumbnailImage->__toString(), 'public');
//             }
//             Storage::disk('s3')->put($path . $name, file_get_contents($data), 'public');
//         } else {
//             if($thumbnail){
                
//                 $thumbnailImage = Image::make($data->getRealPath())->resize(config('constant.thumbnail_image_width'), config('constant.thumbnail_image_height'), function ($constraint) {
// //                $constraint->aspectRatio();
//                     $constraint->upsize();
//                 })->stream();
//                 $thumbnail_path = $thumbnail_path . $name;
//                 $thumbnail_path = str_replace('local', public_path(), $thumbnail_path);
//                 $thumbnailImage->save($thumbnail_path);
//             }

//             $path = str_replace('local', public_path(), $path);
//             $data->move($path, $name);
//         }
    }

    public static function uploadEncodedDynamicFile($path, $name, $data = null, $thumbnail = false, $thumbnail_path = '')
    {
        //TODO: check Condition when do uploadin dev or live
        // if (env('APP_ENV') != "local") {
            Storage::disk('s3')->put($path . $name, $data, 'public');
            if ($thumbnail) {
                $thumbnailImage = Image::make($data)->resize(config('constant.thumbnail_image_width'), config('constant.thumbnail_image_height'), function ($constraint) {
                    $constraint->upsize();
                })->stream();
                $thumbnail_path = $thumbnail_path . $name;
                //Above not work than use this
                Storage::disk('s3')->put($thumbnail_path, $thumbnailImage->__toString(), 'public');
            }
        // } else {
        //     $path = str_replace('local', public_path(), $path);
        //     file_put_contents($path . $name, $data);

        //     if ($thumbnail) {
        //         $thumbnailImage = Image::make($data)->resize(config('constant.thumbnail_image_width'), config('constant.thumbnail_image_height'), function ($constraint) {
        //             $constraint->upsize();
        //         });
        //         $thumbnail_path = $thumbnail_path . $name;

        //         $thumbnail_path = str_replace('local', public_path(), $thumbnail_path);
        //         $thumbnailImage->save($thumbnail_path);
        //     }
        // }
    }

    public static function copyDynamicImage($newpath, $oldpath)
    {
        if (env('APP_ENV') != "local") {
            $oldlink = Storage::disk('s3')->url($oldpath);
            Storage::disk('s3')->put($newpath, file_get_contents($oldlink), 'public');
        } else {
            copy($newpath, $oldpath);
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

    /**
     * Getting static page data from database for displaying content at front side
     */

    public static function get_page_contents($slug = null)
    {

        if (is_null($slug)) {
            $slug = Route::currentRouteName();
        }

        $cms = CmsPages::where('slug', $slug)->first();
        $cms = json_decode($cms->contents, true);
        return $cms;
    }

    public static function generateMenu()
    {
        // return array(
        //     "1" => array( // Dashboard
        //         "is_menu" => true,
        //         "url" => route('admin.index'),
        //         "is_access" => true,
        //         "privilege_key" => "1",
        //         "privilege_require" => "0",
        //         "full_title" => "Dashboard",
        //         "short_title" => "Dashboard",
        //         "icon" => "icon-home2",
        //         "active_menu" => array('admin.index'),
        //         "child" => array(),
        //     ),
        //     "2" => array( // Dancer management
        //         "is_menu" => true,
        //         "url" => route('admin.dancer.index'),
        //         "is_access" => true,
        //         "privilege_key" => "2",
        //         "privilege_require" => "1",
        //         "full_title" => "Dancers",
        //         "short_title" => "Dancers",
        //         "icon" => "icon-speakers",
        //         "active_menu" => array('admin.dancer.index', 'admin.dancer.details',(Str::contains(url()->previous(), 'dancer-details') == true) ? 'admin.view.order.summary' : ''),
        //         "child" => array(),
        //     ),
        //     "3" => array( // Professional management
        //         "is_menu" => true,
        //         "url" => route('admin.professional.index'),
        //         "is_access" => true,
        //         "privilege_key" => "3",
        //         "privilege_require" => "1",
        //         "full_title" => "Professionals",
        //         "short_title" => "Professionals",
        //         "icon" => "icon-chess-king",
        //         "active_menu" => array('admin.professional.index', 'admin.professional.details',(Str::contains(url()->previous(), 'professional-details') == true) ? 'admin.view.order.summary' : ''),
        //         "child" => array(),

        //     ),
        //     "4" => array( // Events management
        //         "is_menu" => TRUE,
        //         "url" => route('admin.events.index'),
        //         "is_access" => TRUE,
        //         "privilege_key" => "4",
        //         "privilege_require" => "1",
        //         "full_title" => "Events",
        //         "short_title" => "Events",
        //         "icon" => "icon-film",
        //         "active_menu" => array('admin.events.index', 'admin.events.details'),
        //         "child" => array(),
        //     ),
        //     "5" => array( // Sponsred Events management
        //         "is_menu" => TRUE,
        //         "url" => route('admin.event.sponsor.index'),
        //         "is_access" => TRUE,
        //         "privilege_key" => "5",
        //         "privilege_require" => "1",
        //         "full_title" => "Sponsored Events",
        //         "short_title" => "Sponsored Events",
        //         "icon" => "icon-book",
        //         "active_menu" => array('admin.event.sponsor.index', 'admin.events.sponsor.details'),
        //         "child" => array(),
        //     ),
        //     "6" => array( // Feed management
        //         "is_menu" => TRUE,
        //         "url" => route('admin.feeds.index'),
        //         "is_access" => TRUE,
        //         "privilege_key" => "6",
        //         "privilege_require" => "1",
        //         "full_title" => "Feed",
        //         "short_title" => "Feed",
        //         "icon" => "icon-instagram",
        //         "active_menu" => array('admin.feeds.index', 'admin.feeds.details','admin.feeds.edit'),
        //         "child" => array(),
        //     ),
        //     "7" => array( // Contests management
        //         "is_menu" => TRUE,
        //         "url" => route('admin.challenge.index'),
        //         "is_access" => TRUE,
        //         "privilege_key" => "7",
        //         "privilege_require" => "1",
        //         "full_title" => "Contests",
        //         "short_title" => "Contests",
        //         "icon" => "icon-trophy2",
        //         "active_menu" => array('admin.challenge.index', 'admin.challenge.detail','admin.challenge.create','admin.challenge.view','admin.entry.view'),
        //         "child" => array(),
        //     ),
        //     "9" => array( // Payment Withdrawals management
        //         "is_menu" => true,
        //         "url" => route('admin.paymentwithdrawals.index'),
        //         "is_access" => true,
        //         "privilege_key" => "9",
        //         "privilege_require" => "1",
        //         "full_title" => "Payment Withdrawals",
        //         "short_title" => "Payment Withdrawals",
        //         "icon" => "icon-coin-dollar",
        //         "active_menu" => array('admin.paymentwithdrawals.index', 'admin.paymentwithdrawals.detail'),
        //         "child" => array(),
        //     ),
        //     "10" => array( // Payment Log management
        //         "is_menu" => true,
        //         "url" => route('admin.payment.index'),
        //         "is_access" => true,
        //         "privilege_key" => "10",
        //         "privilege_require" => "1",
        //         "full_title" => "Payment Log",
        //         "short_title" => "Payment Log",
        //         "icon" => "icon-paypal",
        //         "active_menu" => array('admin.payment.index', 'admin.payment.detail'),
        //         "child" => array(),
        //     ),
        //     "11" => array( // Payment Settings management
        //         "is_menu" => true,
        //         "url" => route('admin.payment.settings'),
        //         "is_access" => true,
        //         "privilege_key" => "11",
        //         "privilege_require" => "1",
        //         "full_title" => "Payment Settings",
        //         "short_title" => "Payment Settings",
        //         "icon" => "icon-cog2",
        //         "active_menu" => array('admin.payment.settings'),
        //         "child" => array(),
        //     ),
        //     "12" => array( // Send Notification management
        //         "is_menu" => TRUE,
        //         "url" => route('admin.notification.index'),
        //         "is_access" => TRUE,
        //         "privilege_key" => "12",
        //         "privilege_require" => "1",
        //         "full_title" => "Send Notification",
        //         "short_title" => "Send Notification",
        //         "icon" => "icon-book",
        //         "active_menu" => array('admin.notification.index', 'admin.notification.detail'),
        //         "child" => array(),
        //     ),
        //     "13" => array( // Staff management
        //         "is_menu" => true,
        //         "url" => route('admin.staff.index'),
        //         "is_access" => false,
        //         "privilege_key" => "13",
        //         "privilege_require" => "1",
        //         "full_title" => "Staff Management",
        //         "short_title" => "Staff Management",
        //         "icon" => "icon-users4",
        //         "active_menu" => array('admin.staff.index', 'admin.staff.create'),
        //         "child" => array(),
        //     ),
        //     "14" => array( // Event types management
        //         "is_menu" => true,
        //         "url" => route('event-type.index'),
        //         "is_access" => true,
        //         "privilege_key" => "14",
        //         "privilege_require" => "1",
        //         "full_title" => "Event Types",
        //         "short_title" => "Event Types",
        //         "icon" => "icon-stack-music",
        //         "active_menu" => array('event-type.index', 'event-type.detail'),
        //         "child" => array(),
        //     ),
        //     "15" => array( // Professional types management
        //         "is_menu" => true,
        //         "url" => route('professional-type.index'),
        //         "is_access" => true,
        //         "privilege_key" => "15",
        //         "privilege_require" => "1",
        //         "full_title" => "Professional types",
        //         "short_title" => "Professional types",
        //         "icon" => "icon-stack-star",
        //         "active_menu" => array('professional-type.index', 'professional-type.detail'),
        //         "child" => array(),
        //     ),
        //     "16" => array( // Dance/Music types management
        //         "is_menu" => true,
        //         "url" => route('dance-type.index'),
        //         "is_access" => true,
        //         "privilege_key" => "16",
        //         "privilege_require" => "1",
        //         "full_title" => "Dance/Music Types",
        //         "short_title" => "Dance/Music Types",
        //         "icon" => "icon-album",
        //         "active_menu" => array('dance-type.index', 'dance-type.detail'),
        //         "child" => array(),
        //     ),
        //     "17" => array( // Report Management
        //         "is_menu" => true,
        //         "url" => "javascript:;",
        //         "is_access" => true,
        //         "privilege_key" => "17",
        //         "privilege_require" => "1",
        //         "full_title" => "Reporting",
        //         "short_title" => "Reporting",
        //         "icon" => "icon-graduation",
        //         "active_menu" => array('admin.report.feeds', 'admin.report.events', 'admin.report.professionals'),
        //         "child" => array(
        //             "1" => array(
        //                 "is_menu" => true,
        //                 "url" => route('admin.report.feeds'),
        //                 "is_access" => true,
        //                 "privilege_key" => "17",
        //                 "privilege_require" => "1",
        //                 "full_title" => "Feeds",
        //                 "short_title" => "Feeds",
        //                 "icon" => "",
        //                 "active_menu" => array('admin.report.feeds'),
        //                 "child" => array(),
        //             ),
        //             "2" => array(
        //                 "is_menu" => true,
        //                 "url" => route('admin.report.events'),
        //                 "is_access" => true,
        //                 "privilege_key" => "17",
        //                 "privilege_require" => "2",
        //                 "full_title" => "Events",
        //                 "short_title" => "Events",
        //                 "icon" => "",
        //                 "active_menu" => array('admin.report.events'),
        //                 "child" => array(),
        //             ),
        //             "3" => array(
        //                 "is_menu" => true,
        //                 "url" => route('admin.report.professionals'),
        //                 "is_access" => true,
        //                 "privilege_key" => "17",
        //                 "privilege_require" => "3",
        //                 "full_title" => "Professionals",
        //                 "short_title" => "Professionals",
        //                 "icon" => "",
        //                 "active_menu" => array('admin.report.professionals'),
        //                 "child" => array(),
        //             ),
        //         ),
        //     ),
        //     "18" => array( // Dynamic Email management
        //         "is_menu" => true,
        //         "url" => route('emails.index'),
        //         "is_access" => true,
        //         "privilege_key" => "18",
        //         "privilege_require" => "1",
        //         "full_title" => "Email Templates",
        //         "short_title" => "Email Templates",
        //         "icon" => "icon-envelop4",
        //         "active_menu" => array('emails.index', 'emails.create', 'emails.edit'),
        //         "child" => array(),
        //     ),
        //     "19" => array( // CMS management
        //         "is_menu" => true,
        //         "url" => route('pages_cms'),
        //         "is_access" => true,
        //         "privilege_key" => "19",
        //         "privilege_require" => "1",
        //         "full_title" => "CMS Management",
        //         "short_title" => "CMS Pages",
        //         "icon" => "icon-book3",
        //         "active_menu" => array('pages_cms', 'edit_pages_cms'),
        //         "child" => array(),
        //     ),
        //     "20" => array( // FAQ management
        //         "is_menu" => true,
        //         "url" => route('faq.index'),
        //         "is_access" => true,
        //         "privilege_key" => "20",
        //         "privilege_require" => "1",
        //         "full_title" => "FAQ Management",
        //         "short_title" => "FAQ",
        //         "icon" => "icon-question7",
        //         "active_menu" => array('faq.index', 'faq.create', 'faq.edit'),
        //         "child" => array(),
        //     ),
        //     "21" => array( // FAQ management
        //         "is_menu" => true,
        //         "url" => route('admin.crash.index'),
        //         "is_access" => true,
        //         "privilege_key" => "21",
        //         "privilege_require" => "1",
        //         "full_title" => "Crash Error",
        //         "short_title" => "Crash Error",
        //         "icon" => "icon-exclamation",
        //         "active_menu" => array('admin.crash.index'),
        //         "child" => array(),
        //     ),
        // );
    }
    /**
     * This function use for get dance/music type with diffrent condition
     * @param array $condition
     * @return mixed
     */
    public static function getDanceMusicTypes($condition = array(), $device = 'web', $whereIn = array())
    {
        //This for common condition, if any where condition is not true than this condition set
        $where[] = array('id', '!=', 0);
        if (isset($condition['active'])) {
            $where['status'] = 1;
        }

        if (isset($condition['inactive'])) {
            $where['status'] = 0;
        }

        $select = '*';
        if ($device == 'mobile') {
            $select = ['id', 'title', 'src as other'];
        }

        $danceTypes = DanceType::select($select)
            ->where($where);

        if (isset($condition['deleted'])) {
            $danceTypes->withTrashed();
        }

        if(!empty($whereIn)){
            $danceTypes = $danceTypes->whereIn('id', $whereIn);
        }

        return $danceTypes->get();
    }

    /**
     * This function use for get dance/music type with diffrent condition
     * @param array $condition
     * @return mixed
     */
    public static function getProfessionalTypes($condition = array(), $device = 'web', $whereIn = array())
    {

        //This for common condition, if any where condition is not true than this condition set
        $where[] = array('id', '!=', 0);
        if (isset($condition['active'])) {
            $where['status'] = 1;
        }

        if (isset($condition['inactive'])) {
            $where['status'] = 0;
        }

        if (isset($condition['deleted'])) {
            $where[] = array('deleted_at', '!=', null);
        } else {
            $where['deleted_at'] = null;
        }

        if ($device == 'mobile') {
            $select = ['id', 'title', 'src as other'];
        } else {
            $select = '*';
        }

        $professionalTypes = ProfessionalType::select($select)
            ->where($where);
        if(!empty($whereIn)){
            $professionalTypes = $professionalTypes->whereIn('id', $whereIn);
        }
        return $professionalTypes->get();
        
    }

    /**
     * This function use for get dance/music type with diffrent condition
     * @param array $condition
     * @return mixed
     */
    public static function getEventTypes($condition = array(), $device = 'web')
    {
        //This for common condition, if any where condition is not true than this condition set
        $where[] = array('id', '!=', 0);
        if (isset($condition['active'])) {
            $where['status'] = 1;
        }

        if (isset($condition['inactive'])) {
            $where['status'] = 0;
        }

        if (isset($condition['deleted'])) {
            $where[] = array('deleted_at', '!=', null);
        } else {
            $where['deleted_at'] = null;
        }

        if ($device == 'mobile') {
            $select = ['id', 'title', 'src as other'];
        } else {
            $select = '*';
        }

        $danceTypes = EventType::select($select)
            ->where($where)
            ->get();
        return $danceTypes;
    }

    public static function getWalletLogs($user_id, $paginate, $device = 'web')
    {
        if (!is_null($paginate)) {
            $data = WalletLog::select('id', 'user_id', 'amount', 'type', 'status', 'created_at', 'updated_at','description')->where('user_id', $user_id)
                ->with('userProfile:id,user_id,wallet_unique_id');
            if ($device == 'web') {
                return $data->orderBy('id', 'desc')->paginate($paginate['page_size']);
            } else {
                $page_size = 10;
                $page_count = 1;
                if (isset($paginate['page_size']) && !empty($paginate['page_size'])) {
                    $page_size = $paginate['page_size'];
                }
                if (isset($paginate['page_count']) && !empty($paginate['page_count'])) {
                    $page_count = $paginate['page_count'];
                }
                $offset = ($page_count - 1) * $page_size;
                $data->offset($offset);
                $data->limit($page_size);
            }
            return $data->orderBy('id','asc')->get();
        }
    }
    /**
     * This function use for get all country
     * @return mixed
     */
    public static function getCountry(){
        $Country = DB::table('events as evt')
            ->select(DB::raw("(cr.name)  AS title, (cr.id) AS id"))
            ->join('countries as cr', function ($join) {
                $join->on('evt.country_id', '=', 'cr.id')
                    ->where('country_id', '!=', null);
            })->where('evt.event_status',1)->groupBy('cr.name')->get();
        return $Country;
    }
    /**
     * This function use for get all country
     * @param array $condition
     * @return mixed
     */
    public static function getCity($condition = array())
    {
        $cacheName = 'cities';
        $cacheName .= (isset($condition['country_id'])) ? 'C_' . $condition['country_id'] : 'C_0';
        $cacheName .= (isset($condition['state_id'])) ? 'S_' . $condition['state_id'] : 'S_0';

        // $value = Cache::rememberForever($cacheName, function () use ($condition) {
            //This for common condition, if any where condition is not true than this condition set
            $where[] = array('id', '!=', 0);
            if (isset($condition['country_id'])) {
                $where['country_id'] = $condition['country_id'];
            }

            if (isset($condition['state_id'])) {
                $where['state_id'] = $condition['state_id'];
            }

            $value =  City::select('id', 'name as title', 'country_id as other')
                ->where($where)
                ->get();
        // });
        return $value;
    }

    /**
     * Get uniq city and country of professional(old)
     * @return Collection
     */
    public static function getProfessionalUniqueCityCountry()
    {

        $where['u.type'] = config('constant.USER.TYPE.PROFESSIONAL');
        $where['u.is_active'] = config('constant.USER.STATUS.Active');
        $where['u.deleted_at'] = null;

        $cityCountry = DB::table('user_profiles as up')
            ->select(DB::raw("CONCAT_WS(', ',ct.name,cr.name)  AS location"))
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'up.user_id');
            })
            ->leftJoin('countries as cr', function ($join) {
                $join->on('up.country_id', '=', 'cr.id')->where('country_id', '!=', null);
            })
            ->leftJoin('cities as ct', function ($join) {
                $join->on('up.city_id', '=', 'ct.id')->where('city_id', '!=', null);
            })
            ->groupBy('location')
            ->where('up.city_id', '!=', null)
            ->where('up.country_id', '!=', null)
            ->where($where);

        if (Auth::check()) {
            $cityCountry = $cityCountry->where('up.user_id', '!=', Auth::user()->id);
        }
        $cityCountry = $cityCountry->get()
            ->toArray();

        return $cityCountry;
    }

    public static function getEventCountry($request){
        $Country = DB::table('events as evt')
            ->select(DB::raw("(cr.name)  AS title, (cr.name) AS id"))
            ->join('countries as cr', function ($join) use ($request) {
                $join->on('evt.country_id', '=', 'cr.id')
                    ->where('country_id', '!=', null);
                if ($request->search != '') {
                    $join->where('cr.name', 'like', $request->search . '%');
                }

            })
            ->groupBy('cr.id')
            ->where('evt.country_id', '!=', null)
            ->where('evt.event_status',1);

        $data = $Country->paginate(config('constant.rpp'))
            ->toJson();
        $data = json_decode($data);

        $response = array();
        $response['results'] = $data->data;
        $response['total_count'] = $data->total;
        return $response;
    }

    public static function getEventCity($request){

        $Cities = DB::table('events as evt')
            ->select(DB::raw("(ct.name) AS title,(ct.name) AS id"))
            ->join('cities as ct', function ($join) use ($request) {
                $join->on('evt.city_id', '=', 'ct.id')
                    ->where('city_id', '!=', null);
                if ($request->search != '') {
                    $join->where('ct.name', 'like', $request->search . '%');
                }

            })
            ->groupBy('ct.id')
            ->where(['event_status' => 1])
            ->where('evt.city_id', '!=', null);


        if ($request->countries != '') {
            $Cities->where('evt.country_id', function ($q) use ($request) {
                $q->select('id')->from('countries')->where('name', $request->countries);
            });
        } else if ($request->id > 0) {
            $Cities->where('evt.country_id', '=', $request->id);
        }

        $data = $Cities->paginate(config('constant.rpp'))
            ->toJson();
        $data = json_decode($data);

        $response = array();
        $response['results'] = $data->data;
        $response['total_count'] = $data->total;
        return $response;
    }

    /**
     * This api use for get uniq professional country,
     * this api use in WEB Home page search professional tab display filtered country with infinity scroll
     * and search professional page in web
     * @param $request
     * @return array
     */
    public static function getProfessionalCountry($request)
    {
        if($request->page_sponsor == 'false'){
            $where['u.type'] = config('constant.USER.TYPE.PROFESSIONAL');
        }
        $where['u.is_active'] = config('constant.USER.STATUS.Active');
        $where['u.deleted_at'] = null;

        $Country = DB::table('user_profiles as up')
            ->select(DB::raw("(cr.name)  AS title, (cr.name) AS id"))
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'up.user_id');
            })
            ->join('countries as cr', function ($join) use ($request) {
                $join->on('up.country_id', '=', 'cr.id')
                    ->where('country_id', '!=', null);
                if ($request->search != '') {
                    $join->where('cr.name', 'like', $request->search . '%');
                }

            })
            ->groupBy('cr.id')
            ->where('up.country_id', '!=', null)
            ->where($where);

            if($request->page_sponsor == 'true'){
                $Country->whereIn('u.type',[config('constant.USER.TYPE.DANCER'), config('constant.USER.TYPE.PROFESSIONAL')]);
            }

        $data = $Country->paginate(config('constant.rpp'))
            ->toJson();
        $data = json_decode($data);

        $response = array();
        $response['results'] = $data->data;
        $response['total_count'] = $data->total;
        return $response;
    }

    /**
     * This api use for get uniq professional country,
     * this api use in WEB Home page search professional tab display filtered city with infinity scroll
     * * and search professional page in web
     * @param $request
     * @return array
     */
    public static function getProfessionalCity($request)
    {
        if($request->page_sponsor == 'false'){
            $where['u.type'] = config('constant.USER.TYPE.PROFESSIONAL');
        }
        $where['u.is_active'] = config('constant.USER.STATUS.Active');
        $where['u.deleted_at'] = null;

        $Cities = DB::table('user_profiles as up')
            ->select(DB::raw("(ct.name) AS title,(ct.name) AS id"))
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'up.user_id');
            })
            ->join('cities as ct', function ($join) use ($request) {
                $join->on('up.city_id', '=', 'ct.id')
                    ->where('city_id', '!=', null);
                if ($request->search != '') {
                    $join->where('ct.name', 'like', $request->search . '%');
                }

            })
            ->groupBy('ct.id')
            ->where('up.city_id', '!=', null)

            ->where($where);
        if ($request->countries != '') {
            $Cities->where('up.country_id', function ($q) use ($request) {
                $q->select('id')->from('countries')->where('name', $request->countries);
            });
        } else if ($request->id > 0) {
            $Cities->where('up.country_id', '=', $request->id);
        }

        $data = $Cities->paginate(config('constant.rpp'))
            ->toJson();
        $data = json_decode($data);

        $response = array();
        $response['results'] = $data->data;
        $response['total_count'] = $data->total;
        return $response;
    }
    public static function sendNotification($request, $data = NULL){
        try{ 
            // Required parameters receiver_id, meta_notification_id, sender_id
            // Required parameters data,
            //Receiver id would be i.e => '2,5,6'
            //data parameter is contain json encoded dynamically, like if event notification then data = {event_id:1,event_slug:'annual-meet',event_name:'Annual Meet',user_id:2,user_slug:'avani-dave',user_name:'Avani Dave'}

            $receiver_id = array();
            DB::beginTransaction();

            if(isset($request->receiver_id) && !empty($request->receiver_id)){
                $receiver_id = explode(',',$request->receiver_id);
            }
            
            $notificationMessage = MetaNotification::select('description')->where('id',$request->meta_notification_id)->first();
            $description = $notificationMessage->description;
            if(isset($request->meta_notification_id) && $request->meta_notification_id == 2 && isset($request->cust_desc)){
                $description = $request->description;
            }
            
            // echo "<pre>";print_r($description);exit;
            $message = "";
            if($data == NULL){
                $message = $request->description;
                // dd($message);
            }else{
                $message = self::dynamicMessage($description,$request->meta_notification_id,json_decode($data));
            }
            // dd($message);
            $Notification = Notification::Create(['meta_notification_id' => $request->meta_notification_id, 'sender_id' => $request->sender_id,'message' => $message,'data' => $data]);
            $data = json_decode($data);
            // echo "<pre>"; print_r($data);exit;

            // $notification_receivers = [];
            if(isset($receiver_id) && !empty($receiver_id)){
                foreach($receiver_id as $key => $value){
                    $notification_receivers[] = ['notification_id' => $Notification->id,'recevier_id' => $value, 'status' => 0, 'created_at' => Carbon::now()];
                    // dd($notification_receivers);
                }
                
                NotificationReceiver::insert($notification_receivers);
            }

            
            if(isset($receiver_id) && !empty($receiver_id)){
                dispatch(new SendPushNotification($receiver_id, $message, $request->title,  $request->meta_notification_id, $request->sender_id, $data, $Notification->id))->onQueue('notifications');
            }

            DB::commit();
            return true;
        } catch(Exception $e){
            // \Log::info('notification:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return false;
        }
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

    public static function dynamicMessage($message,$metaNotificationId,$data){
        if($metaNotificationId){
            switch($metaNotificationId){
                case 1 : //When person you follow posts in challenge
                    $message = str_replace('{user_name}', $data->user_name, $message);
                    $message = str_replace('{challenge_name}', $data->name, $message);
                    break;
                case 2 : //When person you follow posts in feed
                    if(isset($data->user_name))
                            $message = str_replace('{user_name}', $data->user_name, $message);
                    // $message = str_replace('{feed_name}', $data->name, $message);
                    break;
                case 3 : //When person you follow creates an event
                    $message = str_replace('{user_name}', $data->user_name, $message);
                    $message = str_replace('{action}', $data->action, $message);
                    $message = str_replace('{event_name}', $data->name, $message);
                    break;
                case 4 :  //When you buy tickets for any event
                    $message = str_replace('{event_name}', $data->name, $message);
                    $message = str_replace('{booking_id}', $data->booking_id, $message);
                    break;
                case 5 : //When you cancel your booking
                    $message = str_replace('{booking_id}', $data->booking_id, $message);
                    $message = str_replace('{event_name}', $data->name, $message);
                    break;
                case 6 : //When event is cancelled
                    $message = str_replace('{event_name}', $data->name, $message);
                    break;
                case 7 : //Money added to wallet
                    $message = str_replace('{user}', $data->user, $message);
                    $message = str_replace('{amount}', $data->amount, $message);
                    break;
                case 8 : //Money withdrawn from wallet
                    $message = str_replace('{amount}', $data->amount, $message);
                    break;
                case 9 : //Entry approved for any contest
                    $message = str_replace('{entry_name}', $data->name, $message);
                    $message = str_replace('{contest_name}', $data->challenge_name, $message);
                    break;
                case 10 : //Entry rejected for any contest
                    $message = str_replace('{entry_name}', $data->name, $message);
                    $message = str_replace('{contest_name}', $data->challenge_name, $message);
                    break;
                case 11 : //Event promotion starts
                    $message = str_replace('{event_name}', $data->name, $message);
                    break;
                case 12 : //Event promotion ends
                    $message = str_replace('{event_name}', $data->name, $message);
                    break;
                case 13 : //Event promotion ends
                    $message = str_replace('{event_name}', $data->name, $message);
                    break;
                case 15 : //Event promotion ends
                    $message = str_replace('{event_name}', $data->name, $message);
                    $message = str_replace('{reason}', $data->reason, $message);
                    $message = str_replace('{amount}', $data->amount, $message);
                    break;
                case 16 : //Event promotion ends
                    $message = str_replace('{event_name}', $data->name, $message);
                    $message = str_replace('{reason}', $data->reason, $message);
                    $message = str_replace('{amount}', $data->amount, $message);
                    break;
                case 17 : //Challenge is edited
                    $message = str_replace('{challange_name}', $data->name, $message);
                    break;
                case 20 : //Challenge is deleted
                    $message = str_replace('{challange_name}', $data->challenge_name, $message);
                    $message = str_replace('{entry_name}', $data->entry_name, $message);
                    $message = str_replace('{user}', $data->user, $message);
                    break;
                case 21 : //entry is deleted
                    $message = str_replace('{entry_name}', $data->name, $message);
                    break;
                case 18 : //Event gallery uploaded
                case 19 : //Event Home page sponser uploaded
                case 22 : //Event Home page sponser uploaded
                    $message = str_replace('{event_name}', $data->name, $message);
                    break;
                case 23 : //On completed Challenge
                    $message = str_replace('{challenge_name}', $data->name, $message);
                    break;
                case 24 : //On delete Feed
                    $message = str_replace('{feed_name}', $data->title, $message);
                    break;
                case 25 : //On edit feed
                    $message = str_replace('{feed_name}', $data->title, $message);
                    break;
                case 27 : //On completed Challenge
                    $message = str_replace('{entry_name}', $data->name, $message);
                    break;
                case 26 : //On completed Challenge
                    $message = $message;
                    break;
                default:
                    $message = "No content";
                    break; 
            }
            // echo "<pre>";print_r($message);exit;
            return $message;
        }else{
            return;
        }
    }

    public static function notificationCount($user_id){
        $notificationCount = NotificationReceiver::where('recevier_id',$user_id)->where('is_clicked',0)->count();
        return $notificationCount;
    }
    
    public static function messageCount($user_id){
        $messageCount = MessageReceiver::where('recevier_id', $user_id)->where('unread_count','>', 0)->groupBy('event_id')->get();
        $messageCount = $messageCount->sum('unread_count');
        return $messageCount;
    }

    //type = meta notification id
    //data = json encoded data used for redirection of notification

    public static function notificationUrl($type,$data = NULL){
        if($type){
            $data = json_decode($data);
            switch ($type) {
                case 1: // When person you follow posts in challenge
                    $url = route('event.detail',$data->slug);
                    break;
                case 2: // When person you follow posts in feed
                    $url = route('news.feed',$data->slug);
                    break;
                case 3: // When person you follow creates an event
                    $url = route('event.detail',$data->slug);
                    break;
                case 4: // When you buy tickets for any event
                    $url = route('order.summary',$data->booking_id);
                    break;
                case 5: // When you cancel your booking
                    $url = route('order.summary', $data->booking_id);
                    break;
                case 6: // When event is cancelled
                    $url = route('event.detail',$data->slug);
                    break;
                case 7: // Money added to wallet
                    $url = route('user.my-wallet');
                    break;
                case 8: // Money withdrawn from wallet
                    $url = route('user.my-wallet');
                    break;
                case 9: // Entry approved for any contest
                    $url = route('entry.detail.page',$data->slug);
                    break;
                case 10:// Entry rejected for any contest
                    $url = route('entry.detail.page',$data->slug);
                    break;
                case 11: // Event promotion starts
                    $url = route('event.detail',$data->challenge_name);
                    break;
                case 12: // Event promotion ends
                    $url = route('event.detail',$data->challenge_name);
                    break;
                case 13: // Get notification for the latest events happening around you!
                    $url = route('event.detail',$data->slug);
                    break;
                case 14: // Admin notification
                    $url = "javascript:void(0);";
                    break;
                case 15: // Wallet Cash Received.
                    $url = route('user.my-wallet');
                    break;
                case 16: // Debit money in wallet.
                    $url = route('user.my-wallet');
                    break;
                case 17: // challenge is edited 
                    $url = route('challenge.detail',$data->slug);
                    break;
                case 20: // challenge is deleted
                    $url = 'javascript:void(0);';
                    break;
                case 21: // entry is deleted
                    $url = 'javascript:void(0);';
                    break;
                case 18: // Get notification for event gallery uploaded
                    $url = route('event.detail',$data->slug);
                    break;
                case 19: // Get notification for home page sponser image uploaded
                    $data->user_slug = isset($data->user_slug)?$data->user_slug:'';
                    $url = route('professional.vep-sponsered-event',[$data->user_slug, $data->id, $data->action]);
                    break;
                case 22: // Get notification for event complate
                    $url = route('event.detail',$data->slug);
                    break;
                case 23: // Get notification for event complate
                    $url = route('archive.challenge.detail',$data->slug);
                    break;
                case 24: // feed is deleted 
                    $url = 'javascript:void(0);';
                    break;
                case 25: // Get notification for edit feed.
                    $url = route('news.feed',$data->slug);
                    break;
                case 26: // Get notification for event complate
                    $url = route('entry.detail.page',$data->slug);
                    break;
                case 27: // Get notification for event complate
                    $url = route('entry.detail.page',$data->slug);
                    break;
                default:
                    $url = 'javascript:void(0);';
                    break;
            }
            return $url;
        }else{
            return;
        }
    }

    /**
     * 
        * This function for check file exist on draft_folder in AWS s3 or not
        * if exist than cut this image and upload to specific folder
        * and also create thumbnail of this image
        * Currently use in professional profile gallery
        * This function use for cut image from draft and
        * past It to original destination from AWS s3 To AWS S3
        
        * @param String $draftImagePath Drafted image path
        * @param String $destinationPath Destination image path
        * @param Boolian $wantThumb Want ot create thumbnail or not
        * @return mix
        */

    public static function draftImageToOriginalImage(Array $requestArray = array())
    {
        ini_set('memory_limit','256M');
        // Log::info('Call HELPER draftImageToOriginalImage with '. print_r($requestArray, true));

        
        $imgName = isset($requestArray['imgName'])?$requestArray['imgName']:'';

        $draftImagePath = isset($requestArray['draftImagePath']) ? $requestArray['draftImagePath'] : '';

        $requestArray['user_id'] = isset($requestArray['user_id'])?$requestArray['user_id']:0;
        $requestArray['user_name'] = isset($requestArray['user_name'])?$requestArray['user_name']:'';

        $requestArray['slug'] = isset($requestArray['slug'])?$requestArray['slug']:'';

        $requestArray['destinationPath'] = isset($requestArray['destinationPath']) ? $requestArray['destinationPath'] : '';

        $requestArray['thumbnailPath'] = isset($requestArray['thumbnailPath']) ? $requestArray['thumbnailPath'] : '';

        $destinationPath = str_replace('{userSlug}', $requestArray['slug'], $requestArray['destinationPath']);

        $createThumbnail = isset($requestArray['createThumbnail'])?$requestArray['createThumbnail']:true;

        $thumbnailPath = str_replace('{userSlug}', $requestArray['slug'], $requestArray['thumbnailPath']);

        $id = isset($requestArray['id']) ? $requestArray['id'] : 0;

        $action = isset($requestArray['action']) ? $requestArray['action'] : 'move';

        $requestArray['imageFor'] = isset($requestArray['imageFor']) ? $requestArray['imageFor'] : '';

        $requestArray['transaction_action'] = isset($requestArray['transaction_action']) ? $requestArray['transaction_action'] : '';

        Log::info('imageFor:' . $requestArray['imageFor'] . 'id:' . $id . 'destinationPath:' . $destinationPath . ': draftImagePath::' . $draftImagePath . ': thumbnailPath::' . $thumbnailPath);

        /**
         * $id:: 1 //Uniq user id
         * $slug:: kvs-1 //Uniq user slug
         * $imgName:: 1578572738433_rpnrko.jpeg
         * $draftImagePath:: development/upload/draft-upload/202001/1578572738433_rpnrko.jpeg
         * $destinationPath:: development/upload/gallery/
         * $createThumbnail:: true
         * $thumbnailPath:: development/upload/gallery/thumbnail/
         */
        
        //Check file exist in draft or not
        // echo $draftImagePath;exit;
        if (Storage::disk('s3')->exists($draftImagePath)) {
            //if file exist than move it to original destination
            if($action == 'copy')
                Storage::disk('s3')->copy($draftImagePath, $destinationPath . $imgName);
            else
                Storage::disk('s3')->move($draftImagePath, $destinationPath . $imgName);

            if ($createThumbnail) {
                $data = Storage::disk('s3')->get($destinationPath . $imgName);
                $orgImgHeight = Image::make($data)->height();
                $orgImgWidth = Image::make($data)->width();

                if(!in_array($requestArray['imageFor'], array('eventSponser','feedGallery'))){
                    $thumbnailImage = Image::make($data)->resize(config('constant.thumbnail_image_width'), config('constant.thumbnail_image_height'), function ($constraint) {
                        $constraint->aspectRatio();
                        // $constraint->upsize();
                    })->stream();
                    $thumbnail_path = $thumbnailPath . $imgName;
                    Storage::disk('s3')->put($thumbnail_path, $thumbnailImage->__toString(), 'public');
                }

                if(in_array($requestArray['imageFor'], array('eventSponser','eventGallery','feedGallery'))){

                    // $imgExtension  = explode('.',$imgName);
                    // $imgExtension = end($imgExtension);

                    // if(isset($imgExtension) && ($imgExtension != 'gif' && $imgExtension != '.gif')){
                        // $thumbnailImage = Image::make($data)->resize(config('constant.thumbnail_event_image_width'), config('constant.thumbnail_event_image_height'), function ($constraint) {
                        $thumbnailImage = Image::make($data)->resize($orgImgWidth, $orgImgHeight, function ($constraint) {
                            $constraint->aspectRatio();
                            // $constraint->upsize();
                        })->stream();
                    // }
                    if($requestArray['imageFor'] == 'eventGallery'){
                        $thumbnail_path = $thumbnailPath . 'banner/' . $imgName;
                    }
                    else {
                        $thumbnail_path = $thumbnailPath . $imgName;
                    }
                    // if(isset($thumbnailImage)){
                        Storage::disk('s3')->put($thumbnail_path, $thumbnailImage->__toString(), 'public');
                    // }else{
                    //     Storage::disk('s3')->copy($destinationPath . $imgName, $thumbnail_path);
                    // }
                }
            }
            /**
             * Update status in user_gallery table
             */
            if ($id > 0 && $requestArray['imageFor'] == 'profileGallery') {
                UserGallery::where(['user_id' => $id, 'src' => $imgName])
                    ->update(['is_uploaded' => 1]);
                    $pendingUpload = UserGallery::select(DB::raw('count(id) as pendingUpload'))->where(['user_id' => $id, 'is_uploaded' => 0])->first();
                    if(isset($pendingUpload) && isset($pendingUpload->pendingUpload) && $pendingUpload->pendingUpload == 0){
                        $params = new stdClass;
                        $params->receiver_id = $id;
                        $params->title = trans('page.system_notification');
                        $params->description = trans('page.profile_gallery_uploadsucess');
                        $params->meta_notification_id = 14;
                        $params->sender_id = 1;
                        // dd($params);
                        Helper::sendNotification($params);
                    }
            } else if ($id > 0 && $requestArray['imageFor'] == 'eventGallery') {
                EventGallery::where(['event_id' => $id, 'src' => $imgName])
                    ->update(['is_uploaded' => 1]);
                    $pendingUpload = EventGallery::select(DB::raw('count(id) as pendingUpload'))->where(['event_id' => $id, 'is_uploaded' => 0])->first();
                    if(isset($pendingUpload) && isset($pendingUpload->pendingUpload) && $pendingUpload->pendingUpload == 0){
                        $data = array('event_id' => $id, 'slug' => $requestArray['slug'], 'name' => $requestArray['event_name'], 'action' => 'upload');
                        $data =json_encode($data);

                        $params = new stdClass;
                        $params->receiver_id = $requestArray['user_id'];
                        $params->title = trans('page.system_notification');
                        $params->description = trans('page.event_gallery_uploaded_sucess');
                        $params->meta_notification_id = 18;
                        $params->sender_id = 1;
                        Helper::sendNotification($params,$data);
                    }
            } else if ($id > 0 && $requestArray['imageFor'] == 'eventSponser') {
                SponsorEvent::where(['event_id' => $id, 'banner_image' => $imgName])
                    ->update(['is_uploaded' => 1]);
                    $data = array('id' => $requestArray['sponsered_id'], 'user_slug' => $requestArray['user_slug'], 'event_id' => $id, 'slug' => $requestArray['slug'], 'name' => $requestArray['event_name'], 'action' => 'view');
                    $data =json_encode($data);

                    $params = new stdClass;
                    $params->receiver_id = $requestArray['user_id'];
                    $params->title = trans('page.system_notification');
                    $params->description = trans('page.event_sponser_image_uploaded_sucess');
                    $params->meta_notification_id = 19;
                    $params->sender_id = 1;
                    // dd($params);
                    Helper::sendNotification($params,$data);
            }else if ($id > 0 && $requestArray['imageFor'] == 'feedGallery') {
                FeedImage::where(['feed_id' => $id, 'src' => $imgName])
                    ->update(['is_uploaded' => 1]);                    
                    $pendingUpload = FeedImage::select(DB::raw('count(id) as pendingUpload'))->where(['feed_id' => $id, 'is_uploaded' => 0])->first();
                    if(isset($pendingUpload) && isset($pendingUpload->pendingUpload) && $pendingUpload->pendingUpload == 0){
                        $data = array('id' => $id , 'slug' => $requestArray['slug'], 'action' => 'image uploaded', 'user_name' => $requestArray['user_name']);
                        $data =json_encode($data);

                        $params = new stdClass;
                        $params->receiver_id = $requestArray['user_id'];
                        $params->title = trans('page.system_notification');
                        $params->description = trans('page.feed_images_uploadsucess');
                        $params->meta_notification_id = 2;
                        $params->cust_desc = true;                        
                        $params->sender_id = 1;
                        Helper::sendNotification($params,$data);

                        /**
                         * get add/edit post data
                         */
                        $currentUser = array();
                        $request = new Request;
                        $request['id'] = $id;
                        $request['withLikeAndCommentCount'] = 1;
                        $socketRecordData = Helper::getFeed($request, $currentUser, 'web');
                        $socketRecordRowData = (isset($socketRecordData['data']))?$socketRecordData['data']:array();

                        /**
                         * Fire Fire Feed add/edit EVENT
                         */ 
                        if(isset($requestArray['transaction_action']) && $requestArray['transaction_action'] == 'add'){
                            $socketData = array(
                                'event' => 'postAdd',
                                'data' => (object)$socketRecordRowData,
                            );
                            event(new AddPostEventSocket($socketData));
                        }else if(isset($requestArray['transaction_action']) && $requestArray['transaction_action'] == 'edit'){
                            $socketData = array(
                                'event' => 'postEdit',
                                'data' => (object)$socketRecordRowData,
                            );
                            event(new EditPostEventSocket($socketData));
                        }
                    }
            } else if ($id > 0 && $requestArray['imageFor'] == 'challengeCoverImage') {
                Challenge::where(['id' => $id, 'image' => $imgName])
                    ->update(['is_uploaded' => 1]);
                    $params = new stdClass;
                        $params->receiver_id = $requestArray['user_id'];
                        $params->title = trans('page.system_notification');
                        $params->description = trans('page.challenge_cover_image_uploaded_sucess');
                        $params->meta_notification_id = 14;
                        $params->sender_id = 1;
                        // dd($params);
                        Helper::sendNotification($params);
            }
            
        } else {
            // Log::info('Helper draftImageToOriginalImage: image not exist on storage imgName:' . $imgName . ': draftImagePath::' . $draftImagePath);
        }      
    }

    public static function draftVideoToOriginalVideo(Array $requestArray = array()){
        Log::info('find /tmp -type f -exec rm -f {} \;'); 
        exec('find /tmp -type f -exec rm -f {} \;');
        
        // Log::info('Call HELPER draftImageToOriginalImage with '. print_r($requestArray, true));
        // echo "<pre>"; print_r($requestArray);exit;
        
        $videoName = isset($requestArray['videoName'])?$requestArray['videoName']:'';

        $draftImagePath = isset($requestArray['draftImagePath']) ? $requestArray['draftImagePath'] : '';

        $requestArray['user_id'] = isset($requestArray['user_id'])?$requestArray['user_id']:0;

        $requestArray['user_name'] = isset($requestArray['user_name'])?$requestArray['user_name']:'';

        $requestArray['slug'] = isset($requestArray['slug'])?$requestArray['slug']:'';
        
        $requestArray['title'] = isset($requestArray['title'])?$requestArray['title']:'';

        $requestArray['isEdit'] = isset($requestArray['isEdit'])?$requestArray['isEdit']:'';

        $requestArray['platform'] = isset($requestArray['platform'])?$requestArray['platform']:'';

        $requestArray['destinationPath'] = isset($requestArray['destinationPath']) ? $requestArray['destinationPath'] : '';

        $requestArray['thumbnailPath'] = isset($requestArray['thumbnailPath']) ? $requestArray['thumbnailPath'] : '';

        $destinationPath = str_replace('{entrySlug}', $requestArray['slug'], $requestArray['destinationPath']);

        $createThumbnail = isset($requestArray['createThumbnail'])?$requestArray['createThumbnail']:true;

        $thumbnailPath = str_replace('{entrySlug}', $requestArray['slug'], $requestArray['thumbnailPath']);

        $id = isset($requestArray['id']) ? $requestArray['id'] : 0;

        $action = isset($requestArray['action']) ? $requestArray['action'] : 'move';

        $requestArray['videoFor'] = isset($requestArray['videoFor']) ? $requestArray['videoFor'] : '';

        $requestArray['transaction_action'] = isset($requestArray['transaction_action']) ? $requestArray['transaction_action'] : '';

        Log::info('videoFor:' . $requestArray['videoFor'] . 'id:' . $id . 'destinationPath:' . $destinationPath . ': draftImagePath::' . $draftImagePath . ': thumbnailPath::' . $thumbnailPath);
        
        /**
         * $id:: 1 //Uniq user id
         * $slug:: kvs-1 //Uniq user slug
         * $imgName:: 1578572738433_rpnrko.jpeg
         * $draftImagePath:: development/upload/draft-upload/202001/1578572738433_rpnrko.jpeg
         * $destinationPath:: development/upload/gallery/
         * $createThumbnail:: true
         * $thumbnailPath:: development/upload/gallery/thumbnail/
         */
        
        //Check file exist in draft or not
        // echo $draftImagePath;exit;
        if (Storage::disk('s3')->exists($draftImagePath)) {
            //if file exist than move it to original destination
            if($action == 'move')
            Storage::disk('s3')->move($draftImagePath, $destinationPath . $videoName);
            
            if ($createThumbnail) {
                $thumbnailName = $videoName;
                $thumbnailName  = explode('.',$thumbnailName);
                $thumbnailName = $thumbnailName[0].'.png';
                
                Log::info('video thumb:'.$destinationPath.'/thumbnail/'.$thumbnailName);

                // $getDurationInSeconds = FFMpeg::fromDisk('s3')
                //         ->open($destinationPath . $videoName)
                //         ->getDurationInSeconds();

                $thumbnailSec = 1;// rand(1,$getDurationInSeconds);
                // Log::info('video getDurationInSeconds:' . $getDurationInSeconds);
                Log::info('video thumbnailSec:' . $thumbnailSec);

                FFMpeg::fromDisk('s3')
                        ->open($destinationPath . $videoName)
                        ->getFrameFromSeconds($thumbnailSec)
                        ->export()
                        ->toDisk('s3')
                        ->save($destinationPath.'/thumbnail/'.$thumbnailName);
            }
            /**
             * Update status in user_gallery table
             */
            if ($id > 0 && $requestArray['videoFor'] == 'entryVideo') {
                ChallengeEntry::where(['id' => $id, 'video' => $videoName])
                    ->update(['is_uploaded' => 1]);

                    \Log::info($requestArray['isEdit']);
                    \Log::info('ankit');
                    if($requestArray['isEdit'] == null && $requestArray['platform'] == 'web'){
                        $data = array('name' => $requestArray['title'],'slug' => $requestArray['slug']);
                        $params = new stdClass;
                        $params->receiver_id = $requestArray['user_id'];//'1,2'
                        $params->title = "Add Entry";
                        $params->meta_notification_id = 27;
                        $params->sender_id = 1;
                        $data =json_encode($data);
                        Helper::sendNotification($params,$data);
                    }

                    $data = array('slug' => $requestArray['slug']);
                    $params = new stdClass;
                    $params->receiver_id =  $requestArray['user_id'];
                    $params->title = trans('page.entry_video_uploaded');
                    $params->description = trans('page.entry_video_uploadsucess');
                    $params->meta_notification_id = 26;
                    $data = json_encode($data);
                    $params->sender_id = 1;
                    Helper::sendNotification($params,$data);
                    
                    

                    if(isset($requestArray['wantToCompression']) && $requestArray['wantToCompression'] == 'T'){
                        /**
                         * params for video compression
                         */
                        VideoCompression::dispatch($requestArray)->onQueue('videos');
                    }
                    
            }else if ($id > 0 && $requestArray['videoFor'] == 'feedGallery') {
                FeedImage::where(['feed_id' => $id, 'src' => $videoName])
                    ->update(['is_uploaded' => 1]);
                    $pendingUpload = FeedImage::select(DB::raw('count(id) as pendingUpload'))->where(['feed_id' => $id, 'is_uploaded' => 0])->first();
                    if(isset($pendingUpload) && isset($pendingUpload->pendingUpload) && $pendingUpload->pendingUpload == 0){

                        $data = array('id' => $id , 'slug' => $requestArray['slug'], 'action' => 'image uploaded', 'user_name' => $requestArray['user_name']);
                        $data =json_encode($data);
                        $params = new stdClass;
                        $params->receiver_id = $requestArray['user_id'];
                        $params->title = trans('page.system_notification');
                        $params->description = trans('page.feed_images_uploadsucess');
                        $params->meta_notification_id = 2;
                        $params->cust_desc = true;
                        $params->sender_id = 1;
                        Helper::sendNotification($params,$data);

                        /**
                         * get add/edit post data
                         */
                        $currentUser = array();
                        $request = new Request;
                        $request['id'] = $id;
                        $request['withLikeAndCommentCount'] = 1;
                        $socketRecordData = Helper::getFeed($request, $currentUser, 'web');
                        $socketRecordRowData = (isset($socketRecordData['data']))?$socketRecordData['data']:array();
                        /**
                         * Fire Fire Feed add/edit EVENT
                         */ 
                        if(isset($requestArray['transaction_action']) && $requestArray['transaction_action'] == 'add'){
                            $socketData = array(
                                'event' => 'postAdd',
                                'data' => (object)$socketRecordRowData,
                            );
                            event(new AddPostEventSocket($socketData));
                        }else if(isset($requestArray['transaction_action']) && $requestArray['transaction_action'] == 'edit'){
                            $socketData = array(
                                'event' => 'postEdit',
                                'data' => (object)$socketRecordRowData,
                            );
                            event(new EditPostEventSocket($socketData));
                        }

                        if(isset($requestArray['wantToCompression']) && $requestArray['wantToCompression'] == 'T'){
                            /**
                             * params for video compression
                             */
                            VideoCompression::dispatch($requestArray)->onQueue('videos');
                        }
                    }
            }
            
        } else {
            // Log::info('Helper draftImageToOriginalImage: image not exist on storage imgName:' . $imgName . ': draftImagePath::' . $draftImagePath);
        }
    }



    public static function getSettingData(){
        return Setting::where("id", 1)->first();
    }

    /**
 *
 * This function for copy image local to AWS s3
 * Currently use in bookticket time ticket QR Code

 * @param String $newpath Drafted image path
 * @param String $oldpath Destination image path
 * @return mix
 */

    public static function copyImageLocalToAWS($requestArray){
        Log::info('Helper copyImageLocalToAWS: ' . print_r($requestArray, true));
        if(File::exists($requestArray['oldpath'])) {
            Storage::disk('s3')->put($requestArray['newpath'], file_get_contents($requestArray['oldpath']), 'public');
            File::delete($requestArray['oldpath']);
            Log::info('Helper copyImageLocalToAWS: move success:' . $requestArray['oldpath']);
        }else {
            Log::info('Helper copyImageLocalToAWS: image not exist on storage imgName:' . $requestArray['oldpath']);
        }  
        
    }

    public static function filterUsers($request,$device = 'web'){
        $users = User::select('id')->with(['userExpertise', 'userProfile' => function($query){
            $query->select('id','user_id','country_id','city_id');
        },'notificationSettings' => function($query){
            $query->where(['meta_notification_id' => 13, 'status' => 1]);
        }]);
        $users->where('is_active', 1);
        
        $user_type = "";
        if($request->user_type != 1 && (!empty($request->user_type))){
            $user_type = $request->user_type;
            $users->where('type', $user_type);
        } else {
            $user_type = $request->user_type;;
            $users->whereIn('type',[2,3]);
        }        
        $users->whereHas('notificationSettings', function($query) use($request){
            $query->where(function ($query1) use ($request){
                $query1->where(['meta_notification_id' => 13, 'status' => 1]);
            });
        });

        if($request->professional_types != ''){
            $users->where(function($i) use($request){
                $i->whereHas('userExpertise', function($query) use($request){
                    $query->where(function ($query1) use ($request){
                        $query1->whereIn('professional_type_id', $request->professional_types);
                    });
                });
                $i->orWhere('type', 2);
            });            
        }

        if($request->dance_types != ''){
            $users->whereHas('userDanceMusicTypes', function($query) use ($request){
                $query->where(function ($query1) use ($request){
                    $query1->whereIn('dance_type_id', $request->dance_types);
                });
            });
        }

        if($device == 'api'){
            if($request->selected_country != '' || $request->selected_city != ''){
                $request->selected_country = Country::where('id',$request->selected_country)->select('name')->first();
                
                $request->selected_city = City::where('id',(int)$request->selected_city)->select('name')->first();
                $users->whereHas('userProfile', function($query) use($request){
                    if($request->selected_country != ''){
                        $query->whereHas('userCountry', function($query1) use($request){
                            $query1->where(function ($query2) use ($request){
                                $query2->where('name', $request->selected_country->name);
                            });
                        });
                    }
                    if($request->selected_city != ''){
                        $query->whereHas('userCity', function($query) use($request){
                            $query->where(function ($query1) use ($request){
                                $query1->where('name', $request->selected_city->name);
                            });
                        });
                    }
                });
            }
        } else {
            if($request->selected_country != '' || $request->selected_city != ''){
                $users->whereHas('userProfile', function($query) use($request){
                    if($request->selected_country != ''){
                        $query->whereHas('userCountry', function($query1) use($request){
                            $query1->where(function ($query2) use ($request){
                                $query2->where('name', $request->selected_country);
                            });
                        });
                    }
                    if($request->selected_city != ''){
                        $query->whereHas('userCity', function($query) use($request){
                            $query->where(function ($query1) use ($request){
                                $query1->where('name', $request->selected_city);
                            });
                        });
                    }
                });
            }
        }
        return $users->get();
    }
    public static function addOrUpdateSponsorshipEvent($request, $currentUser, $event_id,$device = 'web', $eventObj = null){
        if(isset($event_id) && $event_id > 0){
            $settings = self::getSettingData();
            $charges = $total_charges = 0;
            $number_of_click = $number_of_notification = null;
            $sponsor_type = 3;
            $sponsor_status = 0;
            $notified_users = [];
            if($request->promotion_checkbox == 'click'){
                $sponsor_type = $sponsor_status = 1;
                $charges = $settings->sponser_per_click_price;
                $total_charges = round($request->number_of_click * $charges, 2);

                $number_of_click = (int) $request->number_of_click;
            } else if($request->promotion_checkbox == 'notification'){
                $sponsor_type = 2;
                $sponsor_status = 1;
                $charges = $settings->sponser_per_notification_price;
                $total_charges = round($request->number_of_notification * $charges, 2);

                if($device == 'api'){
                    if($request->dance_types != ''){
                        $dance_types = explode(',',$request->dance_types);
                        $request->dance_types = $dance_types;
                    }
                    if($request->professional_types != '' && $request->user_type != 2){
                        $professional_types = explode(',',$request->professional_types);
                        $request->professional_types = $professional_types;
                    } else {
                        $request->professional_types = 0;
                    }
                    // $request->dance_types = explode(',',$request->dance_types);
                    // $request->professional_types = explode(',',$request->professional_types);

                    $filterRequest = new Request([
                        'user_type'   => isset($request->user_type) ? $request->user_type : "",
                        'selected_country'  => isset($request->countries) ? $request->countries : "",
                        'selected_city'  => isset($request->cities) ? $request->cities : "",
                        'dance_types'  => isset($request->dance_types) ? $request->dance_types : "",
                        'professional_types'  => isset($request->professional_types) ? $request->professional_types : "",
                    ]);

                } else {
                    $filterRequest = new Request([
                        'user_type'   => isset($request->user_type) ? $request->user_type : "",
                        'selected_country'  => isset($request->countries) ? $request->countries : "",
                        'selected_city'  => isset($request->cities) ? $request->cities : "",
                        'dance_types'  => isset($request->dance_music_type) ? $request->dance_music_type : "",
                        'professional_types'  => isset($request->professional_type) ? $request->professional_type : "",
                    ]);
                }

                $users_founded = self::filterUsers($filterRequest,'api');
                
                $number_of_notification = (int) $request->number_of_notification;
                // echo "<pre>"; print_r($number_of_notification);exit;
                
                $users_count = $users_founded->count();
                $dataCollection = collect($users_founded);
                if($dataCollection->count()){
                    if($number_of_notification > $users_count){
                        $msg = str_replace('{0}',$number_of_notification,trans('page.notification_exception'));
                        $msg = str_replace('{1}',$users_count,$msg);
                        return array(
                            'status' => 500,
                            'message' => $msg 
                        );
                    }
                    $notified_users = $dataCollection->pluck('id')->random($number_of_notification)->toArray();
                } else {
                    $notified_users = null;
                }
                
            } else if($request->promotion_checkbox == 'homepage'){
                $sponsor_type = 3;
                $sponsor_status = 0;
                $bannerImage = '';
                /*
                 * save user gallery data
                 */
                $eventSponser= array();
                \Log::info($request->input('sponser_s3_gallery'));
                if($request->input('sponser_s3_gallery') != ''){
                    $draftImagePath = $request->input('sponser_s3_gallery');
                    // foreach ($request->input('sponser_s3_gallery') as $key => $draftImagePath) {
                        $sponserEventOrgDynamicUrl = str_replace('{eventSlug}', $eventObj->slug, config('constant.event_sponser_banner_url'));
                        $sponserEventThumbDynamicUrl = str_replace('{eventSlug}', $eventObj->slug, config('constant.event_sponser_banner_thumb_url'));
                        $imgName = explode('/',$draftImagePath);
                        $imgName = end($imgName);
                        $destinationPath = $sponserEventOrgDynamicUrl;
                        $thumbnailPath = $sponserEventThumbDynamicUrl;
                        $createThumbnail = true;
                        $queueRequestArray = array();
                        $queueRequestArray['user_id'] = $currentUser->id;
                        $queueRequestArray['user_slug'] = $currentUser->slug;
                        $queueRequestArray['id'] = $eventObj->id;
                        $queueRequestArray['slug'] = $eventObj->slug;
                        $queueRequestArray['event_name'] = $eventObj->title;
                        $queueRequestArray['imgName'] = $imgName;
                        $queueRequestArray['draftImagePath'] = $draftImagePath;
                        $queueRequestArray['destinationPath'] = $destinationPath;
                        $queueRequestArray['createThumbnail'] = $createThumbnail;
                        $queueRequestArray['thumbnailPath'] = $thumbnailPath;
                        $queueRequestArray['imageFor'] = 'eventSponser';
                        $queueRequestArray['action'] = 'move';
                        Log::info('Dispatch job from EventController sponser Fronend');
                        /**
                         * We want sponserd id that's why we call this in last
                         */
                        // ImageMoveDraftToOriginalDestination::dispatch($queueRequestArray);
                        $bannerImage = $imgName;
                    // }
                }
            }

            $country_id = $city_id =  null;
            if($device == 'api'){
                $country_id = $request->countries;
                $city_id = $request->cities;
            } else {
                if(isset($request->countries)){
                    $country_id = Country::select('id')->where('name', $request->countries)->first()->id;
                }
                if(isset($request->cities)){
                    $city_id = City::select('id')->where('name', $request->cities)->first()->id;
                }
            }

            if($device == 'web'){
                $datceTypeIds = isset($request->dance_music_type) ? implode(',',$request->dance_music_type) : null;
                $professional_types_ids = isset($request->professional_type) ? implode(',',$request->professional_type) : null;
            }else{
                $datceTypeIds = (!empty($request->dance_types)) ? implode(',',$request->dance_types) : null;
                $professional_types_ids = (!empty($request->professional_types)) ? implode(',',$request->professional_types) : null;
            }


            if(!(is_null($request->id))){
                $sponsorEvent = SponsorEvent::where('id',$request->id)->first();
            };

            \Log::info($sponsor_status);
            \Log::info('$sponsor_status');

            $insertArray = ['user_id' =>$currentUser->id,'event_id' => $event_id,"number_of_click" => (is_null($request->id)) ? $number_of_click : $sponsorEvent->number_of_click,  'number_of_notification' => $number_of_notification, 
                    'notified_users' => (empty($notified_users)) ? null : implode(',',$notified_users), "charges" => (is_null($request->id)) ? $charges : $sponsorEvent->charges, "total_charges" => (is_null($request->id)) ? $total_charges : $sponsorEvent->total_charges, "payment_log_id" => 0, 'sponsor_type' => (is_null($request->id)) ? $sponsor_type : $sponsorEvent->sponsor_type,'status' => (is_null($request->id)) ? $sponsor_status : $sponsorEvent->status,'start_date' => ($sponsor_type == 1) ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d') : null,
                    'dancer_types_ids' => $datceTypeIds , "user_type" => isset($request->user_type) ? $request->user_type : null, 
                    'professional_types_ids' => $professional_types_ids, 
                    'country_id' => $country_id, 'city_id' => $city_id, 'created_by' => $currentUser->id, 'updated_by' => $currentUser->id];

                    if(isset($bannerImage) && $bannerImage != ''){
                        $insertArray['banner_image'] = $bannerImage;
                        $insertArray['is_uploaded'] = 0;
                    }

            $SponsorEvent =  SponsorEvent::updateOrCreate(
                    ["id" => $request->id],
                    $insertArray
                );

            if($request->promotion_checkbox == 'homepage' && isset($queueRequestArray) && count($queueRequestArray) > 0){
                $queueRequestArray['sponsered_id'] = $SponsorEvent->id;
                ImageMoveDraftToOriginalDestination::dispatch($queueRequestArray)->onQueue('images');
            }
            return $SponsorEvent;
            // return SponsorEvent::updateOrCreate(
            //         ["id" => $request->id,"user_id" => $currentUser->id, "event_id" => $event_id, "status" => $sponsor_status, "sponsor_type" => $sponsor_type],
            //         $insertArray
            //     );
        }
    }

    public static function getSponsorData($id = null, $slug = null, $device = 'web'){

        $sponsoredEvents = SponsorEvent::select('*');
        if(is_null($id)){
            $sponsoredEvents = SponsorEvent::select('id','event_id','sponsor_type','status','start_date','end_date');
        }
        $sponsoredEvents = $sponsoredEvents->where('user_id', Auth::user()->id)->where('status','!=',1)->with(['event' => function($query) use($slug){
            $query->select('id','title','slug','event_status');
        },'city:id,name', 'country:id,name','user:id,slug'])
        ->whereHas('event', function($query) {
            $query->where(function ($query1) {
                $query1->whereNotIn('event_status',[4]);
            });
        })
        ->whereHas('user', function($query) use($slug){
            $query->where(function ($query1) use($slug){
                $query1->where('slug',$slug)->where('is_active',1)->where('deleted_at',null);
            });
        });
        
        if(is_null($id)){
            if ($device == 'web') {
                return $sponsoredEvents->orderBy('id', 'desc')->paginate(config('constant.rpp'));
            } else {
                $page_size = config('constant.rpp');
                $page_count = 1;
                if (isset($paginate['page_size']) && !empty($paginate['page_size'])) {
                    $page_size = $paginate['page_size'];
                }
                if (isset($paginate['page_count']) && !empty($paginate['page_count'])) {
                    $page_count = $paginate['page_count'];
                }
                $offset = ($page_count - 1) * $page_size;
                $sponsoredEvents->offset($offset);
                $sponsoredEvents->limit($page_size);
                return $sponsoredEvents->orderBy('id', 'desc')->get();
            }
        } else {
            $sponsoredEvents = $sponsoredEvents->with(['event.country:id,name', 'event.city:id,name','event.eventType' => function ($query) {
                $query->select('id', 'title', 'src', 'status')->withTrashed();
            }]);
            return $sponsoredEvents->where('id', $id)->first();
        }
    }

    /**
     * This function use for cancle booking
     * @param cancleBy String ::My,Owner,EventCancel(Admin delete event Or Owner cancel event)
     * @param bookingId String ::Ticket bookingId only if cancelBy My Or Owner
     */
    public static function cancelTicket(String $cancleBy = null, String $eventId=null, String $bookingId=null, $currentUser = array()) {
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];
        if($cancleBy == null &&  $eventId==null && $bookingId==null){
            $responseData['message'] = 'Atleast one argument require';
            return json_encode($responseData);
        }
        DB::beginTransaction();
        try {
        $bookingDetail = array();
        $bookingDetails = EventBooking::select('id','event_id','used_wallet_balance','booking_unique_id','user_id')
                        ->with(['event:id,user_id,valid_refund_days,from,title','tickets'])
                        ->where(['status' => 1])
                        ->where(function($q) use($cancleBy, $currentUser, $bookingId, $eventId){
                            if ($eventId != null) {
                                $q->where(['event_id' => $eventId]);
                            }

                            if ($bookingId != null) {
                                $q->where(['booking_unique_id' => $bookingId]);
                            }

                            if ($cancleBy == 'My') {
                                $q->where(['user_id' => $currentUser->id]);
                            }

                        });
                        
                        $bookingDetails = $bookingDetails->with(['attendees' => function($q){
                            $q->select('event_booking_id','name','user_id','email')->with('getAttendee');
                        }])->get();

                        $mailReceivers = $bookingDetails->pluck('attendees')->toArray();

                        $allAttendeeEmail = array();
                        foreach($mailReceivers as $attendeeRow){
                            $allAttendeeEmail = $attendeeRow;
                        }
                $response = self::refundProcess($bookingDetails, $cancleBy, $eventId, $bookingId, $currentUser);
                $responseData['status'] = isset($response['status'])?$response['status']:0;
                $responseData['message'] = isset($response['message'])?$response['message']:0;
                if($responseData['status'] == 1){
                DB::commit();
            if(isset($response['sendMail'])){
                foreach ($response['sendMail'] as $mailData) {
                    SendMailController::dynamicEmail([
                        'email_id' => $mailData['email_id'],
                        'user_id' => $mailData['user_id'],
                        'eventBookingId' => $mailData['eventBookingId'],
                        'event_id' => $mailData['event_id'],
                        'attendee' => $allAttendeeEmail,
                    ]);
                }
            }
            // dd($response['sendMailForRefund']);
            if(isset($response['sendMailForRefund'])){
                foreach ($response['sendMailForRefund'] as $mailData) {
                    SendMailController::dynamicEmail([
                        'email_id' => $mailData['email_id'],
                        'user_id' => $mailData['user_id'],
                        'id' => $mailData['id'],
                        'booking_unique_id' => $mailData['booking_unique_id'],
                        'reason' => $mailData['reason'],
                        'amount' => $mailData['amount'],
                    ]);
                }
            }
        }else{
            DB::commit();
            if(isset($response['sendMail'])){
                foreach ($response['sendMail'] as $mailData) {
                    SendMailController::dynamicEmail([
                        'email_id' => $mailData['email_id'],
                        'user_id' => $mailData['user_id'],
                        'eventBookingId' => $mailData['eventBookingId'],
                        'event_id' => $mailData['event_id'],
                        'attendee' => $allAttendeeEmail,
                    ]);
                }
            }
        }
        return json_encode($responseData);
        } catch (Exception $e) {
            // \Log::info($e);
            DB::rollback();
            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = 'Something Went Wrong';
            return json_encode($responseData);
        }
    }

    /**
     * public function 
     */
    public static function refundProcess($bookingDetails,String $cancleBy = '', String $eventId=null, String $bookingId=null, $currentUser = array()){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];
        $responseData['sendMail'] = [];
        $responseData['sendMailForRefund'] = [];
        try {
            
            if(isset($bookingDetails) && count($bookingDetails) > 0){
                foreach($bookingDetails as $bookingDetail){
                    if(!isset($bookingDetail) || !isset($bookingDetail->id)){
                        // Log::info('Event detail not found: Helper cancelTicket cancleBy:'.$cancleBy.':eventId:'.$eventId.':bookingId'.$bookingId);
                        $responseData['message'] = 'Event detail not founbd';
                        return $responseData;
                    }

                    $start =  Carbon::parse($bookingDetail->event->from);
                    $end = Carbon::parse(date('Y-m-d'));
                    $diff_in_days = $end->diffInDays($start, false);

                    $eventBookingUpdateArray = [
                                    'status' => 2,
                                    'cancelled_by' => $currentUser->id,
                                    'cancelled_at' => $start,
                                    'is_cancelbyuser_after_refunddays' => 0,
                                    ];
                    // echo $cancleBy.':'.$diff_in_days.'::'.$bookingDetail->event->valid_refund_days;exit;
                    if($cancleBy == 'My' && $diff_in_days < $bookingDetail->event->valid_refund_days ){
                        // Log::info( 'Event cancel after valida refund days '.$bookingDetail->event->valid_refund_days.'Days - cancleBy:'.$cancleBy.':eventId:'.$eventId.':bookingId'.$bookingId);
                        // $responseData['message'] = 'You can`t cancel ticket before '.$bookingDetail->event->valid_refund_days.' days';
                        // return $responseData;
                        $eventBookingUpdateArray['is_cancelbyuser_after_refunddays'] = 1;
                    }

                    $totalTicketPrice = 0;
                    foreach($bookingDetail->tickets as $ticket){
                        $totalTicketPrice += $ticket->subtotal;
                    }
                    //update event booking status as cancle:2
                    EventBooking::where(['booking_unique_id' => $bookingDetail->booking_unique_id, 'status' => 1])
                                ->update($eventBookingUpdateArray);

                    $eventBooking = EventBooking::where('booking_unique_id', $bookingDetail->booking_unique_id)->with('events:id,slug,title')->first();

                    if($eventBookingUpdateArray['is_cancelbyuser_after_refunddays'] == 0 && $totalTicketPrice > 0){

                        $descriptionMessage = str_replace('{event_name}', $bookingDetail->event->title, trans('page.cancel_event_refund'));

                        //Add money to user wallet 
                        UserProfile::where(['user_id' => $bookingDetail->user_id])
                                    ->update(['wallet_withdrawable_money' => DB::raw("wallet_withdrawable_money + $totalTicketPrice"),'total_wallet' => DB::raw("total_wallet + $totalTicketPrice")]);

                        WalletLog::create(
                            ['user_id' => $bookingDetail->user_id, "event_id"=>$bookingDetail->event->id, "event_booking_id" => $bookingDetail->booking_unique_id, "amount" => $totalTicketPrice, "type" => 6,
                                "status" => 1, "ip_address" => $_SERVER['REMOTE_ADDR'], "created_by" => $currentUser->id, "updated_by" => $currentUser->id, 'description' => $descriptionMessage]);

                        //booker will receive notification when booking is cancelled and add money to booker wallet

                        $data = array('amount' => number_format($totalTicketPrice,2) , 'reason' => "cancel booking",'name' => $eventBooking->events->title);
                        $params = new stdClass;
                        $params->receiver_id = $bookingDetail->user_id;//'1,2'
                        $params->title = "Wallet Cash Received";
                        $params->meta_notification_id = 15;
                        $params->sender_id = $bookingDetail->event->user_id;
                        $data =json_encode($data);
                        $abc = Helper::sendNotification($params,$data);

                        $descriptionMessage = str_replace('{event_name}', $bookingDetail->event->title, trans('page.cancel_eventMoney_debit'));

                        //debit money to event ownwr wallet
                        UserProfile::where(['user_id' => $bookingDetail->event->user_id])
                                    ->update(['wallet_event_money' => DB::raw("wallet_event_money - $totalTicketPrice"),'total_wallet' => DB::raw("total_wallet - $totalTicketPrice")]);
                        
                        WalletLog::create(
                            ['user_id' => $bookingDetail->event->user_id, "event_id"=>$bookingDetail->event->id, "event_booking_id" => $bookingDetail->booking_unique_id, "amount" => $totalTicketPrice, "type" => 10,
                            "status" => 1, "ip_address" => $_SERVER['REMOTE_ADDR'], "created_by" => $bookingDetail->event->user_id, "updated_by" => $bookingDetail->event->user_id, 'description' => $descriptionMessage]);

                        //owner will receive notification when booking is cancelled and debited money from owner's wallet

                        $data = array('amount' => number_format($totalTicketPrice,2) , 'reason' => "cancel booking",'name' => $eventBooking->events->title);
                        $params = new stdClass;
                        $params->receiver_id = $bookingDetail->event->user_id;//'1,2'
                        $params->title = "Debited Money";
                        $params->meta_notification_id = 16;
                        $params->sender_id = $bookingDetail->event->user_id;
                        $data =json_encode($data);
                        $abc = Helper::sendNotification($params,$data);
                    }
                    $sendMail[] = [
                        'email_id' => ($cancleBy == 'EventCancel') ? 8 : 5,
                        'user_id' => $currentUser->id,
                        'eventBookingId' => $eventBooking->id,
                        'event_id' => $eventBooking->events->id,
                    ];

                    if($cancleBy == 'EventCancel'){
                        $sendMail[] = [
                            'email_id' => 5, //cancel booking
                            'user_id' => $currentUser->id,
                            'eventBookingId' => $eventBooking->id,
                            'event_id' => $eventBooking->events->id,
                        ];
                    }

                    $reason = "";
                    if($cancleBy == 'EventCancel'){
                        $reason = "Cancelling Event";
                        $id = $eventBooking->events->id;
                        $user_id = $currentUser->id;
                    }else{
                        $reason = "Cancelling Booking";
                        $id = $eventBooking->id;
                        $user_id = $currentUser->id;
                    }

                    if($eventBookingUpdateArray['is_cancelbyuser_after_refunddays'] == 0 && $totalTicketPrice > 0){
                        $sendMailForRefund[] = [
                            'email_id' => 6,
                            'user_id' => $user_id,
                            'id' => $id,
                            'booking_unique_id' => $bookingDetail->booking_unique_id,
                            'amount' => $totalTicketPrice,
                            'reason' => $reason,
                        ];
                        $responseData['sendMailForRefund'] = $sendMailForRefund;
                    }  
                    $responseData['sendMail'] = $sendMail;
            }
            $responseData['status'] = 1;
            $responseData['message'] = trans('page.cancel_successfully');
            return $responseData;
        }else{
            if(($cancleBy == 'EventCancel')){
                $sendMail[] = [
                    'email_id' => 8,
                    'user_id' => $currentUser->id,
                    'eventBookingId' => 0,
                    'event_id' => $eventId,
                ];

                $responseData['sendMail'] = $sendMail;   
            }
            $responseData['status'] = 2;
            // Log::info('Event detail not found: Helper cancelTicket cancleBy:'.$cancleBy.':eventId:'.$eventId.':bookingId'.$bookingId);
            $responseData['message'] = trans('page.event_notfound');
            return $responseData;
        }

        } catch (Exception $e) {
            // \Log::info($e);
            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = 'Something Wents Wrong';
            return $responseData;
        }
    }

    //upcoming events
    public static function upcomingEvents($latitude = 0,$longitude = 0, $limit=4, $currentUser, $current_city='', $current_country=''){
        $mainLimit = $limit;
        $date = Carbon::today()->format('Y-m-d');
        $dance_type_ids = array();
        $resultCount = 0;

        $userProfilCityId = 0;
        $userProfilCountryId = 0;
        if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id)){
            $userProfile = $currentUser->userProfile;
            $userProfilCityId = !empty($userProfile) ? $userProfile->city_id : '';
            $userProfilCountryId = !empty($userProfile) ? $userProfile->country_id : '';
            // echo 'Before: userProfilCityId'.$userProfilCityId."<br>";
            // echo 'Before userProfilCountryId:'.$userProfilCountryId."<br>";
            // echo $current_city;exit;
            if($current_city != ''){
                $cityObj = City::select('id')->where(['name' => $current_city])->first();
                if(isset($cityObj->id)){
                    $userProfilCityId = $cityObj->id;
                }
            }

            if($current_country != ''){
                $countryObj = Country::select('id')->where(['name' => $current_country])->first();
                if(isset($countryObj->id)){
                    $userProfilCountryId = $countryObj->id;
                }
            }
            // echo 'After: userProfilCityId'.$userProfilCityId."<br>";
            // echo 'After userProfilCountryId:'.$userProfilCountryId."<br>";
        }
        // exit;

        // dd($currentUser);
        // dd($latitude);
        // dd("long ".$longitude."lat ".$latitude);

        /**
         * my city with dance type
         */
        $upcoming_events = Event::select('id','title','slug','from','to','dance_type_id','event_status','time_of_event','event_type_id','country_id','city_id','user_id','venue_name','created_at','address','latitude','longitude',DB::raw("IF((SELECT user_id from event_interesteds where user_id = ".((!empty($currentUser))?$currentUser->id:0)." AND event_id = events.id) , true, false) AS isInterested"), DB::raw(' 111.111 *
        DEGREES(ACOS(LEAST(1.0, COS(RADIANS(latitude))
         * COS(RADIANS('.$latitude.'))
         * COS(RADIANS(longitude - '.$longitude.'))
         + SIN(RADIANS(latitude))
         * SIN(RADIANS('.$latitude.')))))  AS distance'))->with(['country:id,name','city:id,name','eventBanner:id,src,event_id,is_uploaded'])
                ->whereDate('from','>',$date)
                ->with(['country:id,name', 'city:id,name'])->whereHas('eventOwner', function($q){
                        $q->where('is_active',1);
                    })
                ->where('event_status',1);
                if($latitude != '' && $latitude != null){
                    $upcoming_events = $upcoming_events->orderBy('distance', 'asc');
                }
                $upcoming_events = $upcoming_events->orderBy('from', 'asc');

        if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id)){
            // $userProfile = $currentUser->userProfile;

            $dance_type_ids = $currentUser->userDanceMusicTypes->pluck('dance_type_id');
            $upcoming_events = $upcoming_events->whereIn('dance_type_id', $dance_type_ids);

            /**
             * CITY
             */
            $upcoming_events = $upcoming_events->whereHas('city',function ($query) use ($userProfile, $userProfilCityId, $userProfilCountryId){
                    $query->where('id', $userProfilCityId);
            });
        }

        $upcoming_events = $upcoming_events->limit($limit)->get();

        $resultCount += $upcoming_events->count();
        

        // echo "<pre>1"; print_r($upcoming_events->toArray());exit;

        /**
         * my Country / my dance type
         */
        if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id) && $resultCount < $mainLimit){
            $upcommingEventIds = $upcoming_events->pluck('id');
            $limit = $mainLimit - $resultCount;
            // dd($limit);
            $upcoming_events_new = Event::select('id', 'title', 'slug', 'from', 'to', 'user_id','dance_type_id', 'event_status', DB::raw("IF((SELECT user_id from event_interesteds where user_id = ".((!empty($currentUser))?$currentUser->id:0)." AND event_id = events.id) , true, false) AS isInterested"),'time_of_event', 'event_type_id', 'country_id', 'city_id', 'venue_name', 'created_at', 'address', 'latitude', 'longitude', DB::raw(' 111.111 *
                    DEGREES(ACOS(LEAST(1.0, COS(RADIANS(latitude))
                    * COS(RADIANS(' . $latitude . '))
                    * COS(RADIANS(longitude - ' . $longitude . '))
                    + SIN(RADIANS(latitude))
                    * SIN(RADIANS(' . $latitude . ')))))  AS distance'))->with(['country:id,name', 'city:id,name', 'eventBanner:id,src,event_id,is_uploaded'])
                ->whereDate('from', '>', $date)
                ->with(['country:id,name', 'city:id,name'])->whereHas('eventOwner', function ($q) {
                $q->where('is_active', 1);
            })
                ->where('event_status', 1);
                // ->orderBy('distance', 'asc')
                // ->orderBy('from', 'asc');

                if($latitude != '' && $latitude != null){
                    $upcoming_events_new = $upcoming_events_new->orderBy('distance', 'asc');
                }
                $upcoming_events_new = $upcoming_events_new->orderBy('from', 'asc');
                

                if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id)){

                    $upcoming_events_new = $upcoming_events_new->whereIn('dance_type_id', $dance_type_ids);

                    /**
                     * COUNTRY
                     */
                    $upcoming_events_new = $upcoming_events_new->whereHas('country',function ($query) use ($userProfile, $userProfilCityId, $userProfilCountryId){
                            $query->where('id', $userProfilCountryId);
                    });
                }

                

            $upcoming_events_new = $upcoming_events_new->whereNotIn('id', $upcommingEventIds)->limit($limit)->get();
            $resultCount += $upcoming_events_new->count();

            $allItems = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
            $allItems = $allItems->merge($upcoming_events);
            $allItems = $allItems->merge($upcoming_events_new);
            $upcoming_events = $allItems;
        }

// echo "<pre>2"; print_r($upcoming_events->toArray());exit;


        /**
         * My city'e record without dancetype
         */
        if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id) && $resultCount < $mainLimit){
            $upcommingEventIds = $upcoming_events->pluck('id');
            $limit = $mainLimit - $resultCount;
            // dd($limit);
            $upcoming_events_new = Event::select('id', 'title', 'slug', 'from', 'to', 'user_id','dance_type_id', 'event_status', DB::raw("IF((SELECT user_id from event_interesteds where user_id = ".((!empty($currentUser))?$currentUser->id:0)." AND event_id = events.id) , true, false) AS isInterested"),'time_of_event', 'event_type_id', 'country_id', 'city_id', 'venue_name', 'created_at', 'address', 'latitude', 'longitude', DB::raw(' 111.111 *
                    DEGREES(ACOS(LEAST(1.0, COS(RADIANS(latitude))
                    * COS(RADIANS(' . $latitude . '))
                    * COS(RADIANS(longitude - ' . $longitude . '))
                    + SIN(RADIANS(latitude))
                    * SIN(RADIANS(' . $latitude . ')))))  AS distance'))->with(['country:id,name', 'city:id,name', 'eventBanner:id,src,event_id,is_uploaded'])
                ->whereDate('from', '>', $date)
                ->with(['country:id,name', 'city:id,name'])->whereHas('eventOwner', function ($q) {
                $q->where('is_active', 1);
            })
                ->where('event_status', 1);
                // ->orderBy('distance', 'asc')
                // ->orderBy('from', 'asc');

                if($latitude != '' && $latitude != null){
                    $upcoming_events_new = $upcoming_events_new->orderBy('distance', 'asc');
                }
                $upcoming_events_new = $upcoming_events_new->orderBy('from', 'asc');

                if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id)){

                    /**
                     * CITY
                     */
                    $upcoming_events_new = $upcoming_events_new->whereHas('city',function ($query) use ($userProfile, $userProfilCityId, $userProfilCountryId){
                            $query->where('id', $userProfilCityId);
                    });
                }
                

            $upcoming_events_new = $upcoming_events_new->whereNotIn('id', $upcommingEventIds)->limit($limit)->get();
            $resultCount += $upcoming_events_new->count();

            $allItems = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
            $allItems = $allItems->merge($upcoming_events);
            $allItems = $allItems->merge($upcoming_events_new);
            $upcoming_events = $allItems;
        }

// echo "<pre>3"; print_r($upcoming_events->toArray());exit;


        /**
         * My Country's event without dance type
         */
        if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id) && $resultCount < $mainLimit){
            $upcommingEventIds = $upcoming_events->pluck('id');
            $limit = $mainLimit - $resultCount;
            // dd($limit);
            $upcoming_events_new = Event::select('id', 'title', 'slug', 'from', 'to', 'user_id','dance_type_id', 'event_status', DB::raw("IF((SELECT user_id from event_interesteds where user_id = ".((!empty($currentUser))?$currentUser->id:0)." AND event_id = events.id) , true, false) AS isInterested"),'time_of_event', 'event_type_id', 'country_id', 'city_id', 'venue_name', 'created_at', 'address', 'latitude', 'longitude', DB::raw(' 111.111 *
                    DEGREES(ACOS(LEAST(1.0, COS(RADIANS(latitude))
                    * COS(RADIANS(' . $latitude . '))
                    * COS(RADIANS(longitude - ' . $longitude . '))
                    + SIN(RADIANS(latitude))
                    * SIN(RADIANS(' . $latitude . ')))))  AS distance'))->with(['country:id,name', 'city:id,name', 'eventBanner:id,src,event_id,is_uploaded'])
                ->whereDate('from', '>', $date)
                ->with(['country:id,name', 'city:id,name'])->whereHas('eventOwner', function ($q) {
                $q->where('is_active', 1);
            })
                ->where('event_status', 1);
                // ->orderBy('distance', 'asc')
                // ->orderBy('from', 'asc');

                if($latitude != '' && $latitude != null){
                    $upcoming_events_new = $upcoming_events_new->orderBy('distance', 'asc');
                }
                $upcoming_events_new = $upcoming_events_new->orderBy('from', 'asc');

                if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id)){

                    /**
                     * COUNTRY
                     */
                    $upcoming_events_new = $upcoming_events_new->whereHas('country',function ($query) use ($userProfile, $userProfilCityId, $userProfilCountryId){
                            $query->where('id', $userProfilCountryId);
                    });
                }

            $upcoming_events_new = $upcoming_events_new->whereNotIn('id', $upcommingEventIds)->limit($limit)->get();
            $resultCount += $upcoming_events_new->count();

            $allItems = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
            $allItems = $allItems->merge($upcoming_events);
            $allItems = $allItems->merge($upcoming_events_new);
            $upcoming_events = $allItems;
        }

// echo "<pre>4"; print_r($upcoming_events->toArray());exit;


        /**
         * Event with my dance type
         */
        if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id) && $resultCount < $mainLimit){
            $upcommingEventIds = $upcoming_events->pluck('id');
            $limit = $mainLimit - $resultCount;
            // dd($limit);
            $upcoming_events_new = Event::select('id', 'title', 'slug', 'from', 'to', 'user_id','dance_type_id', 'event_status', DB::raw("IF((SELECT user_id from event_interesteds where user_id = ".((!empty($currentUser))?$currentUser->id:0)." AND event_id = events.id) , true, false) AS isInterested"),'time_of_event', 'event_type_id', 'country_id', 'city_id', 'venue_name', 'created_at', 'address', 'latitude', 'longitude', DB::raw(' 111.111 *
                    DEGREES(ACOS(LEAST(1.0, COS(RADIANS(latitude))
                    * COS(RADIANS(' . $latitude . '))
                    * COS(RADIANS(longitude - ' . $longitude . '))
                    + SIN(RADIANS(latitude))
                    * SIN(RADIANS(' . $latitude . ')))))  AS distance'))->with(['country:id,name', 'city:id,name', 'eventBanner:id,src,event_id,is_uploaded'])
                ->whereDate('from', '>', $date)
                ->with(['country:id,name', 'city:id,name'])->whereHas('eventOwner', function ($q) {
                $q->where('is_active', 1);
            })
                ->where('event_status', 1);
                // ->orderBy('distance', 'asc')
                // ->orderBy('from', 'asc');

                if($latitude != '' && $latitude != null){
                    $upcoming_events_new = $upcoming_events_new->orderBy('distance', 'asc');
                }
                $upcoming_events_new = $upcoming_events_new->orderBy('from', 'asc');

            if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id)){
                $upcoming_events_new = $upcoming_events_new->whereIn('dance_type_id', $dance_type_ids);
            }

            $upcoming_events_new = $upcoming_events_new->whereNotIn('id', $upcommingEventIds)->limit($limit)->get();
            $resultCount += $upcoming_events_new->count();

            $allItems = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
            $allItems = $allItems->merge($upcoming_events);
            $allItems = $allItems->merge($upcoming_events_new);
            $upcoming_events = $allItems;
        }

// echo "<pre>5"; print_r($upcoming_events->toArray());exit;

        if(isset($currentUser) && !empty($currentUser)  && isset($currentUser->id) && $resultCount < $mainLimit){
            $upcommingEventIds = $upcoming_events->pluck('id');
            $limit = $mainLimit - $resultCount;
            // dd($limit);
            $upcoming_events_new = Event::select('id', 'title', 'slug', 'from', 'to', 'user_id','dance_type_id', 'event_status', DB::raw("IF((SELECT user_id from event_interesteds where user_id = ".((!empty($currentUser))?$currentUser->id:0)." AND event_id = events.id) , true, false) AS isInterested"),'time_of_event', 'event_type_id', 'country_id', 'city_id', 'venue_name', 'created_at', 'address', 'latitude', 'longitude', DB::raw(' 111.111 *
                    DEGREES(ACOS(LEAST(1.0, COS(RADIANS(latitude))
                    * COS(RADIANS(' . $latitude . '))
                    * COS(RADIANS(longitude - ' . $longitude . '))
                    + SIN(RADIANS(latitude))
                    * SIN(RADIANS(' . $latitude . ')))))  AS distance'))->with(['country:id,name', 'city:id,name', 'eventBanner:id,src,event_id,is_uploaded'])
                ->whereDate('from', '>', $date)
                ->with(['country:id,name', 'city:id,name'])->whereHas('eventOwner', function ($q) {
                $q->where('is_active', 1);
            })
                ->where('event_status', 1);
                // ->orderBy('distance', 'asc')
                // ->orderBy('from', 'asc');
                if($latitude != '' && $latitude != null){
                    $upcoming_events_new = $upcoming_events_new->orderBy('distance', 'asc');
                }
                $upcoming_events_new = $upcoming_events_new->orderBy('from', 'asc');

            $upcoming_events_new = $upcoming_events_new->whereNotIn('id', $upcommingEventIds)->limit($limit)->get();
            $allItems = new \Illuminate\Database\Eloquent\Collection; //Create empty collection which we know has the merge() method
            $allItems = $allItems->merge($upcoming_events);
            $allItems = $allItems->merge($upcoming_events_new);
            $upcoming_events = $allItems;
        }
        // echo "<pre>last"; print_r($upcoming_events->toArray());exit;
        return $upcoming_events;
    }

    //Get Notification During Register
    public static function notificationSettings($id = null,$type = null){
        DB::beginTransaction();
        $notificationUserTypes = NotificationUserType::where('user_type',$type)->select('id','meta_notification_id','default')->get();

        $Notification = array();
        foreach($notificationUserTypes as $notificationUserType){
            $Notification[] = ['user_id' => $id,'notification_user_type_id' => $notificationUserType->id,'meta_notification_id' => $notificationUserType->meta_notification_id,'status' => $notificationUserType->default,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')];
        }
        $addNotification = NotificationSettings::insert($Notification);
        DB::commit();
        return $addNotification;
    }

    public static function isValidForRefund($eventDate, $validDays){
        $start =  Carbon::parse($eventDate);
        $end = Carbon::parse(date('Y-m-d'));
        $diff_in_days = $end->diffInDays($start, false);
        if($diff_in_days > $validDays ){
            return 'true';
        }else return 'false';
    }

    public static function homePageBanner($currentUser = null,$request = null,$device = 'api'){
        try{
            $size = ( isset($request->size) && $request->size > 0)?$request->size:5;
            $today = Carbon::now()->format('Y-m-d');
            $sponsorEvent = Event::select('id','from','from as event_from','slug','slug as event_slug','to', 'title',DB::raw("IF((SELECT user_id from event_interesteds where user_id = ".((!empty($currentUser) && isset($currentUser->id) && $currentUser->id > 0)?$currentUser->id:0)." AND event_id = events.id) , true, false) AS isInterested"))->where(['event_status' => 1])->whereDate('to','>=',$today)
            ->with(['sponsorsEvents' => function($query){
                $query->select('id','event_id','banner_image','is_uploaded');
                $query->where(['sponsor_type' => 3,'deleted_at' => null,'status' => 2]);
            },'eventOwner' => function($query){
                $query->where('deleted_at',null);
            }])->whereHas('sponsorsEvents',function($query){
                $query->where(['sponsor_type' => 3,'deleted_at' => null,'status' => 2,'is_uploaded' => 1]);
            })
            ->has('eventOwner')
            ->orderBy('from');
            // if($device == 'api'){
                // $sponsorEvent = $sponsorEvent->limit(5)->get();
                // $sponsorEvent = $sponsorEvent->paginate(5);
            // } else {
            $sponsorEvent = $sponsorEvent->paginate($size);
            // }
            foreach($sponsorEvent as $sp){
                $sp->banner_image = $sp->sponsorsEvents[0]->banner_image;
                $sp->event_id = $sp->id;
                $sp->sponsor_id = $sp->sponsorsEvents[0]->id;
                // unset($sp->sponsorsEvents);
                // unset($sp->id);
            }
            // if($device == 'api'){
            //     return $sponsorEvent;
            // }
            $sponsorEvent = $sponsorEvent->toJson();
            $sponsorEvent = json_decode($sponsorEvent);
            // echo "<pre>"; print_r($sponsorEvent);exit;
            //sort By asc logic
            unset($sponsorEvent->last_page_url);
            unset($sponsorEvent->first_page_url);
            unset($sponsorEvent->next_page_url);
            unset($sponsorEvent->prev_page_url);
            unset($sponsorEvent->path);
            // echo "<pre>"; print_r($sponsorEvent);exit;
            return $sponsorEvent;
        } catch(Exception $e){
            return  null;
        }
    }

    /**
     * get first 3 sponser event result
     * if 3 result not foundthan repeat old result
     * @param $request Request
     * @response Array
     */
    public static function sponserdEventSearchResult($request, $latitude, $longitude, $rpp = 3){
        $today = Carbon::now()->format('Y-m-d');

        $data = Event::with(['eventType' => function($q) {
            $q->select('id','title')->withTrashed();
        },'danceMusicTypes' => function($q) {
            $q->select('id','title')->withTrashed();
        },'country:id,name','city:id,name','eventTicketTypes:id,price,event_id','eventBanner:id,event_id,src,is_uploaded','eventGallery' => function($query){
            $query->select('id','event_id','src');
        }])->whereHas('eventOwner', function($query){
            $query->where('deleted_at',null)->where('is_active',1);
        })->whereHas('sponsorsEvents', function($query) use($today){
            $query->where('deleted_at',null)
            ->where('start_date', '<=', $today)
            ->where('sponsor_type',1)
            ->where('status',2);
        })
        ->where(['is_sponsored' => 1])
        ->where('event_status', 1)
        ->whereDate('to', '>=', $today);

        if($latitude && $longitude){
            $data = $data->select('id','title','slug','from','to','dance_type_id','event_status','time_of_event','event_type_id','country_id','city_id','venue_name','created_at','address','latitude','longitude', DB::raw('1 as isSponserdResult'), DB::raw('1 as isSponsered'),
                DB::raw(' 111.111 *
        DEGREES(ACOS(LEAST(1.0, COS(RADIANS(latitude))
            * COS(RADIANS('.$latitude.'))
            * COS(RADIANS(longitude - '.$longitude.'))
            + SIN(RADIANS(latitude))
            * SIN(RADIANS('.$latitude.')))))  AS distance')
        );
        }else{
            $data = $data->select('id','title','slug','from','to','dance_type_id','event_status','time_of_event','event_type_id','country_id','city_id','venue_name','created_at','address','latitude','longitude', DB::raw('0 as distance'), DB::raw('1 as isSponserdResult'), DB::raw('1 as isSponsered'));
        }
        
        if($latitude != 0 && $latitude != null){
            $data = $data->orderBy('distance','ASC');
        }
        $data = $data->orderBy('from', 'ASC');

        $data = $data->withCount(['eventBookedAttendee','eventInterested'])
        ->paginate($rpp);
        return $data;
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

    /**
     * This api use for get all active professional from db
     * @use all feed time get user list for tagging
     * @param $request Request
     * @return Json Object
     */

    public static function searchTagUser($request, $currentUser=array()){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
        $page_size = config('constant.feedTagUserRpp');
        try{
            // $currentUser = $request->get('user');
            // echo "<pre>"; print_r($currentUser->id);exit;
            $where['users.deleted_at'] = null;
            $where['users.is_active'] = 1;
            $whereIn[] = config('constant.USER.TYPE.PROFESSIONAL');
            // if($request->onlyProfessional == '' || $request->onlyProfessional == 0){
                $whereIn[] = config('constant.USER.TYPE.DANCER');
            // }
            $where['users.is_profile_filled'] = 1;

			if ($request->has('page_size')) {
				$page_size = $request->page_size;
            }

            $data = User::where($where)
            ->whereIn('type', $whereIn);
            // if(isset($request->onlyProfessional) && $request->onlyProfessional == 1){
            //     if(isset($currentUser->id)){
            //         $data->where('users.id','!=',$currentUser->id);
            //     }
            // }

            if(isset($request->exaptMe) && $request->exaptMe == 1){
                if(isset($currentUser->id)){
                    $data->where('users.id','!=',$currentUser->id);
                }
            }

            if($request->selectedUsers != ''){
                $selectedUsers = explode(',',$request->selectedUsers);
                $data->whereNotIn('users.id', $selectedUsers);
            }

            if($request->keyword != ''){
                $data->where(function ($query) use ($request){
                    $query->whereRaw("IF (users.is_nickname_use = 1, users.nick_name, users.name) LIKE '%$request->keyword%'");
                });
            }
            $data->orderBy('users.name', 'asc')->orderBy('users.id', 'ASC');

            $data = $data->select(
                    'users.id',
                    'users.type',
                    'users.logo',
                    'users.logo as avatar',
                    'users.slug',
                    DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name")
            )->paginate($page_size);
            $data = $data->toJson();
            $data = json_decode($data);
            
            unset($data->last_page_url);
            unset($data->first_page_url);
            unset($data->next_page_url);
            unset($data->prev_page_url);
            unset($data->path);
            return $data;
        } catch(Exception $e){
            Log::emergency('searchTagUser Helper :: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            $code = ($e->getCode() != '')?$e->getCode():500;
            $responseData['message'] = trans('common.something_went_wrong');
        }
    }

    /**
     * This function use for Store(Add/Edit) Feed
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function storeFeed(Request $request, $currentUser, $platform='web'){
        // echo "<pre>"; print_r($request->all());
        // echo "</br></br><pre>"; print_r($currentUser);exit;
    $responseData = array();
    $responseData['status'] = 0;
    $responseData['message'] = '';
    $responseData['data'] = (object) [];

        DB::beginTransaction();
        try {
            if ($currentUser && $currentUser->type == config('constant.USER.TYPE.PROFESSIONAL')) {
                if ($request->description == '' || $request->description == null) {
                    unset($request['description']);
                }
                
                $validationArray = [
                    'description' => "required_without:attachment|min:2|max:512",
                    'attachment' => "required_without:description",
                    'danceType' => "required",
                ];

                $validator = Validator::make($request->all(),$validationArray , [
                    'danceType' => "Please select atleast one dance type",
                    'description.required' => "Please enter feed discription",
                    'description.min' => "Minimum 2 character are require to add Feed",
                    'description.max' => "Maximim 512 character are allow in Feed",
                ]);

                if ($validator->fails()) {
                    DB::rollback();
                    $responseData['message'] = $validator->errors()->first();
                    //$responseData['errors'] = $validator->errors()->toArray();
                    return $responseData;
                }else if($request->input('attachment') != ''){
                    $attachmentArray = explode(',', $request->input('attachment'));
                    foreach ($attachmentArray as $key => $draftImagePath) {
                        $mimeType = MimeType::from($draftImagePath);
                        if((substr($mimeType, 0,5) != 'image' && substr($mimeType, 0,5) != 'video')){
                            DB::rollback();
                            $responseData['message'] = trans('page.only_imageAndVideo_allowed');
                            return $responseData;
                        }
                    }
                }

                $feedDetails = [
                    'user_id' => $currentUser->id,
                    'dance_type_id' => $request->danceType,
                    'description' => isset($request->description)?$request->description:'',
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                ];
                if($request->id == '' || $request->id <= 0 ){
                    $feedDetails['slug_title'] = 'feed-'.Str::random(8);
                }
                $existFeed = Feed::select('id','deleted_at')->where('id',$request->id)->withTrashed()->first();
                // dd($existFeed->deleted_at != null);
                // dd("i am");
                if(isset($existFeed->id) && $existFeed->deleted_at != null){
                    $responseData['status'] = 0;
                    $responseData['message'] = trans('page.feed_not_exists');
                    return $responseData;
                }

                $feed = Feed::updateOrCreate(
                    ['id' => $request->id, 'user_id' => $currentUser->id],
                    $feedDetails
                );

                $feedWithInsertArray = array();
                if($request->input('feedWith') != ''){
                    $feedWithArray = explode(',', $request->input('feedWith'));
                    foreach ($feedWithArray as $feedWith) {
                        $feedWithInsertArray[] = ['feed_id' => $feed->id, 'user_id' => $feedWith, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
                    }
                }

                /**
                 * Only edit time delete old image 
                 */
                if($request->id > 0){
                    if($request->input('deletedAttachment') && $request->input('deletedAttachment') != '') {
                        $deletedAttachment = explode(',', $request->input('deletedAttachment'));
                        $feed->feedImages()
                        ->where('feed_id', $feed->id)
                        ->where(function($q) use($request, $deletedAttachment){
                            $q->whereIn('id', $deletedAttachment)
                            ->orWhereIn('src', $deletedAttachment);
                        })
                        ->delete();
                    }

                    /**
                     * delete old feed with
                     */
                    $feed->feedWithUser()
                    ->where('feed_id', $feed->id)
                    ->delete();
                }

                /**
                 * insert after delete
                 */
                if(count($feedWithInsertArray) > 0)
                    $feed->feedWithUser()->insert($feedWithInsertArray);

                /**
                 * Add/edit feed time get default gallery path dynamic
                 * this path use only edit time and 'attachment' not set
                 */
                $galleryOrgDynamicUrl = str_replace('{feedSlug}', $feed->slug, config('constant.feed_gallery_url'));
                $galleryThumbDynamicUrl = str_replace('{feedSlug}', $feed->slug, config('constant.feed_gallery_thumb_url'));

                /*
                 * save user gallery data
                 */
                $feedGallery= array();
                $userName = ($currentUser->is_nickname_use == 1) ? $currentUser->nick_name : $currentUser->name;
                if($request->input('attachment') != ''){
                    $attachmentArray = explode(',', $request->input('attachment'));
                    foreach ($attachmentArray as $key => $draftImagePath) {
                        $mimeType = MimeType::from($draftImagePath);
                        $mimeType = substr($mimeType, 0,5);
                        $mimeType = ($mimeType == 'image')?1:2;
                        $galleryOrgDynamicUrl = str_replace('{feedSlug}', $feed->slug, config('constant.feed_gallery_url'));
                        $galleryThumbDynamicUrl = str_replace('{feedSlug}', $feed->slug, config('constant.feed_gallery_thumb_url'));

                        $imgName = explode('/',$draftImagePath);
                        $imgName = end($imgName);
                        $destinationPath = $galleryOrgDynamicUrl;
                        $thumbnailPath = $galleryThumbDynamicUrl;
                        $createThumbnail = true;
                        $queueRequestArray = array();
                        $queueRequestArray['user_name'] = $userName;
                        $queueRequestArray['user_id'] = $currentUser->id;
                        $queueRequestArray['id'] = $feed->id;
                        $queueRequestArray['slug'] = $feed->slug;
                        if($platform == 'web'){
                            $queueRequestArray['wantToCompression'] = 'T';    ;
                        }
                        $queueRequestArray['imgName'] = $imgName;
                        $queueRequestArray['videoName'] = $imgName;
                        $queueRequestArray['draftImagePath'] = $draftImagePath;
                        $queueRequestArray['destinationPath'] = $destinationPath;
                        $queueRequestArray['createThumbnail'] = $createThumbnail;
                        $queueRequestArray['thumbnailPath'] = $thumbnailPath;
                        $queueRequestArray['imageFor'] = 'feedGallery';
                        $queueRequestArray['videoFor'] = 'feedGallery';
                        $queueRequestArray['media_type'] = $mimeType;
                        $queueRequestArray['action'] = 'move';
                        $queueRequestArray['transaction_action'] = ($request->id > 0)?'edit':'add';
                        Log::info('Dispatch job from helper feed Fronend');
                        if($mimeType == 1)
                            ImageMoveDraftToOriginalDestination::dispatch($queueRequestArray)->onQueue('images');
                        else {
                            VideoMoveDraftToOrignalDestination::dispatch($queueRequestArray)->onQueue('videos');
                        }
                        $feedGalleryArray = ['feed_id' => $feed->id, 'media_type' => $mimeType, 'src' => $imgName, 'is_uploaded' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
                        if($platform == 'web' && $mimeType == 2){
                            $feedGalleryArray['compressed_date'] = null;
                        }else{
                            $feedGalleryArray['compressed_date'] = Carbon::now();
                        }
                        $feedGallery[] = $feedGalleryArray;
                    }
                }

                // echo "<pre>"; print_r($feedGallery);exit;
                if(count($feedGallery) > 0)
                    $feed->feedImages()->insert($feedGallery);

                DB::commit();
                

                /*
                 * if successfully update feed than delete old gallery file
                 */
                if(isset($deletedAttachment) && is_array($deletedAttachment)){
                    foreach ($deletedAttachment as $deletedAttachmentRow){
                        Helper::checkFileExists($galleryOrgDynamicUrl . $deletedAttachmentRow, true, true);
                        //delete thumbnail
                        $del_thumb = explode('.',$deletedAttachmentRow);
                        $del_thumb = $del_thumb[0].'.png';
                        Helper::checkFileExists($galleryThumbDynamicUrl . $del_thumb, false, true);
                    }
                }

                /**
                 * get add/edit post data
                 */
                $request['org_id'] = $request->id;
                $request['id'] = $feed->id;
                $request['withLikeAndCommentCount'] = 1;
                $socketRecordData = Helper::getFeed($request, $currentUser, 'web');
                $socketRecordRowData = (isset($socketRecordData['data']))?$socketRecordData['data']:array();

                if($request->org_id > 0){
                    /**
                     * Fire Fire Feed edit EVENT
                     */ 
                    if(!isset($attachmentArray) || (isset($attachmentArray) && count($attachmentArray) <= 0)){
                        $socketData = array(
                            'event' => 'postEdit',
                            'data' => (object)$socketRecordRowData,
                        );
                        event(new EditPostEventSocket($socketData));
                    }
                } else if($request->org_id == '' || $request->org_id <= 0 ){
                    /**
                     * Fire Fire Feed create EVENT
                     */ 
                    if(!isset($attachmentArray) || (isset($attachmentArray) && count($attachmentArray) <= 0)){
                        $socketData = array(
                            'event' => 'postAdd',
                            'data' => (object)$socketRecordRowData,
                        );
                        event(new AddPostEventSocket($socketData));
                    }
                    /**
                     * Send notification to followers
                     */

                    $notifiedUsers = UserFollower::select('followers_id')->where('user_id',$currentUser->id)->get()->pluck('followers_id')->toArray();
                    $receivers = NotificationSettings::select('user_id')->whereIn('user_id',$notifiedUsers)->where('meta_notification_id',2)->where('status',1)->get()->pluck('user_id')->toArray();

                    $receivers = implode(',' ,$receivers);

                    $data = array('id' => $feed->id , 'slug' => $feed->slug, 'user_name' => $userName, 'action' => 'created');
                    $params = new stdClass;
                    $params->receiver_id = $receivers;//'1,2'
                    $params->title = "Post Feed";
                    $params->meta_notification_id = 2;
                    $params->sender_id = $currentUser->id;
                    $data = json_encode($data);
                    Helper::sendNotification($params,$data);
                }

                $redirect = '';
                $responseData['data'] = isset($socketRecordRowData)?$socketRecordRowData:(object)array();
                if ($request->org_id > 0) {
                    // if($platform == 'web'){
                    //     Session::flash('status', trans('page.feed_has_been_update_successfully'));
                    //     $responseData['redirect'] = $redirect;
                    // }
                    $responseData['message'] = trans('page.feed_has_been_update_successfully');
                } else {
                    // if($platform == 'web'){
                    //     Session::flash('status', trans('page.feed_has_been_added_successfully'));
                    //     $responseData['redirect'] = $redirect;
                    // }
                    $responseData['message'] = trans('page.feed_has_been_added_successfully');
                }
                $responseData['status'] = 1;
                return $responseData;
            } else {
                DB::rollback();
                $responseData['status'] = 0;
                $responseData['message'] = trans('page.error_in_save_feed');
                return $responseData;
            }
        } catch (Exception $e) {
            DB::rollback();
            \Log::emergency('store api Exception :: Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());
            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = $e->getMessage();
            $responseData['redirect'] = '';
            // if($platform == 'web')
            //     Session::flash('error', $e->getMessage());
            return $responseData;

        }
    }

    /**
    * This function use for delete challenge and for delete entries
     * @param delete String ::challenge = delete challenge and its entries , entry = delete perticular entry
     */

    public static function deleteChallengeEntries($deleteFor = null , $slug = null, $currentUser = array()){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];
        try{
            DB::beginTransaction();
            if($deleteFor == 'challenge'){
                $where = [
                    'slug' => $slug,
                    'deleted_at' => null
                ];
                $sendMail = $sendnotification = array();
                $requiredData = $deleteData = Challenge::where($where)->first();
                
                if(isset($deleteData) && !empty($deleteData)){

                    if(isset($deleteData->challengeEntry) && !empty($deleteData->challengeEntry)){
                        //notification for delete entries
                        $challengeEntries = $requiredData->challengeEntry;

                        $notifiedUsers = $challengeEntries->pluck('user_id')->toArray();
                        // dd($notifiedUsers);
                        
                        $receivers = NotificationSettings::select('user_id')->whereIn('user_id',$notifiedUsers)->where('meta_notification_id',20)->where('status',1)->get()->pluck('user_id')->toArray();
                        
                        foreach($deleteData->challengeEntry as $entries){
                            $deleteEntry = $entries;
                            // $entries->delete();

                            $deleteEntry->update([
                                'deleted_at' => date('Y-m-d H:i:s'),
                                'deleted_by' => $currentUser->id,
                            ]);

                            $sendMail[] = [
                                'email_id' => 18,
                                'user_id' => $deleteEntry->user_id,
                                'entry_id' => $deleteEntry->id,
                                'entry_name' => $deleteEntry->title,
                                'challenge_name' => $deleteData->name,
                            ];

                            if(in_array($entries->user_id, $receivers)){
                                $sendnotification[] = [
                                    'entry_name' => $deleteEntry->title, 
                                    'challenge_name' => $deleteData->name, 
                                    'user' => $currentUser->name,
                                    'receiver' => $entries->user_id,
                                ];                            
                            }
                        }                        
                    }
                    // DB::commit();
                    
                    $deleteData->update([
                        'deleted_at' => date('Y-m-d H:i:s'),
                        'status' => 3,
                        'deleted_by' => $currentUser->id,
                    ]);

                    DB::commit();

                    foreach ($sendnotification as $notificationRow) {
                        // dd($notificationRow['user']);
                        $data = array('entry_name' => $notificationRow['entry_name'],'challenge_name' => $notificationRow['challenge_name'], 'user' => $notificationRow['user']);

                        $params = new stdClass;
                        $params->receiver_id = $notificationRow['receiver'];//'1,2'
                        $params->title = "Delete Challenge";
                        $params->meta_notification_id = 20;
                        $params->sender_id = 1;
                        $data =json_encode($data);
                        Helper::sendNotification($params,$data);
                    }

                    foreach ($sendMail as $mailData) {
                        SendMailController::dynamicEmail([
                            'email_id' => $mailData['email_id'],
                            'user_id' => $mailData['user_id'],
                            'entry_id' => $mailData['entry_id'],
                            'entry_name' => $mailData['entry_name'],
                            'challenge_name' => $mailData['challenge_name'],
                        ]);
                    }
                    
                    // $receivers = NotificationSettings::select('user_id')->whereIn('user_id',$notifiedUsers)->where('meta_notification_id',20)->where('status',1)->get()->pluck('user_id')->toArray();
                    
                    // $receivers = implode(',' ,$receivers);
                    
                    // $data = array('name' => $requiredData->name, 'user' => $currentUser->name);

                    // $params = new stdClass;
                    // $params->receiver_id = $receivers;//'1,2'
                    // $params->title = "Delete Challenge";
                    // $params->meta_notification_id = 20;
                    // $params->sender_id = 1;
                    // $data =json_encode($data);
                    // Helper::sendNotification($params,$data);
                    // dd($requiredData);
                    //email for delete challenge to challenge entries(user)
                    // SendMailController::dynamicEmail([
                    //     'email_id' => 18,
                    //     'user_id' => 1,
                    //     'challenge_id' => $requiredData->id,
                    // ]);

                    $responseData['status'] = 1;
                    $responseData['message'] = trans('page.challenge_deleted');
                    $responseData['data'] = 'challenge';
                }else{
                    DB::commit();
                    $responseData['status'] = 0;
                    $responseData['message'] = trans('page.error_occurred_in_delete_challenge');   
                }
                

            }else if($deleteFor == 'entry'){
                $where = [
                    'slug' => $slug,
                    'deleted_at' => null,
                ];
                if($currentUser->type != 1){
                    $where = [
                        'slug' => $slug,
                        'deleted_at' => null,
                        'user_id' => $currentUser->id,
                    ];
                }
                $requiredData = $deleteData = ChallengeEntry::where($where)->with('challenge')->first();
                
                if(isset($deleteData) && !empty($deleteData)){
                    $deleteData->delete();
                    
                    $requiredData->update([
                        'deleted_by' => $currentUser->id,
                    ]);

                    DB::commit();

                    if($currentUser->id == 1){
                        // $requiredData->with(['challengeEntry' => function($query){
                        //     $query->select('id','user_id')->where('status',2);
                        // }])->first(); // status 2 for approved entry

                        $challengeEntries = $requiredData;
                        $notifiedUsers = $challengeEntries->user_id;
                        
                        $receivers = NotificationSettings::select('user_id')->where('user_id',$notifiedUsers)->where('meta_notification_id',21)->where('status',1)->get()->pluck('user_id')->toArray();
                        
                        $receivers = implode(',' ,$receivers);
                        
                        $data = array('name' => $challengeEntries->title, 'user' => $currentUser->name);
    
                        $params = new stdClass;
                        $params->receiver_id = $receivers;//'1,2'
                        $params->title = "Delete Entry";
                        $params->meta_notification_id = 21;
                        $params->sender_id = 1;
                        $data =json_encode($data);
                        Helper::sendNotification($params,$data);
    
                        //email for delete entry to participated
                        SendMailController::dynamicEmail([
                            'email_id' => 19,
                            'user_id' => $challengeEntries->user_id,
                            'entry_id' => $challengeEntries->id,
                            'entry_name' => $challengeEntries->title,
                            'challenge_name' => $challengeEntries->challenge->name,
                        ]);
                    }
                    $responseData['status'] = 1;
                    $responseData['message'] = trans('page.entry_delete');
                    $responseData['data'] = 'entry';
                }else{
                    DB::commit();
                    $responseData['status'] = 0;
                    $responseData['message'] = trans('page.error_occurred_in_delete_entry');  
                }
            }else{

                $responseData['status'] = 0;
                $responseData['message'] = trans('page.Sorry_something_went_worng_Please_try_again');
            }
            return $responseData;
            
        }catch(Exception $e){
            DB::rollback();
            $responseData = array();
            $responseData['status'] = 400;
            Log::emergency('Helper deleteChallengeEntry Exception :: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            $responseData['message'] = trans('common.something_went_wrong');
            return json_encode($responseData);
        }
    }

    /**
     * This function use for get Feed
     * @param Request $request
     * @param User $currentUser  
     * @param String $platform  web or api
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getFeed(Request $request, $currentUser, $platform='web'){

        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = (object)[];

        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if($validator->fails()){
                $responseData['message'] = $validator->errors()->first();
                return $responseData;
            }
            
            $feed = Feed::select('id','slug','user_id','dance_type_id','description','created_at',
            DB::raw("IF((SELECT id from spam_reportings where type = 1 AND reported_by = ".((!empty($currentUser))?$currentUser->id:0)." AND reported_id = feeds.id) , true, false) AS is_reported"),
            DB::raw("IF((SELECT id from feed_likes where user_id = ".((!empty($currentUser))?$currentUser->id:0)." AND feed_id = feeds.id) , true, false) AS isLiked"))
            ->with(['feedOwner' => function($q) use($currentUser, $request){
                    $q->select('users.id','users.name','users.slug','users.logo',
                    DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                    DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow")
                    )->where('users.is_active',1);
                },'feedWith' => function($q) use($currentUser, $request){
                $q->select('users.id','users.name','users.slug','users.logo','users.type',
                DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow")
                )->where('users.is_active',1);
            },
            'danceMusicType' => function($q) {
                $q->select('id','title','src')->withTrashed();
            },
            'feedImages' => function($q) {
                $q->select('id','feed_id','src','is_uploaded','media_type');
            },
            ])->whereHas('feedOwner', function($qa){
                $qa->where('is_active',1);
            });
            if($request->withLikeAndCommentCount == 1){
                $feed = $feed->withCount(['feedLikes' => function($q){
                    $q->whereHas('user', function($qa){
                        $qa->where('is_active',1);
                    });
                },'feedComments' => function($q){
                    // $q->where(['parent_id' => 0]);
                    $q->whereHas('user', function($qa){
                        $qa->where('is_active',1);
                    });
                }]);
            }

            if($request->my == 1){
                $feed = $feed->where(['user_id' => $currentUser->id]);
            }
            $feed = $feed->where(['id' => $request->id, 'status' => 1])
            ->first();

            if(isset($feed->id) && $feed->id > 0){
                $responseData['status'] = 1;
                $responseData['data'] = $feed;
                $responseData['message'] = trans('page.success');
            } else {
                $responseData['status'] = 0;
                $responseData['data'] = "";
                $responseData['message'] = trans('page.feed_not_exists');
            }
            return $responseData;
            
        } catch (Exception $e) {
            \Log::emergency('getFeed api Exception :: Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());

            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = $e->getMessage();
            $responseData['redirect'] = '';
            if($platform == 'web')
                Session::flash('error', $e->getMessage());
            return $responseData;
        }
    }

    /**
     * This function use for get All Feed
     * @param Request $request
     * @param User $currentUser  
     * @param String $platform  web or api
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getAllFeed(Request $request, $currentUser, $platform='web'){

        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
// echo 's:'.$currentUser->id;exit;
        try {
            $rpp = ($request->page_size) ? $request->page_size : config('constant.rpp');

            $feed = Feed::select('id','slug','user_id','dance_type_id','description','created_at',
            DB::raw("IF((SELECT id from spam_reportings where type = 1 AND reported_by = ".((!empty($currentUser))?$currentUser->id:0)." AND reported_id = feeds.id) , true, false) AS is_reported"),
            DB::raw("IF((SELECT id from feed_likes where user_id = ".((!empty($currentUser))?$currentUser->id:0)." AND feed_id = feeds.id) , true, false) AS isLiked"))
            ->with(['feedOwner' => function($q) use($currentUser, $request){
                    $q->select('users.id','users.name','users.slug','logo',
                    DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                    DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow")
                    )->where('users.is_active',1);
                },'feedWith' => function($q) use($currentUser, $request){
                $q->select('users.id','users.name','users.slug','users.logo','users.type',
                DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow")
                )->where('users.is_active',1);
            },
            'danceMusicType' => function($q) {
                $q->select('id','title','src')->withTrashed();
            },
            'feedImages' => function($q) {
                $q->select('id','feed_id','src','is_uploaded','media_type');
            },
            ])->whereHas('feedOwner', function($qa){
                $qa->where('is_active',1);
            });
            if($request->lastSyncTime != ''){
                $feed = $feed->where('created_at', '<=', $request->lastSyncTime);
            }
            if($request->slug != ''){
                $feed = $feed->where('slug', $request->slug);
            }
            if($request->withLikeAndCommentCount == 1){
                $feed = $feed->withCount(['feedLikes' => function($q){
                    $q->whereHas('user', function($qa){
                        $qa->where('is_active',1);
                    });
                },'feedComments' => function($q){
                    // $q->where(['parent_id' => 0]);
                    $q->whereHas('user', function($qa){
                        $qa->where('is_active',1);
                    });
                }]);
            }
            
            if((!empty($request->professionalSlug))){
                $professional = User::select('id')->where(['slug' => $request->professionalSlug])->first();
                if(isset($professional->id))
                    $feed = $feed->where(['user_id' => $professional->id]);
            }else if($request->myFeed == 1 && (!empty($currentUser))){
                $feed = $feed->where(['user_id' => $currentUser->id]);
            }
            
            $feed = $feed->where(['status' => 1]);

            if($platform == 'admin'){
                if($request->dance_types != ''){
                    
                    $danceTypes = $request->dance_types;
                    $feed->whereHas('danceMusicType',function($query) use ($danceTypes){
                        $query->whereIn('id',$danceTypes);
                    });
                }
                if($request->professionals != ''){
                    $professionals = $request->professionals;
                    $feed->whereHas('feedOwner',function($query) use ($professionals){
                        $query->whereIn('id',$professionals);
                    });
                }
            }

            $feed = $feed->orderBy('created_at', 'DESC')->paginate($rpp);
            $feed = $feed->toJson();
            $feed = json_decode($feed);
            unset($feed->last_page_url);
            unset($feed->first_page_url);
            unset($feed->next_page_url);
            unset($feed->prev_page_url);
            unset($feed->path);
            if($feed->current_page == 1){
                $feed->lastSyncTime = Carbon::now()->format('Y-m-d H:i:s');
            }else {
                $feed->lastSyncTime = $request->lastSyncTime;
            }
            $responseData['status'] = 1;
            $responseData['data'] = $feed;
            $responseData['message'] = trans('page.success');
            return $responseData;
            
        } catch (Exception $e) {
            \Log::emergency('getAllFeed api Exception :: Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());

            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = $e->getMessage();
            $responseData['redirect'] = '';
            if($platform == 'web')
                Session::flash('error', $e->getMessage());
            return $responseData;
        }
    }

    /**
     * this api use for get feed likers list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getLikersList(Request $request, $currentUser, $platform='web'){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
        $responseData['html'] = '';
        $responseData['current_page'] = 1;
        $responseData['total'] = 1;
        try {
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if($validator->fails()){
                $responseData['message'] = $validator->errors()->first();
                return $responseData;
            }

            $rpp = ($request->page_size) ? $request->page_size : config('constant.rpp');
            $likers = FeedLike::select('id', 'feed_id', 'user_id')->with(['user' => function($q) use($currentUser, $request){
                $q->select('id','logo','slug','type','is_nickname_use','nick_name',DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow"));
            }])->where('feed_id', $request->id)
            ->whereHas('user', function($a){
                $a->where(['is_active' => 1]);
            });
            
            if($platform == 'web'){
                if(trim($request->search_likers) != '' && $request->search_likers != null ){
                    $likers->whereHas('user',function ($q) use($request){
                        $q->where('name', 'like', "%$request->search_likers%");
                        $q->orwhere('nick_name', 'like', "%$request->search_likers%");
                    });
                }
            }
            // dd($likers);
            $likersData = $likers->paginate($rpp);
            
            $likerUser = $likersData->pluck('user')->toArray();
            $likersData = $likersData->toJson();
            $likersData = $results = json_decode($likersData);
            $likersData->data = $likerUser;
            unset($likersData->last_page_url);
            unset($likersData->first_page_url);
            unset($likersData->next_page_url);
            unset($likersData->prev_page_url);
            unset($likersData->path);
            $responseData['current_page'] = isset($likersData->current_page)?$likersData->current_page:1;
            $responseData['next_page'] = isset($likersData->current_page)?$likersData->current_page+1:2;
            $responseData['total'] = isset($likersData->last_page)?$likersData->last_page:0;

            $responseData['status'] = 1;
            $responseData['data'] = $likersData;
            if($platform != 'api'){
                $responseData['html'] = view('admin.pages.ajax.admin-get-likers', compact('results'))->render();
            }
            $responseData['message'] = trans('page.success');
            return $responseData;

        } catch(Exception $e){
            \Log::emergency('getLikersList api Exception :: Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());

            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = $e->getMessage();
            $responseData['redirect'] = '';
            if($platform == 'web')
                Session::flash('error', $e->getMessage());
            return $responseData;
        }
    }

    /**
     * this api use for get feed tagged user's list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getTaggedUserList(Request $request, $currentUser, $platform='web'){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if($validator->fails()){
                $responseData['message'] = $validator->errors()->first();
                return $responseData;
            }

            $rpp = ($request->page_size) ? $request->page_size : config('constant.rpp');
            $taggedUsers = FeedWith::select('id', 'feed_id', 'user_id')->with(['user' => function($q) use($currentUser, $request){
                $q->select('id','logo','slug','type',DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow"));
            }])->where('feed_id', $request->id)
            ->whereHas('user', function($a){
                $a->where(['is_active' => 1]);
            });
            $taggedUsers = $taggedUsers->
            // orderBy('created_at', 'DESC')->
            paginate($rpp);

            $likerUser = $taggedUsers->pluck('user')->toArray();
            $taggedUsers = $taggedUsers->toJson();
            $taggedUsers = json_decode($taggedUsers);
            $taggedUsers->data = $likerUser;
            unset($taggedUsers->last_page_url);
            unset($taggedUsers->first_page_url);
            unset($taggedUsers->next_page_url);
            unset($taggedUsers->prev_page_url);
            unset($taggedUsers->path);

            $responseData['status'] = 1;
            $responseData['data'] = $taggedUsers;
            $responseData['message'] = trans('page.success');
            return $responseData;

        } catch(Exception $e){
            \Log::emergency('getTaggedUserList api Exception :: Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());

            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = $e->getMessage();
            $responseData['redirect'] = '';
            if($platform == 'web')
                Session::flash('error', $e->getMessage());
            return $responseData;
        }
    }
    /**
     * This api use for get all challenges from plateform,
     * @param $request
     * @return array
     */
    public static function getAllChallenges($request)
    {
        $today = Carbon::today()->format('Y-m-d H:i:s');
        $challenges = Challenge::where(['status' => 1,'deleted_at' => null])->whereDate('start_date','<=',$today)->whereDate('end_date','>=',$today)->select('id','name as title','slug');

        if($request->search != ''){
            $challenges->where(function($query) use ($request){
                $query->where('name','like','%'.$request->search.'%');
            });
        }
        
        $challenges = $challenges->orderBy('created_at','desc')->paginate(config('constant.rpp'))->toJson();

        $data = json_decode($challenges);

        $response = array();
        $response['results'] = $data->data;
        $response['total_count'] = $data->total;
        return $response;
    }

    /**
     * This api use for get all hastags selected in entries
     * @param $request
     * @return array
     */
    public static function entryHashTagList($request)
    {
        // dd($request->all());
        $hashtags = HashTag::select('id','name As label');
        
        if($request['query'] != ''){
            $keyword = $request['query'];
            $keyword = trim($keyword,'#');
            $hashtags->where(function($q) use ($keyword){
                $q->where('name','like','%'.$keyword.'%');
            });
        }
        /**
         * for not showing already selected hashtags again
         */
        $selectedHashtags = explode(',',$request['selectedHashtags']);
        
        // $hashtags->whereNotIn('id',$selectedHashtags);
        $hashtags->whereNotIn('name',$selectedHashtags);
        
        $finalHashtags = $hashtags->paginate(config('constant.rpp'))->toJson();
        
        $sugestedHashTags = json_decode($finalHashtags,true);
        
        foreach ($sugestedHashTags['data'] as $key => $value) {
            $sugestedHashTags['data'][$key]['label'] = '#'.$value['label'];
            $sugestedHashTags['data'][$key]['id'] = $value['id'];
            $sugestedHashTags['data'][$key]['value'] = $value['label'];
        }
        // dd($sugestedHashTags);

        $response = array();
        $response['results'] = $sugestedHashTags['data'];
        // $response['total_count'] = $data->total;
        // dd($response['results']);
        return $response;
    }

    //ENTRY EXISTS COUNTRY DROPDOWN
    public static function entryCountryList(){
        $where['up.deleted_at'] = null;

        $data = DB::table('countries as cr')
        ->select(DB::raw("cr.id,(cr.name)  AS title,(cr.created_at) AS other"))
        ->join('challenge_entries as up', function($join) {
            $join->on('cr.id', '=', 'up.country_id');
        })
        ->whereNotNull('cr.id')
        ->groupBy('cr.id')
        ->where($where);

        $data  = $data->get();
        return $data;
    }

    //Vote entry
    public static function upvoteEntry($currentUser = array(),$request = null){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'slug' => 'required',
            ]);
            if($validator->fails()){
                DB::rollback();
                $responseData['message'] = $validator->errors()->first();
                return $responseData;
            }
            if($currentUser != null){
                //User must be active to vote for entry
                if($currentUser->is_active == 1){
                    //Entry must be approved by admin
                    $where = [
                        'deleted_at' => null,
                        'status' => 2,
                        'slug' => $request->slug,
                    ];
                    $date = Carbon::today()->format('Y-m-d H:i:s');
                    //Challenge must be active to vote for that particular entry
                    $requestedEntry = ChallengeEntry::where($where)->select('id','challenge_id','user_id')->with(['challenge' => function($query) use ($date){
                        $query->where(['deleted_at' => null,'status' => 1])
                        ->select('id','name','slug')
                        ->whereDate('start_date','<=',$date)
                        ->whereDate('end_date','>=',$date);
                    }])->whereHas('challenge',function($query) use ($date){
                        $query->where(['deleted_at' => null,'status' => 1])
                        ->whereDate('start_date','<=',$date)
                        ->whereDate('end_date','>=',$date);
                    })->first();

                    if(isset($requestedEntry) && $requestedEntry != null){
                        $where_vote = [
                            'entry_id' => $requestedEntry->id,
                            'challenge_id' => $requestedEntry->challenge_id,
                            'voted_user_id' => $currentUser->id,
                        ];
                        $vote = EntryVote::where($where_vote)->first();
                        if(isset($vote) && $vote != null){
                            $result['status'] = false;
                            $vote = $vote->delete();
                        } else {
                            $result['status'] = true;
                            $vote = EntryVote::create($where_vote);
                        }
                        DB::commit();

                        //Get Vote Count
                        $vote_count = EntryVote::where(['entry_id' => $requestedEntry->id,
                        'challenge_id' => $requestedEntry->challenge_id])
                        ->whereHas('user',function($query) use ($date){
                            $query->where(['deleted_at' => null,'is_active' => 1]);
                        })
                        ->whereHas('challenge',function($query) use ($date){
                            $query
                            ->where(['deleted_at' => null,'status' => 1])
                            ->whereDate('start_date','<=',$date)
                            ->whereDate('end_date','>=',$date);
                        })
                        ->whereHas('entry',function($query) use ($date){
                            $query->where(['deleted_at' => null,'status' => 2]);
                        })->count();

                        $result['total_votes'] = $vote_count;
                        $responseData['status'] = 1;
                        $responseData['message'] = trans('page.success');
                        $responseData['data'] = $result;

                        $socketData = array(
                            'event' => 'upVote',
                            'data' => (object)array(
                                'entrySlug' => $request->slug,
                                'upVotedBy' => $currentUser->slug,
                                'isUpvote' => $result['status'],
                                'totalVotes' => $result['total_votes'],
                            ),
                        );
                        event(new EntryVoteSocket($socketData));
                        
                    } else {
                        DB::rollback();
                        $responseData['status'] = 0;
                        $responseData['message'] = trans('page.entry_not_exists');
                    }
                } else {
                    DB::rollback();
                    $responseData['status'] = 0;
                    $responseData['message'] = trans('page.user_not_active');
                }
            } else {
                DB::rollback();
                $responseData['status'] = 0;
                $responseData['message'] = trans('page.user_not_exists');
            }
            return $responseData;
        } catch(Exception $e){
            DB::rollback();
            Log::emergency('upvoteEntry Exception :: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            $responseData['message'] = trans('common.something_went_wrong');
            return $responseData;
        }
    }

    /**
     * This api use for delete own feed
     * @param Request $request
     * @param User $currentUser
     * @param string $platform web or api
     * @return \Illuminate\Http\JsonResponse
     */
    public static function deleteFeed(Request $request, $currentUser, $platform='web'){
        DB::beginTransaction();
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = (object)[];
        try{
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if($validator->fails()){
                DB::rollback();
                $responseData['message'] = $validator->errors()->first();
                return $responseData;
            }else{
                if($currentUser && ($currentUser->type == 3 || $currentUser->type == 1 || $currentUser->type == 5)){
                    $where = [
                        'deleted_at' => null,
                        'id' => $request->id,
                    ];
                    if(($currentUser->type != 1) && ($currentUser->type != 5)){
                        $where['user_id'] = $currentUser->id;
                    }

                    $feed = Feed::where($where)
                    ->update([
                        'deleted_by' => $currentUser->id,
                        'deleted_at' => Carbon::now(),
                    ]);

                    if($feed){
                        DB::commit();
                        $responseData['message'] = trans('page.feed_delete');
                        $responseData['status'] = 1;
                        /**
                         * Fire feed delete EVENT
                         */
                        $socketData = array(
                            'event' => 'postDelete',
                            'data' => (object)array(
                                'feedId' => (int)$request->id,
                            ),
                        );
                        event(new DeletePostEventSocket($socketData));
                    } else {
                        DB::rollback();
                        $responseData['message'] = trans('page.error_occurred_in_delete_feed');
                        $responseData['status'] = 0;
                    }                
                } else {
                    DB::rollback();
                    $responseData['status'] = 0;
                    $responseData['message'] = trans('page.user_not_exists');
                }
            }
            return $responseData;
        } catch(Exception $e){
            DB::rollback();
            Log::emergency('deleteFeed Exception :: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            $responseData['message'] = trans('common.something_went_wrong');
            return $responseData;
        }
    }

    /**
     * This api use for like feed
     * @param Request $request
     * @param User $currentUser
     * @param string $platform web or api
     * @return \Illuminate\Http\JsonResponse
     */
    public static function likeFeed(Request $request, $currentUser, $platform='web'){
        DB::beginTransaction();
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = (object)[];
        try{
            $validator = Validator::make($request->all(), [
                'feed_id' => 'required',
            ]);
            if($validator->fails()){
                DB::rollback();
                $responseData['message'] = $validator->errors()->first();
                return $responseData;
            }else{
                if((!empty($currentUser)) && isset($currentUser->id)) {
                    if($currentUser->is_active == 1){
                        if( isset($currentUser->is_profile_filled) && $currentUser->is_profile_filled == 0) {
                            DB::rollback();
                            $responseData['status'] = 0;
                            $responseData['message'] = trans('page.please_fill_profile');
                        }else {
                            $result['status'] = true;
                            $liked = false;
                            $where_feedLike = [
                                'feed_id' => $request->feed_id,
                                'user_id' => $currentUser->id,
                            ];
                            $feedLike = FeedLike::where($where_feedLike)->first();
                            if(isset($feedLike) && $feedLike != null){
                                $result['status'] = false;
                                $feedLike = $feedLike->delete();
                                $liked = ($feedLike == 1)?true:false;                        
                            } else {
                                $result['status'] = true;
                                $vote = FeedLike::create($where_feedLike);
                                $liked = (isset($vote->id) && $vote->id > 0)?true:false;
                            }
                            if($liked){
                                DB::commit();
                                
                                /**
                                 * get total likes count
                                 */
                                $feedLikes = FeedLike::select('id','user_id')
                                ->where('feed_id',$request->feed_id)
                                ->whereHas('user', function($qa){
                                    $qa->where('is_active',1);
                                })->count();

                                $responseData['message'] = ($result['status'] == true)?trans('page.feed_has_been_liked_successfully'):trans('page.feed_has_been_disliked_successfully');
                                $responseData['status'] = 1;
                                $responseData['totalLikes'] = $feedLikes;
                                /**
                                 * Fire feed like EVENT
                                 */
                                $socketData = array(
                                    'event' => 'postLike',
                                    'data' => (object)array(
                                        'feedId' => (int)$request->feed_id,
                                        'likedBy' => $currentUser->slug,
                                        'isliked' => $result['status'],
                                        'totalLikes' => $feedLikes,
                                        'totalComments' => 0,
                                        'action' => 'feedLike',
                                        'totalComments' => 0
                                    ),
                                );
                                event(new LikePostEventSocket($socketData));
                                
                            }else{
                                DB::rollback();
                                $responseData['message'] = ($result['status'] == true)?trans('page.error_in_feed_like'):trans('page.error_in_feed_dislike');
                            }
                        }
                    } else {
                        DB::rollback();
                        $responseData['status'] = 0;
                        $responseData['message'] = trans('page.user_not_active');
                    }
                }else {
                    DB::rollback();
                    $responseData['status'] = 2;
                    $responseData['message'] = trans('auth.login_first');
                }
            }
            return $responseData;
        } catch(Exception $e){
            DB::rollback();
            Log::emergency('deleteFeed Exception :: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            $responseData['message'] = trans('common.something_went_wrong');
            return $responseData;
        }
    }

    /**
     * This function use for Add/Edit Feed Comment
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function addFeedComment(Request $request, $currentUser, $platform='web'){
        DB::beginTransaction();
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = (object) [];

        try {
            if( isset($currentUser->is_profile_filled) && $currentUser->is_profile_filled == 0) {
                DB::rollback();
                $responseData['status'] = 3;
                $responseData['message'] = trans('page.please_fill_profile');
            }else{
                $validationArray = [
                    'feed_id' => "required",
                    'comment' => "required|min:1|max:2000",
                ];

                $validator = Validator::make($request->all(),$validationArray , [
                    'feed_id.required' => "Feed id is require",
                    'comment.required' => "Please enter comment",
                    'comment.min' => "Minimum 2 character are require to add comment",
                    'comment.max' => "Maximim 15000 character are allow in comment",
                ]);

                if ($validator->fails()) {
                    DB::rollback();
                    $responseData['message'] = $validator->errors()->first();
                    $responseData['errors'] = $validator->errors()->toArray();
                    return $responseData;
                }

                $feedCommnetDetails = [
                    'feed_id' => $request->feed_id,
                    'parent_id' => ($request->parentId > 0)?$request->parentId:0,
                    'comment' => $request->comment,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                ];

                $feedComment = FeedComment::create(
                    $feedCommnetDetails
                );
                if(isset($feedComment->id) && $feedComment->id > 0){
                    DB::commit();
                    if ($request->id > 0) {
                        $responseData['message'] = trans('page.feed_comment_update_successfully');
                    } else {
                        $responseData['message'] = trans('page.feed_comment_added_successfully');
                    }
                    $responseData['status'] = 1;

                    /**
                     * Fire Add/Edit Comment Event.
                     */ 
                    $request['id'] = $feedComment->id;
                    $request['parentCommnetId'] = $feedComment->parent_id;
                    if($feedComment->parent_id > 0){
                        // echo "<pre>"; print_r($request->all());exit;
                        $socketRecordData = Helper::getFeedSubComment($request, $currentUser, 'web');
                    }else{
                        $socketRecordData = Helper::getFeedComment($request, $currentUser, 'web');
                    }
                    // echo "<pre>"; print_r($socketRecordData['data']->data[0]);exit;
                        /**
                         * to get feed comment count
                         */
                        $request->id = 0;
                        $request->wantCount = true;
                        $likeSocketRecordData = Helper::getFeedComment($request, $currentUser, 'web');
                        /**
                         * Fire feed like EVENT
                         */
                        $socketData = array(
                            'event' => 'postLike',
                            'data' => (object)array(
                                'feedId' => (int)$request->feed_id,
                                'likedBy' => 0,
                                'isliked' => false,
                                'totalLikes' => 0,
                                'action' => 'feedcomment',
                                'totalComments' => isset($likeSocketRecordData['data'])?$likeSocketRecordData['data']:0
                            ),
                        );
                        event(new LikePostEventSocket($socketData));
                    // }
                    $socketRecordRowData = (isset($socketRecordData['data']->data[0]))?$socketRecordData['data']->data[0]:array();
                    // echo "<pre>"; print_r($socketRecordRowData);exit;
                    $socketDataCM = array(
                        'event' => 'postCommentAdd',
                        'data' => (object)$socketRecordRowData,
                    );
                    event(new CommentPostEventSocket($socketDataCM));

                    /**
                     * comment add 
                     */
                }else{
                    DB::rollback();
                    $responseData['message'] = trans('page.feed_comment_error');
                }
            }
            return $responseData;
        } catch (Exception $e) {
            DB::rollback();
            \Log::emergency('addFeedComment api Exception :: Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());
            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = $e->getMessage();
            $responseData['redirect'] = '';
            return $responseData;
        }
    }

    /**
     * this api use for get feed comment list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getFeedComment(Request $request, $currentUser, $platform='web'){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
        try {
            $validator = Validator::make($request->all(), [
                'feed_id' => 'required',
            ]);
            if($validator->fails()){
                $responseData['message'] = $validator->errors()->first();
                return $responseData;
            }

            $rpp = ($request->page_size) ? $request->page_size : config('constant.rpp');
            $feedComments = FeedComment::select('id', 'feed_id', 'created_by','created_by as commentBy','parent_id','comment','created_at')->with(['feed:id,user_id','singleSubComment' => function($q) use($request, $currentUser){
                $q->select('id', 'feed_id', 'created_by','created_by as commentBy','parent_id','comment','created_at')
                ->orderBy('created_at', 'DESC')->whereHas('user', function($a){
                    $a->where(['is_active' => 1]);
                })
                ->with(['user' => function($q) use($currentUser, $request){
                        $q->select('id','logo','slug','type',DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                        DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow"));
                    }]);
            },'user' => function($q) use($currentUser, $request){
                $q->select('id','logo','slug','type',DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow"));
            }])->where(['feed_id'=>$request->feed_id])
                // }])->where(['feed_id'=>$request->feed_id,'parent_id'=>0])
            ->whereHas('user', function($a){
                $a->where(['is_active' => 1]);
            })
            ->whereHas('feed', function($a){
                $a->where(['status' => 1]);
            })->withCount('subComment');
            if($request->lastSyncTime != ''){
                $feedComments = $feedComments->where('created_at', '<=', $request->lastSyncTime);
            }
            if($request->id != ''){
                $feedComments = $feedComments->where('id', $request->id);
            }
            $feedComments = $feedComments->orderBy('created_at', 'DESC');
            if($request->wantCount){
                $feedComments = $feedComments->count();
            }else{
                $feedComments = $feedComments->where('parent_id', 0);
                $feedComments = $feedComments->paginate($rpp);
            
                $feedComments = $feedComments->toJson();
                $feedComments = json_decode($feedComments);
                unset($feedComments->last_page_url);
                unset($feedComments->first_page_url);
                unset($feedComments->next_page_url);
                unset($feedComments->prev_page_url);
                unset($feedComments->path);
                if($feedComments->current_page == 1){
                    $feedComments->lastSyncTime = Carbon::now()->format('Y-m-d H:i:s');
                }else {
                    $feedComments->lastSyncTime = $request->lastSyncTime;
                }
            }
            $responseData['status'] = 1;
            $responseData['data'] = $feedComments;
            $responseData['message'] = trans('page.success');
            return $responseData;

        } catch(Exception $e){
            \Log::emergency('getFeedComment api Exception :: Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());

            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = $e->getMessage();
            $responseData['redirect'] = '';
            return $responseData;
        }
    }

    /**
     * this api use for get feed sub-comment list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getFeedSubComment(Request $request, $currentUser, $platform='web'){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
        try {
            if( isset($currentUser->is_profile_filled) && $currentUser->is_profile_filled == 0) {
                DB::rollback();
                $responseData['status'] = 3;
                $responseData['message'] = trans('page.please_fill_profile');
            }else{
                $validator = Validator::make($request->all(), [
                    'feed_id' => 'required',
                    'parentCommnetId' => 'required',
                ]);
                if($validator->fails()){
                    $responseData['message'] = $validator->errors()->first();
                    return $responseData;
                }

                $rpp = ($request->page_size) ? $request->page_size : config('constant.rpp');

                $feedComments = FeedComment::select('id', 'feed_id', 'created_by','created_by as commentBy' ,'parent_id','comment','created_at')->with(['feed:id,user_id','user' => function($q) use($currentUser, $request){
                    $q->select('id','logo','slug','type',DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                    DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow"));
                }])->where(['feed_id'=>$request->feed_id, 'parent_id' => $request->parentCommnetId])
                ->whereHas('user', function($a){
                    $a->where(['is_active' => 1]);
                })
                ->whereHas('feed', function($a){
                    $a->where(['status' => 1]);
                });

                if($request->lastSyncTime != ''){
                    $feedComments = $feedComments->where('created_at', '<=', $request->lastSyncTime);
                }
                if($request->id != ''){
                    $feedComments = $feedComments->where('id', $request->id);
                }
                $feedComments = $feedComments->orderBy('created_at', 'DESC')->paginate($rpp);
                $feedComments = $feedComments->toJson();
                $feedComments = json_decode($feedComments);

                unset($feedComments->last_page_url);
                unset($feedComments->first_page_url);
                unset($feedComments->next_page_url);
                unset($feedComments->prev_page_url);
                unset($feedComments->path);
                if($feedComments->current_page == 1){
                    $feedComments->lastSyncTime = Carbon::now()->format('Y-m-d H:i:s');
                }else {
                    $feedComments->lastSyncTime = $request->lastSyncTime;
                }
                $responseData['status'] = 1;
                $responseData['data'] = $feedComments;
                $responseData['message'] = trans('page.success');
            }
            return $responseData;

        } catch(Exception $e){
            \Log::emergency('getFeedComment api Exception :: Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());

            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = $e->getMessage();
            $responseData['redirect'] = '';
            return $responseData;
        }
    }

    /**
     * this function use for get time of feed post
     */
    public static function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';

        /**
         * echo time_elapsed_string('2013-05-01 00:22:35');
         * echo time_elapsed_string('@1367367755'); # timestamp input
         * echo time_elapsed_string('2013-05-01 00:22:35', true);
         * OUTPUT
         * 4 months ago
         * 4 months, 2 weeks, 3 days, 1 hour, 49 minutes, 15 seconds ago
         */
    }

    public static function timeAgoFeed($timestamp){
  
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

            } else{
                // if(1){
                    $rDate =  Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone(env('APP_TIMEZONE'))->format('M d Y');
                    $rDate .=  ' at '.Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC')->setTimezone(env('APP_TIMEZONE'))->format('h:i A');
                // }else{
                //     $rDate =  Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format('M d Y');
                //     $rDate .=  ' at '.Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format('h:i A');
                // }
                return $rDate;
            }
            
            // else if ($days <= 7){

            //     if ($days == 1){

            //     return "yesterday";

            //     } else {

            //     return "$days days ago";

            //     }

            // } else if ($weeks <= 4.3){

            //     if ($weeks == 1){

            //     return "a week ago";

            //     } else {

            //     return "$weeks weeks ago";

            //     }

            // } else if ($months <= 12){

            //     if ($months == 1){

            //     return "a month ago";

            //     } else {

            //     return "$months months ago";

            //     }

            // } else {
                
            //     if ($years == 1){

            //     return "one year ago";

            //     } else {

            //     return "$years years ago";

            //     }
            // }
        }

        public static function timeAgoFeedComent($timestamp){
  
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

            } else if ($days <= 7){

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
        }

        /**
         * This function use for get feed  discription
         */
        public static function getFeedDisciption($description){
            $newDescription = '';
            // $description = 'Hi :1#kaushik:1#kaushik_1:1#How Are You?';
            $descriptionArray = explode(':1#',$description);
            for($i = 0; $i<count($descriptionArray);$i++){
                if(isset($descriptionArray[$i]))
                    $newDescription .= $descriptionArray[$i];
                if(isset($descriptionArray[$i+1])){
                    $slugString = isset($descriptionArray[$i+2])?$descriptionArray[$i+2]:'';
                    $slugArray = explode('~',$slugString);
                    $slug = isset($slugArray[0])?$slugArray[0]:'';
                    $userType = isset($slugArray[1])?$slugArray[1]:2;
                    $href = ($userType == 2)?'javascript:void(0);':route('professional.viewProfile',$slug);
                    $newDescription .= "<a href='".$href."'>".$descriptionArray[$i+1]."</a>";
                    $i = $i+2;
                }
            }
            return $newDescription;
        }

        //LeaderBoard Entry api
    public static function leaderBoardEntries($currentUser = array(),$request=null){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
        try{
            $result['user'] = null;
            // dd($currentUser);
            $date = Carbon::today()->format('Y-m-d H:i:s');
            $votes = ChallengeEntry::where(['deleted_at' => null,'status' => 2])->with(['challenge:id,name','totalVotes' => function($query){
                $query->select('id','entry_id','challenge_id','created_at');
            },'user:id,name,slug,logo,type'])
            ->leftJoin('entry_votes', function($leftJoin)
            {
                $leftJoin->on('challenge_entries.id', '=', 'entry_votes.entry_id')
                ->where(function($q){
                    $q->whereRaw('entry_votes.id IN (select MAX(entry_votes.id) FROM entry_votes GROUP BY entry_id)');
                });
            })
            ->select('challenge_entries.id','challenge_entries.title','challenge_entries.slug','challenge_entries.user_id','challenge_entries.challenge_id','entry_votes.created_at as voted_created_at')
            ->whereHas('user',function($query) use ($date){
                $query->where(['deleted_at' => null,'is_active' => 1]);
            })
            ->whereHas('totalVotes',function($query) use ($date){
                // $query->whereHas('challenge',function($q) use ($date){
                //     $q
                //     ->where(['deleted_at' => null,'status' => 1])
                //     ->whereDate('start_date','<=',$date)
                //     ->whereDate('end_date','>=',$date);
                // });
                $query->whereHas('user',function($q){
                    $q->where(['deleted_at' => null,'is_active' => 1]);
                });
                $query->whereHas('entry',function($q){
                    $q->where(['deleted_at' => null,'status' => 2]);
                });
            })
            ->whereHas('challenge',function($query) use ($date){
                $query
                ->where(['deleted_at' => null,'status' => 2])
                ->whereDate('end_date','<',$date);
            })->withCount(['totalVotes' => function($query) use ($date){
                // $query->whereHas('challenge',function($q) use ($date){
                //     $q
                //     ->where(['deleted_at' => null,'status' => 1])
                //     ->whereDate('start_date','<=',$date)
                //     ->whereDate('end_date','>=',$date);
                // });
                $query->whereHas('user',function($q){
                    $q->where(['deleted_at' => null,'is_active' => 1]);
                });
                $query->whereHas('entry',function($q){
                    $q->where(['deleted_at' => null,'status' => 2]);
                });
            }])
            ->orderBy('total_votes_count','desc')
            ->orderBy('voted_created_at','asc')
            ->limit(10)
            ->get();

            if(count($votes) > 0 && $votes != null){
                $i = 1;
                foreach($votes as $vote){
                    //Challenge
                    $vote->challenge_name = $vote->challenge->name;
                    //User
                    $vote->user_name = $vote->user->name;
                    $vote->user_logo = $vote->user->logo;
                    $vote->user_slug = $vote->user->slug;
                    $vote->isProfessional = ($vote->user->type == 3) ? 1 : 0;
                    $vote->rank = $i;
                    $i++;
                    unset($vote->challenge);
                    unset($vote->user);
                    unset($vote->totalVotes);
                    unset($vote->voted_created_at);
                    // unset($vote->user_id);
                    unset($vote->challenge_id);
                }

                if($currentUser != null){
                    $user = $votes->whereIn('user_id',$currentUser->id)->first();
                    $result['user'] = $user;
                }

                $result['leaderBoard'] = $votes;

                $responseData['status'] = 1;
                $responseData['message'] = trans('page.success');
                $responseData['data'] = $result;
            } else {
                $responseData['status'] = 0;
                $responseData['message'] = trans('page.no_record_found');
            }
            return $responseData;
        } catch(Exception $e){
            Log::emergency('leaderBoardEntries Exception :: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            $responseData['message'] = trans('common.something_went_wrong');
            return $responseData;
        }
    }

    //LeaderBoard User api
    public static function leaderBoardUsers($currentUser = array(),$request = null){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
        try{
            $result['user'] = null;
            $date = Carbon::today()->format('Y-m-d H:i:s');
            $votes = User::where(['is_active' => 1,'deleted_at' => null])
            ->with('totalVotes')
            ->whereHas('totalVotes',function($query){
                $query->whereHas('user',function($q){
                    $q->where(['deleted_at' => null,'is_active' => 1]);
                });
                $query->whereHas('entry',function($q){
                    $q->where(['deleted_at' => null,'status' => 2]);
                });
            })
            ->leftJoin('entry_votes', function($leftJoin)
            {
                $leftJoin->on('users.id', '=', 'entry_votes.voted_user_id')
                ->where(function($q){
                    $q->whereRaw('entry_votes.id IN (select MAX(entry_votes.id) FROM entry_votes GROUP BY voted_user_id)');
                });
            })->select('users.id','users.type','slug',DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),DB::raw("IF((SELECT id from user_followers where user_followers.followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_followers.user_id = users.id) , true, false) AS isFollow"),'logo','entry_votes.created_at as voted_created_at')
            ->whereHas('entries',function($query) use ($date){
                $query->where(['status' => 2,'deleted_at' => null])->whereHas('challenge',function($query) use ($date){
                    $query->where(['status' => 2,'deleted_at' => null])->where('end_date','<',$date);
                });
            })
            ->whereHas('votes',function($query) use ($date){
                // $query->whereHas('challenge',function($q) use ($date){
                //     $q
                //     ->where(['deleted_at' => null,'status' => 1])
                //     ->whereDate('start_date','<=',$date)
                //     ->whereDate('end_date','>=',$date);
                // });
                $query->whereHas('user',function($q){
                    $q->where(['deleted_at' => null,'is_active' => 1]);
                });
                $query->whereHas('entry',function($q){
                    $q->where(['deleted_at' => null,'status' => 2]);
                });
            })
            ->withCount('totalVotes')
            ->orderBy('total_votes_count','desc')
            ->orderBy('voted_created_at','asc')
            ->limit(10)
            ->get();

            if(count($votes) > 0 && $votes != null){
                $i = 1;
                foreach($votes as $vote){
                    $vote->rank = $i;
                    $i++;
                    $vote->isProfessional = ($vote->type == 3) ? 1 : 0;
                    unset($vote->voted_created_at);
                    // unset($vote->user_id);
                    // unset($vote->challenge_id);
                    // unset($vote->totalVotes);
                }

                if($currentUser != null){
                    $user = $votes->whereIn('id',$currentUser->id)->first();
                    $result['user'] = $user;
                }
                $result['leaderBoard'] = $votes;
                $responseData['status'] = 1;
                $responseData['message'] = trans('page.success');
                $responseData['data'] = $result;
            } else {
                $responseData['status'] = 0;
                $responseData['message'] = trans('page.no_record_found');
            }
            return $responseData;
        } catch(Exception $e){
            Log::emergency('leaderBoardUsers Exception :: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            $responseData['message'] = trans('common.something_went_wrong');
            return $responseData;
        }
    }

    public static function getSlugWithLogo($slug = null,$logo = null){
        $defaultProUrl = Helper::images(config('constant.default_profile_url')).'default.png';
        $profilePicOrgDynamicUrl = str_replace('{userSlug}', $slug, config('constant.profile_url'));
        $profilePicThumbDynamicUrl = str_replace('{userSlug}', $slug, config('constant.profile_thumb_url'));
        
        $profile_url = Helper::images($profilePicOrgDynamicUrl);
        $profile_thumb_url = Helper::images($profilePicThumbDynamicUrl);

        if(isset($logo) && $logo != ''){
            $image = $profile_thumb_url.$logo;
        } else {
            $image = $defaultProUrl;
        }
        return $image;
    }

    /**
     * This api use for deleteFeedComment own feed
     * @param Request $request
     * @param User $currentUser
     * @param string $platform web or api
     * @return \Illuminate\Http\JsonResponse
     */
    public static function deleteFeedComment(Request $request, $currentUser, $platform='web'){
        DB::beginTransaction();
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = (object)[];
        try{
            $validator = Validator::make($request->all(), [
                'comment_id' => 'required',
                'feed_id' => 'required',
            ]);
            if($validator->fails()){
                DB::rollback();
                $responseData['message'] = $validator->errors()->first();
                return $responseData;
            }else{

                $feedComments = FeedComment::select('id', 'feed_id', 'created_by','parent_id')->with(['feed:id,user_id'])
                ->where(['feed_id'=>$request->feed_id, 'id' => $request->comment_id])
                ->whereHas('user', function($a){
                    $a->where(['is_active' => 1]);
                })
                ->whereHas('feed', function($a){
                    $a->where(['status' => 1]);
                })->first();

                if(isset($feedComments->id) && $feedComments->id > 0){
                    \Log::info($currentUser->id);
                    \Log::info('$currentUser->id');
                    \Log::info($feedComments->created_by);
                    \Log::info('$feedComments->created_by');
                    if(($currentUser->id == $feedComments->created_by) || ($currentUser->id == $feedComments->feed->user_id) || $currentUser->type == 1 || $currentUser->type == 5){
                        $where = [
                            'deleted_at' => null,
                            'id' => $request->comment_id,
                            'feed_id' => $request->feed_id,
                        ];
                        $feedC = FeedComment::where($where)
                        ->update([
                            'deleted_by' => $currentUser->id,
                            'deleted_at' => Carbon::now(),
                        ]);
                        /**
                         * feed subcoment deete
                         */
                        $where = [
                            'parent_id' => $request->comment_id,
                            'feed_id' => $request->feed_id,
                        ];
                        $feedSC = FeedComment::where($where)
                        ->update([
                            'deleted_by' => $currentUser->id,
                            'deleted_at' => Carbon::now(),
                        ]);
                            
                        if($feedC){
                            DB::commit();
                            $responseData['message'] = trans('page.feed_comment_delete');
                            $responseData['status'] = 1;
                            /**
                             * Fire feed comemnt delete EVENT
                             */
                            $socketData = array(
                                'event' => 'postCommentDelete',
                                'data' => (object)array(
                                    'feedId' => (int)$request->feed_id,
                                    'parentId' => (int)$feedComments->parent_id,
                                    'commentId' => (int)$request->comment_id,
                                ),
                            );
                            event(new DeletePostCommentEventSocket($socketData));

                            /**
                             * to get feed comment count
                             */
                            $request->id = 0;
                            $request->wantCount = true;
                            $likeSocketRecordData = Helper::getFeedComment($request, $currentUser, 'web');
                            /**
                             * Fire feed like EVENT
                             */
                            $socketData = array(
                                'event' => 'postLike',
                                'data' => (object)array(
                                    'feedId' => (int)$request->feed_id,
                                    'likedBy' => 0,
                                    'isliked' => false,
                                    'totalLikes' => 0,
                                    'action' => 'feedCommentDelete',
                                    'totalComments' => isset($likeSocketRecordData['data'])?$likeSocketRecordData['data']:0
                                ),
                            );
                            event(new LikePostEventSocket($socketData));

                        } else {
                            DB::rollback();
                            $responseData['message'] = trans('page.error_occurred_in_delete_feed_comment');
                            $responseData['status'] = 0;
                        }
                    } else {
                        DB::rollback();
                        $responseData['message'] = trans('page.no_rights_to_delete_comment');
                        $responseData['status'] = 0;
                    }                
                } else {
                    DB::rollback();
                    $responseData['status'] = 0;
                    $responseData['message'] = trans('page.feed_comment_not_exists');
                }
            }
            return $responseData;
        } catch(Exception $e){
            DB::rollback();
            Log::emergency('deleteFeed Exception :: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            $responseData['message'] = trans('common.something_went_wrong');
            return $responseData;
        }
    }

public static function videoCompress(Array $requestArray = array()){
        Log::info('find /tmp -type f -exec rm -f {} \;'); 
        exec('find /tmp -type f -exec rm -f {} \;');

        Log::info('Call HELPER videoCompress with '. print_r($requestArray, true));
        
        $videoName = isset($requestArray['videoName'])?$requestArray['videoName']:'';

        $draftImagePath = isset($requestArray['draftImagePath']) ? $requestArray['draftImagePath'] : '';

        $requestArray['user_id'] = isset($requestArray['user_id'])?$requestArray['user_id']:0;

        $requestArray['slug'] = isset($requestArray['slug'])?$requestArray['slug']:'';

        $requestArray['destinationPath'] = isset($requestArray['destinationPath']) ? $requestArray['destinationPath'] : '';

        $requestArray['thumbnailPath'] = isset($requestArray['thumbnailPath']) ? $requestArray['thumbnailPath'] : '';

        $destinationPath = str_replace('{entrySlug}', $requestArray['slug'], $requestArray['destinationPath']);

        $createThumbnail = isset($requestArray['createThumbnail'])?$requestArray['createThumbnail']:true;

        $thumbnailPath = str_replace('{entrySlug}', $requestArray['slug'], $requestArray['thumbnailPath']);

        $id = isset($requestArray['id']) ? $requestArray['id'] : 0;

        $action = isset($requestArray['action']) ? $requestArray['action'] : 'move';

        $requestArray['videoFor'] = isset($requestArray['videoFor']) ? $requestArray['videoFor'] : '';

        Log::info('videoCompress:' . $requestArray['videoFor'] . ':id:' . $id . 'destinationPath:' . $destinationPath . ': draftImagePath::' . $draftImagePath . ': thumbnailPath::' . $thumbnailPath);
        
        /**
         * $id:: 1 //Uniq user id
         * $slug:: kvs-1 //Uniq user slug
         * $imgName:: 1578572738433_rpnrko.jpeg
         * $draftImagePath:: development/upload/draft-upload/202001/1578572738433_rpnrko.jpeg
         * $destinationPath:: development/upload/gallery/
         * $createThumbnail:: true
         * $thumbnailPath:: development/upload/gallery/thumbnail/
         */
        
        //Check file exist in draft or not
        
        if (Storage::disk('s3')->exists($destinationPath . $videoName)) {
            Log::info('Helper videoCompress: image exist on storage ' . ': original image::' . $destinationPath . $videoName);

            /**
             * START
             */

            // create a video format...
            // $lowBitrateFormat = (new X264('aac', 'libx264'))->setKiloBitrate(800);
            // $lowBitrateFormat = (new X264('libmp3lame', 'libx264'))->setKiloBitrate(800)
            $lowBitrateFormat = (new X264)->setKiloBitrate(800)
            ->setAudioCodec("aac")
            ->setAudioChannels(2)
            ->setAudioKiloBitrate(256);

            // open the uploaded video from the right disk...
            // $thumbnailSec = 3;// rand(1,$getDurationInSeconds);

            FFMpeg::fromDisk('s3')
                ->open($destinationPath . $videoName)

            // call the 'export' method...
                ->export()

            // tell the MediaExporter to which disk and in which format we want to export...
                ->toDisk('s3')
                ->inFormat($lowBitrateFormat)

            // call the 'save' method with a filename...
                ->save($destinationPath .'compressed/'. $videoName);

                
                $ThumImagePath = $destinationPath.$videoName;
                $CompressedVideo = $destinationPath .'compressed/'. $videoName;
                
                if(Storage::disk('s3')->exists($destinationPath . $videoName)) {
                    Storage::disk('s3')->delete($destinationPath . $videoName);
                }else{
                    Log::channel('customlog')->info('Helper videoCompress::video not exists');
                }

                Storage::disk('s3')->move($CompressedVideo, $ThumImagePath);
                if ($id > 0 && $requestArray['videoFor'] == 'entryVideo') {
                    /**
                     * For updating compressed time 
                     */
                    ChallengeEntry::where('id',$id)->update(['compressed_date' => Carbon::now()]);
                    
                }else if ($id > 0 && $requestArray['videoFor'] == 'feedGallery') {
                    FeedImage::where(['feed_id' => $id, 'src' => $videoName])
                        ->update(['compressed_date' => Carbon::now()]);
                }
             /**
              * END
              */
            
        } else {
            Log::info('Helper videoCompress: image not exist on storage ' . ': original image::' . $destinationPath . $videoName);
        }
    }

    /**
     * this api use for get feed sub-comment list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getFeedSubCommentWithComment(Request $request, $currentUser, $platform='web'){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['data'] = [];
        try {
            $validator = Validator::make($request->all(), [
                'feed_id' => 'required',
                'parentCommnetId' => 'required',
            ]);
            if($validator->fails()){
                $responseData['message'] = $validator->errors()->first();
                return $responseData;
            }

            $rpp = ($request->page_size) ? $request->page_size : config('constant.rpp');
            // $maincomment = array();
            // if($request->page <= 1){
                $maincomment = FeedComment::select('id', 'feed_id', 'created_by','created_by as commentBy' ,'parent_id','comment','created_at')
                ->with(['feed:id,user_id','user' => function($q) use($currentUser, $request){
                    $q->select('id','logo','slug','type',DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                    DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow"));
                }])->where(['feed_id'=>$request->feed_id, 'id' => $request->parentCommnetId])->first();
            // }
            $feedComments = FeedComment::select('id', 'feed_id', 'created_by','created_by as commentBy' ,'parent_id','comment','created_at')
            ->with(['feed:id,user_id','user' => function($q) use($currentUser, $request){
                $q->select('id','logo','slug','type',DB::raw("IF (users.is_nickname_use = 1, users.nick_name, users.name) as name"),
                DB::raw("IF((SELECT id from user_followers where followers_id = ".((!empty($currentUser))?$currentUser->id:0)." AND user_id = users.id) , true, false) AS isFollow"));
            }])->where(['feed_id'=>$request->feed_id, 'parent_id' => $request->parentCommnetId])
            ->whereHas('user', function($a){
                $a->where(['is_active' => 1]);
            })
            ->whereHas('feed', function($a){
                $a->where(['status' => 1]);
            });

            if($request->lastSyncTime != ''){
                $feedComments = $feedComments->where('created_at', '<=', $request->lastSyncTime);
            }
            if($request->id != ''){
                $feedComments = $feedComments->where('id', $request->id);
            }
            $feedComments = $feedComments->orderBy('created_at', 'DESC')->paginate($rpp);
            $feedComments = $feedComments->toJson();
            $feedComments = json_decode($feedComments);

            unset($feedComments->last_page_url);
            unset($feedComments->first_page_url);
            unset($feedComments->next_page_url);
            unset($feedComments->prev_page_url);
            unset($feedComments->path);
            if($feedComments->current_page == 1){
                $feedComments->lastSyncTime = Carbon::now()->format('Y-m-d H:i:s');
            }else {
                $feedComments->lastSyncTime = $request->lastSyncTime;
            }
            $feedComments->maincomment = $maincomment;
            // echo "<pre>ss"; print_r($feedComments);exit;
            $responseData['status'] = 1;
            $responseData['data'] = $feedComments;
            $responseData['message'] = trans('page.success');
            return $responseData;

        } catch(Exception $e){
            \Log::emergency('getFeedComment api Exception :: Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());

            $responseData = array();
            $responseData['status'] = 400;
            $responseData['message'] = $e->getMessage();
            $responseData['redirect'] = '';
            return $responseData;
        }
    }

    public static function getDanceTypeIdFromTitle($title){
        return DanceType::select('id','title')->where('title', $title)->first()->id;   
    }

    public static function getEventTypeIdFromTitle($title){
        return EventType::select('id','title')->where('title', $title)->first()->id;   
    }
}

