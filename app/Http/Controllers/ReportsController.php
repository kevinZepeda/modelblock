<?php namespace App\Http\Controllers;

use App\Models\ktBoard;
use Illuminate\Http\Request;
use Auth;
use App\Models\ktUser;
use App\Models\User;
use App\Models\ktFinance;
use App\Models\ktCustomer;
use App\Models\ktReports;
use Route;
use Illuminate\Http\Response;
use App\Modules\invoicr\invoicr;

class ReportsController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Reports Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "Reports" view for users that
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
        $this->middleware('auth');
    }

    /**
     * Shows the Account Finance Report to the user.
     *
     * @return Response
     */
    public function invoices(Request $request)
    {
        $account = ktUser::getAccountData();

        if($request->isMethod('post')){
            $reportList = ktReports::triggerEvent($request);
            $graph['profit'] = ktReports::getFinanceGraph(date("01/01/Y"), date("d/m/Y"), 'INVOICE', $account->currency);
            $graph['estimates'] = ktReports::getFinanceGraph(date("01/01/Y"), date("d/m/Y"), 'QUOTE', $account->currency);
            $graph['expenses'] = ktReports::getFinanceGraph(date("01/01/Y"), date("d/m/Y"), 'EXPENSE', $account->currency);
        }else{
            $reportList['report'] = ktReports::getFinanceReport(date("01/01/Y"), date("d/m/Y"), 'INVOICE');
            $graph['profit'] = ktReports::getFinanceGraph(date("01/01/Y"), date("d/m/Y"), 'INVOICE', $account->currency);
            $graph['estimates'] = ktReports::getFinanceGraph(date("01/01/Y"), date("d/m/Y"), 'QUOTE', $account->currency);
            $graph['expenses'] = ktReports::getFinanceGraph(date("01/01/Y"), date("d/m/Y"), 'EXPENSE', $account->currency);
        }

        if(isset($reportList['status']) && $reportList['status'] != 'ok'){
            $messages = @$reportList['messages'];
            $reportList = array();
        }
        $data = ktUser::getUserData();
        $currency = ktReports::getMyInvoiceCurrencies();

        return view('reports.report', [
            'validation_messages' => @$messages,
            'report' => @$reportList['report'],
            'currency' => @$currency,
            'graph' => @$graph,
            'data' => $data,
            'default_currency' => @$account->currency
        ]);
    }

}
