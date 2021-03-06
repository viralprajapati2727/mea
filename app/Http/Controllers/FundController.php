<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Helper;
use App\User;
use Image;
use Illuminate\Support\Facades\Log;
use Auth;
use DB;
use Session;
use Carbon\Carbon;
use App\Models\StartUpPortal;
use App\Models\ScheduleAppointment;
use App\Models\StartupTeamMembers;
use App\Models\RaiseFund;

class FundController extends Controller
{
    public function index(){
        
        $startups = StartUpPortal::whereNull('deleted_at')->where('user_id',Auth::id())->orderBy('id','DESC')->paginate(10);

        return view('fund.index',compact('startups'));    
        
    }

    public function create($action = null,$id = null)
    {
        try{
            $fund = null;

            if($id != null){
                $fund = StartUpPortal::with(['appoinment','startup_team_member'])->where('id',$id)->whereNull('deleted_at')->first();
            }

            if($action != null && $action == 'create'){
                return view('fund.create',compact('fund'));
            }else{
                return view('fund.view',compact('fund'));
            }

        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('warning',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];

        DB::beginTransaction();

        try{
            $status = $is_view = 0;
            $user_id = Auth::id();

            if(isset($request->is_view)){
                $is_view = 1;
            }
            if(isset($request->status)){
                $status = $request->status;
            }
            $param = [];
            if($request->has('id') && $request->id > 0){
                $param[ "id" ] = $request->id;
            }

            $business_plan = $financial = $pitch_deck = "";

            
            if($request->file('fileinput_business_plan') != ''){
                $business_plan_file = $request->file('fileinput_business_plan');                    
                $business_plan_file_ext = $business_plan_file->getClientOriginalName();
                $business_plan = uniqid('business_plan_', true) . time() . '.' . $business_plan_file_ext;
            }else{
                $business_plan = $request->old_business_plan;
            }

            if($request->file('fileinput_financial') != ''){
                $financial_file = $request->file('fileinput_financial');                    
                $financial_file_ext = $financial_file->getClientOriginalName();
                $financial = uniqid('financial_', true) . time() . '.' . $financial_file_ext;
            }else{
                $financial = $request->old_financial;
            }

            if($request->file('fileinput_pitch_deck') != ''){
                $pitch_deck_file = $request->file('fileinput_pitch_deck');                    
                $pitch_deck_file_ext = $pitch_deck_file->getClientOriginalName();
                $pitch_deck = uniqid('pitch_deck_', true) . time() . '.' . $pitch_deck_file_ext;
            }else{
                $pitch_deck = $request->old_pitch_deck;
            }
            
            if($request->hasFile('fileinput_business_plan')){
                Helper::uploaddynamicFile(config('constant.business_plan'), $business_plan, $business_plan_file);
                if(isset($request->old_business_plan)){
                    Helper::checkFileExists(config('constant.profile_url') . $request->old_business_plan, true, true);
                }
            }
            
            if($request->hasFile('fileinput_financial')){
                Helper::uploaddynamicFile(config('constant.financial'), $financial, $financial_file);
                if(isset($request->old_financial)){
                    Helper::checkFileExists(config('constant.profile_url') . $request->old_financial, true, true);
                }
            }
            
            if($request->hasFile('fileinput_pitch_deck')){
                Helper::uploaddynamicFile(config('constant.pitch_deck'), $pitch_deck, $pitch_deck_file);
                if(isset($request->old_financial)){
                    Helper::checkFileExists(config('constant.profile_url') . $request->old_business_plan, true, true);
                }
            }

            $param2 = [
                "name" => $request->name,
                "description" => $request->description,
                "industry" => $request->industry,
                "location" => $request->location,
                "team_members" => implode(",",$request->team_members),
                "stage_of_startup" => $request->startup_stage,
                "important_next_step" => $request->important_next_step,
                "other_important_next_step" => $request->other_important_next_step,
                "web_link" => $request->web_link,
                "fb_link" => $request->fb_link,
                "insta_link" => $request->insta_link,
                "tw_link" => $request->tw_link,
                "linkedin_link" => $request->linkedin_link,
                "tiktok_link" => $request->tiktok_link,
                "business_plan" => $business_plan,
                "financial" => $financial,
                "pitch_deck" => $pitch_deck,
                "is_view" => $is_view,
                "status" => $status,
                "user_id" => $user_id
            ];

            if($request->has('id') && $request->id > 0){
                $startup_portal = StartUpPortal::updateOrCreate(
                    $param,
                    $param2
                );
            }else{
                $startup_portal = StartUpPortal::create(
                    $param2
                );
            }
            $user_status = 0;
            if($startup_portal != null){
                StartupTeamMembers::where('startup_id',$startup_portal->id)->delete();
                foreach ($request->team_members as $key => $team_member) {
                    StartupTeamMembers::create([
                        'startup_id' => $startup_portal->id, 
                        'user_id' => $team_member,
                        'status' => $user_status
                    ]);
                }
            }

            $message_text = "Startup Portal";

            DB::commit();
            $message = 'Your '.$message_text.' has been saved successfully';
            $responseData['status'] = 1;
            $responseData['redirect'] = route('startup-portal');
            $responseData['message'] = $message;
            Session::flash('success', $responseData['message']);

            return $this->commonResponse($responseData, 200);

        } catch(Exception $e){
            Log::emergency('Startup portal save Exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            $code = ($e->getCode() != '')?$e->getCode():500;
            $responseData['message'] = trans('common.something_went_wrong');
            return $this->commonResponse($responseData, $code);
        }
    }

    public function storeAppoinment(Request $request){

        DB::beginTransaction();
        $status = 0;
        try {
            if($request->startup_id != null){
                $appointment = new ScheduleAppointment;
                $appointment->startup_id = (int)$request->startup_id;
                $appointment->user_id = Auth::id();
                $appointment->date = $request->date;
                $appointment->time = $request->time;
                $appointment->zone = $request->zone;
                $appointment->purpose_of_meeting = $request->purpose_of_meeting;
                $appointment->status = $status;
                $appointment->save();
                
            }
            DB::commit();
            
            return redirect()->back()->with('success','Your Schedule an appoinment request has been submitted successfully');

        } catch(Exception $e){
            Log::emergency('Schedule appointment save Exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            $code = ($e->getCode() != '')?$e->getCode():500;
            $responseData['message'] = trans('common.something_went_wrong');
            return $this->commonResponse($responseData, $code);
        }    
    }

}
