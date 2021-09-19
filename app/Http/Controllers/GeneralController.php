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
use App\Models\StripeAccount;
use App\Models\PaymentLogs;
use Stripe\Stripe;
use Stripe\Checkout;

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

	public function resource($id) {
		if(empty($id)){
			return redirect()->back();
		}

		$id = base64_decode($id);

		$topic = Topic::find($id);
		
		$resources = Resource::whereHas('topics', function($query) use($id){
			$query->where('topic_id', $id);
		})->where('deleted_at',null)->orderBy('resource_order')->get();

		return view('pages.resources', compact('resources','topic'));
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

			$members = User::with('skills')->with(['userProfile'=>function($q){
				$q->select('id', 'user_id', 'city');
			}])->whereNull('deleted_at');

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
			->has('userProfile')
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
			// 'century' => $request->century,
			'phone' => $request->phone,
			'email'=> $request->email,
			'age'=> $request->age,
			'gender'=> $request->gender,
			'occupation'=> $request->occupation,
			'description' => $request->description
		];

		SendMailController::dynamicEmail($email_param);

		$responseData['status'] = 1;
		$responseData['message'] = "Sent Successfully!";
		return $this->commonResponse($responseData, 200);
		
		// return redirect()->back()->with('success',trans('Sent Successfully!'));
	}
	public function getStartupPortal(){
		// $recentMembers = Helper::getRecentMembers();

		$startups = StartUpPortal::with('user')->whereNull('deleted_at')->where('status',1)->orderBy('id','DESC')->take(5)->get();

		return view('pages.startup-portal',compact('startups'));
	}
	public function getFundRequests(){
		$funds = RaiseFund::where('status',1)->paginate(10);
		return view('pages.fund-requests',compact('funds'));
	}
	public function viewFundRequest(Request $request, $id = null, $status = null){
		try{
						$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
						$message = "";
            $fund = null;
						$donors = 0;
						$justDonors = 0;

            if($id != null){
                $fund = RaiseFund::where('id',$id)->first();
                $donors = PaymentLogs::where('raise_fund_id',$id)->where('payment_status', 'paid')->count(); // get total donors
                $justDonors = PaymentLogs::where('raise_fund_id',$id)->where('payment_status', 'paid')->where('updated_at', ">", Carbon::now()->subMinutes(30)->toDateTimeString())->count(); //  get recent 30 mins donors count 
            
								RaiseFund::where('id',$id)->update([
									'views' => $fund->views + 1
								]);
							}

						if ($status != null) {
							$retrieve  =  $stripe->checkout->sessions->retrieve(
								$request->session()->get('payment_session')['id'],
								[]
							);

							if($retrieve['payment_status'] == 'paid') {
								$message = "Thanks you";
								
								$paymentLog = PaymentLogs::updateOrCreate([
											"id" => $request->session()->get('paymentLog')->id
									], [
											"payment_status" => $retrieve->payment_status,
											"payment_object" => json_encode($retrieve)
									]);

									$raiseFund = RaiseFund::where("id", $paymentLog->raise_fund_id)->first();
									$receivedAmount = 0;

									if (!is_null($raiseFund)) {
										$receivedAmount = $raiseFund->received_amount;
									}

									RaiseFund::updateOrCreate([
										"id" => $paymentLog->raise_fund_id
									], [
										"received_amount" => $receivedAmount + $paymentLog->amount,
										"donors" => $donors
									]);
							} else {
								$message = "Something went wrong";
							}

							$request->session()->forget('payment_session');
							$request->session()->forget('paymentLog');

							return redirect(route('page.fund-requests.view', ['id'=> $id]));
						}

            return view('pages.view-fund-request',compact('fund', "message", "donors", "justDonors"));

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