<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailSubscriptions;
use Datatables;
use Helper;
use DB;
use Validator;
use Auth;
use App\Models\ChatGroup;
use App\Models\ChatMasters;
use App\Models\ChatMessage;
use App\Models\ChatMessagesReceiver;
use App\Http\Controllers\SendMailController;
use Carbon\Carbon;

class MessageController extends Controller
{
    public function index(Request $req) {

        return view('admin.message.index');
    }

    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = ChatGroup::with(['members'])->
			whereHas('members',function($query){
				
			});

        // if(!empty($keyword)){
        //     $Query->where(function ($query1) use($keyword) {
        //         $query1->where('email','like','%'.$keyword.'%');
        //     });
        // }

        $data = datatables()->of($Query)
            ->addColumn('group', function ($Query) {
                $group = "";
                foreach ($Query->members as $key => $member) {
                    if($key == 1){
                        $group .= " <strong>VS</strong> ";
                        $group .= " VS ".$member->user->name;
                    } else {
                        $group .= $member->user->name;
                    }
                }

                return $group;
            })
            ->addColumn('created_by', function ($Query) {
                return $Query->createdBy->name;
            })
            ->addColumn('created_at', function ($Query) {
                return Carbon::parse($Query->created_at)->format('d M Y, h:i A');
            })
            ->addColumn('action', function ($Query) {
                return "<a href='".route('admin.message.detail',['id' => base64_encode($Query->id)])."' class='detail'><i class='icon-eye mr-3 text-primary'> View Chat</i></a>";
            })
            ->rawColumns(['group','action'])
            ->make(true);
            
        return $data;
    }
    public function detail($id = null){
        if(is_null($id)){
            return redirect()->back();
        }

        $group_id = base64_decode($id);

        $messages = ChatMessage::with('members')
            ->where('group_id', $group_id)
            ->select('id','sender_id','text','group_id','created_at')
            ->orderBy('id', 'ASC')
            ->get();
        return view('admin.message.details', compact('group_id','messages'));
    }
}

