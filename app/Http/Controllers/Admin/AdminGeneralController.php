<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use App\Models\WalletLog;
use App\Models\UserProfile;
use App\Models\DanceType;
use Helper;
use App\Models\ProfessionalType;
use App\Models\NotificationSettings;
use App\Models\EventAttendee;
use App\Models\Event;
use App\Models\EventBooking;
use App\Models\EventType;
use DB;
use Auth;
use stdClass;

class AdminGeneralController extends Controller
{
    //SUSPEND USER FOR WEEK OR PERMANENTLY Or ACTIVATE USER
    public function userStatus(Request $request){
        try{
            DB::beginTransaction();
            $currentUser = User::where('id',$request->id)->first();
            $currentDate = Carbon::now();
            $nextWeek = $currentDate->addDays(7);
            $format = $nextWeek->format('d/m/Y');
            $nextWeek = ($request->flag == 4) ? $nextWeek->format('Y/m/d') : NULL;

            $currentUser = $currentUser->update([
                'is_active' => $request->flag,
                'suspended_till' => $nextWeek,
            ]);
            if($request->flag == 3){
                $msg = 'User has been Suspended Permanently';
            } else if($request->flag == 4){
                $msg = 'User has been Suspended for a '.$format;
            } else {
                $msg = 'User has been Activated Successfully';
            }
            DB::commit();
            return array('status' => 200,'msg_success' => $msg,'suspended_till' => $format,'flag' => $request->flag);
        } catch(Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
        }
    }

    //ACTIVATE USER
    public function checkNickname(Request $request){
        try{
            if($request->id){
                $currentUser = User::where('id',$request->id)->first();
                $currentUser = $currentUser->update([
                    'is_nickname_use' => ($request->flag == 'true') ? 1 : 0,
                ]);
                if($request->flag == true){
                    return response()->json(['status' => 200,'msg_success' => 'NickName is set Successfully']);
                } else{
                    return response()->json(['status' => 200,'msg_success' => 'NickName is unset Successfully']);
                }
            }
        } catch(Exception $e){
            \Log::info($e->getMessage());
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
        }
    }

    //ADD MONEY TO WALLET
    public function addMoneyToWallet(Request $request){
        try{
            DB::beginTransaction();
            $admin_id = Auth::user()->id;
            $userData = User::select('id','slug')->where('id',$request->user_id)->first();
            $user = User::where('id',$request->user_id)->count();
            $wallet_type = 5; //added by admin

            $total_wallet = 0;
            if($user > 0 && $request->add_money_input){
                WalletLog::create(
                    ['user_id' => $request->user_id, "event_booking_id" => null, "amount" => $request->add_money_input, "type" => $wallet_type,
                    "status" => 1, "ip_address" => $request->ip(),"created_by" => $admin_id, "updated_by" => $admin_id]);

                $userProfile = UserProfile::select('id','total_wallet','wallet_withdrawable_money')->where('user_id',$request->user_id)->first();
                $userProfile->total_wallet += $request->add_money_input;
                $userProfile->wallet_withdrawable_money += $request->add_money_input;
                $total_wallet = $userProfile->total_wallet;
                $userProfile->update();
            }
            DB::commit();

            $receivers = NotificationSettings::select('user_id')->where('user_id',$userData->id)->where('meta_notification_id',7)->where('status',1)->get()->pluck('user_id')->toArray();
                        
            $receivers = implode(',' ,$receivers);

            $data = array('amount' => number_format($request->add_money_input,2) ,'user' => 'Admin');
            $params = new stdClass;
            $params->receiver_id = $receivers;//'1,2'
            $params->title = "Add Money";
            $params->meta_notification_id = 7;
            $params->sender_id = 1;
            $data =json_encode($data);
            Helper::sendNotification($params,$data);


            return response()->json(['status' => 200,'msg_success' => 'Successfully added money to '.$request->user_name.'\'s wallet','total_wallet' => $total_wallet]);
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
        }
    }

    //AJAX Wallet Data
    public function ajaxWalletData(Request $request){
        $keyword = "";
        
        $Query = WalletLog::where('user_id',$request->id)->orderBy('created_at','desc');
        if(!empty($request->dates && $request->is_filtered == 1)){
            if(!empty($request->keyword)){
                $keyword = $request->keyword;
            }
            
            $dates = str_replace(' ','',$request->dates);
            $dates = explode('-',$dates);
            $startDate = str_replace('/','-',$dates[0]);
            $endDate = str_replace('/','-',$dates[1]);

            $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
            
            $Query->whereDate('created_at', '>=', $startDate);
            $Query->whereDate('created_at', '<=', $endDate);
        }

        $sr_no = $request->start;
        $data = datatables()->of($Query)
        ->addColumn('sr_no', function ($Query) use (&$sr_no) {
            $sr_no++;
            return $sr_no;
        })
        ->addColumn('created_at', function ($Query) {
            return Carbon::parse($Query->created_at)->format('d/m/Y');
        })
        ->addColumn('type', function ($Query) {
            $description = config('constant.USER.WALLET_TYPE')[$Query->type];
            return $description;
        })
        ->addColumn('status', function ($Query) {
            $status = strtolower(config('constant.USER.WALLET_STATUS')[$Query->status]);
            if($Query->status == 1){
                return $text = "<span class='badge badge-success'>".ucfirst($status)."</span>";
            }
            $text = "<span class='badge badge-danger'>".ucfirst($status)."</span>";
            return $text;
        })
        ->addColumn('amount', function ($Query) {
            $amountSign = ' + ';
            if(in_array($Query->type,config('constant.USER.WALLET_SIGN_STATUS'))){
                $amountSign = ' - ';
            }
            return $amountSign.''.$Query->amount.' '.config('constant.USD');
        })
        ->rawColumns(['action','status'])


        ->make(true);
        return $data;
    }

    //Check types unique
    public function checkUniqueTypes(Request $request){
        try{
            $requestedType = $request->title;
        
            if($request->flag == 1){
                $exists = DanceType::where('title',$requestedType)->where('id','!=',$request->id)->exists();
            }
            else if($request->flag == 2) {
                $exists = ProfessionalType::where('title', $requestedType)->where('id','!=',$request->id)->exists();
            }
            else if ($request->flag == 3) {
                $exists = EventType::where('title', $requestedType)->where('id','!=',$request->id)->exists();
            } else {
                $exists = null;
            }

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

    //Booking Section Dynamic Call in Dancer and Professional Section
    public function bookingSection(Request $request){
        $responseData = array();
        $responseData['current_page'] = 1;
        $responseData['total'] = 1;
        $responseData['status'] = '';
        try{
            if($request->id){
                $bookings = EventBooking::select('id','user_id','event_id','booking_unique_id','status')->where('user_id',$request->id)->with(['events' => function($query){
                    $query->select('id','title','slug','address','city_id','country_id','from','to','time_of_event')->with('country:id,name', 'city:id,name');
                },'tickets'])->whereIn('status',[1,2]);

                // return $bookings->get();

                if(!(is_null($request->isBooking))){
                    $bookings = $bookings->whereHas('events',function($query3) use ($request){
                        if($request->isBooking && ($request->isBooking == true || $request->isBooking == 1))
                            $query3->whereIn('event_status', [1,2]);
                        // else{
                        //     $query3->whereIn('event_status', [5,2]);
                        // }
                    });
                }

                if(!empty($request->date)){
                    $dates = str_replace(' ','',$request->date);
                    $dates = explode('-',$dates);
                    $startDate = str_replace('/','-',$dates[0]);
                    $endDate = str_replace('/','-',$dates[1]);
                    $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
                    
                    $bookings = $bookings->whereHas('events',function($query3) use ($startDate,$endDate){
                        $query3->where('from', '>=', $startDate);
                        $query3->where('to', '<=', $endDate);
                    });
                }

                $bookings = $bookings->paginate(config('constant.epp'))->toJson();
                $bookings = json_decode($bookings);

                $responseData['current_page'] = isset($bookings->current_page)?$bookings->current_page:1;
                $responseData['next_page'] = isset($bookings->current_page)?$bookings->current_page+1:2;
                $responseData['total'] = isset($bookings->last_page)?$bookings->last_page:0;
			    $responseData['status'] = 200;
                $responseData['html'] = view('admin.ajax.booking-section', compact('bookings'))->render();
                echo json_encode($responseData);  //Do not remove echo from here it is appended record of events in my event section of professional view profile
            } else {
                $responseData['status'] = 400;
                $responseData['message'] = trans('page.user_not_found');
                return $this->commonResponse($responseData, 200);
            }
        } catch(Exception $e){
            $responseData['status'] = 400;
            $responseData['message'] = trans('common.something_went_wrong');
            return $this->commonResponse($responseData, 200);
        }
    }

    //viewOrderSummary
    public function getOrderSummary($id){
        // DD('ANKIT');
        $eventBookings = EventBooking::where('booking_unique_id',$id)->select('event_id','id','booking_unique_id')->first();
        $events = Event::where(['id' => $eventBookings->event_id])
        ->select('id','user_id','slug','title','from','to','dance_type_id','event_status','time_of_event','event_type_id','country_id','city_id','venue_name','address')
        ->with(['eventType' => function($q){
            $q->select('id','title')->withTrashed();
        },'eventDanceMusicTypes' => function($query){
            $query->with(['danceMusicTypes' => function($query){
                $query->select('id','title')->withTrashed();
            }]);
        },'country:id,name','city:id,name','eventTicketTypes' => function($query) use ($eventBookings){
            $query->with(['eventAttendees' => function($query) use ($eventBookings){
                $query->where('event_booking_id',$eventBookings->id)->with(['getAttendee:id,name,email,slug,logo','bookingRecords' => function($q){
                    $q->select('id','status');
                }])->whereHas('bookingRecords',function($query){
                    $query->where('status',1);
                });
            }]);
        }])->withCount('eventTicketTypes')->first();

        $ticketcount = $ticketprice = $totalPrice = 0;
        foreach($events->eventTicketTypes as $ticket){
            $ticketcount = $ticket->eventAttendees->count();
            $ticketprice = $ticketcount * $ticket->price;
            $ticket->ticket_count = $ticketcount;
            $ticket->ticket_price = $ticketprice;
            $totalPrice += $ticketprice;
        }
        $events->totalPrice = $totalPrice;
                
        return view('admin.order-summary',compact('eventBookings','events'));
    }

    //viewOrderAttendees
    public function viewOrderSummaryAjaxData(Request $request){
        $eventBookings = EventBooking::where('booking_unique_id',$request->id)->select('event_id','id','booking_unique_id')->with('event:id,slug')->first();
        $Query = EventAttendee::where('event_id',$eventBookings->event_id)
        ->where('event_booking_id',$eventBookings->id)
        ->with('getAttendee:id,name,email,slug,is_active')
        ->whereHas('getAttendee',function($q){
            $q->where('is_active',1)->where('deleted_at', null);
        })
        ->whereHas('bookingRecords',function($q){
            $q->where('status',1);
        });

        $sr_no = $request->start;
        $data = datatables()->of($Query)
        ->addColumn('sr_no', function ($Query) use ($sr_no) {
            $sr_no++;
            return $sr_no;
        })
        ->addColumn('attendee_name', function ($Query) {
            if($Query->user_id != 0){
                return $Query->getAttendee->name;
            } else {
                return $Query->name;
            }
        })
        ->addColumn('email1', function ($Query) {
            if($Query->user_id != 0){
                return $Query->getAttendee->email;
            } else {
                return $Query->email;
            }
        })
        ->addColumn('ticket_no', function ($Query) {
            return $Query->ticket_no;
        })
        ->addColumn('actions', function ($Query) use ($eventBookings) {
            $qrcodeDynamicUrl = str_replace('{eventSlug}', $eventBookings->events->slug, config('constant.event_ticket_qrcode'));
            $qrcodeDynamicUrl = Helper::images($qrcodeDynamicUrl);
            return '<a href="#" class="text-primary px-2 py-1 py-lg-0 d-block d-lg-inline-block qrcode_attendee" data-src="'.$qrcodeDynamicUrl.$Query->qrcode.'" data-slug="'.$Query->slug.'" data-ticket="'.$Query->ticket_no.'" data-toggle="modal" data-target="#qr-model">View QR</a>';
        })
        ->rawColumns(['actions'])


        ->make(true);
        return $data;
    }
}
