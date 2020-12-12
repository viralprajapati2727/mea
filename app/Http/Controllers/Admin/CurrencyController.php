<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use DB;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class CurrencyController extends Controller
{
    public function index(){
        return view('admin.currency.index');
    }

    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = Currency::orderBy('id','desc');
        if(!empty($keyword)){
            $Query->where('title','like','%'.$keyword.'%');
        }

        $data = datatables()->of($Query)
        ->addColumn('title', function ($Query) {
            return $Query->title;
        })
        ->addColumn('code', function ($Query) {
            return $Query->code;
        })
        ->addColumn('symbol', function ($Query) {
            return $Query->symbol;
        })
        ->addColumn('status', function ($Query) {
            $text = "<span class='badge badge-danger'><a href='javascript:;' class='type-status' data-active='1' data-id='".$Query->id."'>INACTIVE</a></span>";
            if($Query->status == 1){
                $text = "<span class='badge badge-success'><a href='javascript:;' class='type-status' data-active='0' data-id='".$Query->id."'>ACTIVE</a></span>";
            }
            return $text;
        })
        ->addColumn('action', function ($Query) {
            $action_link = "";
            $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_dance_type openCurrencyPopoup' data-title = '".$Query->title."' data-code = '".$Query->code."' data-symbol = '".$Query->symbol."' data-id = '".$Query->id."' ><i class='icon-pencil7 mr-3 text-primary edit_currency'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='jobtitle_deleted' data-id='".$Query->id . "' data-active='2' data-inuse=''><i class='icon-trash mr-3 text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','status'])
        ->make(true);
        return $data;
    }


    //Add jobtitle
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $status = 0;
            if(isset($request->status)){
                $status = 1;
            }
            $currencyData = [
                'title' => $request->title,
                'code' => $request->code,
                'symbol' => $request->symbol,
                'status' => $status,
            ];

            if($request->id){
                $currencyData['updated_by'] = Auth::id();
            } else {
                $currencyData['created_by'] = Auth::id();
            }

            Currency::updateOrCreate(['id' => $request->id], $currencyData);
            DB::commit();
            if($request->id){
                return redirect()->route('currency.index')->with('success','Currency has been updated Successfully');
            }
            return redirect()->route('currency.index')->with('success','Currency has been added Successfully');
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 0,'message' =>'Something Went Wrong']);
        }
    }

    public function edit($id){
        $currency = Currency::where('id',$id)->first();
        return view('admin.currency.index',compact('currency'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $currency = Currency::find($id);
                $currency->delete();
                $currency->deleted_by = $user->id;
                $currency->save();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Currency has been deleted Successfully');
            } catch(Exception $e){
                DB::rollback();
                return array('status' => '0', 'msg_fail' => 'Something went wrong');
            }
        }
    }

    //Change Status
    public function changeStatus(Request $request){
        DB::beginTransaction();
        try {
            $currency = Currency::findOrFail($request->id);
            if ($currency->status == 0) {
                $currency->status = '1';
                $currency->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Currency has been activated successfully");
            } else {
                $currency->status = '0';
                $currency->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Currency has been inactivated successfully");
            }
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }

    //Check category unique
    public function checkUniqueCurrency(Request $request){
        try{
            $title = $request->title;
        
            $exists = Currency::where('title', $title)->where('id','!=', $request->id)->exists();

            if($exists){
                return "false";
            } else {
                return "true";
            }
            
        } catch(Exception $e){
            \Log::info($e);
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
        }
    }
}
