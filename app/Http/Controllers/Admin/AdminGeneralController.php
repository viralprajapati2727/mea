<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

            $currentUser = $currentUser->update([
                'is_active' => $request->active,
            ]);
            if($request->active == 2){
                $msg = 'User has been Deactivated Successfully';
            }else {
                $msg = 'User has been Activated Successfully';
            }
            DB::commit();
            return array('status' => 200,'msg_success' => $msg,'active' => $request->active);
        } catch(Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
        }
    }
}
