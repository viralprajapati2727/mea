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

        $appointments = Appointment::where('deleted_at',null)->orderBy('id','DESC')->paginate(10);

        return view('pages.appointments',compact('appointments'));
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
            if($request->has('appointment_id')){
                $param[ "id" ] = $request->appointment_id;
                $message_text = "updated";
            }
            
            $param2 = ["user_id" => $user_id, "name" => $request->name, "email" => Auth::user()->email, "time" => $request->time, "appointment_date" => $appointment_date,"description" => $request->description];

            $appointment = Appointment::updateOrCreate(
                $param,
                $param2
            );

            if($appointment){

                DB::commit();
                $message = 'Your '.$message_text.' has been sent successfully';
                $responseData['status'] = 1;
                $responseData['redirect'] = route('community.appointments');
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
}
