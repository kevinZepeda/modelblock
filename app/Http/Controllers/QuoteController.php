<?php namespace App\Http\Controllers;

use App\Models\ktBoard;
use Illuminate\Http\Request;
use Auth;
use App\Models\ktUser;
use App\Models\User;
use App\Models\ktProject;
use App\Models\ktCustomer;
use App\Models\ktSettings;
use App\Models\ktQuote;
use Route;
use Config;

class QuoteController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Questionarrie Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "Questionnaries" for users that
    | are authenticated.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    /**
     * Show the qa that are pending for a review..
     *
     * @return Response
     */
    public function pendingreview(Request $request)
    {

        $data = ktUser::getUserData();
        $account = ktUser::getAccountData();
        $list = ktSettings::getSubmitedQuestionnaries();

        return view('quote.pendigreview', [
            'data'  => $data,
            'account' => $account,
            'questionnaries' => @$list
        ]);

    }

    /**
     * Renders and processes the QA request from a customer.
     *
     * @return Response
     */
    public function request(Request $request)
    {
        $quote = ktSettings::getPublicQuestionnarie(Route::input('quote_id'));
        $messages = '';
        if(is_object(@$quote[0])){
            if(!in_array($quote[0]->status , ['SUBMITTED','REVIEWED'])){
                if($quote[0]->public != 0 || Auth::check()){
                    $messages = '';
                    $fields = json_decode(@$quote[0]->template);
                    if($request->isMethod('post')){
                        $rules = ktQuote::fetchQuoteValidationRules($fields);
                        if(Config::get('app.reCaptcha.enabled')){
                            $tmp_request_data = $request->input();
                            $tmp_request_data['g-recaptcha-response'] = ktSettings::getCaptcha($request->input('g-recaptcha-response'));
                            $request->replace($tmp_request_data);
                        }
                        $messages = ktQuote::triggerEvent($request, $rules, $quote[0]);
                    }
                    if($messages === true){
                        return view('quote.thankyou', [
                            'questionarie' => @$quote[0]
                        ]);
                    }else {
                        return view('quote.request', [
                            'questionarie' => @$quote[0],
                            'validation_messages' => $messages,
                            'fields' => $fields,
                            'data' => @$_POST
                        ]);
                    }
                }
            }else{
                return view('quote.thankyou', [
                    'questionarie' => @$quote[0]
                ]);
            }
        }

        $fields = json_decode(@$quote[0]->template);
        return view('quote.notfound', []);

    }

    /**
     * Shows the QA for review to the authenticated user.
     *
     * @return Response
     */
    public function review(Request $request)
    {

        $messages = array();
        if($request->isMethod('post')){
            $messages = ktQuote::triggerEvent($request);
        }

        $quote = ktQuote::reviewQuestionnarie(Route::input('quote_id'));
        if(is_object($quote) && Auth::check()){
            $customers = ktCustomer::getCustomersData();
            return view('quote.review', [
                'questionarie' => $quote,
                'messages' => $messages,
                'customers' => $customers,
                'qa' => json_decode($quote->template)
            ]);
        }else{
            return view('quote.notfound', []);
        }

    }

}
