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
use App\Models\KeySkill;
use App\Models\UserProfile;

class GeneralController extends Controller {
	public function index() {
		$blogs = Blog::select('id','slug','title','src','short_description','created_by','updated_at')->where('status',1)->where('deleted_at',null)->orderBy('id','DESC')->limit(3)->get();
		
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
	public function resource() {
		$resources = Resource::select('id','slug','title','src','short_description','created_by','updated_at')->where('deleted_at',null)->orderBy('id','ASC')->get();
		return view('pages.resources',compact('resources'));
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

			\Debugbar::warning($params);
			
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
			
			$members = $members->whereIn('type',[config('constant.USER.TYPE.SIMPLE_USER'),config('constant.USER.TYPE.ENTREPRENEUR')])
			->where('is_active',1)
			->where('deleted_at',null)
			->orderBy('id','DESC')
			->paginate(10);

			$skills = KeySkill::select('id', 'title','status')->where('status',1)->get();
			$cities = UserProfile::select('city')->groupBy('city')->get();
			$keyword = '';
			return view('pages.members',compact('members', 'skills', 'cities', 'params','keyword'));

		}catch(Exception $e){
			DB::rollback();
			return redirect()->back()->with('warning',$e->getMessage());
		}
    }
	public function blogs() {
		$blogs = Blog::select('id','slug','title','src','short_description','created_by','updated_at')->where('deleted_at',null)->orderBy('id','DESC')->paginate(9);
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
}
