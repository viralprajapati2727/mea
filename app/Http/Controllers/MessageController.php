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
use App\Models\StartUpPortal;

class MessageController extends Controller {
	
	/**
	 * Member chat message module
	 */
	public function index(Request $request,$user = null)
    {

		$activated_group = "";
		if(isset($_GET['g'])){
			$activated_group = $_GET['g'];
		}
        $currentUser = Auth::user();
		if(!is_null($user)){

        	$user = User::where('slug',$user)->select('id', 'slug', 'name', 'email', 'logo')->first();
		}
        $new_group_id = null;
        $messages = null;
		$checkGroup = [];
        /**
		 * If user is not exits or not active
		 */
		// if(empty($user)){
        //     return redirect()->back()->with('error',trans('User not exists or not active'));
        // }
		/**
		 * If user try to send message to himself
		 */
		if(!is_null($user) && $user->id == Auth::id()){
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
				if(!is_null($user) && $member->user_id == $user->id){
					$checkGroup[] = $chat;
				}
			}
		}


		if(sizeof($checkGroup) == 0){
			if(!is_null($user)){
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
				$new_group_id = $group->id;
			}
        }else{
            $new_group_id = $checkGroup[0]->id;
			// $messages = ChatMessage::with('members')->where('group_id',$new_group_id)->select('id','sender_id','text','group_id','created_at')->orderBy('id', 'DESC')->paginate(config('constant.rpp'));
			// $messages = $messages->reverse();
        }

		
		if(!empty($currentChats) && $currentChats->count()){
			$firstGroupMemberCollection = collect($currentChats[0]->members);
			$oppMember = $firstGroupMemberCollection->whereNotIn('user_id', [Auth::id()])->first();
			// $user = User::find($oppMember->id);
		} else {
			return redirect('/')->with('error','No User found');
		}

		//this ajax response use only in message pagination
        // if($request->ajax()){
        //     $view = view('message.ajax.message-list',compact('messages'))->render();
        //     return response()->json(['html'=>$view]);
        // }

        return view('message.index',compact('new_group_id','user','currentChats','currentUser','activated_group'));

    }
	public function getMessageList(Request $request){
		try{
			if($request->group_id != ''){
				$user = Auth::user();
				$user_id = $user->id;
				// for right sidebar
				$messages = ChatMessage::with('members')
				->where('group_id', $request->group_id)
				->select('id','sender_id','text','group_id','created_at')
				->orderBy('id', 'DESC')
				// ->get();
				->paginate(config('constant.rpp'));
				
				
				ChatMessagesReceiver::where(['group_id'=> $request->group_id,'receiver_id'=> $user->id])->update(['unreadable_count' => 0]);
				
				$currentPage = $messages->currentPage();
				$lastPage = $messages->lastPage();
				$current_page = isset($currentPage) ? $currentPage : 1;
				$next_page = isset($currentPage) ? $currentPage+1 : 2;
				$total = isset($lastPage) ? $lastPage : 0;
				$messages = $messages->reverse();
				$message_list_html = view('message.ajax.message-list')->with(compact('messages', 'user', 'currentPage','lastPage'))->render();
				
				return response()->json(['status' => '200', 'msg_success' => 'successfully run','html' => $message_list_html,'next_page' => $next_page, 'total' => $total]);
			}else{
				return;
			}
		}catch(Exception $e){
			Log::info('GeneralController message list Message:: ' . $e->getMessage() . ' line:: ' . $e->getLine() . ' Code:: ' . $e->getCode() . ' file:: ' . $e->getFile());
			DB::rollback();
			return redirect()->route('index')->with('error','Something went wrong');
		}
    }
    public function sendMessage(Request $request)
    {

		$checkGruopId = ChatGroup::where('id',$request->g_id)->whereNull('deleted_at')->exists();
		
		$receiverData = ChatMasters::where('group_id', $request->g_id)->whereNotIn('user_id', [Auth::id()])->first();

		$receiver_id = 0;
		if(!empty($receiverData)){
			$receiver_id = $receiverData->user_id;
		}
		
        if($checkGruopId){
			$message = $request->type_msg;
		
			ChatMessage::insert([
				'group_id' => (int) $request->g_id,
				'sender_id' => Auth::id(),
				'text' => $message,
				'created_at' => Carbon::now()->toDateTimeString(),
			]);
				
			$unread_count = ChatMessagesReceiver::where('group_id', $request->g_id)->where('receiver_id', $receiver_id)->first();
			$unreadable_count = 1;
			if(!empty($unread_count)){
				$unreadable_count = $unread_count->unreadable_count + 1;
			}	
				
			ChatMessagesReceiver::updateOrCreate(
				[
					'group_id'=> $request->g_id,
					'receiver_id'=> $receiver_id
				],
				[
					'unreadable_count' => $unreadable_count
				]
			);

		}

		// return redirect()->back();
		$responseData['status'] = 1;
		$responseData['redirect'] = route('member.message', ['g' => $request->g_id]);
		$responseData['message'] = "success";
		return $this->commonResponse($responseData, 200);
		
        // return request()->json(['status'=>200,'message'=>'message sent successfully']);
	}
}