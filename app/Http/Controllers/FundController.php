<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Helper;
use App\User;
use Image;
use Illuminate\Support\Facades\Log;
use Auth;
use DB;
use Session;
use Carbon\Carbon;
use App\Models\StartUpPortal;
use App\Models\ScheduleAppointment;
use App\Models\StartupTeamMembers;
use App\Models\RaiseFund;
use App\Models\PaymentLogs;
use App\Models\StripeAccount;

class FundController extends Controller
{
    public function index(){
        $funds = RaiseFund::where('user_id',Auth::id())->orderBy('id','DESC')->paginate(10);
        $stripeAccountExists = StripeAccount::where('user_id', Auth::id())->where('details_submitted', 'true')->exists();
        
        return view('fund.index',compact('funds', 'stripeAccountExists'));    
    }

    public function create($action = null,$id = null)
    {
        try{
            $fund = null;

            if($id != null){
                $fund = RaiseFund::where('id',$id)->where('user_id',Auth::id())->first();
            }

            if($action != null && $action == 'create'){
                return view('fund.create',compact('fund'));
            }else{
                return view('fund.view',compact('fund'));
            }

        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('warning',$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];

        DB::beginTransaction();

        try{
            $status = $is_view = 0;
            $user_id = Auth::id();

            if(isset($request->is_view)){
                $is_view = 1;
            }
            if(isset($request->status)){
                $status = $request->status;
            }
            $param = [];
            if($request->has('id') && $request->id > 0){
                $param[ "id" ] = $request->id;
            }

            $param2 = [
                "title" => $request->title,
                "description" => $request->description,
                "currency" => $request->currency,
                "amount" => $request->amount,
                "status" => $status,
                "user_id" => $user_id
            ];

            if($request->has('id') && $request->id > 0){
                $startup_portal = RaiseFund::updateOrCreate(
                    $param,
                    $param2
                );
            }else{
                $startup_portal = RaiseFund::create(
                    $param2
                );
            }
            
            DB::commit();
            $message = 'Your fund request has been saved successfully';
            $responseData['status'] = 1;
            $responseData['redirect'] = route('startup.raise-fund');
            $responseData['message'] = $message;
            Session::flash('success', $responseData['message']);

            return $this->commonResponse($responseData, 200);

        } catch(Exception $e){
            Log::emergency('fund save Exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            $code = ($e->getCode() != '')?$e->getCode():500;
            $responseData['message'] = trans('common.something_went_wrong');
            return $this->commonResponse($responseData, $code);
        }
    }

    public function viewDonorList($id)
    {
        $payments = PaymentLogs::where('raise_fund_id', $id)->paginate(10);

        return view('pages.view-donors', compact('payments', 'id'));
    }
}
