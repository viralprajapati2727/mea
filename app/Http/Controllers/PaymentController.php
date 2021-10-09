<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout;
use App\Models\StripeAccount;
use App\Models\PaymentLogs;
use Auth;

class PaymentController extends Controller
{
    protected $stripe;

    public function __construct() {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    }

    public function index(Request $request)
    {
        return view('payment.index');
    }

    public function create(Request $request)
    {
        // Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        /**
         * Check if account is already created or not
         */

         $isExists = StripeAccount::where("user_id" , Auth::user()->id)->select('id', 'stripe_id', 'details_submitted')->first();
      
        /**
         * Creating account in stripe portal
         */
        $account = $isExists;
        $stripeId = '';
        if(is_null($account)) {
            $account = \Stripe\Account::create([
                'country' => 'US',
                'type' => 'express',
                'email' => Auth::user()->email
            ]);
            $stripeId = $account->id; 
        } else {
            $stripeId = $account->stripe_id;
            $account->id = $account->stripe_id;
        }

        /**
         * Storing stripe account details in DB
         */


        StripeAccount::updateOrCreate(
        [
            "user_id" => Auth::user()->id
        ],
        [
            "stripe_id" =>  $stripeId,
            "account_status" => "incomplete",
            "details_submitted" => $account->details_submitted,
            "stripe_object" => json_encode($account),
        ]);

        $request->session()->put('account', $account);
        $request->session()->put('stripe_acc_id', $stripeId);
        
        $origin = $request->headers->get('referer') ?? $request->headers->get('origin');
      
        $account_link_url =  self::generate_account_link($stripeId, $origin);

        return redirect($account_link_url);
    }

    public function success(Request $request, $success)
    {
        // dd($request->session()->get('payment_session')->toArray() );

        // $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));          
        $account =  $this->stripe->accounts->retrieve(
            $request->session()->get('stripe_acc_id'),
            []
        );
        StripeAccount::where("stripe_id" ,$account->id)
            ->where("user_id" ,Auth::user()->id)
            ->update([
                "account_status" => "completed",
                "details_submitted" => json_encode($account->details_submitted),
                "stripe_object" => json_encode($account),
                "bank_name" => $account->external_accounts->data[0]['bank_name'] ?? "",
                "account_holder_name" => $account->external_accounts->data[0]['account_holder_name'] ?? "",
                "routing_number" => $account->external_accounts->data[0]['routing_number'] ?? "",
                "last4" => $account->external_accounts->data[0]['last4'] ?? ""
            ]);

        // $account_retrieve = $stripe->checkout->sessions->retrieve(
        //     $request->session()->get('payment_session')->id,
        //     []
        // );

        // $account_retrieve = $stripe->accounts->all();

        return redirect('bank-account');

    }

    public function payment(Request $request)
    {

        $referer = $request->headers->get('referer');
        // Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $getAccId = StripeAccount::where('user_id', $request->user_id)->select('id', "stripe_id")->first();
 
        // dd($getAccId->stripe_id);
        $newSession = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
                'line_items' => [[
                    'name' => $request->title ?? "MEA Fund Raise Request" ,
                    'amount' => $request->amount * 100,
                    'currency' => 'usd',
                    'quantity' => 1,
                ]],
                'payment_intent_data' => [
                    'application_fee_amount' => $request->amount * 100 / 10,
                    'transfer_data' => [
                    'destination' => $getAccId->stripe_id,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => "{$referer}/success",
                'cancel_url' => "{$referer}/cancel",
          ]);
          
          $paymentLog = PaymentLogs::Create([
                "user_id" => Auth::user()->id,
                "stripe_acc_id" => $getAccId->id, // stripe account table id
                "raise_fund_id" => $request->raise_fund_id,
                'amount' => $request->amount,
                "payment_status" => $newSession->payment_status,
                "payment_object" => json_encode($newSession),
            ]);

          $request->session()->put('payment_session', $newSession);
          $request->session()->put('paymentLog', $paymentLog);

          return redirect($newSession->url);
    }

    public static function generate_account_link(string $account_id, string $origin) {
        /**
         * Generating redirect link for store bank details in STRIPE
         */

        $account_link =  \Stripe\AccountLink::create([
          'type' => 'account_onboarding',
          'account' => $account_id,
          'refresh_url' => "{$origin}/refresh",
          'return_url' => "{$origin}/success"
        ]);
      
        return $account_link->url;
    }

      public function bankAccount()
      {
        $stripeDetails =  StripeAccount::where("user_id", Auth::user()->id)->first();

        return view('bank-account', compact('stripeDetails'));
      }
}
