<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        return view('payment.index');
    }

    public function create(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $account = \Stripe\Account::create([
            'country' => 'US',
            'type' => 'express',
        ]);

        $request->session()->put('account', $account);
        $request->session()->put('stripe_acc_id', $account->id);
        
        $origin = $request->headers->get('origin');
        $account_link_url = self::generate_account_link($account->id, $origin);
        
    //   return $this->withJson(array('url' => $account_link_url));
        return redirect($account_link_url);
    }

    public function success(Request $request)
    {
        // dd($request->session()->get('payment_session')->toArray() );

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $account_retrieve = $stripe->checkout->sessions->retrieve(
            $request->session()->get('payment_session')->id,
            []
        );

        // $account_retrieve = $stripe->accounts->all();

        // dd($account_retrieve->toArray());

        return view('payment.success');
    }

    public function payment(Request $request)
    {
        $origin = $request->headers->get('origin');
        // Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        // cs_test_a1oaBsxAk5i1U7zSOg6UJrS91UJYOmalN2OMgxvgGhQhQidgAkuLtOuLet

        // $retrieve  =  $stripe->checkout->sessions->retrieve(
        //     'cs_test_a1oaBsxAk5i1U7zSOg6UJrS91UJYOmalN2OMgxvgGhQhQidgAkuLtOuLet',
        //     []
        //   );
 
        $newSession = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card', 'apple_pay', 'google_pay'],
                'line_items' => [[
                    'name' => 'Nikunj payment',
                    'amount' => $request->amount,
                    'currency' => 'usd',
                    'quantity' => 1,
                ]],
                'payment_intent_data' => [
                    'application_fee_amount' => 123,
                    'transfer_data' => [
                    'destination' => $request->accId,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => "{$origin}/success",
                'cancel_url' => "{$origin}",
          ]);

          $request->session()->put('payment_session', $newSession);

          return redirect($newSession->url);
    }

    public static function generate_account_link(string $account_id, string $origin) {
        $account_link = \Stripe\AccountLink::create([
          'type' => 'account_onboarding',
          'account' => $account_id,
          'refresh_url' => "{$origin}/onboard-user/refresh",
          'return_url' => "{$origin}/success"
        ]);
      
        return $account_link->url;
      }
}
