<?php

namespace App\Http\Controllers;
use App\Helpers\Helper;
use App\Mail\DynamicEmail;
use App\Model\EmailTemplate;
use App\User;
use Carbon;
use DB;
use Illuminate\Mail\Markdown;
use Mail;
use Auth;
use PDF;

class SendMailController extends Controller {
	/*
		send dynamic mail
	*/
	public static function dynamicEmail($data) {
		$email_body = $email_subject = $user_email = $attachment  = '' ;
		$param = array();
		$email = EmailTemplate::findorfail($data['email_id']);
		$user = User::select('*', DB::raw("name, is_nickname_use, nick_name"))
            ->where('id', $data['user_id'])
            ->where('deleted_at', null)
			->first();

		$manager_email = array();
		$admin_email = config('constant.admin_email');
		if ($email && $user) {
			$email_subject = $email->emat_email_subject;
			$email_body = $email->emat_email_message;

			$user_email = $user->email;
			if($user->is_nickname_use == 1) $user_name = ucwords($user->nick_name);
			else $user_name = ucwords($user->name);

			// Get email template body content as per requirement
			switch ($email->id) {
			case 1:
				// New Registration - Activation link
				$email_body = str_replace('{link}', $data['verificationUrl'], $email_body);
				$email_body = str_replace('{user_name}', $user_name, $email_body);
				break;
			case 2:
				//Welcome message after successfully verify
				$email_body = str_replace('{user_name}', $user_name, $email_body);
				break;
			case 3:
				//Forgot Password- Password Reset Link
                $email_body = str_replace('{link}', $data['verificationUrl'], $email_body);
                $email_body = str_replace('{user_name}', $user_name, $email_body);
				break;
			case 4:
				$bookingId = $data['eventBookingId'];
				//Used keyword {bookingId}, {eventName}, {eventDateAndtime}, {eventAddress},
								//Ticket: {sr} {name} {email} {ticketno} {src}
				//Get event name using query
					$eventDetail = EventBooking::select('id','event_id','booking_unique_id','user_id')
					// ->with('event:id,title')
                    ->with(['event' => function($query){
							$query->select('id','slug','title','from','to','user_id','time_of_event','city_id','country_id','venue_name','address')->with(['country:id,name', 'city:id,name','eventOwner:id,email,name,is_nickname_use,nick_name']);
					},'attendees' => function($query){
							$query->select('id','user_id','event_booking_id','name','email','ticket_no','qrcode')->with(['getAttendee:id,name,is_nickname_use,nick_name']);
					},'user'])
					->where(['id' => $bookingId])->first();
					
					$eventAddress = $eventDetail->event->venue_name.', '.$eventDetail->event->address.', '.$eventDetail->event->city->name.', '.$eventDetail->event->country->name;
					$eventDate = isset($eventDetail->event->from) ? Carbon\Carbon::parse($eventDetail->event->from)->format('d M, Y') : "";
					$eventDate .= isset($eventDetail->event->to) ? ' to '.Carbon\Carbon::parse($eventDetail->event->to)->format('d M, Y') : "";
					$eventTime = $eventDetail->event->time_of_event;
					$eventDateTime = $eventDate.' '.$eventTime;
                    $email_body  = str_replace('{eventName}', $eventDetail->event->title, $email_body);
                    $email_body  = str_replace('{bookingId}', $eventDetail->booking_unique_id, $email_body);
					$email_body  = str_replace('{eventDateAndtime}', $eventDateTime, $email_body);
					$email_body  = str_replace('{eventaddress}', $eventAddress, $email_body);
					$orgBody = $email_body;
					$startTicketPos = SendMailController::getStringPosition($orgBody,'<tr>',1);
					$endTicketTrPos = SendMailController::getStringPosition($orgBody,'</tr>',1) + 5;
					// echo 'startTicketPos:'.$startTicketPos.':endTicketPos:'.$endTicketPos;exit;
					$endTicketPos = ($endTicketTrPos - $startTicketPos);
					$orgTicketTrHtml = substr($orgBody, $startTicketPos, $endTicketPos);
					
					$stringBeforeTr = substr($orgBody, 0, $startTicketPos);
					$stringAfterTr = substr($orgBody, $endTicketTrPos, strlen($orgBody));
					$orgBody = $stringBeforeTr . '{ticketInfo}' . $stringAfterTr;
					$eventQrcodePath = str_replace('{eventSlug}', $eventDetail->event->slug, config('constant.event_ticket_qrcode'));
					$eventQrcodePath = Helper::images($eventQrcodePath);
					
					$loop = 1;
					foreach ($eventDetail->attendees as $attendee) {
						$ticketTrHtml = $orgTicketTrHtml;
						if($attendee->user_id != $user->id){
							$loop = 1;
							$attendee_email = $attendee->email;
							// $userName = ($attendee->user_id > 0) ? (($attendee->getAttendee->is_nickname_use == 0) ? $attendee->getAttendee->name : $attendee->getAttendee->nick_name) : $attendee->name;
							$userName = $attendee->name;

							$ticketTrHtml = str_replace('{sr}', $loop, $ticketTrHtml);
							$ticketTrHtml = str_replace('{name}', $userName, $ticketTrHtml);
							$ticketTrHtml = str_replace('{email}', $attendee_email, $ticketTrHtml);
							$ticketTrHtml = str_replace('{ticketno}', $attendee->ticket_no, $ticketTrHtml);
							$ticketTrHtml = str_replace('{src}', $eventQrcodePath.$attendee->qrcode, $ticketTrHtml);
							$email_body = str_replace('{ticketInfo}', $ticketTrHtml, $orgBody);
							$email_body = str_replace('{user_name}', $userName, $email_body);
							
							$finalData = array();
							$finalData['email_subject'] =  $email_subject;
							$finalData['email_body'] =  $email_body;
							$finalData['user_email'] =  $attendee_email;
							$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
							$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
							$finalData['param'] =  $param;
							SendMailController::finalMailSend($finalData);
							$loop ++;
						}
					}
				
					/**
					 * send message to who ticket booked
					 */
					$email_body = str_replace('{user_name}', $user_name, $orgBody);
					$ticketTrHtml = '';

					$loop = 1;
					$newTr = '';
					foreach ($eventDetail->attendees as $attendee) {
							$attendee_email = $attendee->email;
							// $userName = ($attendee->user_id > 0) ? (($attendee->getAttendee->is_nickname_use == 0) ? $attendee->getAttendee->name : $attendee->getAttendee->nick_name) : $attendee->name;
							$userName = $attendee->name;
							$ticketTrHtml .= str_replace('{sr}', $loop, $orgTicketTrHtml);
							$ticketTrHtml = str_replace('{user_name}', $userName, $ticketTrHtml);
							$ticketTrHtml = str_replace('{name}', $userName, $ticketTrHtml);
							$ticketTrHtml = str_replace('{email}', $attendee_email, $ticketTrHtml);
							$ticketTrHtml = str_replace('{ticketno}', $attendee->ticket_no, $ticketTrHtml);
							$ticketTrHtml = str_replace('{src}', $eventQrcodePath.$attendee->qrcode, $ticketTrHtml);
							$loop ++;
					}
					$email_body = str_replace('{ticketInfo}', $ticketTrHtml, $email_body);
					$finalData = array();
					$finalData['email_body'] =  $email_body;
					$finalData['email_subject'] =  $email_subject;
					$finalData['user_email'] =  $eventDetail->user->email;
					$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
					$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
					$finalData['param'] =  $param;
					SendMailController::finalMailSend($finalData);


					/**
					 * send message to event owner
					 */
					$email_body = str_replace('{user_name}', ($eventDetail->event->eventOwner->is_nickname_use == 1) ? $eventDetail->event->eventOwner->nick_name : $eventDetail->event->eventOwner->name , $orgBody);
					$ticketTrHtml = '';

					$loop = 1;
					$newTr = '';
					foreach ($eventDetail->attendees as $attendee) {
							$attendee_email = $attendee->email;
							// $userName = ($attendee->user_id > 0) ? (($attendee->getAttendee->is_nickname_use == 0) ? $attendee->getAttendee->name : $attendee->getAttendee->nick_name) : $attendee->name;
							$userName = $attendee->name;
							$ticketTrHtml .= str_replace('{sr}', $loop, $orgTicketTrHtml);
							$ticketTrHtml = str_replace('{user_name}', $userName, $ticketTrHtml);
							$ticketTrHtml = str_replace('{name}', $userName, $ticketTrHtml);
							$ticketTrHtml = str_replace('{email}', $attendee_email, $ticketTrHtml);
							$ticketTrHtml = str_replace('{ticketno}', $attendee->ticket_no, $ticketTrHtml);
							$ticketTrHtml = str_replace('{src}', $eventQrcodePath.$attendee->qrcode, $ticketTrHtml);
							$loop ++;
					}
					$email_body = str_replace('{ticketInfo}', $ticketTrHtml, $email_body);
					$finalData = array();
					$finalData['email_body'] =  $email_body;
					$finalData['email_subject'] =  $email_subject;
					$finalData['user_email'] =  $eventDetail->event->eventOwner->email;
					$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
					$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
					$finalData['param'] =  $param; 

					SendMailController::finalMailSend($finalData);

					
				break;
			case 5: // Cancel Ticket Booking - Done
					$bookingId = $data['eventBookingId'];
					//Used keyword {bookingId}, {eventName}, {eventDateAndtime}, {eventAddress},
									//Ticket: {sr} {name} {email} {ticketno} {src}
					//Get event name using query
					$eventDetail = EventBooking::select('id','event_id','booking_unique_id','user_id')
					// ->with('event:id,title')
                    ->with(['event' => function($query){
							$query->select('id','slug','title','from','to','user_id','time_of_event','city_id','country_id','venue_name','address')->with(['country:id,name', 'city:id,name','eventOwner:id,name,is_nickname_use,nick_name,email']);
					},'attendees' => function($query){
							$query->select('id','user_id','event_booking_id','name','email','ticket_no','qrcode')->with(['getAttendee:id,name,is_nickname_use,nick_name']);
					}])
					->where(['id' => $bookingId])->first();

					$eventAddress = $eventDetail->event->venue_name.', '.$eventDetail->event->address.', '.$eventDetail->event->city->name.', '.$eventDetail->event->country->name;
					$eventDate = isset($eventDetail->event->from) ? Carbon\Carbon::parse($eventDetail->event->from)->format('d M, Y') : "";
					$eventDate .= isset($eventDetail->event->to) ? ' to '.Carbon\Carbon::parse($eventDetail->event->to)->format('d M, Y') : "";
					$eventTime = $eventDetail->event->time_of_event;
					$eventDateTime = $eventDate.' '.$eventTime;
                    $email_body  = str_replace('{eventName}', $eventDetail->event->title, $email_body);
                    $email_body  = str_replace('{bookingId}', $eventDetail->booking_unique_id, $email_body);
					$email_body  = str_replace('{eventDateAndtime}', $eventDateTime, $email_body);
					$email_body  = str_replace('{eventaddress}', $eventAddress, $email_body);
					$orgBody = $email_body;
					$startTicketPos = SendMailController::getStringPosition($orgBody,'<tr>',1);
					$endTicketTrPos = SendMailController::getStringPosition($orgBody,'</tr>',1) + 5;
					// echo 'startTicketPos:'.$startTicketPos.':endTicketPos:'.$endTicketPos;exit;
					$endTicketPos = ($endTicketTrPos - $startTicketPos);
					$orgTicketTrHtml = substr($orgBody, $startTicketPos, $endTicketPos);
					
					$stringBeforeTr = substr($orgBody, 0, $startTicketPos);
					$stringAfterTr = substr($orgBody, $endTicketTrPos, strlen($orgBody));
					$orgBody = $stringBeforeTr . '{ticketInfo}' . $stringAfterTr;
					$eventQrcodePath = str_replace('{eventSlug}', $eventDetail->event->slug, config('constant.event_ticket_qrcode'));
					$eventQrcodePath = Helper::images($eventQrcodePath);
					
					$loop = 1;
					foreach ($eventDetail->attendees as $attendee) {
						$ticketTrHtml = $orgTicketTrHtml;
						if($attendee->user_id != $user->id){
							$loop = 1;
							$attendee_email = $attendee->email;
							$userName = ($attendee->user_id > 0) ? (($attendee->getAttendee->is_nickname_use == 0) ? $attendee->getAttendee->name : $attendee->getAttendee->nick_name) : $attendee->name;

							$ticketTrHtml = str_replace('{sr}', $loop, $ticketTrHtml);
							$ticketTrHtml = str_replace('{name}', $userName, $ticketTrHtml);
							$ticketTrHtml = str_replace('{email}', $attendee_email, $ticketTrHtml);
							$ticketTrHtml = str_replace('{ticketno}', $attendee->ticket_no, $ticketTrHtml);
							$ticketTrHtml = str_replace('{src}', $eventQrcodePath.$attendee->qrcode, $ticketTrHtml);
							$email_body = str_replace('{ticketInfo}', $ticketTrHtml, $orgBody);
							$email_body = str_replace('{user_name}', $userName, $email_body);
							
							$finalData = array();
							$finalData['email_subject'] =  $email_subject;
							$finalData['email_body'] =  $email_body;
							$finalData['user_email'] =  $attendee_email;
							$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
							$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
							$finalData['param'] =  $param;
							SendMailController::finalMailSend($finalData);
							$loop ++;
						}
					}
				
					/**
					 * send message to who ticket booked
					 */
					$email_body = str_replace('{user_name}', $user_name, $orgBody);
					$ticketTrHtml = '';

					$loop = 1;
					$newTr = '';
					if($eventDetail->attendees){
						foreach ($eventDetail->attendees as $attendee) {
							$attendee_email = $attendee->email;
							$userName = ($attendee->user_id > 0) ? (($attendee->getAttendee->is_nickname_use == 0) ? $attendee->getAttendee->name : $attendee->getAttendee->nick_name) : $attendee->name;
							$ticketTrHtml .= str_replace('{sr}', $loop, $orgTicketTrHtml);
							$ticketTrHtml = str_replace('{user_name}', $userName, $ticketTrHtml);
							$ticketTrHtml = str_replace('{name}', $userName, $ticketTrHtml);
							$ticketTrHtml = str_replace('{email}', $attendee_email, $ticketTrHtml);
							$ticketTrHtml = str_replace('{ticketno}', $attendee->ticket_no, $ticketTrHtml);
							$ticketTrHtml = str_replace('{src}', $eventQrcodePath.$attendee->qrcode, $ticketTrHtml);
							$loop ++;
						}
					}

					$email_body = str_replace('{ticketInfo}', $ticketTrHtml, $email_body);
					$finalData = array();
					$finalData['email_body'] =  $email_body;
					$finalData['email_subject'] =  $email_subject;
					$finalData['user_email'] =  $user_email;
					$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
					$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
					$finalData['param'] =  $param;


					SendMailController::finalMailSend($finalData);
				
					break;
			case 6: // Refund - Done
				// Receiver - who cancelled event or booking,
				if($data['reason'] == 'Cancelling Event'){
					$eventData = Event::select('id','slug','title','from','to','time_of_event','city_id','user_id','country_id','venue_name','address')->with(['country:id,name', 'city:id,name','eventOwner:id,name,is_nickname_use,nick_name','eventBookings' => function($query){
						$query->with('user:id,email,name,is_nickname_use,nick_name');
					}])->where('id',$data['id'])->whereHas('eventBookings' , function($q) use ($data){
						$q->where('booking_unique_id',$data['booking_unique_id']);
					})->first();

					if(isset($eventData)){
						if($eventData->eventOwner->is_nickname_use == 1 ){
							$eventOwner = $eventData->eventOwner->nick_name;
						}else{
							$eventOwner = $eventData->eventOwner->name;
						}

						$eventAddress = $eventData->venue_name.', '.$eventData->address.', '.$eventData->city->name.', '.$eventData->country->name;
						$eventDate = isset($eventData->from) ? Carbon\Carbon::parse($eventData->from)->format('d M, Y') : "";
						$eventDate .= isset($eventData->to) ? ' to '.Carbon\Carbon::parse($eventData->to)->format('d M, Y') : "";
						$eventTime = $eventData->time_of_event;
						$eventDateTime = $eventDate.' '.$eventTime;
						$eventTitle = $eventData->title;

						foreach ($eventData->eventBookings as $bookings) {
							$booker_email = $bookings->user->email;
							$email_body  =  str_replace('{user_name}', ($bookings->user->is_nickname_use == 1) ? $bookings->user->name : $bookings->user->nick_name, $email_body);
							
							$email_body = str_replace('{eventName}', $eventTitle, $email_body);
							$email_body = str_replace('{user_name}', $eventOwner, $email_body);
							$email_body  = str_replace('{eventDateAndtime}', $eventDateTime, $email_body);
							$email_body  = str_replace('{eventaddress}', $eventAddress, $email_body);
							$email_body  = str_replace('{amount}', $data['amount'], $email_body);
							$email_body  = str_replace('{reason}', $data['reason'], $email_body);

							$finalData = array();
							$finalData['email_body'] =  $email_body;
							$finalData['email_subject'] =  $email_subject;
							$finalData['user_email'] =  $booker_email;
							$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
							$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
							$finalData['param'] =  $param;
							SendMailController::finalMailSend($finalData);
						}
					}

				}else if($data['reason'] == 'Cancelling Booking'){
					// $bookingId = EventBooking::where('id',$data['id'])->get();

					$bookingId = EventBooking::select('id','event_id','booking_unique_id','user_id')
					// ->with('event:id,title')
                    ->with(['event' => function($query){
							$query->select('id','slug','title','from','to','time_of_event','city_id','country_id','venue_name','address','user_id')->with(['country:id,name', 'city:id,name','eventOwner:id,name,is_nickname_use,nick_name']);
					},'user'])->where('id',$data['id'])->first();

					if($bookingId->event->eventOwner->is_nickname_use == 1 ){
						$eventOwner = $bookingId->event->eventOwner->nick_name;
					}else{
						$eventOwner = $bookingId->event->eventOwner->name;
					}

					$eventAddress = $bookingId->event->venue_name.', '.$bookingId->event->address.', '.$bookingId->event->city->name.', '.$bookingId->event->country->name;
					$eventDate = isset($bookingId->event->from) ? Carbon\Carbon::parse($bookingId->event->from)->format('d M, Y') : "";
					$eventDate .= isset($bookingId->event->to) ? ' to '.Carbon\Carbon::parse($bookingId->event->to)->format('d M, Y') : "";
					$eventTime = $bookingId->event->time_of_event;
					$eventDateTime = $eventDate.' '.$eventTime;
					$eventTitle = $bookingId->event->title;
					$email_body  =  str_replace('{bookingId}', $bookingId->booking_unique_id, $email_body);

					$email_body = str_replace('{eventName}', $eventTitle, $email_body);
					$email_body = str_replace('{user_name}', $eventOwner, $email_body);
					$email_body  = str_replace('{eventDateAndtime}', $eventDateTime, $email_body);
					$email_body  = str_replace('{eventaddress}', $eventAddress, $email_body);
					$email_body  = str_replace('{amount}', $data['amount'], $email_body);
					$email_body  = str_replace('{reason}', $data['reason'], $email_body);

					$finalData = array();
					$finalData['email_body'] =  $email_body;
					$finalData['email_subject'] =  $email_subject;
					$finalData['user_email'] =  $bookingId->user->email;
					$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
					$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
					$finalData['param'] =  $param;
					SendMailController::finalMailSend($finalData);
				}

				break;
			case 7: // Event Edited - Done
				// Receiver - attendees
				$event_id = $data['event_id'];
				$eventData = Event::select('id','slug','title','from','to','time_of_event','city_id','user_id','country_id','venue_name','address')->with(['country:id,name', 'city:id,name','eventOwner:id,name,is_nickname_use,nick_name','eventBookings' => function($query){
					$query->where('status',1)->with('attendees');
				}])->where('id',$event_id)->first();
				
				if($eventData->eventOwner->is_nickname_use == 1 ){
					$eventOwner = $eventData->eventOwner->nick_name;
				}else{
					$eventOwner = $eventData->eventOwner->name;
				}

				$eventAddress = $eventData->venue_name.', '.$eventData->address.', '.$eventData->city->name.', '.$eventData->country->name;
				$eventDate = isset($eventData->from) ? Carbon\Carbon::parse($eventData->from)->format('d M, Y') : "";
				$eventDate .= isset($eventData->to) ? ' to '.Carbon\Carbon::parse($eventData->to)->format('d M, Y') : "";
				$eventTime = $eventData->time_of_event;
				$eventDateTime = $eventDate.' '.$eventTime;

				$email_body  = str_replace('{eventName}', $eventData->title, $email_body);
				$email_body  = str_replace('{eventDateAndtime}', $eventDateTime, $email_body);
				$email_body  = str_replace('{eventaddress}', $eventAddress, $email_body);
				$email_body  = str_replace('{event_owner}', $eventOwner, $email_body);
				$orgBody = $email_body;

				if(isset($eventData->eventBookings) && !empty($eventData->eventBookings)){
					foreach ($eventData->eventBookings as $bookings) {
						if(isset($bookings->attendees) && !empty($bookings->attendees)){
							foreach ($bookings->attendees as $attendee) {
								$attendee_email = $attendee->email;
								$email_body  =  str_replace('{user_name}',$attendee->name, $orgBody);
								$finalData = array();
								$finalData['email_body'] =  $email_body;
								$finalData['email_subject'] =  $email_subject;
								$finalData['user_email'] =  $attendee_email;
								$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
								$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
								$finalData['param'] =  $param;
								SendMailController::finalMailSend($finalData);
							}
						}
					}
				}
				break;
			case 8: // Cancel event - Done
					// Receiver - attendees
					$event_id = $data['event_id'];
					//Used keyword {bookingId}, {eventName}, {eventDateAndtime}, {eventAddress},
									//Ticket: {sr} {name} {email} {ticketno} {src}
					//Get event name using query
					$eventDetail = Event::select('id','slug','title','from','to','time_of_event','city_id','user_id','country_id','venue_name','address')->with(['country:id,name', 'city:id,name','eventOwner:id,name,is_nickname_use,nick_name,email'])->where('id',$event_id)->first();
					
					$eventAddress = $eventDetail->venue_name.', '.$eventDetail->address.', '.$eventDetail->city->name.', '.$eventDetail->country->name;
					$eventDate = isset($eventDetail->from) ? Carbon\Carbon::parse($eventDetail->from)->format('d M, Y') : "";
					$eventDate .= isset($eventDetail->to) ? ' to '.Carbon\Carbon::parse($eventDetail->to)->format('d M, Y') : "";
					$eventTime = $eventDetail->time_of_event;
					$eventDateTime = $eventDate.' '.$eventTime;
					
					if(Auth::check() && isset(Auth::user()->type)  && Auth::user()->type == 1){
						$eventOwner = Auth::user()->name;
					}else{
						if($eventDetail->eventOwner->is_nickname_use == 1 ){
							$eventOwner = $eventDetail->eventOwner->nick_name;
						}else{
							$eventOwner = $eventDetail->eventOwner->name;
						}
					}
					$email_body = str_replace('{eventName}', $eventDetail->title, $email_body);
					$email_body = str_replace('{event_owner}', $eventOwner, $email_body);
					$email_body  = str_replace('{eventDateAndtime}', $eventDateTime, $email_body);
					$email_body  = str_replace('{eventaddress}', $eventAddress, $email_body);
					$orgEmailBody =  $email_body;

					if(isset($data['attendee']) && !empty($data['attendee'])){
						foreach ($data['attendee'] as $attendee) {
							$attendee_email = $attendee['email'];
							$finalData = array();
							$finalData['email_subject'] =  $email_subject;
							$finalData['email_body'] =  $email_body;
							$finalData['user_email'] =  $attendee_email;
							$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
							$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
							$finalData['param'] =  $param;
							SendMailController::finalMailSend($finalData);
							$email_body = $orgEmailBody;
						}
					}
					
					$finalData = array();
					$finalData['email_subject'] =  $email_subject;
					$finalData['email_body'] =  $email_body;
					$finalData['user_email'] =  $eventDetail->eventOwner->email;
					$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
					$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
					$finalData['param'] =  $param;
					SendMailController::finalMailSend($finalData);
					break;
			case 11: // Contact enquiry - Done
					// Receiver - attendees
					$user_email = $admin_email;
                    $email_body  =  str_replace('{user_name}', $user_name, $email_body);
                    $email_body  = str_replace('{name}', $data['name'], $email_body);
                    $email_body  = str_replace('{email}', $data['email'], $email_body);
                    $email_body  = str_replace('{message}', $data['message'], $email_body);
					break;
			case 12: // Sponsor Event - Done
                    // Receiver - event owner
                    $email_body  =  str_replace('{user_name}', $user_name, $email_body);
                    $email_body  = str_replace('{event_name}', $data['event_name'], $email_body);
                    $email_body  = str_replace('{sponsor_type}', $data['sponsor_type'], $email_body);
                    $email_body  = str_replace('{amount}', $data['amount'], $email_body);
					break;
			case 13: // Event Manager add password
					// Receiver - event manager
                    $email_body  =  str_replace('{user_name}', $user_name, $email_body);
					$email_body  =  str_replace('{email}', $user_email, $email_body);
					$email_body = str_replace('{link}', $data['passwordUrlEventManager'], $email_body);
					break;
					
                    break;
			case 14: // Print Attendee List
					$eventData = Event::where(['user_id' => $data['user_id'],'slug' => $data['event_slug']])->select('id','slug','title')->first();
					$slug = $data['event_slug'];
					$path = public_path('upload/pdf');
					$fileName = $slug.'attendees'.time().'.pdf';
					$full_path = $path.'/'.$fileName;
					$pdf = PDF::loadView('events.attendees-pdf', compact('slug'));
					$pdf->save($full_path);
					$attachment = $full_path;
					// echo $full_path;exit;
					
					break;
			case 15: //Reject Banner Sponsor Accept By admin
				$email_body  =  str_replace('{user_name}', $data['user_name'], $email_body);
				$email_body  = str_replace('{event_name}', $data['event_name'], $email_body);
				$email_body  = str_replace('{amount}', $data['amount'], $email_body);
				
				break;
			case 16: //Reject Banner Sponsor Request By admin
				$email_body  =  str_replace('{user_name}', $data['user_name'], $email_body);
				$email_body  = str_replace('{event_name}', $data['event_name'], $email_body);
				
				break;
			case 17: //When Challenge is edited by admin
					//Receivers - participents of that challange
				$challenge_id = $data['challenge_id'];
				
				$participents = Challenge::where('id',$challenge_id)->with(['challengeEntry' => function($query){
					$query->with(['user']);
				}])->first();

				$email_body  = str_replace('{challenge_name}', $participents->name, $email_body);
				$orgEmailBody =  $email_body;

				$userEmail = array();
				if(isset($participents->challengeEntry)){
					foreach($participents->challengeEntry as $entry){
						
						if(!in_array($entry->user->email,$userEmail)){
							$userEmail[] = $entry->user->email;
							$user_email = $entry->user->email;
							$email_body  =  str_replace('{user_name}', $entry->user->is_nickname_use == 1 ? $entry->user->nick_name : $entry->user->name, $email_body);
							$finalData = array();
							$finalData['email_subject'] =  $email_subject;
							$finalData['email_body'] =  $email_body;
							$finalData['user_email'] =  $user_email;
							$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
							$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
							$finalData['param'] =  $param;
							SendMailController::finalMailSend($finalData);
							$email_body = $orgEmailBody;
						}
					}
				}
				break;
			case 18: //When Challenge is deleted by admin
				//Receivers - participents of that challange

				$email_body  = str_replace('{challenge_name}', $data['challenge_name'], $email_body);
				$email_body  = str_replace('{entry_name}', $data['entry_name'], $email_body);
				$email_body  =  str_replace('{user_name}', $user_name, $email_body);

				break;
			case 19: //When entry is deleted by admin
				//Receivers - who applied for challenge

				$email_body  = str_replace('{entry_name}', $data['entry_name'], $email_body);
				$email_body  = str_replace('{challenge_name}', $data['challenge_name'], $email_body);
				$email_body  =  str_replace('{user_name}', $user_name, $email_body);

				break;
					
			case 20: //When entry is approved or reject by admin
				//Receivers - who applied for entry
				$email_body  = str_replace('{entry_name}', $data['entry_name'], $email_body);
				$email_body  = str_replace('{challenge_name}', $data['challenge_name'], $email_body);
				$email_body  = str_replace('{action}', $data['action'], $email_body);
				$email_body  =  str_replace('{user_name}', $user_name, $email_body);

				break;
			case 21: //Feed is deleted by admin
				//Receivers - Feed owner
				$email_body  = str_replace('{feed_name}', $data['feed_name'], $email_body);
				$email_body  =  str_replace('{user_name}', $user_name, $email_body);
				break;

			case 22: //Feed is edited by admin
				//Receivers - Feed owner
				$email_body  = str_replace('{feed_name}', $data['feed_name'], $email_body);
				$email_body  =  str_replace('{user_name}', $user_name, $email_body);
				break;

			case 23: //Staff Log in credentials
				//Receiver - Staff User
				$email_body  =  str_replace('{user_name}', $data['user_name'], $email_body);
				$email_body  = str_replace('{email}', $data['email'], $email_body);
				$email_body  = str_replace('{password}', $data['password'], $email_body);

				$finalData = array();
				$finalData['email_subject'] =  $email_subject;
				$finalData['email_body'] =  $email_body;
				$finalData['user_email'] =  $data['email'];
				$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
				$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
				$finalData['param'] =  $param;
				SendMailController::finalMailSend($finalData);

				break;
			case 24: //Staff reset password
				//Receiver - Staff User
				$type =  $data['type'];
				if($type == 5){

					$email_body  =  str_replace('{user_name}', $data['user_name'], $email_body);
					$email_body  = str_replace('{email}', $data['email'], $email_body);
					$email_body  = str_replace('{password}', $data['password'], $email_body);
					
					$finalData = array();
					$finalData['email_subject'] =  $email_subject;
					$finalData['email_body'] =  $email_body;
					$finalData['user_email'] =  $data['email'];
					$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
					$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
					$finalData['param'] =  $param;
					SendMailController::finalMailSend($finalData);
				}

				break;
					
			default:
				$email_body = "No content";
				break;
			}

			if(isset($email->id) && $email->id != 4 && $email->id != 6 && $email->id != 7 && $email->id != 8  && $email->id != 17  && $email->id != 23 ){
				$finalData = array();
				$finalData['email_subject'] =  $email_subject;
				$finalData['email_body'] =  $email_body;
				$finalData['user_email'] =  $user_email;
				$finalData['attachment'] =  $attachment;
				$finalData['manager_email'] =  (isset($manager_email))?$manager_email:'';
				$finalData['admin_email'] =  (isset($admin_email))?$admin_email:'';
				$finalData['param'] =  $param;
				SendMailController::finalMailSend($finalData);
			}
			return;			

		} else {	
			return;
		}
		return;
	}

	public static function getStringPosition($string, $needle, $nth){
		$i = $pos = 0;
		do {
			$pos = strpos($string, $needle, $pos + 1);
		} while ($i++ < $nth);
		return $pos;
	}

	public static function finalMailSend($data){
		// set data in content array to pass in view
		$content = [
			'subject' => $data['email_subject'],
			'body' => $data['email_body'],
		];
		if(isset($data['attachment']) && $data['attachment'] != '')
			$content['attachment'] = isset($data['attachment'])?$data['attachment']:'';
		$receiverAddress = $data['user_email'];
		$message = Mail::to($receiverAddress);
		if (isset($data['manager_email']) && !empty($data['manager_email'])) {
			$message->cc($data['manager_email']);
		}
		if(isset($data['admin_email']) && !empty($data['admin_email'])){
			$message->bcc($data['admin_email']);
		}
		$message->send(new DynamicEmail($content,$data['param']));
		return;
	}
}
	