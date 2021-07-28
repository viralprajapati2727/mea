<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use DB, Log;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class AppointmentController extends Controller
{
    public function index(){
        return view('admin.appointment.index');
    }
    
    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = Appointment::orderBy('id','desc')->where('deleted_at',null);
        
        if(!empty($request->created_at_from) && !empty($request->created_at_to)){
            $posted_date_from = date('Y-m-d',strtotime($request->created_at_from));
            $posted_date_to = date('Y-m-d',strtotime($request->created_at_to));
            $Query->whereBetween('created_at',[$posted_date_from, $posted_date_to]);
        }

        $data = datatables()->of($Query)
        ->addColumn('name', function ($Query) {
            $title = $Query->name;
            return "<a href='".route('admin.appointment.detail',['id' => $Query->id])."' class='detail'>".$title."</a>&nbsp;&nbsp;";
        })
        ->addColumn('email', function ($Query) {
            return $Query->user->email;
        })
        ->addColumn('appointment_date', function ($Query) {
            return $Query->appointment_date .' '.$Query->appointment_time;
        })
        ->addColumn('time', function ($Query) {
            return $Query->time;
        })
        ->addColumn('action', function ($Query) {
            $action_link = "";
            if($Query->status == 0){
                $action_link .= "<span class='translation-status'>";
                $action_link .= "<a href='javascript:;' class='approve-reject' data-id='".$Query->id."' data-active='1'>APPROVE</a>&nbsp;/&nbsp;";
                $action_link .= "<a href='javascript:;' class='approve-reject' data-id='".$Query->id."' data-active='2'>REJECT</a>&nbsp;&nbsp;";
                $action_link .= "</span>";
                
                
                $action_link .= "<span class='after_approve_reject'>";
                $action_link .= "</span>";
            }

            if($Query->status == 1){
                $action_link = "<span class='badge badge-success'><a href='javascript:;'>APPROVED</a></span>";
            }

            if($Query->status == 2){
                $action_link = "<span class='badge badge-danger'><a href='javascript:;'>REJECTED</a></span>";
            }

            return $action_link;
        })
        ->rawColumns(['action','name'])
        ->make(true);
        return $data;
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
    public function detail($id){
        $appointment = Appointment::whereId($id)->first();

        if(empty($appointment)){
            return redirect()->route('admin.appointment.index')->with('error','No data found!');
        }

        return view('admin.appointment.detail',compact('appointment'));
    }
}
