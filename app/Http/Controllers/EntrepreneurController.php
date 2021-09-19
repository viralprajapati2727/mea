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
use Illuminate\Support\Str;


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
        DB::beginTransaction();
        try{
            $dob = null;
            if ($request->dob) {
                $dob = Carbon::createFromFormat('m/d/Y', $request->dob)->format('Y-m-d');
            }
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
                    $random = Str::random(10);
                    $extension = $file->extension();
                    $logo_name = uniqid('profile_', true) . time() . $random . '.' . $extension;
                } else {
                    $logo_name = $request->old_logo;
                }
                
                $coverfile = $cover_name = "";
                if($request->file('cover') != ''){
                    $coverfile = $request->file('cover');                    
                    $ext = $coverfile->getClientOriginalName();
                    $random = Str::random(10);
                    $extension = $coverfile->extension();
                    $cover_name = uniqid('cover_', true) . time() . $random . '.' . $extension;
                } else {
                    $cover_name = $request->old_cover;
                }
                
                $cvfile = $cv_name = "";
                if($request->file('resume') != ''){
                    $cvfile = $request->file('resume');                    
                    $ext = $cvfile->getClientOriginalName();
                    $random = Str::random(10);
                    $extension = $cvfile->extension();
                    $cv_name = uniqid('resume_', true) . time() . $random . '.' . $extension;
                } else {
                    $cv_name = $request->old_resume;
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
                
                if($request->hasFile('cover')){
                    Helper::uploaddynamicFile(config('constant.profile_cover_url'), $cover_name, $coverfile);
                    if(isset($request->old_cover)){
                        Helper::checkFileExists(config('constant.profile_cover_url') . $request->old_cover, true, true);
                    }
                }
                
                if($request->hasFile('resume')){
                    Helper::uploaddynamicFile(config('constant.resume_url'), $cv_name, $cvfile);
                    if(isset($request->old_resume)){
                        Helper::checkFileExists(config('constant.resume_url') . $request->old_resume, true, true);
                    }
                }

                $is_experience = 1;
                if($request->has('is_experience')){
                    $is_experience = 0;
                }
                
                $is_education = 1;
                if($request->has('is_education')){
                    $is_education = 0;
                }
                
                /*
                 * save user profile data
                 */
                $user->userProfile()->updateOrCreate(
                    ['user_id' => Auth::user()->id],[
                    "dob" => $dob,
                    "cover" => $cover_name,
                    "resume" => $cv_name,
                    "phone" => $request->phone,
                    "gender" => $request->gender,
                    'description' => $request->about,
                    'fb_link' => $request->fb_link,
                    'insta_link' => $request->insta_link,
                    'tw_link' => $request->tw_link,
                    'web_link' => $request->web_link,
                    'city' => $request->city,                    
                    'is_experience' => $is_experience,
                    "linkedin_link" => $request->linkedin_link,
                    "github_link" => $request->github_link,
                    "is_resume_public" => $request->is_resume_public ?? 0,
                    "is_email_public" => $request->is_email_public ?? 0,
                    "is_phone_public" => $request->is_phone_public ?? 0,
                    "is_education" => $is_education
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
                    if($is_experience){
                        foreach ($request->exp as $key => $experience) {
                            $userExperiences[] = ['user_id' => Auth::user()->id,'user_profile_id' => $user->userProfile->id, 'is_experience' => $is_experience,'company_name' => $experience['company_name'], 'designation' => $experience['designation'], 'year' => $experience['year'], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), "responsibilities" => $experience['responsibilities']];
                        }

                        $user->workExperience()->insert($userExperiences);
                    }
                }
                
                /*
                 * save user education data
                 */
                if(!empty($request->edu)){
                    $user->educationDetails()->delete();
                    foreach ($request->edu as $key => $education) {
                        $educationDetails[] = [
                            'user_id' => Auth::user()->id,
                            'user_profile_id' => $user->userProfile->id, 
                            'course_name' => $education['course_name'], 
                            'organization_name' => $education['organization_name'], 
                            'percentage' => $education['percentage'], 
                            'year' => $education['year'], 
                            'major' => $education['major'],
                            'minor' => $education['minor'],
                            'created_at' => date('Y-m-d H:i:s'), 
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                    }

                    $user->educationDetails()->insert($educationDetails);
                }

                DB::commit();
                $responseData['status'] = 1;
                $responseData['redirect'] = route('user.view-profile',['slug' => $user->slug]);
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
