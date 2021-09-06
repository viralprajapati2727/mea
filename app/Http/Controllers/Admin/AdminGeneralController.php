<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SendMailController;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use App\Models\WalletLog;
use App\Models\UserProfile;
use App\Models\DanceType;
use Helper;
use App\Models\ProfessionalType;
use App\Models\NotificationSettings;
use App\Models\EventAttendee;
use App\Models\Event;
use App\Models\EventBooking;
use App\Models\EventType;
use DB;
use Auth;
use stdClass;

class AdminGeneralController extends Controller
{
    //SUSPEND USER FOR WEEK OR PERMANENTLY Or ACTIVATE USER
    public function userStatus(Request $request){
        try{
            DB::beginTransaction();
            $currentUser = User::where('id',$request->id)->first();

            $current_status = $currentUser->active;

            $currentUser->is_active = $request->active;
            $currentUser->save();
            
            if($request->active == 2){
                $msg = 'User has been Deactivated Successfully';
            }else {
                $msg = 'User has been Activated Successfully';
            }
            
            DB::commit();

            if($current_status == 0){
                SendMailController::dynamicEmail([
                    'email_id' => 2,
                    'user_id' => $currentUser->id,
                ]);
            }

            return array('status' => 200,'msg_success' => $msg,'active' => $request->active);
        } catch(Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
        }
    }
}
