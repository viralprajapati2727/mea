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
				// $attachment = Helper::assets('images/MEA-User-Guide.pdf');
				// $attachment = base_path().'/public/images/MEA-User-Guide.pdf';
				$attachment = public_path('images/MEA-User-Guide.pdf');
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
			case 7: // Drop Idea - Done
				$user_email = $admin_email;
				$email_body  = str_replace('{user_name}', $user_name, $email_body);
				$email_body  = str_replace('{first_name}', $data['first_name'], $email_body);
				$email_body  = str_replace('{last_name}', $data['last_name'], $email_body);
				$email_body  = str_replace('{company_name}', $data['company_name'], $email_body);
				$email_body  = str_replace('{city}', $data['city'], $email_body);
				// $email_body  = str_replace('{century}', $data['century'], $email_body);
				$email_body  = str_replace('{phone}', $data['phone'], $email_body);
				$email_body  = str_replace('{email}', $data['email'], $email_body);
				$email_body  = str_replace('{age}', $data['age'], $email_body);
				$email_body  = str_replace('{gender}', $data['gender'], $email_body);
				$email_body  = str_replace('{occupation}', $data['occupation'], $email_body);
				$email_body  = str_replace('{description}', $data['description'], $email_body);
				break;
			case 9:
				$email_subject = 'Subscription Email';
				$email_body = $data['email_body'];
				$user_email = $data['email'];
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
	