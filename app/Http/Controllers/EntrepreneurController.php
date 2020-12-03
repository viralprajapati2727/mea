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
        // DB::beginTransaction();
        try{
            $dob = Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
            // $request['dob'] =  date('Y-m-d', strtotime($request->dob));
            $validationArray = [
                // 'name' => 'required|min:2|max:255',
                // 'nick_name' => 'required|min:2|max:255',
                // 'expertise' => 'required',
                // 'gender' => 'required',
                // 'dob' => 'required',
                // 'phone' => 'required', 'max:15',
                // 'danceMusicTypes' => 'required',
                // 'fb_link' => 'nullable',
                // 'web_link' => 'nullable',
                // 'country' => 'required',
                // 'city' => 'required',
            ];
            // if(Auth::user()->is_profile_filled != 1){
            //     $validationSettingArray = [
            //         'bank_account_number' => 'required|max:40|min:6',
            //         'bank_name' => 'required|max:100',
            //         'bank_ifsc_code' => 'required|max:12',
            //         'bank_country' => 'required|max:100',
            //     ];
            //     $validationArray = array_merge($validationArray, $validationSettingArray);
            // }

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
                $file = $logo_name = "";
                if($request->file('profile_image') != ''){
                    $file = $request->file('profile_image');                    
                    $ext = $file->getClientOriginalName();
                    $logo_name = uniqid('profile_', true) . time() . '.' . $ext;
                } else {
                    $logo_name = $request->old_logo;
                }

                $user = Auth::user();
                $user->name = $request->name;
                $user->logo = $logo_name;
                $user->is_profile_filled = 1;
                $user->save();

                /*
                 * Profile image upload also check for old uploaded image
                 */
                if($request->hasFile('profile_image')){
                    Helper::uploaddynamicFile(config('constant.profile_url'), $logo_name, $file);
                    if(isset($request->old_profile_image)){
                        Helper::checkFileExists(config('constant.profile_url') . $request->old_profile_image, true, true);
                    }
                }
                
                /*
                 * save user profile data
                 */
                $user->userProfile()->updateOrCreate(
                    ['user_id' => Auth::user()->id],[
                    "dob" => $dob,
                    "phone" => $request->phone,
                    "gender" => $request->gender,
                    'description' => $request->about,
                    'fb_link' => $request->fb_link,
                    'insta_link' => $request->insta_link,
                    'tw_link' => $request->tw_link,
                    'web_link' => $request->web_link,
                    'city' => $request->city,                    
                ]);

                /*
                 * save user interest data
                 */
                if(!empty($request->interests)){
                    $user->interests()->delete();
                    $interest_explode = explode(', ', $request->interests);

                    foreach ($interest_explode as $key => $interest) {
                        $userInterests[] = ['user_id' => Auth::user()->id,'user_profile_id' => $user->userProfile->id, 'title' => $interest, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
                    }

                    $user->interests()->insert($userInterests);
                }

                /*
                 * save user skills data
                 */
                if(!empty($request->skills)){
                    $user->skills()->delete();
                    $interest_explode = explode(', ', $request->skills);

                    foreach ($interest_explode as $key => $skill) {
                        $userSkills[] = ['user_id' => Auth::user()->id,'user_profile_id' => $user->userProfile->id, 'title' => $skill, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
                    }

                    $user->skills()->insert($userSkills);
                }
                
                /*
                 * save user answers data
                 */
                if(!empty($request->ans)){
                    $user->answers()->delete();
                    foreach ($request->ans as $key => $answer) {
                        $userAnswers[] = ['user_id' => Auth::user()->id,'user_profile_id' => $user->userProfile->id, 'question_id' => $key, 'title' => $answer, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
                    }

                    $user->answers()->insert($userAnswers);
                }
                
                /*
                 * save user work experience data
                 */
                if(!empty($request->exp)){
                    $user->workExperience()->delete();
                    foreach ($request->exp as $key => $experience) {
                        $userExperiences[] = ['user_id' => Auth::user()->id,'user_profile_id' => $user->userProfile->id, 'is_experience' => 1,'company_name' => $experience['company_name'], 'designation' => $experience['designation'], 'year' => $experience['year'], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
                    }

                    $user->workExperience()->insert($userExperiences);
                }
                
                /*
                 * save user education data
                 */
                if(!empty($request->edu)){
                    $user->educationDetails()->delete();
                    foreach ($request->edu as $key => $education) {
                        $educationDetails[] = ['user_id' => Auth::user()->id,'user_profile_id' => $user->userProfile->id, 'course_name' => $education['course_name'], 'organization_name' => $education['organization_name'], 'percentage' => $education['percentage'], 'year' => $education['year'], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
                    }

                    $user->educationDetails()->insert($educationDetails);
                }

                // DB::commit();
                $responseData['status'] = 1;
                // $responseData['redirect'] = url('professional/'.$user->slug);
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
