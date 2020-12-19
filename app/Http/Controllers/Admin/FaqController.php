<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Faq;
use Datatables;
use Helper;
use DB;
use Validator;
use Auth;

class FaqController extends Controller
{
    //display data in faq listing at admin side 
    public function index(){
        return view('admin.faq.index');
    }

     public function ajaxData(Request $request){
        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }
        
        $Query = Faq::orderBy('id','desc');
        if(!empty($keyword)){
            $Query->where('question','like','%'.$keyword.'%');
        }

        $data = datatables()->of($Query)
        ->addColumn('question', function ($Query) {
            return $Query->question;
        })
        ->addColumn('date_updated', function ($Query) {
            return date("d-m-Y",strtotime($Query->updated_at));
        })
        ->addColumn('status', function ($Query) {
            $text = "<span class='badge badge-danger'><a href='javascript:;' class='faq-status' data-active='1' data-id='".$Query->id."'>INACTIVE</a></span>";
            if($Query->status == 1){
                $text = "<span class='badge badge-success'><a href='javascript:;' class='faq-status' data-active='0' data-id='".$Query->id."'>ACTIVE</a></span>";
            }
            return $text;
        })
        ->addColumn('action', function ($Query) {
            $action_link = "";
            $action_link .= "<a href='".route('faq.edit',$Query->id)."' class='edit'><i class='icon-pencil7 mr-3 text-primary'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='faq-deleted' data-id='".$Query->id . "' data-active='2'><i class='icon-trash mr-3 text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','status'])
        ->make(true);
        return $data;
    }

    public function create(){
        $id = null;
        return view('admin.faq.create',compact('id'));
    }

    public function store(Request $request){
        DB::beginTransaction();
        try{
            // dd($request->all());
            $validator = Validator::make($request->all(),[
                'question' => "required|unique:faqs,question,NULL,id",
                'answer' => "required",
            ],[
                'question.required' => 'Please enter question',
                'answer.required' => 'Please enter answer',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $faqDetails = Faq::create(['created_by' => Auth::id(), 'question' => $request->question , 'answer' => $request->answer , 'status' => isset($request->status) ? $request->status : 0 ]);  
            if($faqDetails) {
                DB::commit();
                return redirect()->route('faq.index')->with('success',trans('page.fAQ_has_been_added_successfully'));
            }

        } catch(Exception $e){
            DB::rollback();
			return redirect()->back();
        }
    }

    public function changeStatus(Request $request){
        DB::beginTransaction();
		try {
			$faq = Faq::findOrFail($request->id);
			if ($faq->status == 0) {
				$faq->status = '1';
				$faq->update();
				DB::commit();
				return array('status' => '200', 'msg_success' => trans("page.fAQ_has_been_activated_successfully"));
			} else {
				$faq->status = '0';
				$faq->update();
				DB::commit();
				return array('status' => '200', 'msg_success' => trans("page.fAQ_has_been_inactivated_successfully"));
			}
		} catch (Exception $e) {
			DB::rollback();
			echo $e->getMessage();
		}
		exit;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $model = Faq::where('id',$id)->first();    
        return view('admin.faq.create',compact('model','id'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        DB::beginTransaction();
        try{

            $validator = Validator::make($request->all(),[
               'question' => "required|unique:faqs,question,".$id.",id",
                'answer' => "required",
            ],[
                'question.required' => 'Please enter question',
                'answer.required' => 'Please enter answer',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $faqDetails = Faq::where('id',$request->id)->update(['updated_by' => Auth::id(), 'question' => $request->question , 'answer' => $request->answer , 'status' => isset($request->status) ? $request->status : 0]); 
            if($faqDetails) {
                DB::commit();
                return redirect()->route('faq.index')->with('success',trans('page.fAQ_has_been_updated_successfully'));
            } 
            
        } catch(Exception $e){
            DB::rollback();
			return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $faq = Faq::find($id);
                $faq->delete();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Question has been deleted Successfully');
            } catch(Exception $e){
                DB::rollback();
                return array('status' => '0', 'msg_fail' => 'Something went wrong');
            }
        }
    }

    public function checkUniqueQuestion(Request $request){
        // dd($request->all());
        $faqExist = Faq::where('question',$request->question)->where('id','!=',$request->id)->exists();
        if($faqExist){
            return 'false';
        }else {
            return 'true';
        }
    }
}
