<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RaiseFund;
use Auth;
use App\Models\PaymentLogs;

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

    public function fundStatus(Request $request){
        $admin_id = Auth::user()->id;
        try {
            $startup = RaiseFund::whereId($request->id)->first();
            $startup->status = $request->status;
            $startup->update();

            $statusText = "Approved";
            if($request->status == 2){
                $statusText = "Rejected";
            }

            return array('status' => '200', 'msg_success' => "Fund request has been ".$statusText." successfully");

		} catch (Exception $e) {
            
			Log::info($e);
            return response()->json(['status' => 400, 'msg_fail' => 'Something Went Wrong']);
		}
    }

    public function detail($id = null)
    {
        if($id != null){
            $fund = RaiseFund::where('id',$id)->first();
            return view('admin.fund.view',compact('fund'));
        }else{
            return redirect()->route('admin.fund.index');
        }
    }

    public function donorList(Request $request)
    {
        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = PaymentLogs::orderBy('id','desc');
        
        if($keyword != ""){
            $Query->where('payment_object', $keyword);
        }

        $data = datatables()->of($Query)
        ->addColumn('payment_id', function ($Query) {
            $paymentObject = json_decode($Query->payment_object);
            return $paymentObject->id;
        })
        ->addColumn('email', function ($Query) {
            $paymentObject = json_decode($Query->payment_object);
            return $paymentObject->customer_details->email ?? "-";
        })
        ->addColumn('amount', function ($Query) {
            return $Query->amount ?? '-';
        })
        ->addColumn('payment_status', function ($Query) {
            return $Query->payment_status ?? '-';
        })
        ->rawColumns(['action','title'])
        ->make(true);
        
        return $data;
    }
}
