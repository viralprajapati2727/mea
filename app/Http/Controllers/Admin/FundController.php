<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RaiseFund;
use Auth;

class FundController extends Controller
{
    public function index(){
        return view('admin.fund.index');
    }

    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = RaiseFund::orderBy('id','desc');
        
        if($keyword != ""){
            $Query->where('title', $keyword);
        }

        $data = datatables()->of($Query)
        ->addColumn('title', function ($Query) {
            $title = $Query->title;
            return "<a href='".route('admin.fund.detail',['id' => $Query->id])."' class='detail'>".$title."</a>&nbsp;&nbsp;";
        })
        ->addColumn('currency', function ($Query) {
            return $Query->currency;
        })
        ->addColumn('amount', function ($Query) {
            return $Query->amount;
        })
        ->addColumn('received_amount', function ($Query) {
            return $Query->received_amount ?? 0;
        })
        ->addColumn('donors', function ($Query) {
            return $Query->donors ?? 0;
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
        ->rawColumns(['action','title'])
        ->make(true);
        return $data;
    }

    public function startupStatus(Request $request){
        $admin_id = Auth::user()->id;
        try {
            $startup = StartUpPortal::whereId($request->id)->first();
            $startup->status = $request->status;
            $startup->update();

            $statusText = "Approved";
            if($request->status == 2){
                $statusText = "Rejected";
            }

            return array('status' => '200', 'msg_success' => "Startup Portal has been ".$statusText." successfully");

		} catch (Exception $e) {
            
			Log::info($e);
            return response()->json(['status' => 400, 'msg_fail' => 'Something Went Wrong']);
		}
    }

    public function detail($portal_id = null)
    {
        if($portal_id != null){

            $startup = StartupPortal::with(['appoinment','startup_team_member'])->where('id',$portal_id)->whereNull('deleted_at')->first();
            
            return view('admin.startup-portal.view',compact('startup'));
        }else{
            return redirect()->route('admin.startup-portal.index');
        }
    }

    public function updateAppoinment(Request $request)
    {
        try {
            $status = 0;
            if($request->has('status')){
                $status = $request->status;
            }
            ScheduleAppointment::where('id',$request->appoinment_id)
            ->where('startup_id',$request->startup_id)
            ->update([
                "date" => $request->date,
                "time" => $request->time,
                "zone" => $request->zone,
                "purpose_of_meeting" => $request->purpose_of_meeting,
                "status" => $status,
                "reason" => $request->reason
            ]);

            return redirect()->back()->with('success',"Appoinment Schedule has been saved successfully");
        } catch (Exception $e) {   
			Log::info($e);
            return response()->json(['status' => 400, 'msg_fail' => 'Something Went Wrong']);
		}
    }

}
