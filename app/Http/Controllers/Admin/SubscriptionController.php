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
use App\Http\Controllers\SendMailController;

class SubscriptionController extends Controller
{
    public function index(Request $req) {

        return view('admin.subscription.index');
    }

    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = EmailSubscriptions::select('id','email')->orderBy('id','desc');

        if(!empty($keyword)){
            $Query->where(function ($query1) use($keyword) {
                $query1->where('email','like','%'.$keyword.'%');
            });
        }

        $data = datatables()->of($Query)
            ->addColumn('email', function ($Query) {
                return $Query->email;
            })
            ->make(true);
            
        return $data;
    }

    public function sendEmail(Request $request)
    {
        try {
            $allUsers = EmailSubscriptions::select('id','email')->orderBy('id','desc')->get();

            foreach ($allUsers as $key => $user) {
            
            $email_param = [
                'email_id' => 9,
                'user_id' => $user->id,
                'email'=> $user->email,   
                'email_body' => $request->description             
            ];
            
            SendMailController::dynamicEmail($email_param);
            // dd($email_param);
        }
        
            return redirect()->route('admin.email-subscriptions')->with('success','Email sent successfully.');
        } catch (Exception $e) {

        return redirect()->back()->with('errors','Something Went Wrong');
        }
    }

}

