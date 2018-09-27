<?php namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\ktFinance;
use App\Models\ktUser;
use App\Models\ktLang;
use App\Models\ktCustomer;
use Mail;

class Billing extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'subscription:execute';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Display an inspiring quote';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        $result = DB::transaction(function () {
            try {

            	$this->comment('Subscription Billing Started');

				$today = date("Y-m-d");
				$pendingSubscriptions = $this->getPendingSubscriptions($today);

				if(count($pendingSubscriptions) <= 0){
					$this->comment('No pending Subscriptions');
				}

				foreach($pendingSubscriptions as $subscription){
		            
					date_default_timezone_set($subscription->timezone);
					$subscriptionItems = array();

		            $invoice_prefix = '';
		            if(isset($subscription->invoice_prefix) && !empty($subscription->invoice_prefix)){
		                $invoice_prefix = $subscription->invoice_prefix;
		            }

		            $invoice_padding = 0;
		            if(isset($subscription->invoice_padding) && !empty($subscription->invoice_padding)){
		                $invoice_padding = $subscription->invoice_padding;
		            }

		            $latest_issued_invoice = ktFinance::getLatestIssuedInvoice($invoice_prefix , $subscription->account_id);

		            if(!is_object($latest_issued_invoice)){
		                $latest_invoice_no = str_pad($invoice_prefix, $invoice_padding, '0',STR_PAD_RIGHT);
		            }else{
		                $latest_invoice_no = $latest_issued_invoice->invoice_number;
		            }

		            $new_number = bcadd($latest_invoice_no, 1);

		            if(!empty($subscription->invoice_layout_color)){
		                $layout_color = $subscription->invoice_layout_color;
		            }else{
		                $layout_color = '#000000';
		            }

					$tmp_date = date_create($subscription->r_next_date);
					date_add($tmp_date,date_interval_create_from_date_string("{$subscription->r_due_days} days"));

    				if(!empty($subscription->vat)){
                        $a_vat = 'VAT: '.$account->vat;
                    }else{
                        $a_vat = '';
                    }

                    if(!empty($subscription->phone_number)){
                        $a_phone = 'Phone Number: '.$account->phone_number;
                    }else{
                        $a_phone = '';
                    }


                    $billed_from = json_encode(
                        array(
                            $subscription->company_name,$subscription->address,
                            implode(',' ,array_filter([
                                $subscription->city ,
                                @ktLang::$countryList[$subscription->country] , 
                                trim("{$subscription->country} {$subscription->postal_code}")
                            ])), 
                            $a_phone, 
                            $a_vat
                        )
                    );
                    
                    $customer = ktCustomer::getCustomerData($subscription->customer_id, $subscription->account_id);
                    if(is_object($customer)){
                    	
                        if(!empty($customer->b_vat)){
                            $vat = 'VAT: '.$customer->b_vat;
                        }else{
                            $vat = '';
                        }

                        if(!empty($customer->b_phone_number)){
                            $phone_number = 'Phone: '.$customer->b_phone_number;
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

                    if(!empty($subscription->invoice_logo)){
                        $invoice_logo = $subscription->invoice_logo;
                    }else{
                        $invoice_logo = NULL;
                    }      

					DB::table('invoices')->insert([
		                'account_id'    	=> $subscription->account_id,
		                'customer_id'  		=> $subscription->customer_id,
		                'invoice_date'  	=> $today,
		                'due_date'      	=> date_format($tmp_date, 'Y-m-d'),
		                'type'          	=> 'INVOICE',
		                'layout_color'		=> $layout_color,
		                'invoice_logo'		=> $invoice_logo,

		                'billed_from'		=> @$billed_from,
		                'billed_to'			=> @$billed_to,

		                'currency'      	=> $subscription->currency,
		                'invoice_subtotals' => $subscription->invoice_subtotals,
		                'invoice_pre_tax'   => $subscription->invoice_pre_tax,
		                'invoice_tax'   	=> $subscription->invoice_tax,

		                'notes'         	=> $subscription->notes,
		                'legal_notes'   	=> $subscription->legal_notes,
		                'language'      	=> $subscription->language,
		                'invoice_number'	=> $new_number
		            ]);   

					$invoice_id = DB::getPdo()->lastInsertId();

					$subscriptionItems = $this->getSubscriptionItems($subscription->id);

					foreach($subscriptionItems as $subscriptionItem){
						unset($subscriptionItem->id);
						$subscriptionItem->invoice_id = $invoice_id;	
						DB::table('invoice_items')->insert((array)$subscriptionItem);
					}

					$periods = [
						'MONTHLY' => 'month',
						'DAILY' => 'day',
						'WEEKLY' => 'week',
						'YEARLY' => 'year'
					];

					$new_billing_date = date_create($subscription->r_next_date);
					date_add($new_billing_date,date_interval_create_from_date_string('1 '.$periods[$subscription->r_due_period]));

					DB::table('invoices')
                        ->where([
                            'id' => $subscription->id
                        ])
                        ->update([
                            'r_next_date' => date_format($new_billing_date, 'Y-m-d')
                        ]);
                    $this->comment('Issued Invoice: '.$new_number);

					if(is_object($customer)) {
						$account = ktUser::getAccountDataById($subscription->account_id);
						ktFinance::mailInvoice($invoice_id, $customer, $new_number, $account);
					}

				}

				$this->comment('Subscription Billing Ended');

			}catch (\PDOException $e) {
                $this->comment('Subscription Billing Exception ');
            }
        });
	}

	private static function getPendingSubscriptions($r_date){
	    try {
	        $invoices = DB::table('invoices')
	            ->select('invoices.*','account.invoice_prefix', 'account.id as account_id', 'account.invoice_padding', 'account.invoice_layout_color', 'account.invoice_logo','account.country', 'account.postal_code', 'account.address', 'account.company_name', 'account.city' ,'account.timezone')
	            ->leftJoin('account', 'account.id', '=', 'invoices.account_id')
	            ->where('invoices.type', 'RECURRING')
	            ->where('invoices.r_next_date', $r_date)
	            ->where('invoices.r_end_date', '>', $r_date)
	            ->where('invoices.r_ready', 1)
	            ->orderBy('invoices.invoice_number', 'desc')
	            ->get();
	        return $invoices;
	    }catch (\Exception $e) {
	        return 'Subscription Billing Failed: ';
	    }
	}

	private static function getSubscriptionItems($subscription_id){
	    try {
	        $invoices = DB::table('invoice_items')
	            ->where('invoice_id', $subscription_id)
	            ->get();
	        return $invoices;
	    }catch (\Exception $e) {
	        return 'Subscription Billing Failed: ';
	    }
	}
}
