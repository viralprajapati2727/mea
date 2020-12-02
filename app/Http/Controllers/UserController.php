<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Helper;
use DB;
use Carbon\Carbon;
use Validator;


class UserController extends Controller
{
    /**
     * Store a newly created and updated dancer profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function updateProfile(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $dob = Carbon::createFromFormat('d/m/Y', $request->birthdate)->format('Y-m-d');
            $validator = Validator::make($request->all(), [
                'dancer_name' => "required",
                'dancer_nickname' => "required",
                'birthdate' => "required",
                'phone' => "required",
                'gender' => "required",
                'dance_music_type' => "required",
                'country' => "required",
                'city' => "required",
			],[
                'dancer_name' => "Please enter your name",
                'dancer_nickname' => "Please enter your nick name",
                'birthdate' => "Please select birthdate",
                'phone' => "Please enter contact number",
                'gender' => "Please select gender",
                'dance_music_type' => "Please choose dance music type",
                'country' => "Please select country",
                'city' => "Please select city",
            ]);

            if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
            }
            

            $user_id = Auth::id();
            $nickname_use = 0;

            /*
            * Profile image upload also check for old uploaded image
            */

            $logo_name = "";
            if ($request->input('profile_picture') != "") {
                $file = $request->input('profile_picture');
                $ext = explode('/',explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $file));
                $logo_name = "profile_".time().'.'.$ext;
                // Helper::uploadEncodedDynamicFile($profilePicOrgDynamicUrl, $logo_name, $image_data, true, $profilePicThumbDynamicUrl);
                // if (isset($request->old_logo) && $request->old_logo != "" && $request->old_logo != '') {
                //     Helper::checkFileExists($profilePicOrgDynamicUrl . $request->old_logo, true, true);
                //     //delete thumbnail
                //     Helper::checkFileExists($profilePicThumbDynamicUrl . $request->old_logo, true, true);
                // }
            } else {
                $logo_name = $request->old_logo;
            }
            if(isset($request->is_nickname_use)){
                $nickname_use = $request->is_nickname_use;
            }

            //Check Country and City Exists in its table
            $country = ucfirst(strtolower($request->country));
            $newCountry = Country::firstOrCreate(['name' => $country]);
            // $countryExists = Country::where('name',$country)->first();
            // $newCountry = '';
            // if(is_null($countryExists)){
            //     $newCountry = Country::create([
            //         'name' => $country,
            //     ]);
            // } else {
            //     $newCountry = $countryExists;
            // }
            // dd($newCountry);
            \Log::info($newCountry);
            $city = ucfirst(strtolower($request->city));
            $newCity = City::firstOrCreate(['name' => $city,'country_id' => $newCountry->id]);
            // // $cityExists = City::where(['name' => $city,'country_id' => $newCountry->id])->first();       
            // if(is_null($cityExists) && $newCountry->id){
            //     $newCity = City::create([
            //         'country_id' => $newCountry->id,
            //         'name' => $city,
            //     ]);
            // } else {
            //     $newCity = $cityExists;
            // }

            /*
            * save user profile data
            */

            $dancerProfile = User::updateOrCreate(['id' => $user_id],['name' => $request->dancer_name, 'nick_name' => $request->dancer_nickname, 'is_nickname_use' =>  $nickname_use, 'logo' => $logo_name,'is_profile_filled' => 1]);
            
            $profilePicOrgDynamicUrl = str_replace('{userSlug}', $dancerProfile->slug, config('constant.profile_url'));
            $profilePicThumbDynamicUrl = str_replace('{userSlug}', $dancerProfile->slug, config('constant.profile_thumb_url'));
            if ($request->input('profile_picture') != "") {
                Helper::uploadEncodedDynamicFile($profilePicOrgDynamicUrl, $logo_name, $image_data, true, $profilePicThumbDynamicUrl);
                if (isset($request->old_logo) && $request->old_logo != "" && $request->old_logo != '') {
                    Helper::checkFileExists($profilePicOrgDynamicUrl . $request->old_logo, true, true);
                    //delete thumbnail
                    Helper::checkFileExists($profilePicThumbDynamicUrl . $request->old_logo, true, true);
                }
            } else {
                $logo_name = $request->old_logo;
            }

            $dancerProfile->userProfile()->updateOrCreate(
                [ "user_id" => $user_id ],
                [ "dob" => $dob, "phone" => $request->phone, "gender" => $request->gender, 'address' =>$request->city_country, 'country_id' => $newCountry->id , 'city_id' => $newCity->id, "latitude" => $request->latitude, "longitude" => $request->longitude]
            );
            /*
            * save user dance type data
            */
            $danceMusicTypes = array();
            foreach ($request->dance_music_type as $values) {
                // $dancerProfile->userDanceMusicTypes()->create(['user_id' => $user_id,'dance_type_id' => $values ]);
                $danceMusicTypes[] = ['user_id' => $user_id,'dance_type_id' => $values, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ];
            }
            $dancerProfile->userDanceMusicTypes()->delete();
            $dancerProfile->userDanceMusicTypes()->insert($danceMusicTypes);

            DB::commit();
            if(isset($dancerProfile->id)){
                return redirect()->route('index')->with('success',trans('page.Profile_saved_successfully'));
            }

        } catch(Exception $e){
            Log::info('Dancer profile save Exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return redirect()->back()->with('warning',$e->getMessage());
        }
    }
}
