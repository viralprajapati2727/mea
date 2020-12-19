<?php

namespace App\Http\Controllers;
use App\Helpers\Helper;
use App\Mail\DynamicEmail;
use App\Models\EmailTemplate;
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
		$user = User::select('*', DB::raw("name"))
            ->where('id', $data['user_id'])
            ->where('deleted_at', null)
			->first();

		$manager_email = array();
		$admin_email = config('constant.admin_email');
		if ($email && $user) {
			$email_subject = $email->emat_email_subject;
			$email_body = $email->emat_email_message;

			$user_email = $user->email;
			$user_name = ucwords($user->name);

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
			case 4: // Contact enquiry - Done
					$user_email = $admin_email;
                    $email_body  =  str_replace('{user_name}', $user_name, $email_body);
                    $email_body  = str_replace('{name}', $data['name'], $email_body);
                    $email_body  = str_replace('{email}', $data['email'], $email_body);
                    $email_body  = str_replace('{subject}', $data['subject'], $email_body);
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
	