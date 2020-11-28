<?php

namespace App\Http\Controllers;

use App\User;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use stdClass;


class EntrepreneurController extends Controller
{
    /**
     * opening dancer fill profile form.
     *
     * @return \Illuminate\Http\Response
     */
    public function fillProfile()
    {
        
        try {

            //Logged-in user's profile
            $profile = User::select('*')
                ->where('id',Auth::id())->with([
                    'userProfile' => function($query){
                        $query->select('id','user_id','dob','phone','gender','address','latitude','longitude','wallet_unique_id','fb_link','web_link','description','country_id','state_id','city_id','total_wallet');
                    },
                ])
                ->first();
            if(!$profile->id){
                return redirect()->route('index')->with('error','User doesn`t exists');
            }
            return view('entrepreneur.fill-profile',compact('profile'));
        } catch(\Exception $e){
            // echo "<pre>"; print_r($e->getMessage()); exit;
            Log::info('EntrepreneurController fillProfile catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return redirect()->route('index')->with('error','Something went wrong! Please try again.');
        }
    }

    /**
     * Store a newly created and updated dancer profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];
        DB::beginTransaction();
        try{
            // echo "<pre>"; print_r($request->all());exit;
            $dob = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
            $request['bank_account_number'] = str_replace('-', '',
                str_replace('_','',$request->bank_account_number)
            );
            $request['dob'] =  date('Y-m-d', strtotime($request->dob));
            $validationArray = [
                'name' => 'required|min:2|max:255',
                'nick_name' => 'required|min:2|max:255',
                'expertise' => 'required',
                'gender' => 'required',
                'dob' => 'required',
                'phone' => 'required', 'max:15',
                'danceMusicTypes' => 'required',
                'fb_link' => 'nullable',
                'web_link' => 'nullable',
                'country' => 'required',
                'city' => 'required',
            ];
            if(Auth::user()->is_profile_filled != 1){
                $validationSettingArray = [
                    'bank_account_number' => 'required|max:40|min:6',
                    'bank_name' => 'required|max:100',
                    'bank_ifsc_code' => 'required|max:12',
                    'bank_country' => 'required|max:100',
                ];
                $validationArray = array_merge($validationArray, $validationSettingArray);
            }

            $validator = Validator::make($request->all(), $validationArray);
            if ($validator->fails()) {
                DB::rollback();
                $responseData['message'] = $validator->errors()->first();
                $responseData['errors'] = $validator->errors()->toArray();
                return $this->commonResponse($responseData, 200);
            } else {                

                /*
                 * Profile image upload also check for old uploaded image
                 */
                if($request->input('professional_profile_picture') != ''){
                   $file = $request->input('professional_profile_picture');
                   $ext = explode('/',explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                   $image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $file));
                   $logo_name = uniqid('profile_', true) . time() . '.' . $ext;
                } else {
                    $logo_name = $request->old_logo;
                }

                $user = Auth::user();
                $user->name = $request->input('name');
                $user->nick_name = $request->input('nick_name');
                $user->is_nickname_use = ($request->input('is_nickname_use') == 1)?1:0;
                $user->is_visible_email = ($request->input('is_visible_email') == 1)?1:0;
                $user->is_visible_phone = ($request->input('is_visible_phone') == 1)?1:0;
                $user->logo = $logo_name;
                $user->is_profile_filled = 1;
                $user->save();

                $profilePicOrgDynamicUrl = str_replace('{userSlug}', Auth::user()->slug, config('constant.profile_url'));
                $profilePicThumbDynamicUrl = str_replace('{userSlug}', Auth::user()->slug, config('constant.profile_thumb_url'));

                $galleryOrgDynamicUrl = str_replace('{userSlug}', Auth::user()->slug, config('constant.gallery_url'));
                $galleryThumbDynamicUrl = str_replace('{userSlug}', Auth::user()->slug, config('constant.gallery_thumb_url'));

                /*
                 * Profile image upload also check for old uploaded image
                 */
                if($request->input('professional_profile_picture') != ''){
                   Helper::uploadEncodedDynamicFile($profilePicOrgDynamicUrl, $logo_name, $image_data, true, $profilePicThumbDynamicUrl);
                   if (isset($request->old_logo) && $request->old_logo != "" && $request->old_logo != '') {
                       Helper::checkFileExists($profilePicOrgDynamicUrl . $request->old_logo, true, true);
                       //delete thumbnail
                       Helper::checkFileExists($profilePicThumbDynamicUrl . $request->old_logo, true, true);
                   }
                } else {
                    $logo_name = $request->old_logo;
                }
                
                /*
                 * save user gallery data
                 */
                $userGallery= array();
                if($request->input('s3_gallery') != ''){
                    foreach ($request->input('s3_gallery') as $key => $draftImagePath) {
                        $imgName = explode('/',$draftImagePath);
                        $imgName = end($imgName);
                        $destinationPath = $galleryOrgDynamicUrl;
                        $thumbnailPath = $galleryThumbDynamicUrl;
                        $createThumbnail = true;
                        $queueRequestArray = array();
                        $queueRequestArray['user_id'] = $user->id;
                        $queueRequestArray['id'] = $user->id;
                        $queueRequestArray['slug'] = $user->slug;
                        $queueRequestArray['imgName'] = $imgName;
                        $queueRequestArray['draftImagePath'] = $draftImagePath;
                        $queueRequestArray['destinationPath'] = $destinationPath;
                        $queueRequestArray['createThumbnail'] = $createThumbnail;
                        $queueRequestArray['thumbnailPath'] = $thumbnailPath;
                        $queueRequestArray['imageFor'] = 'profileGallery';
                        $queueRequestArray['action'] = 'move';
                        Log::info('Dispatch job from ProfessionalController Fronend');
                        ImageMoveDraftToOriginalDestination::dispatch($queueRequestArray)->onQueue('images');
                        $userGallery[] = ['user_id' => Auth::user()->id, 'src' => $imgName, 'is_uploaded'=>0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
                    }
                }
                if($request->input('old-gallery-photos')) {
                    $deletedGallarys = $user->userGallery()
                        ->select('src')
                        ->where('user_id', Auth::user()->id)
                        ->whereNotIn('id', $request->input('old-gallery-photos'))
                        ->get()->toArray();

                    $user->userGallery()
                        ->where('user_id', Auth::user()->id)
                        ->whereNotIn('id', $request->input('old-gallery-photos'))
                        ->delete();
                }else {
                    $deletedGallarys = $user->userGallery()
                        ->select('src')
                        ->where('user_id', Auth::user()->id)
                        ->get()->toArray();
                    /*
                     * if update time user delete all old image than
                     */
                    $user->userGallery()->delete();
                }

                $user->userGallery()->insert($userGallery);

                //Check Country and City Exists in its table
                $country = ucfirst(strtolower($request->country));
                $newCountry = Country::firstOrCreate(['name' => $country]);

                $city = ucfirst(strtolower($request->city));
                $newCity = City::firstOrCreate(['name' => $city,'country_id' => $newCountry->id]);


                /*
                 * save user profile data
                 */
                $user->userProfile()->updateOrCreate(
                    ['user_id' => Auth::user()->id],[
                    "dob" => $dob,
                    "phone" => $request->phone,
                    "gender" => $request->gender,
                    'address' => $request->city_country,
                    'description' => $request->about,
                    'fb_link' => $request->fb_link,
                    'web_link' => $request->web_link,
                    'country_id' => $newCountry->id,
                    'city_id' => $newCity->id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                /*
                 * save user dance type data
                 */
                $danceMusicTypes = array();
                foreach ($request->danceMusicTypes as $values) {
                    $danceMusicTypes[] = ['user_id' => Auth::user()->id,'dance_type_id' => $values, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ];
                }
                $user->userDanceMusicTypes()->delete();
                $user->userDanceMusicTypes()->insert($danceMusicTypes);

                /*
                 * save user expertise data
                 */
                $userExpertise = array();
                foreach ($request->expertise as $values) {
                    $userExpertise[] = ['user_id' => Auth::user()->id,'professional_type_id' => $values ];
                }
                $user->userExpertise()->delete();
                $user->userExpertise()->insert($userExpertise);

                /*
                 * save user settings
                 */

                $user->userSettings()->updateOrCreate(
                    ['user_id' => Auth::user()->id],[
                    "bank_account_number" => $request->bank_account_number,
                    "bank_name" => $request->bank_name,
                    'bank_ifsc_code' =>$request->bank_ifsc_code,
                    'bank_country' =>$request->bank_country,
                ]);

                DB::commit();
                /*
                 * if successfully update profile than delete old gallery file
                 */
                if(isset($deletedGallarys) && is_array($deletedGallarys)){
                    foreach ($deletedGallarys as $deletedGallary){
                        Helper::checkFileExists($galleryOrgDynamicUrl . $deletedGallary['src'], true, true);
                        //delete thumbnail
                        Helper::checkFileExists($galleryThumbDynamicUrl . $deletedGallary['src'], true, true);
                    }
                }
                $responseData['status'] = 1;
                $responseData['redirect'] = url('professional/'.$user->slug);
                $responseData['message'] = trans('page.Profile_saved_successfully');
                Session::flash('success', $responseData['message']);
                return $this->commonResponse($responseData, 200);
            }

        } catch(Exception $e){
            Log::emergency('Professional profile save Exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            $code = ($e->getCode() != '')?$e->getCode():500;
            $responseData['message'] = trans('common.something_went_wrong');
            return $this->commonResponse($responseData, $code);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function followProfessional(Request $request){
        // dd($request->all());
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];
        try {
            if(Auth::check() && (Auth::user()->type == config('constant.USER.TYPE.PROFESSIONAL') || Auth::user()->type == config('constant.USER.TYPE.DANCER'))) {
                $following_user = User::where(array('slug' => $request->input('following-user')))->first();
                $following_user_name = ($following_user->is_nickname_use == 1)?$following_user->nick_name:$following_user->name;
                if(Auth::user()->is_profile_filled != 1){
                    Session::flash('error', trans('page.please_fill_profile'));
                    $responseData['message'] = trans('page.please_fill_profile');
                }else  if($following_user->id != Auth::user()->id) {
                    DB::beginTransaction();
                    if ($request->input('request-for') == 'follow') {
                        $userFollowers = UserFollower::updateOrCreate(
                            ['followers_id' => Auth::user()->id,'user_id' => $following_user->id],[
                            'followers_id' => Auth::user()->id,
                            'user_id' => $following_user->id
                        ]);
                        $responseData['status'] = 1;
                        $responseData['message'] = sprintf(trans('page.successfully_follow'), $following_user_name);
                    } else if ($request->input('request-for') == 'un-follow') {
                        UserFollower::where(array(
                            'followers_id' => Auth::user()->id,
                            'user_id' => $following_user->id
                        ))->delete();
                        $responseData['status'] = 1;
                        $responseData['message'] =  sprintf(trans('page.successfully_unfollow'), $following_user_name);
                    }
                    DB::commit();
                }else {
                    $responseData['message'] = trans('page.cant_follow_your_self');
                }
                // $followers = UserFollower::where('user_id', $following_user->id)
                // ->select(DB::raw("count(id) as totalFollowers"))->get()->first();
                $followers = UserFollower::where(['user_followers.user_id' => $following_user->id, 'users.deleted_at' => null, 'users.is_active' => 1])
                ->whereIn('users.type',[config('constant.USER.TYPE.DANCER'),config('constant.USER.TYPE.PROFESSIONAL')])
                ->select(DB::raw("count(user_followers.id) as totalFollowers"))
                ->join('users', function($join) {
                    $join->on('user_followers.followers_id', '=', 'users.id');
                })->first();
            }else {
                $responseData['status'] = 2;
                $responseData['message'] = trans('auth.login_first');

                $followers = UserFollower::where('user_id', DB::raw("(select id from users where slug = '{$request->input('following-user')}')"))->select(DB::raw("count(id) as totalFollowers"))->get()->first();
            }

            $responseData['totalFollowers'] = $followers->totalFollowers;

            return $this->commonResponse($responseData, 200);
        } catch(Exception $e){
            Log::emergency('followProfessional Exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            $code = ($e->getCode() != '')?$e->getCode():500;
            $responseData['message'] = trans('common.something_went_wrong');
            return $this->commonResponse($responseData, $code);
        }
    }

    /**
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewProfile($slug = '')
    {
        try {
            
            return view('professional.professional-profile', compact('profile', 'followers', 'following_professionals','user','slug','feedSlug','danceMusicTypes','currentUser','currentPage'));
        } catch(\Exception $e){
            Log::info('ProfessionalController viewProfile catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            return redirect()->route('index')->with('error','Something went wrong! Please try again.');
        }
    }
}
