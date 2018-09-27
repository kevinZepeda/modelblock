<?php namespace App\Http\Controllers;

use App\Models\ktBoard;
use Illuminate\Http\Request;
use Auth;
use App\Models\ktUser;
use App\Models\User;
use App\Models\ktFinance;
use App\Models\ktCustomer;
use Route;
use Illuminate\Http\Response;
use App\Modules\invoicr\invoicr;

class FinanceController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Finance Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "Office/Finance" view for users that
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
     * Show the main finance list with the issues invoices to the user.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data = ktUser::getUserData();
        $invoices = ktFinance::getInvoices(['INVOICE']);
        $account = ktUser::getAccountData();

        return view('finance.finance', [
            'block' => 'invoices',
            'customers' => array(),
            'account' => @$account,
            'validation_messages'   => @$messages,
            'invoices' => @$invoices,
            'data'  => @$data,
        ]);

    }

    /**
     * Show the archived invoices list to the user.
     *
     * @return Response
     */
    public function archive(Request $request)
    {
        $data = ktUser::getUserData();
        $invoices = ktFinance::getInvoices(['INVOICE'], true);
        $account = ktUser::getAccountData();

        return view('finance.finance', [
            'block' => 'archive',
            'customers' => array(),
            'account' => @$account,
            'validation_messages'   => @$messages,
            'invoices' => @$invoices,
            'data'  => @$data,
        ]);

    }

    /**
     * Show the draft invoices list to the user.
     *
     * @return Response
     */
    public function drafts(Request $request)
    {
        $data = ktUser::getUserData();
        $drafts = ktFinance::getInvoices(['DRAFT']);
        $account = ktUser::getAccountData();

        return view('finance.finance', [
            'block' => 'drafts',
            'customers' => array(),
            'account' => @$account,
            'validation_messages'   => @$messages,
            'drafts' => @$drafts,
            'data'  => @$data,
        ]);
    }

    /**
     * Show the expense list to the user.
     *
     * @return Response
     */
    public function expenses(Request $request)
    {
        $data = ktUser::getUserData();
        $drafts = ktFinance::getInvoices(['EXPENSE']);
        $account = ktUser::getAccountData();

        return view('finance.finance', [
            'block' => 'expenses',
            'customers' => array(),
            'account' => @$account,
            'validation_messages'   => @$messages,
            'drafts' => @$drafts,
            'data'  => @$data,
        ]);
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function subscriptions(Request $request)
    {
        $data = ktUser::getUserData();
        $subscriptions = ktFinance::getInvoices(['RECURRING']);
        $account = ktUser::getAccountData();

        return view('finance.finance', [
            'block' => 'subscriptions',
            'customers' => array(),
            'account' => @$account,
            'validation_messages'   => @$messages,
            'subscriptions' => @$subscriptions,
            'data'  => @$data,
        ]);
    }

    /**
     * Show the quotes list to the user.
     *
     * @return Response
     */
    public function quotes(Request $request)
    {
        $data = ktUser::getUserData();
        $quotes = ktFinance::getInvoices(['QUOTE']);
        $account = ktUser::getAccountData();

        return view('finance.finance', [
            'block' => 'quotes',
            'customers' => array(),
            'account' => @$account,
            'validation_messages'   => @$messages,
            'quotes' => @$quotes,
            'data'  => @$data,
        ]);
    }

    /**
     * Show any type of an invoice to the user
     *
     * @return Response
     */
    public function invoice(Request $request)
    {
        $items['ITEM'] = array();
        $items['TAX'] = array();
        $items['PRE-TAX'] = array();
        $invoice_id = false;
        if ($request->isMethod('post') && $request->input('event') == 'invoice_change') {
            $result = ktFinance::triggerEvent($request);
            $invoice = ktFinance::getInvoice(Route::input('invoice_id'));
            if (is_numeric(@$invoice->id)) {
                $invoice_id = $invoice->id;
                $items_object = ktFinance::getInvoiceItems(Route::input('invoice_id'));
                foreach ($items_object as $item) {
                    $items[$item->item_type][] = [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                        'label_1' => $item->label_1,
                        'label_2' => $item->label_2,
                        'factor' => $item->factor,
                        'item_cost' => $item->item_cost,
                    ];
                }
            } else {
                //@TODO: If something is wrong we need to re-route the controller...
            }

        } else {
            if ($request->isMethod('post') || $request->input('event') == 'new_draft') {
                $result = ktFinance::triggerEvent($request);
                $messages = @$result['messages'];
                $invoice_id = $result['invoice_id'];
            } else {
                $invoice = ktFinance::getInvoice(Route::input('invoice_id'));
                if (is_numeric(@$invoice->id)) {
                    $invoice_id = $invoice->id;
                    $items_object = ktFinance::getInvoiceItems(Route::input('invoice_id'));
                    foreach ($items_object as $item) {
                        $items[$item->item_type][] = [
                            'id' => $item->id,
                            'quantity' => $item->quantity,
                            'label_1' => $item->label_1,
                            'label_2' => $item->label_2,
                            'factor' => $item->factor,
                            'item_cost' => $item->item_cost,
                        ];
                    }
                } else {
                    //@TODO: If something is wrong we need to re-route the controller...
                }
            }
        }

        if($request->input('event') == 'new_draft' && $request->input('type') == 'QUOTE' && is_numeric($invoice_id)){
            return redirect('/office/finance/quote/'.$invoice_id);
        }else if($request->input('event') == 'new_draft' && $request->input('type') == 'RECURRING' && is_numeric($invoice_id)){
            return redirect('/office/finance/subscription/'.$invoice_id);            
        }else if($request->input('event') == 'new_draft' && is_numeric($invoice_id)){
            return redirect('/office/finance/invoice/'.$invoice_id);
        }

        if(!is_numeric($invoice_id)){
            return redirect('/office/finance');
        }

        $data = ktUser::getUserData();
        $customers = ktCustomer::getCustomersData();
        $account = ktUser::getAccountData();
        $invoice_customer = ktCustomer::getCustomerData(@$invoice->customer_id);

        if($invoice->type == 'INVOICE'){
            $invoices = ktFinance::getInvoices(['INVOICE']);
        }else if($invoice->type == 'QUOTE'){
            $invoices = ktFinance::getInvoices(['QUOTE']);
        }else if($invoice->type == 'RECURRING'){
            $invoices = ktFinance::getInvoices(['RECURRING']);
        }

        if($invoice->currency != $account->currency){
            $account->currency = $invoice->currency;
        }
        
        return view('finance.finance', [
            'block' => 'invoice',
            'account' => @$account,
            'customers' => @$customers,
            'customer' => @$invoice_customer,
            'invoices' => @$invoices,
            'validation_messages'   => @$messages,
            'invoice_id' => @$invoice_id,
            'invoice' => @$invoice,
            'items' => @$items,
            'data'  => $data
        ]);
    }

    /**
     * Show any type of the invoice to the user
     *
     * @return Response
     */
    public function expense(Request $request)
    {
        $items['ITEM'] = array();
        $items['TAX'] = array();
        $items['PRE-TAX'] = array();
        $invoice_id = false;
        if ($request->isMethod('post') && $request->input('event') == 'invoice_change') {
            $result = ktFinance::triggerEvent($request);
            $invoice = ktFinance::getInvoice(Route::input('invoice_id'));
            if (is_numeric(@$invoice->id)) {
                $invoice_id = $invoice->id;
                $items_object = ktFinance::getInvoiceItems(Route::input('invoice_id'));
                foreach ($items_object as $item) {
                    $items[$item->item_type][] = [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                        'label_1' => $item->label_1,
                        'label_2' => $item->label_2,
                        'factor' => $item->factor,
                        'item_cost' => $item->item_cost,
                    ];
                }
            } else {
                //@TODO: If something is wrong we need to re-route the controller...
            }

        } else {
            if ($request->isMethod('post') || $request->input('event') == 'new_draft') {
                $result = ktFinance::triggerEvent($request);
                $messages = @$result['messages'];
                $invoice_id = $result['invoice_id'];
            } else {
                $invoice = ktFinance::getInvoice(Route::input('invoice_id'));
                if (is_numeric(@$invoice->id)) {
                    $invoice_id = $invoice->id;
                    $items_object = ktFinance::getInvoiceItems(Route::input('invoice_id'));
                    foreach ($items_object as $item) {
                        $items[$item->item_type][] = [
                            'id' => $item->id,
                            'quantity' => $item->quantity,
                            'label_1' => $item->label_1,
                            'label_2' => $item->label_2,
                            'factor' => $item->factor,
                            'item_cost' => $item->item_cost,
                        ];
                    }
                } else {
                    //@TODO: If something is wrong we need to re-route the controller...
                }
            }
        }

        if($request->input('event') == 'new_draft' && $request->input('type') == 'QUOTE' && is_numeric($invoice_id)){
            return redirect('/office/finance/quote/'.$invoice_id);
        }else if($request->input('event') == 'new_draft' && $request->input('type') == 'RECURRING' && is_numeric($invoice_id)){
            return redirect('/office/finance/subscription/'.$invoice_id);     
        }else if($request->input('event') == 'new_draft' && $request->input('type') == 'EXPENSE' && is_numeric($invoice_id)){
            return redirect('/office/finance/expense/'.$invoice_id);                     
        }else if($request->input('event') == 'new_draft' && is_numeric($invoice_id)){
            return redirect('/office/finance/invoice/'.$invoice_id);
        }
        
        if(!is_numeric($invoice_id)){
            return redirect('/office/finance');
        }

        $data = ktUser::getUserData();
        $customers = ktCustomer::getCustomersData();
        $account = ktUser::getAccountData();
        $invoice_customer = ktCustomer::getCustomerData(@$invoice->customer_id);
        $expense_categories = ktUser::getExpenseCategories();

        if($invoice->type == 'INVOICE'){
            $invoices = ktFinance::getInvoices(['INVOICE']);
        }else if($invoice->type == 'QUOTE'){
            $invoices = ktFinance::getInvoices(['QUOTE']);
        }else if($invoice->type == 'RECURRING'){
            $invoices = ktFinance::getInvoices(['RECURRING']);
        }

        if($invoice->currency != $account->currency){
            $account->currency = $invoice->currency;
        }
        
        return view('finance.finance', [
            'block' => 'expense',
            'account' => @$account,
            'customers' => @$customers,
            'customer' => @$invoice_customer,
            'invoices' => @$invoices,
            'category_expenses' => @$expense_categories,
            'validation_messages'   => @$messages,
            'invoice_id' => @$invoice_id,
            'invoice' => @$invoice,
            'items' => @$items,
            'data'  => $data
        ]);
    }

}
