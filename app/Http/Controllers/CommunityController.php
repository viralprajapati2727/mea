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
use App\Models\Tags;
use App\Models\Community;
use App\Models\CommunityTags;
use App\Models\QuestionCategory;



class CommunityController extends Controller
{
    public function index(){
        $categories = QuestionCategory::select('id','title','status')->where('deleted_at',null)->where('status',1)->orderBy('id','DESC')->get();
        $tags = Tags::where('status',1)->select('title')->get()->pluck('title');
        $questions = Community::with('communityTags')->where('user_id',Auth::id())->where('deleted_at',null)->where('status',1)->orderBy('id','DESC')->get();

        \Debugbar::info($questions);
        
        return view('community.index',compact('categories','tags','questions'));
    }
    public function updateCommunity(Request $request){
        
        \Debugbar::info($request);
        
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];

        $user_id = Auth::id();
        $param = [];

        DB::beginTransaction();

        try{

            $message_text = "posted"; // if new questioned asked
            $param = [];
            if($request->has('question_id')){
                $param[ "id" ] = $request->question_id;
                $message_text = "updated";
            }

            $param2 = [
                "user_id" => $user_id, 
                "title" => $request->title, 
                "description" => $request->description, 
                "question_category_id" => $request->category_id,
                "created_by" => $user_id
             ];

            $tags = explode(',',preg_replace('/\s*,\s*/', ',', $request->tag));
            
            $tagArr = [];
            foreach ($tags as $k => $tag) {
                if(!empty($tag)){
                    /**added new tags in tags table */
                    $tagModel = Tags::firstOrCreate(['title' => $tag],["created_by" => $user_id]);

                    if(isset($tagModel) && !empty($tagModel) && $tagModel->count() > 0){
                        $tagArr[] = $tagModel->id;
                    }
                }
            }

            if(!empty($tagArr)){
                $param2[ "tags" ] = implode(',',$tagArr);
            }

            $community = Community::create($param2);

            
            if($community){
                /**Created question tags entry in community table */
                foreach($tagArr as $tag){
                    $CommunityTags = CommunityTags::create(['community_id'=>$community->id,'tag_id'=>$tag]);
                }

                DB::commit();
                $message = 'Your question has been '.$message_text.' successfully';
                $responseData['status'] = 1;
                $responseData['redirect'] = route('community.index');
                $responseData['message'] = $message;
                Session::flash('success', $responseData['message']);
            } else {
                DB::rollback();
                $responseData['message'] = trans('common.something_went_wrong');
                Session::flash('success', $responseData['message']);
            }
            return $this->commonResponse($responseData, 200);

        } catch(Exception $e){
            Log::emergency('job controller save Exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            $code = ($e->getCode() != '')?$e->getCode():500;
            $responseData['message'] = trans('common.something_went_wrong');
            return $this->commonResponse($responseData, $code);
        }
    }
    public function detail(Request $request){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];

        $appointment = Appointment::whereId($request->id)->first();
        $responseData['data']['name'] = $appointment->name;
        $responseData['data']['email'] = $appointment->user->email;
        $responseData['data']['date'] = $appointment->appointment_date;
        $responseData['data']['time'] = $appointment->time;
        $responseData['data']['description'] = $appointment->description;
        $responseData['status'] = 1;

        return $this->commonResponse($responseData, 200);
    }
    public function destroy(Request $request){
        DB::beginTransaction();
        try{
            $Appointment = Appointment::find($request->id);
            $Appointment->delete();
            DB::commit();
            Session::flash('success', 'Appointment has been deleted Successfully');
            return array('status' => '200', 'msg_success' => 'Appointment has been deleted Successfully');
        } catch(Exception $e){
            DB::rollback();
            return array('status' => '0', 'msg_fail' => 'Something went wrong');
        }
    }
}
