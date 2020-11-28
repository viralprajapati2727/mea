<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Datatables;
use Helper;
use DB;
use Validator;
use Auth;

class EmailController extends Controller
{
    public function index() {
		$email = EmailTemplate::orderBy('updated_at','desc')->where('emat_is_active', 1)->get();
		return view('admin.email.index',compact('email'));
	}

	public function create() {
        $id = null;
		return view('admin.email.create',compact('id'));
    }
    
    public function store(Request $request) {
		DB::beginTransaction();
		try {
			$validator = Validator::make($request->all(), [
				'emat_email_name' => 'required|unique:email_template,emat_email_name,' . base64_decode($request->email_id),
				'emat_email_subject' => 'required',
				'emat_email_message' => 'required',
			], [
				'emat_email_name.required' => 'Please enter email name',
				'emat_email_name.unique' => 'This email name is already_exists',
				'emat_email_subject.required' => 'Please enter email subject',
				'emat_email_message.required' => 'Please enter email body',
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$email = EmailTemplate::create(['emat_email_name' => $request->emat_email_name, 'emat_email_subject' => $request->emat_email_subject, 'emat_email_message' => $request->emat_email_message]);

			if ($email) {
				DB::commit();
				return redirect()->route('emails')->with('success','Email details has been added successfully');
			} else {
				DB::rollback();
				return redirect()->back()->with('success','Error occur during adding, Please try again latter');
			}
		} catch (Exception $e) {
			DB::rollback();
			return redirect()->back();
		}
    }
    
	public function edit($id) {
        $email = EmailTemplate::where('id',$id)->first();
		return view('admin.email.create',compact('email','id'));
    }
    
    public function update(Request $request, $id) {
		DB::beginTransaction();
		try {

			$validator = Validator::make($request->all(), [
				'emat_email_name' => 'required|unique:email_templates,emat_email_name,' . base64_decode($request->email_id),
				'emat_email_subject' => 'required',
				'emat_email_message' => 'required',
			], [
				'emat_email_name.required' => 'Please enter email name',
				'emat_email_name.unique' => 'This email name is already_exists',
				'emat_email_subject.required' => 'Please enter email subject',
				'emat_email_message.required' => 'Please enter email body',
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

            $email = EmailTemplate::where('id', base64_decode($request->email_id))->update(['emat_email_name' => $request->emat_email_name, 'emat_email_subject' => $request->emat_email_subject, 'emat_email_message' => $request->emat_email_message]);

			if ($email) {
				DB::commit();
				return redirect()->route('emails.index')->with('success','Email details has been updated successfully');
			} else {
				DB::rollback();
				return redirect()->back()->with('error','Error occur during update, Please try again latter');
			}
		} catch (Exception $e) {
			DB::rollback();
			return redirect()->back();
		}
	}
}
