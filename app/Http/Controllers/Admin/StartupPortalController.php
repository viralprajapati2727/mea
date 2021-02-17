<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StartUpPortal;
use Auth;

class StartupPortalController extends Controller
{
    public function index(){
        return view('admin.startup-portal.index');
    }

    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = StartUpPortal::orderBy('id','desc');
        
        if($keyword != ""){
            $Query->where('name', $keyword);
        }

        $data = datatables()->of($Query)
        ->addColumn('name', function ($Query) {
            $title = $Query->name;
            return "<a href='".route('admin.startup.detail',['id' => $Query->id])."' class='detail'>".$title."</a>&nbsp;&nbsp;";
        })
        ->addColumn('industry', function ($Query) {
            return $Query->industry;
        })
        ->addColumn('location', function ($Query) {
            return $Query->location;
        })
        ->addColumn('stage_of_startup',function ($Query){
            return config('constant.stage_of_startup')[$Query->stage_of_startup];
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

    public function details($id = null)
    {
        if($id != null){

            
        }else{
            return redirect()->route('admin.startup-portal.index');
        }
    }

}
