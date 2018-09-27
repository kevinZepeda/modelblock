<?php namespace App\Models;

use Carbon\Carbon;
use DB;
use League\Flysystem\Exception;
use Validator;
use Hash;
use Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\ktUser;
use Mail;

require_once(base_path() . '/app/Modules/invoicr/invoicr.php');

class ktFinance {

    public static  $rules = [
        'new_draft' => [
            'type' => 'in:DRAFT,QUOTE,RECURRING,EXPENSE'
        ],
        'new_item' => [
            'invoice_id'=> 'required|numeric'
        ],
        'new_pre_tax' => [
            'invoice_id'=> 'required|numeric'
        ],
        'new_tax' => [
            'invoice_id'=> 'required|numeric'
        ],
        'delete_item' => [
            'item_id'=> 'required|numeric'
        ],
        'invoice_change' => [
            'invoice_id'    => 'required|numeric',
            'legal_notes'   => 'min:1',
            'notes'         => 'min:1',
            'customer_id'   => 'numeric',
            'invoice_date'  => 'date_format:d/m/Y',
            'currency'      => 'min:3|max:3',            
            'due_date'      => 'date_format:d/m/Y',
            'r_next_date'   => 'date_format:d/m/Y',
            'r_end_date'    => 'date_format:d/m/Y',
            'r_due_period'  => 'in:DAILY,WEEKLY,MONTHLY,YEARLY',
            'r_due_days'    => 'numeric',
            'category_id'   => 'numeric'
        ],
        'get_customer_address' => [
            'customer_id'   => 'required|numeric'
        ],
        'download_invoice' =>[
            'invoice_id'    => 'required|numeric',
        ],
        'client_download_invoice' =>[
            'invoice_id'    => 'required|numeric',
        ],
        'mail_invoice' =>[
            'invoice_id'    => 'required|numeric',
        ],
        'delete_invoice' =>[
            'invoice_id'    => 'required|numeric',
        ],
        'subscription_ready' => [
            'invoice_id'    => 'required|numeric',
            'r_ready'       => 'in:1,0'
        ],
        'issue_invoice' =>[
            'invoice_id'    => 'required|numeric',
        ],
        'archive_invoice' => [
            'invoice_id'    => 'required|numeric',
            'archived'      => 'required|in:0,1',
        ],
        'invoice_state_update' => [
            'invoice_id'    => 'required|numeric',
            'status'        => "required|in:COLLECT,STORNO,CREDIT,DEBT,FRAUD,PAID,UNPAID"
        ],
        'clone_invoice' => [
            'invoice_id' => 'required|numeric'
        ],
        'finance_search' => [
            'q' => 'required|min:1'
        ]
    ];

    public static $rules_messages = [

    ];

    public static function triggerEvent($request)
    {
        if($request->has('event')){
            if(isset(self::$rules[$request->input('event')])){
                $event = $request->input('event');
                $rules = self::$rules[$event];
                $messages = (isset(self::$rules_messages[$event]))
                    ? self::$rules_messages[$event]
                    :array();
            }else{
                return array(trans('application.intruder'));
            }
        }

        $data = array();
        foreach($rules as $field => $rule){
            $data[$field] = trim($request->input($field));
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails() === false){
            switch($event) {
                case 'new_draft':
                    $date = date("Y-m-d");
                    $result = DB::transaction(function () use ($data, $date) {
                        try {

                            $data['type'] = (empty($data['type']))?'DRAFT':$data['type'];
                            $account = ktUser::getAccountData();

                            if(in_array($data['type'], array('RECURRING'))){
                                DB::table('invoices')->insert([
                                    'account_id'    => ktUser::getAccountId(),
                                    'type'          => $data['type'],
                                    'currency'      => $account->currency,
                                    'notes'         => $account->invoice_note,
                                    'legal_notes'   => $account->invoice_legal_note,
                                    'language'      => $account->invoice_language

                                ]);

                            }else if ($data['type'] == 'RECURRING'){
                                 DB::table('invoices')->insert([
                                    'account_id'    => ktUser::getAccountId(),
                                    'invoice_date'  => $date,
                                    'due_date'      => $date,
                                    'type'          => $data['type'],
                                    'currency'      => $account->currency,
                                    'language'      => $account->invoice_language
                                ]);                               
                            }else{
                                 DB::table('invoices')->insert([
                                    'account_id'    => ktUser::getAccountId(),
                                    'invoice_date'  => $date,
                                    'due_date'      => $date,
                                    'type'          => $data['type'],
                                    'currency'      => $account->currency,
                                    'notes'         => $account->invoice_note,
                                    'legal_notes'   => $account->invoice_legal_note,
                                    'language'      => $account->invoice_language
                                ]);   
                            }

                            $invoice_id = DB::getPdo()->lastInsertId();

                            return $invoice_id;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    if(is_numeric($result)) {
                        return [
                            'invoice_id' => $result,
                            'date' => $date
                        ];
                    }else{
                        return [
                            'messages' => $result
                        ];
                    }
                    break;
                case 'new_pre_tax':
                case 'new_tax':
                case 'new_item':

                    $result = DB::transaction(function () use ($data, $event) {
                        try {
                            $types = array(
                              'new_pre_tax' => 'PRE-TAX',
                              'new_tax'     => 'TAX',
                              'new_item'    => 'ITEM',
                            );

                            DB::table('invoice_items')->insert([
                                'account_id'    => ktUser::getAccountId(),
                                'invoice_id'    => $data['invoice_id'],
                                'item_type'     => $types[$event]
                            ]);

                            $item_id = DB::getPdo()->lastInsertId();
                            return $item_id;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    if(is_numeric($result)) {
                        return [
                            'status' => 'ok',
                            'item_id' => $result,
                        ];
                    }else{
                        return [
                            'messages' => $result
                        ];
                    }

                    break;
                case 'delete_item':

                    $result = DB::transaction(function () use ($data) {
                        try {
                            DB::table('invoice_items')
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->where('id', '=', $data['item_id'])
                                ->delete();

                            return $data['item_id'];
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    if(is_numeric($result)) {
                        return [
                            'status' => 'ok',
                            'id' => $result,
                        ];
                    }else{
                        return [
                            'messages' => $result
                        ];
                    }

                    break;
                case 'invoice_change':
                        $inputs = $request->input();
                        $items = array();
                        $ignore = array();
                        $accounts_id = ktUser::getAccountId();

                        foreach($inputs as $key => $input) {
                            if (preg_match('/^(iq|is|id|td|tf|pd|pf|i)((\d)*)$/', $key, $match) === 1){
                                if(!in_array($match[2], $ignore)) {
                                    $ignore[] = $match[2];
                                    if(strpos($match[1],'i') === 0) {
                                        $items[] = [
                                            'type'  => 'item',
                                            'where' => [
                                                'id'            => $match[2],
                                                'account_id'    => $accounts_id,
                                                'invoice_id'    => $data['invoice_id'],
                                            ],
                                            'update' => [
                                                'quantity'      => @$inputs['iq' . $match[2]],
                                                'label_1'       => @$inputs['is' . $match[2]],
                                                'label_2'       => @$inputs['id' . $match[2]],
                                                'item_cost'     => number_format(@$inputs['i' . $match[2]], 2, ".", "")
                                            ]
                                        ];
                                    }else if(strpos($match[1],'t') === 0){
                                        $items[] = [
                                            'type'  => 'tax',
                                            'where' => [
                                                'id'            => $match[2],
                                                'account_id'   => $accounts_id,
                                                'invoice_id'    => $data['invoice_id'],
                                            ],
                                            'update' => [
                                                'label_1'       => @$inputs['td' . $match[2]],
                                                'factor'        => @$inputs['tf' . $match[2]]
                                            ]
                                        ];
                                    }else if(strpos($match[1],'p') === 0){
                                        $items[] = [
                                            'type'  => 'pretax',
                                            'where' => [
                                                'id'            => $match[2],
                                                'account_id'    => $accounts_id,
                                                'invoice_id'    => $data['invoice_id'],
                                            ],
                                            'update' => [
                                                'label_1'       => @$inputs['pd' . $match[2]],
                                                'factor'        => @$inputs['pf' . $match[2]]
                                            ]
                                        ];
                                    }
                                }
                            }
                        }
                        $items_sum = 0;
                        foreach($items as $item){
                            $update_result = DB::table('invoice_items')
                                ->where($item['where'])
                                ->update($item['update']);
                            if($item['type'] == 'item'){
                                 $items_sum = number_format($items_sum + $item['update']['item_cost'] * $item['update']['quantity'], 2, ".", "");
                            }
                        }

                        $pretax_sum = 0;
                        foreach($items as $item){
                            if($item['type'] == 'pretax'){    
                                $tmp_pretax_value = 0;
                                if(strpos($item['update']['factor'], '%') !== false){
                                    list($factor,) = explode('%', $item['update']['factor'], 2);
                                    $tmp_pretax_value = $items_sum / 100 * $factor;
                                }else{
                                    $tmp_pretax_value = $item['update']['factor'];
                                }
                                $pretax_sum = number_format($pretax_sum + $tmp_pretax_value, 2, ".", "");
                            }
                        }

                        $tax_sum = 0;
                        foreach($items as $item){
                            if($item['type'] == 'tax'){    
                                $tmp_tax_value = 0;
                                if(strpos($item['update']['factor'], '%') !== false){
                                    list($factor,) = explode('%', $item['update']['factor'], 2);
                                    $tmp_tax_value = ($items_sum - $pretax_sum) / 100 * $factor;
                                }else{
                                    $tmp_tax_value = $item['update']['factor'];
                                }
                                $tax_sum = number_format($tax_sum + $tmp_tax_value, 2, ".", "");
                            }
                        }

                        if(isset($data['invoice_date']) && !empty($data['invoice_date'])){
                            $invoice_date_tmp = date_parse_from_format('d/m/Y', $data['invoice_date']);
                            $invoice_date = $invoice_date_tmp['year'] . '-' . $invoice_date_tmp['month'] . '-' . $invoice_date_tmp['day'];
                        }else{
                            $invoice_date = NULL;
                        }

                        if(isset($data['due_date']) && !empty($data['due_date'])){
                            $due_date_tmp = date_parse_from_format('d/m/Y', $data['due_date']);
                            $due_date = $due_date_tmp['year'] . '-' . $due_date_tmp['month'] . '-' . $due_date_tmp['day'];
                        }else{
                            $due_date = NULL;
                        }

                        if(isset($data['r_next_date']) && !empty($data['r_next_date'])){
                            $r_next_date_tmp = date_parse_from_format('d/m/Y', $data['r_next_date']);
                            $r_next_date = $r_next_date_tmp['year'] . '-' . $r_next_date_tmp['month'] . '-' . $r_next_date_tmp['day'];
                        }else{
                            $r_next_date = NULL;
                        }

                        if(isset($data['r_end_date']) && !empty($data['r_end_date'])){
                            $r_end_date_tmp = date_parse_from_format('d/m/Y', $data['r_end_date']);
                            $r_end_date = $r_end_date_tmp['year'] . '-' . $r_end_date_tmp['month'] . '-' . $r_end_date_tmp['day'];
                        }else{
                            $r_end_date = NULL;
                        }

                        $update_result = DB::table('invoices')
                            ->where([
                                'id' => $data['invoice_id']
                            ])
                            ->update([
                                'customer_id'       => @$data['customer_id'],
                                'notes'             => @$data['notes'],
                                'legal_notes'       => @$data['legal_notes'],
                                'currency'          => @$data['currency'],                                
                                'invoice_date'      => $invoice_date,
                                'due_date'          => $due_date,
                                'r_next_date'       => $r_next_date,
                                'r_end_date'        => $r_end_date,
                                'r_due_period'      => @$data['r_due_period'],
                                'r_due_days'        => @$data['r_due_days'],
                                'invoice_subtotals' => $items_sum,
                                'invoice_pre_tax'   => $pretax_sum,
                                'invoice_tax'       => $tax_sum,
                                'category_id'       => @$data['category_id']
                            ]);

                    break;
                case 'get_customer_address':

                    $customer = DB::table('customers')
                        ->where('id', '=', $data['customer_id'])
                        ->where('account_id', '=', ktUser::getAccountId())
                        ->first();

                    $html = '';

                    if(!empty($customer->b_address)){
                        $html = $html . $customer->b_address .'<br/>';
                    }

                    if(isset(ktLang::$countryList[$customer->b_country])){
                        $html = $html . ktLang::$countryList[$customer->b_country];
                    }

                    if(!empty($customer->b_country)){
                        $html = $html . ' ' . $customer->b_country;
                    }

                    if(!empty($customer->b_postal_code)){
                        $html = $html . ' ' . $customer->b_postal_code;
                    }

                    if(!empty($customer->b_phone_number)){
                        $html = $html . '<br/>Phone: ' . $customer->b_phone_number;
                    }

                    // if(!empty($customer->b_email)){
                    //     $html = $html . '<br/>Email:' . $customer->b_email;
                    // }

                    if(!empty($customer->b_vat)){
                        $html = $html . '<br/>VAT:' . $customer->b_vat;
                    }

                    return [
                        'status' => 'ok',
                        'html' => $html
                    ];

                    break;
                case 'download_invoice':
                    $account = ktUser::getAccountData();
                    self::downloadInovice($data, 'I', $account);
                    exit();
                    break;

                case 'client_download_invoice':
                    $account = ktUser::getAccountData();
                    $client = ktUser::getUserData();

                    $invoice = DB::table('invoices')
                        ->where('id', '=', $data['invoice_id'])
                        ->where('account_id', '=', ktUser::getAccountId())
                        ->where('customer_id', '=', $client->customer_id)
                        ->first();

                    if(is_object($invoice)) {
                        self::downloadInovice($data, 'I', $account);
                    }

                    exit();
                    break;
                case 'delete_invoice':

                    $result = DB::transaction(function () use ($data) {
                        try {
                            $result = DB::table('invoices')
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->where('id', '=', $data['invoice_id'])
                                ->where('r_ready', '<>', 1)
                                ->delete();

                            return $result;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    if(is_numeric($result)) {
                        return [
                            'status' => 'ok',
                            'result' => $result,
                            'id' => $data['invoice_id']
                        ];
                    }else{
                        return [
                            'messages' => $result
                        ];
                    }

                    break;
                case 'subscription_ready':

                     $update_result = DB::table('invoices')
                            ->where([
                                'id' => $data['invoice_id']
                            ])
                            ->update([
                                'r_ready'   => @$data['r_ready'],
                            ]);

                    return [
                        'status' => 'ok',
                        'result' => $update_result,
                    ];

                    break;
                case 'archive_invoice':
                     $update_result = DB::table('invoices')
                            ->where([
                                'id' => $data['invoice_id']
                            ])
                            ->update([
                                'archived'   => @$data['archived'],
                            ]);
                    return [
                        'status' => 'ok',
                        'invoice_id' => $data['invoice_id'],
                        'result' => $update_result,
                    ];

                    break;
                case 'invoice_state_update':
                     $update_result = DB::table('invoices')
                            ->where([
                                'id' => $data['invoice_id']
                            ])
                            ->update([
                                'status'   => @$data['status'],
                            ]);

                    return [
                        'status' => 'ok',
                        'result' => $update_result,
                    ];

                    break;
                case 'clone_invoice':

                    $invoice_id = DB::transaction(function () use ($data) {
                        try {
                            $invoice = self::getInvoice($data['invoice_id']);
                            $invoice_items = self::getInvoiceItems($data['invoice_id']);

                            unset($invoice->id);
                            unset($invoice->customer_id);                
                            unset($invoice->invoice_number);           
                            unset($invoice->status);      
                            unset($invoice->billed_from);    
                            unset($invoice->billed_to);    
                            unset($invoice->invoice_logo);   
                            unset($invoice->layout_color);  

                            if($invoice->type == 'INVOICE'){
                                $invoice->type = 'DRAFT';
                            }        

                            DB::table('invoices')->insert((array)$invoice);   

                            $invoice_id = DB::getPdo()->lastInsertId();

                            foreach($invoice_items as $item){
                                unset($item->id);
                                $item->invoice_id = $invoice_id;
                                DB::table('invoice_items')->insert((array)$item);
                            }

                            return $invoice_id;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    
                    if(is_numeric($invoice_id)){
                        return [
                            'status' => 'ok',
                            'invoice_id' => $invoice_id
                        ];
                    }else{
                        return [
                            'status' => 'error',
                            'message' => $invoice_id
                        ];                     
                    }

                    break;
                case 'issue_invoice':

                    $account = ktUser::getAccountData();

                    $invoice = ktFinance::getInvoice($data['invoice_id']);
                    $customer = ktCustomer::getCustomerData($invoice->customer_id);

                    $invoice_prefix = '';

                    if(isset($account->invoice_prefix) && !empty($account->invoice_prefix) && $account->invoice_number_format == 'NUMBERFORMAT'){
                        $invoice_prefix = $account->invoice_prefix;
                    }else if($account->invoice_number_format == 'DATEFORMAT'){
                        $invoice_prefix = Carbon::today()->format("Ym");
                    }

                    $invoice_padding = 0;
                    if(isset($account->invoice_padding) && !empty($account->invoice_padding)){
                        $invoice_padding = $account->invoice_padding;
                    }

                    if($account->invoice_number_format == 'DATEFORMAT' && $invoice_padding < 8){
                        $invoice_padding = 8;
                    }

                    $latest_issued_invoice = self::getLatestIssuedInvoice($invoice_prefix, ktUser::getAccountId());

                    if(!is_object($latest_issued_invoice)){
                        $latest_invoice_no = str_pad($invoice_prefix, $invoice_padding, '0',STR_PAD_RIGHT);
                    }else{
                        $latest_invoice_no = $latest_issued_invoice->invoice_number;
                    }

                    $new_number = bcadd($latest_invoice_no, 1);

                    if(!empty($account->invoice_layout_color)){
                        $layout_color = $account->invoice_layout_color;
                    }else{
                        $layout_color = '#000000';
                    }

                   if(!empty($account->invoice_logo)){
                        $invoice_logo = $account->invoice_logo;
                    }else{
                        $invoice_logo = NULL;
                    }             

                    if(!empty($account->vat)){
                        $a_vat = 'VAT: '.$account->vat;
                    }else{
                        $a_vat = '';
                    }

                    if(!empty($account->phone_number)){
                        $a_phone = 'Phone Number: '.$account->phone_number;
                    }else{
                        $a_phone = '';
                    }

                    //Set from
                    $billed_from = json_encode(
                        array(
                            $account->company_name,$account->address,
                            implode(', ' ,array_filter([
                                $account->city ,
                                @ktLang::$countryList[$account->country] , 
                                trim("{$account->country} {$account->postal_code}")
                            ])), 
                            $a_phone, 
                            $a_vat
                        )
                    );
                    
                    if(is_object($customer)){
                        if(!empty($customer->b_vat)){
                            $vat = 'VAT: '.$customer->b_vat;
                        }else{
                            $vat = '';
                        }

                        if(!empty($customer->b_phone_number)){
                            $phone_number = 'Phone Number: '.$customer->b_phone_number;
                        }else{
                            $phone_number = '';
                        }

                        $billed_to = json_encode(
                                array(
                                $customer->b_customer_name,
                                $customer->b_address,
                                implode(', ', array_filter([
                                    $customer->b_city ,
                                    @ktLang::$countryList[$customer->b_country] ,
                                    trim("{$customer->b_country} {$customer->b_postal_code}")
                                ])), 
                                $phone_number,
                                $vat
                            )
                        );
                    }

                    $update_result = DB::table('invoices')
                        ->where([
                            'id' => $data['invoice_id'],
                            'invoice_number' => NULL
                        ])
                        ->update([
                            'type'             => 'INVOICE',
                            'invoice_number'   => $new_number,
                            'layout_color'     => $layout_color,
                            'invoice_logo'     => $invoice_logo,
                            'billed_from'      => @$billed_from,
                            'billed_to'        => @$billed_to
                        ]);

                    if(is_object($customer)){
                        self::mailInvoice($invoice->id, $customer, $new_number, $account);
                    }

                    if($update_result){
                        return [
                            'status' => 'ok',
                            'invoice_no' => $new_number,
                        ];
                    }else{
                        return [
                            'status' => 'error'
                        ];                 
                    }

                    break;
                case 'mail_invoice':
                    $account = ktUser::getAccountData();
                    $invoice = ktFinance::getInvoice($data['invoice_id']);
                    $customer = ktCustomer::getCustomerData($invoice->customer_id);
                    if(is_object($customer) && is_object($invoice)){
                        self::mailInvoice($invoice->id, $customer, $invoice->invoice_number, $account);
                        return [
                            'status' => 'ok'
                        ];
                    }else{
                        return [
                            'status' => 'error'
                        ];
                    }
                    break;
                case 'finance_search':
                    $results = self::searchInvoices($data['q']);
                    return [
                            'result' => $results
                        ];  
                break;
            }
            return false;
        }else{
            return [
                'status' => 'error',
                'messages' => $validator->messages()->all()
            ];
        }

    }

    public static function formatInvoiceData($data){
        $billed_to_filtered = array_filter($data);
        $billed_to_count = count($billed_to_filtered );
        if($billed_to_count < 5){
            $billed_to_fill = array_fill(
                $billed_to_count, 
                5 - $billed_to_count, 
                ''
            );  
        
            $billed_to = array_merge(
                $billed_to_filtered, 
                $billed_to_fill
            );
        }else{
            $billed_to = $data;
        }

        return $billed_to;
    }

    public static function getInvoices($filter_type = false, $archived = false){
        try {
            if($filter_type === false) {
                $invoices = DB::table('invoices')
                    ->select('customers.customer_name', 'invoices.id', 'invoices.invoice_subtotals', 'invoices.invoice_date', 'invoices.status','invoices.invoice_pre_tax', 'invoices.invoice_tax', 'invoices.currency', 'invoices.invoice_date','invoices.invoice_number', 'invoice_category.name')
                    ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
                    ->leftJoin('invoice_category','invoice_category.id', '=', 'invoices.category_id')             
                    ->where('invoices.account_id', '=', ktUser::getAccountId())
                    ->orderBy('invoices.invoice_number', 'desc');

                if(Auth::user()->isClient()) {
                    $client = ktUser::getUserData();
                    $invoices->where('invoices.customer_id','=', $client->customer_id );
                }else{
                    $invoices->where('invoices.archived', '=', ($archived) ? 1 : 0);
                }

                $invoices  =  $invoices->get();
            }else{
                $invoices = DB::table('invoices')
                    ->select('customers.customer_name', 'invoices.id', 'invoices.invoice_subtotals', 'invoices.invoice_date', 'invoices.status','invoices.invoice_pre_tax', 'invoices.invoice_tax', 'invoices.currency', 'invoices.invoice_date','invoices.invoice_number', 'invoice_category.name')
                    ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
                    ->leftJoin('invoice_category','invoice_category.id', '=', 'invoices.category_id')  
                    ->where('invoices.account_id', '=', ktUser::getAccountId())
                    ->where('invoices.type', $filter_type)
                    ->orderBy('invoices.invoice_number', 'desc');

                    if(Auth::user()->isClient()) {
                        $client = ktUser::getUserData();
                        $invoices->where('invoices.customer_id','=', $client->customer_id );
                    }else{
                        $invoices->where('invoices.archived', '=', ($archived) ? 1 : 0);
                    }

                    $invoices  =  $invoices->get();
            }
            return $invoices;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function searchInvoices($searchTerm){
        try {
            // DB::listen(function($sql) 
            // { 
            //     var_dump($sql); 
            // });
            $invoices = DB::table('invoices')
                ->select('customers.customer_name', 'invoices.id', 'invoices.invoice_subtotals', 'invoices.invoice_date', 'invoices.status','invoices.invoice_pre_tax', 'invoices.invoice_tax', 'invoices.invoice_date','invoices.invoice_number')
                ->leftJoin('customers', 'invoices.customer_id', '=', 'customers.id')
                ->where('invoices.account_id', '=', ktUser::getAccountId())
                ->orWhere('invoices.invoice_number', 'LIKE', '%' . $searchTerm . '%')                                      
                ->orderBy('invoices.invoice_number', 'desc')
                ->get();

            return $invoices;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getInvoice($id) {
        try {
            $invoice = DB::table('invoices')
                ->select('invoices.*', 'invoice_category.name')
                ->leftJoin('invoice_category','invoice_category.id', '=', 'invoices.category_id')
                ->where('invoices.id', '=', $id)
                ->where('invoices.account_id', '=', ktUser::getAccountId())
                ->first();
            return $invoice;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getAccountInvoice($id, $account_id) {
        try {
            $invoice = DB::table('invoices')
                ->select('invoices.*', 'invoice_category.name')
                ->leftJoin('invoice_category','invoice_category.id', '=', 'invoices.category_id')
                ->where('invoices.id', '=', $id)
                ->where('invoices.account_id', '=', $account_id)
                ->first();
            return $invoice;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getInvoiceItems($id){
        try {
            $items = DB::table('invoice_items')
                ->where('invoice_id', '=', $id)
                ->where('account_id', '=', ktUser::getAccountId())
                ->get();
            return $items;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getAccountInvoiceItems($id,$account_id){
        try {
            $items = DB::table('invoice_items')
                ->where('invoice_id', '=', $id)
                ->where('account_id', '=', $account_id)
                ->get();
            return $items;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getLatestIssuedInvoice($prefix = '', $account_id){
        try {
            $invoice = DB::table('invoices')
                ->where('account_id', '=', $account_id)
                ->whereNotNull('invoice_number')
                ->where('invoice_number', 'like', $prefix . '%')
                ->orderBy('invoice_number', 'desc')
                ->first();
            return $invoice;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    static function getProfit(){
        date_default_timezone_set(ktUser::getAccountTimeZone());
        $profit = DB::select("SELECT SUM(invoice_subtotals - invoice_pre_tax + invoice_tax) as total FROM invoices
            WHERE account_id = ".ktUser::getAccountId(). ' AND type IN ("INVOICE") AND status IN ("PAID") AND invoice_date LIKE "'.date("Y").'-%"');
        return $profit[0];
    }

    public static function downloadInovice($data, $procedure, $account){
        $logo_path = base_path(). '/accounts/'.$account->id . '/';

        $invoice = ktFinance::getAccountInvoice($data['invoice_id'], $account->id);
        $invoice_items = ktFinance::getAccountInvoiceItems($data['invoice_id'], $account->id);
        $customer = ktCustomer::getCustomerData($invoice->customer_id, $account->id);

        $items['ITEM'] = array();
        $items['TAX'] = array();
        $items['PRE-TAX'] = array();

        foreach ($invoice_items as $item) {
            $items[$item->item_type][] = [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'label_1' => $item->label_1,
                'label_2' => $item->label_2,
                'factor' => $item->factor,
                'item_cost' => $item->item_cost,
            ];
        }
        //Create a new instance
        $invoicer = new \invoicr("A4",ktLang::$currencyList[$invoice->currency]['format'],ktLang::$invoiceLangMap[$invoice->language]);
        //Set number formatting
        $invoicer->setNumberFormat('.',',');
        //Set your logo
            if(empty($invoice->invoice_logo)){
                if(!empty($account->invoice_logo) && $invoice->type != 'INVOICE'){
                    if(file_exists($logo_path . $account->invoice_logo)) {
                        $invoicer->setLogo($logo_path . $account->invoice_logo, 200);
                    }
                }
            }else{
                if(file_exists($logo_path . $invoice->invoice_logo)) {
                    $invoicer->setLogo($logo_path . $invoice->invoice_logo, 200);
                }
            }
        //Set theme color     

        //#437bbf
        if(empty($invoice->layout_color)){
            if(!empty($account->invoice_layout_color)){                  
                $invoicer->setColor($account->invoice_layout_color);
            }else{               
                $invoicer->setColor("#437bbf");
            }
        }else{
            $invoicer->setColor($invoice->layout_color);
        }

        if($invoice->status == 'CREDIT'){
            $invoicer->setType('CREDIT NOTE');
        }else{
            $invoicer->setType($invoice->type);
        }
        //Set type
        if(!empty($invoice->invoice_number)){
            $invoicer->setReference($invoice->invoice_number);
        }

        if($invoice->type != 'RECURRING'){
            //Set date
            $invoicer->setDate(date('d.m.Y',strtotime($invoice->invoice_date)));
            //Set due date
            $invoicer->setDue(date('d.m.Y',strtotime($invoice->due_date)));
        }

        if(!empty($account->vat)){
            $a_vat = 'VAT: '.$account->vat;
        }else{
            $a_vat = '';
        }

        if(!empty($account->phone_number)){
            $a_phone = 'Phone Number: '.$account->phone_number;
        }else{
            $a_phone = '';
        }

        if(!empty($invoice->billed_from)){
            $billed_from = json_decode($invoice->billed_from);
        }else{
            $billed_from = array(
                $account->company_name,
                $account->address,
                implode(', ' ,array_filter([
                    $account->city ,
                    @ktLang::$countryList[$account->country] , 
                    trim("{$account->country} {$account->postal_code}")
                ])), 
                $a_phone, 
                $a_vat
            );
        }

        //Set from
        $invoicer->setFrom(self::formatInvoiceData($billed_from));

        //Set to
        if(is_object($customer)){
            if(!empty($customer->b_vat)){
                $vat = 'VAT: '.$customer->b_vat;
            }else{
                $vat = '';
            }

            if(!empty($customer->b_phone_number)){
                $phone_number = 'Phone Number: '.$customer->b_phone_number;
            }else{
                $phone_number = '';
            }

            if(!empty($invoice->billed_from)){
                $billed_to = json_decode($invoice->billed_to);
            }else{
                $billed_to = array(
                    $customer->b_customer_name,
                    $customer->b_address,
                    implode(', ', array_filter([
                        $customer->b_city ,
                        @ktLang::$countryList[$customer->b_country] ,
                        trim("{$customer->b_country} {$customer->b_postal_code}")
                    ])), 
                    $phone_number,
                    $vat
                );
            }

            $invoicer->setTo(self::formatInvoiceData($billed_to));
        }
        //Add items
        $subtotals = 0;
        foreach ($items['ITEM'] as $item) {
            $qty = (is_numeric($item['quantity']))?$item['quantity']:1;
            if(isset($item['item_cost'])){
                $subtotals = number_format($subtotals + $item['item_cost'] * $qty, 2, ".", "");;
                $invoicer->addItem($item['label_1'],$item['label_2'],$qty,$item['item_cost'],'','',$item['item_cost']*$qty);
            }
        } 
        //Add totals
        $invoicer->addTotal(ktLang::$invoiceText['ENG']['SUBTOTALS'], $subtotals, true);     

        //Add items
        $pre_tax = 0;
        foreach ($items['PRE-TAX'] as $item) {
            if(strpos($item['factor'], '%') !== false){
                list($factor,) = explode('%', $item['factor']);
                $factor = $subtotals / 100 * $factor;
            }else{
                $factor = $item['factor'];
            }

            $invoicer->addTotal($item['label_1'], $factor * -1);
            $pre_tax = number_format($pre_tax + $factor, 2, ".", "");
        } 

        $after_pre_taxation  = $subtotals - $pre_tax;
        $invoicer->addTotal(ktLang::$invoiceText['ENG']['TAXABLE'],$after_pre_taxation,true);

        //Add items
        $tax = 0;
        foreach ($items['TAX'] as $item) {
            if(strpos($item['factor'], '%') !== false){
                list($factor,) = explode('%', $item['factor']);
                $factor = $after_pre_taxation / 100 * $factor;
            }else{
                $factor = $item['factor'];
            }                        
            $invoicer->addTotal($item['label_1'], $factor);
            $tax = number_format($tax + $factor, 2, ".", "");
        } 

        $totals = $after_pre_taxation + $tax;

        $invoicer->addTotal(ktLang::$invoiceText['ENG']['TOTAL_DUE'],number_format($totals,2, ".", ""),true);

        //Add badge
        if(in_array($invoice->status,array('STORNO','FRAUD','DEBT','COLLECT'))){
            $invoicer->addBadge(ktLang::$invoiceStates[$invoice->status]);
        }else if($invoice->type == 'RECURRING'){
            $invoicer->addBadge('PREVIEW');
        }

        if(!empty($invoice->legal_notes)){
            //Add Title
            $invoicer->addTitle(ktLang::$invoiceText['ENG']['LEGAL_NOTES']);
            //Add Paragraph
            $invoicer->addParagraph($invoice->legal_notes);
        }

        if(!empty($invoice->notes)){
            //Add Title
            $invoicer->addTitle(ktLang::$invoiceText['ENG']['NOTES']);
            //Add Paragraph
            $invoicer->addParagraph($invoice->notes);
        }
        //Set footer note
        //$invoicer->setFooternote("This document was genearted by \"Easy Office Project Management Software\" http://www.codeking.co.at");
        //Render the PDF

        if (!empty($invoice->invoice_number)) {
            $i = $invoicer->render('Invoice #' . $invoice->invoice_number . '.pdf', $procedure);
        } else {
            $i = $invoicer->render('Draft Slip.pdf', $procedure);
        }

        if($procedure == 'S'){
            return $i;
        }
    }


    public static function mailInvoice($invoice_id, $customer, $invoice_number, $account)
    {
        if (!empty($customer->b_email)) {
            $data['invoice_id'] = $invoice_id;
            $data['invoice_number'] = $invoice_number;
            Mail::send('emails.invoice', $data, function ($message) use ($data, $customer, $invoice_number, $account) {
                $message->to($customer->b_email, $customer->customer_name)
                    ->subject('New Invoice #' . $invoice_number)
                    ->attachData(ktFinance::downloadInovice($data, 'S', $account), 'Invoice #' . $invoice_number . '.pdf');
            });
        }
    }

}