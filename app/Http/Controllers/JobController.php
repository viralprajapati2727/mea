<?php

namespace App\Http\Controllers;

use App\User;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use stdClass;
use App\Models\JobTitle;
use App\Models\Currency;



class JobController extends Controller
{
    /**
     * opening user fill profile form.
     *
     * @return \Illuminate\Http\Response
     */
    public function fillJob($job_unique_id = null)
    {
        try{
            $jobtitles = JobTitle::where('deleted_at',null)->get();
            $currencies = Currency::where('deleted_at',null)->get();
			return view('job.fill-job',compact('jobtitles','currencies'));
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('warning',$e->getMessage());
        }
	}

    
}
