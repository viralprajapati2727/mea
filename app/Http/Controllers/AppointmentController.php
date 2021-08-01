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
use App\Models\Appointment;



class AppointmentController extends Controller
{
    public function index(){

        $appointments = Appointment::where('deleted_at',null)->where('receiver_id', Auth::id())->orderBy('id','DESC')->paginate(10);
        $type = "received";
        return view('pages.appointments',compact('appointments','type'));
    }
    public function sent(){

        $appointments = Appointment::where('deleted_at',null)->where('user_id', Auth::id())->orderBy('id','DESC')->paginate(10);
        $type = "sent";
        return view('pages.appointments',compact('appointments','type'));
    }
    public function updateAppointment(Request $request){

        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];

        $user_id = Auth::id();
        $param = [];

        DB::beginTransaction();

        try{
            $appointment_date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');

            $message_text = "sent";
            $param = [];
            if($request->has('appointment_id')){
                $param[ "id" ] = $request->appointment_id;
                $message_text = "updated";
            }
            
            $param2 = ["user_id" => $user_id, "receiver_id" => $request->receiver_id, "name" => $request->name, "email" => Auth::user()->email, "time" => $request->time, "appointment_date" => $appointment_date,"description" => $request->description, "appointment_time" => $request->appointment_time ];

            $appointment = Appointment::create($param2);

            if($appointment){

                DB::commit();
                $message = 'Your appointment has been '.$message_text.' successfully';
                $responseData['status'] = 1;
                $responseData['redirect'] = route('appointment.index');
                $responseData['message'] = $message;
                Session::flash('success', $responseData['message']);
            } else {
                DB::rollback();
                $responseData['message'] = trans('common.something_went_wrong');
                Session::flash('success', $responseData['message']);
            }
            return $this->commonResponse($responseData, 200);

        } catch(Exception $e){
            Log::emergency('job controller save Exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            $code = ($e->getCode() != '')?$e->getCode():500;
            $responseData['message'] = trans('common.something_went_wrong');
            return $this->commonResponse($responseData, $code);
        }
    }
    public function detail(Request $request){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];

        $appointment = Appointment::whereId($request->id)->first();
        $responseData['data']['name'] = $appointment->name;
        $responseData['data']['email'] = $appointment->user->email;
        $responseData['data']['date'] = $appointment->appointment_date;
        $responseData['data']['time'] = $appointment->time;
        $responseData['data']['description'] = $appointment->description;
        $responseData['status'] = 1;
        $responseData['data']['appointment_time'] = $appointment->appointment_time;

        return $this->commonResponse($responseData, 200);
    }
    public function destroy(Request $request){
        DB::beginTransaction();
        try{
            $Appointment = Appointment::find($request->id);
            $Appointment->delete();
            DB::commit();
            Session::flash('success', 'Appointment has been deleted Successfully');
            return array('status' => '200', 'msg_success' => 'Appointment has been deleted Successfully');
        } catch(Exception $e){
            DB::rollback();
            return array('status' => '0', 'msg_fail' => 'Something went wrong');
        }
    }
    public function appointmentStatus(Request $request) {
        $admin_id = Auth::user()->id;
        try {
            $appointment = Appointment::whereId($request->id)->first();
            $appointment->status = $request->status;
            $appointment->update();

            $statusText = "Approved";
            if($request->status == 2){
                $statusText = "Rejected";
            }
            return array('status' => '200', 'msg_success' => "Appointment has been ".$statusText." successfully");
		} catch (Exception $e) {
			Log::info($e);
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
		}
		exit;
    }
}
