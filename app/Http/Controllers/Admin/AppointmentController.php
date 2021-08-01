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
            return $title;
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
        ->addColumn('status', function ($Query) {
            $statuss = config('constant.appointment_status');

            return '<span class="badge badge-success">'.$statuss[$Query->status].'</span>';
        })
        ->addColumn('action', function ($Query) {
            return "<a href='".route('admin.appointment.detail',['id' => $Query->id])."' class='detail'><i class='icon-eye mr-3 text-primary'></i></a>";
        })
        ->rawColumns(['status','name','action'])
        ->make(true);
        return $data;
    }
    public function detail($id){
        $appointment = Appointment::whereId($id)->first();

        if(empty($appointment)){
            return redirect()->route('admin.appointment.index')->with('error','No data found!');
        }

        return view('admin.appointment.detail',compact('appointment'));
    }
}
