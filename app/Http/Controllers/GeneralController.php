<?php
namespace App\Http\Controllers;

use App\Http\Controllers\SendMailController;
use App\User;
use Auth;
use DB;
use Hash;
use Helper;
use Illuminate\Http\Request;
use Validator;
use App;
use Route;
use PHPUnit\Framework\Exception;
use App\Models\ProfileQuestion;
use App\Models\Faq;
use App\Models\Blog;
use App\Models\Resource;
use App\Models\BusinessCategory;
use App\Models\UserSkill;
use App\Models\UserProfile;
use App\Models\ChatGroup;
use App\Models\ChatMasters;
use App\Models\ChatMessage;
use App\Models\ChatMessagesReceiver;
use App\Models\RaiseFund;
use App\Models\Topic;
use App\Models\EmailSubscriptions;
use Carbon\Carbon;

class GeneralController extends Controller {
	
	public function index() {
		$blogs = Blog::select('id','slug','title','src','short_description','created_by','updated_at',"published_at","author_by")->where('status',1)->where('deleted_at',null)->orderBy('id','DESC')->limit(3)->get();
		
        return view('welcome',compact('blogs'));
	}
	public function changePassword() {
		return view('auth.change_password');
    }
    public function updatePassword(Request $request) {
		try {
			$loggedInUser = Auth::user();
			$validator = Validator::make($request->all(), [
				'confirm_password' => 'required',
				'new_password' => 'required|min:6',
				'confirm_password' => 'required|same:new_password'
			], [
				'current_password.required' => trans('auth.Please_enter_old_password'),
				'new_password.required' => trans('auth.Please_enter_new_password'),
				'new_password.min' => trans('auth.AT_LEAST_CHARACTERS_REQUIRED'),
				'confirm_password.required' => trans('auth.Please_enter_confirm_password'),
				'confirm_password.same' => trans('auth.Please_enter_the_same_password_as_above'),
			]);
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
				$loggedInUser->password = Hash::make($request->new_password);
				$loggedInUser->save();
			DB::commit();
			return redirect()->back()->with('success',trans('auth.Password_changed_successfully'));

		} catch (Exception $e) {
			Log::emergency('change password exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
			return back()->with('error',trans('app.something_went_wrong'));
		}
	}
	 /**
     * opening user fill profile form.
     *
     * @return \Illuminate\Http\Response
     */
    public function fillProfile()
    {
        try{
            $user = Auth::user();
            $preprofile = true;
            $where['active'] = true;

			$questions = ProfileQuestion::where('deleted_at',null)->get();
			
			// get profile data if exists
            $profile = User::where('id',$user->id)
                ->select('id','name','logo','is_profile_filled','slug','email')
                ->with([
                    'userProfile',
                ])
                ->first();

            if(empty($profile)){
                $preprofile = false;
			}

			if($user->type == config('constant.USER.TYPE.SIMPLE_USER')){
				return view('user.fill-profile',compact('profile','questions'));
			}

			return view('entrepreneur.fill-profile',compact('profile','questions'));
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('warning',$e->getMessage());
        }
	}
	public function viewProfile($slug){
		if(empty($slug)){
			return redirect()->route('index');
		}

		$profile = Helper::userProfile($slug);
		$questions = ProfileQuestion::where('deleted_at',null)->get();
		if(empty($profile)){
			return redirect()->route('index')->with('error', 'No user details found!');
		}

		return view('profile',compact('profile','questions'));
	}
	public function aboutUs() {
		return view('pages.about-us');
    }
	public function team() {
		return view('pages.our-team');
    }
	// Old resource
	// public function resource() {
	// 	$resources = Resource::select('id','slug','title','src','short_description','created_by','updated_at')->where('deleted_at',null)->orderBy('id','ASC')->get();
	// 	return view('pages.resources',compact('resources'));
	// }
	public function resourceNew() {
		$topics = Topic::whereNull("parent_id")->where("status", 1)->get();

		$resourcesNew = Resource::select('id','topic_id','slug','title','src','document','description','created_by','updated_at')->with([
			'topic'
		])->whereIn('topic_id', $topics->pluck('id')->toArray())->where('deleted_at',null)->orderBy('id','ASC')->get();
		
	return view('pages.resources-new', compact('resourcesNew', "topics"));
	}
	public function resourceDetail($slug = null){
		if(is_null($slug)){
			return redirect()->route('page.resources');
		}

		$resource = Resource::where('deleted_at',null)->where('slug',$slug)->first();
		return view('pages.resource-detail',compact('resource'));

	}
	public function members() {
		try{
			$params = [];
			if (isset($_GET['skill']) != '' && !empty($_GET['skill'])) {
				foreach ($_GET['skill'] as $t) {
					$skill[] = $t;
				}
				$params['skill'] = $skill;
			}

			if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
				$keyword = $_GET['keyword'];
				$params['keyword'] = $keyword;
			}	

			if (isset($_GET['city']) != '' && !empty($_GET['city'])) {
				foreach ($_GET['city'] as $t) {
					$city[] = $t;
				}
				$params['city'] = $city;
			}

			// \Debugbar::warning($params);
			
			$members = User::with('skills')->with(['userProfile'=>function($q){
				$q->select('id', 'user_id', 'city');
			}]);

			if (!empty($params['keyword'])) {
				$members->where('name','LIKE', '%' .$params['keyword']. '%');
			}

			if (!empty($params['skill'])) {
				$members->whereHas('skills',function($q) use ($params) {
					$q->whereIn('title',$params['skill']);
				});
			}

			if (!empty($params['city'])) {
				$members->whereHas('userProfile',function($q) use ($params) {
					$q->whereIn('city',$params['city']);
				});
			}
			if(Auth::check()){
				$members->whereNotIn('id', [Auth::id()]);
			}

			$members = $members->whereIn('type',[config('constant.USER.TYPE.SIMPLE_USER'),config('constant.USER.TYPE.ENTREPRENEUR')])
			->where('is_active',1)
			->where('deleted_at',null)
			->orderBy('id','DESC')
			->paginate(10);

			$skills = UserSkill::select('title')->groupBy('title')->get();
			$cities = UserProfile::select('city')->groupBy('city')->get();
			$keyword = '';

			/**
			 * Getting message count
			 */

			// $message_count = User::with(['members'])->get();
			$receiver = null;
			if(Auth::check()){
				$receiver = ChatMessagesReceiver::select('unreadable_count','group_id')->where('receiver_id',Auth::id())->get();
				// return $receiver;
			}

			return view('pages.members',compact('members', 'skills', 'cities', 'params','keyword'));

		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('warning',$e->getMessage());
		}
    }
	public function blogs() {
		$blogs = Blog::select('id','slug','title','src','short_description','created_by','updated_at',"published_at","author_by")->where('deleted_at',null)->orderBy('id','DESC')->paginate(9);
		return view('pages.blogs',compact('blogs'));
	}
	public function blogDetail($slug = null){
		if(is_null($slug)){
			return redirect()->route('page.blogs');
		}

		$blog = Blog::where('deleted_at',null)->where('slug',$slug)->first();
		return view('pages.blog-detail',compact('blog'));

	}
	public function faq() {
		$faqs = Faq::where('deleted_at',null)->get();
		return view('pages.faq',compact('faqs'));
	}
	public function contactUs() {
		return view('pages.contact-us');
	}
	public function contactRequest(Request $request){
		$email_param = ['email_id' => 4,'user_id' => 1,'name' => $request->name,'email'=>$request->email,'subject'=>$request->subject,'message' => $request->message];

		SendMailController::dynamicEmail($email_param);

		return redirect()->back()->with('success',trans('Sent Successfully!'));
	}
	/**
	 * Member chat message module
	 */

     public function getMessages(Request $request,$user = null)
     {
        $currentUser = Auth::user();
        $user = User::where('slug',$user)->select('id', 'slug', 'name', 'email', 'logo')->first();
        $new_group_id = null;
        $messages = null;
		$checkGroup = [];
        /**
		 * If user is not exits or not active
		 */
		if(empty($user)){
            return redirect()->back()->with('error',trans('User not exists or not active'));
        }
		/**
		 * If user try to send message to himself
		 */
		if($user->id == Auth::id()){
			return redirect(route('page.members'))->with('error',"You can not send message to yourself");
		}

		/**
		 * Check all groups with current logged in user
		 */
		$currentChats = ChatGroup::with(['members','members.user'])->
			whereHas('members',function($query) use ($currentUser){
				$query->where('user_id',$currentUser->id);
			})->get();
		
		/**
		 * Get group of receiver id from current chats if created 
		 */
		foreach ($currentChats as $key => $chat) {
			foreach($chat->members as $member){
				if($member->user_id == $user->id){
					$checkGroup[] = $chat;
				}
			}
		}
		
		if(sizeof($checkGroup) == 0){

            $group = ChatGroup::Create([ 'is_single' => 1, 'created_by' => $currentUser->id ]);

            $create = [];
            $members_array = [];
            $members_array[] = $currentUser->id;
            $members_array[] = $user->id;

            foreach($members_array as $member){
                $create[] = [
                    'user_id' => $member,
                    'group_id' => $group->id
                ];
            }

            if(!empty($create)){
                ChatMasters::insert($create);
            }
            $create_reciever = [];
            foreach($members_array as $member){
                $create_reciever[] = [
                    'receiver_id' => $member,
                    'group_id' => $group->id,
                    'unreadable_count' => 0,
                ];
            } 
            if(!empty($create_reciever)){
                $abs = ChatMessagesReceiver::insert($create_reciever);
            }
            $new_group_id = $group->id;
        }else{
            $new_group_id = $checkGroup[0]->id;
			$messages = ChatMessage::with('members')->where('group_id',$new_group_id)->select('id','sender_id','text','group_id','created_at')->orderBy('id', 'DESC')->paginate(config('constant.rpp'));
			$messages = $messages->reverse();
        }
	
		//this ajax response use only in message pagination
        if($request->ajax()){
            $view = view('message.ajax.message-list',compact('messages'))->render();
            return response()->json(['html'=>$view]);
        }
		
        return view('message.index',compact('new_group_id','user','checkGroup','messages'));

    }
     
    public function sendMessage(Request $request)
    {
		$checkGruopId = ChatGroup::where('id',$request->group_id)->whereNull('deleted_at')->exists();
		
        if($checkGruopId){
			$message = $request->type_msg;
		
			ChatMessage::insert([
				'group_id' => (int) $request->group_id,
				'sender_id' => Auth::id(),
				'text' => $message,
				'created_at' => Carbon::now()->toDateTimeString(),
			]);
				
			$unread_count = ChatMessagesReceiver::where('group_id', $request->group_id)->where('receiver_id', $request->receiver_id)->first();
				
			ChatMessagesReceiver::where([
				'group_id'=> $request->group_id ,
				'receiver_id'=> $request->receiver_id
			])->update([
				'unreadable_count' => $unread_count->unreadable_count + 1
			]);
		}

		// return redirect()->back();
		$responseData['status'] = 1;
		$responseData['message'] = "success";
		return $this->commonResponse($responseData, 200);
		
        // return request()->json(['status'=>200,'message'=>'message sent successfully']);
	}
	
	public function idea() {
		return view('pages.drop-idea');
	}
	public function sendIdea(Request $request){
		$email_param = [
			'email_id' => 7,
			'user_id' => 1,
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'company_name' => $request->company_name,
			'city' => $request->city,
			'century' => $request->century,
			'phone' => $request->phone,
			'email'=> $request->email,
			'age'=> $request->age,
			'gender'=> $request->gender,
			'occupation'=> $request->occupation,
			'description' => $request->description
		];

		SendMailController::dynamicEmail($email_param);

		return redirect()->back()->with('success',trans('Sent Successfully!'));
	}
	public function getStartupPortal(){
		$recentMembers = Helper::getRecentMembers();
		return view('pages.startup-portal',compact('recentMembers'));
	}
	public function getFundRequests(){
		$funds = RaiseFund::where('status',1)->paginate(10);
		return view('pages.fund-requests',compact('funds'));
	}
	public function viewFundRequest($id = null){
		try{
            $fund = null;

            if($id != null){
                $fund = RaiseFund::where('id',$id)->first();
            }

            return view('pages.view-fund-request',compact('fund'));

        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('warning',$e->getMessage());
        }
	}

	public function subscriptionEmail(Request $request)
	{
		try {
			$status = '';
			$message = '';
			$email = $request->email;
			$checkMail = EmailSubscriptions::where('email', $email)->exists();
			
			if(!$checkMail) {
				$subscribe = EmailSubscriptions::Create(['email' => $email]);
				
				$status = 'success';
				$message = 'You have subscribed successfully';
			} else {
				$status = 'error';
				$message = 'Email is already subscribed';
			}

			return redirect()->to('/')->with($status, $message);

		} catch (Exception $e) {
			return redirect()->back()->with('warning',$e->getMessage());
		}
	}
}