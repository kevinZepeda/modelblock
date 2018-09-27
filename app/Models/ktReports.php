<?php namespace App\Models;

use DB;
use League\Flysystem\Exception;
use Validator;
use Hash;
use Auth;
use Crypt;
use Illuminate\Database\Eloquent\Model;
use App\Models\ktUser;

class ktReports {

    public static  $rules = [
        'get_report' => [
            'date_from'   => 'required|date_format:d/m/Y',
            'date_to'     => 'required|date_format:d/m/Y',
            'breakdown'   => 'required|in:INVOICE,QUOTE,EXPENSE'
        ],
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
        }else{
            return array(trans('application.intruder'));
        }

        $data = array();
        foreach($rules as $field => $rule){
            $data[$field] = trim($request->input($field));
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails() === false){
            switch($event) {
                case 'get_report':

                    $report = self::getFinanceReport($data['date_from'], $data['date_to'], strtoupper($data['breakdown']));

                    return [
                        'status' => 'ok',
                        'report' => $report,
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

    static function getMyInvoiceCurrencies(){
        $sql = 'SELECT DISTINCT currency
                FROM invoices
                WHERE account_id = '.ktUser::getAccountId(). ' GROUP BY currency';

        //echo $sql;
        $report = DB::select($sql);

        return $report;
    }   

    static function getFinanceReport($startDate, $endDate, $type){
        date_default_timezone_set(ktUser::getAccountTimeZone());

        $tmp_start_date = date_parse_from_format('d/m/Y', $startDate);
        $tmp_end_date = date_parse_from_format('d/m/Y', $endDate);

        $start_date = $tmp_start_date['year'] . '-' . $tmp_start_date['month'] . '-' . $tmp_start_date['day'];
        $end_date = $tmp_end_date['year'] . '-' . $tmp_end_date['month'] . '-' . $tmp_end_date['day'];

        $report = DB::select('SELECT DATE_FORMAT(i.invoice_date, "%d/%m/%Y") as invoice_date, i.invoice_number, (i.invoice_subtotals - i.invoice_pre_tax) as taxable, i.invoice_tax as tax, (i.invoice_subtotals - i.invoice_pre_tax + i.invoice_tax) as total, i.status, i.currency, c.b_customer_name as customer_name
            FROM invoices as i LEFT JOIN customers as c ON c.id = i.customer_id 
            WHERE i.account_id = '.ktUser::getAccountId(). ' AND type IN ("'.$type.'") AND i.invoice_date >= "'.$start_date . '" AND i.invoice_date <=  "'. $end_date . '"');

        return $report;
    }    

    static function getFinanceGraph($startDate, $endDate, $type, $currency){
        date_default_timezone_set(ktUser::getAccountTimeZone());

        $tmp_start_date = date_parse_from_format('d/m/Y', $startDate);
        $tmp_end_date = date_parse_from_format('d/m/Y', $endDate);

        $start_date = $tmp_start_date['year'] . '-' . $tmp_start_date['month'] . '-' . $tmp_start_date['day'];
        $end_date = $tmp_end_date['year'] . '-' . $tmp_end_date['month'] . '-' . $tmp_end_date['day'];

        $filter = '';
        if($type == 'INVOICE'){
            $filter = "AND status NOT IN ('STORNO','FRAUD','CREDIT')";
        }


        $sql = 'SELECT DATE_FORMAT(invoice_date, "%b/%y") as date, DATE_FORMAT(invoice_date, "%b") as month, DATE_FORMAT(invoice_date, "%y") as year, SUM(invoice_subtotals - invoice_pre_tax + invoice_tax) as total, currency
            FROM invoices
            WHERE account_id = '.ktUser::getAccountId(). ' AND type IN ("'.$type.'") AND invoice_date >= "'.$start_date . '" AND invoice_date <=  "'. $end_date . '"  AND currency = "'.$currency.'" '.$filter.'
            GROUP BY DATE_FORMAT(invoice_date, "%m/%Y")';

        //echo $sql;
        $report = DB::select($sql);

        return $report;
    }    

}