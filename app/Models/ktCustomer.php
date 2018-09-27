<?php namespace App\Models;

use DB;
use League\Flysystem\Exception;
use Validator;
use Hash;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Route;

class ktCustomer {

    public static  $rules = [
        'new_customer' => [
            'customer_name'         => 'required|min:3',
            'company_number'        => 'min:3',
            'country'               => 'min:2',
            'city'                  => 'min:3',
            'address'               => 'min:3',
            'postal_code'           => 'min:3',
            'contact_full_name'     => 'min:3',
            'phone_number'          => 'min:3',
            'email'                 => 'min:5|email',
            'b_customer_name'       => 'required|min:3',
            'b_vat'                 => 'min:3',
            'b_country'             => 'min:2',
            'b_city'                => 'min:3',
            'b_address'             => 'min:3',
            'b_postal_code'         => 'min:3',
            'b_contact_full_name'   => 'min:3',
            'b_phone_number'        => 'min:3',
            'b_email'               => 'min:5|email'
        ],
        'update_contact' => [
            'contact_id'            => 'required|numeric',
            'customer_name'         => 'required|min:3',
            'country'               => 'min:2',
            'city'                  => 'min:3',
            'address'               => 'min:3',
            'postal_code'           => 'min:3',
            'contact_full_name'     => 'min:3',
            'phone_number'          => 'min:3',
            'email'                 => 'min:5|email'
        ],
        'update_billing_contact' => [
            'contact_id'            => 'required|numeric',
            'company_number'        => 'min:3',
            'b_customer_name'       => 'required|min:3',
            'b_vat'                 => 'min:3',
            'b_country'             => 'min:2',
            'b_city'                => 'min:3',
            'b_address'             => 'min:3',
            'b_postal_code'         => 'min:3',
            'b_contact_full_name'   => 'min:3',
            'b_phone_number'        => 'min:3',
            'b_email'               => 'min:5|email'
        ],
        'delete_customer' => [
            'customer_id' => 'required|numeric'
        ],
        'new_customer_questionnarie' => [
            'template_id' => 'required',
            'reference_id' => 'required|numeric'
        ],
        'new_client_user' => [
            'first_name' => 'required',
            'last_name' => 'required',
            'email_address' => 'required',
            'password' => 'required',
            'customer_id' => 'required|numeric'
        ],
        'update_client_user' => [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'active' => 'required|in:0,1',
            '_user_id' => 'required|numeric'
        ],
        'delete_client_user' => [
            '_user_id' => 'required|numeric'
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
                case 'new_customer':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $data['account_id'] = ktUser::getAccountId();
                            DB::table('customers')->insert($data);
                            return DB::getPdo()->lastInsertId();;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    return [
                        'status' => 'ok',
                        'id' => $result
                    ];
                    break;
                case 'update_contact':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $id = $data['contact_id'];
                            unset($data['contact_id']);
                            $update_result = DB::table('customers')
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->where('id', '=', $id)
                                ->update($data);

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return false;
                    break;
                case 'update_billing_contact':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $id = $data['contact_id'];
                            unset($data['contact_id']);
                            $update_result = DB::table('customers')
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->where('id', '=', $id)
                                ->update($data);

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    $customer_id = DB::getPdo()->lastInsertId();
                    return false;
                    break;
                case 'delete_customer':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            $result = DB::table('customers')->where([
                                'id'    => $data['customer_id']
                            ])->delete();

                            return $result;

                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator').$e->getMessage());
                        }
                    });

                    return [
                        'status' => $result
                    ];
                    break;
                case 'new_customer_questionnarie':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $data['account_id'] = ktUser::getAccountId();

                            if($data['template_id'] == 'NULL'){
                                $insert_data = [
                                    'type' => 'QA',
                                    'target' => 'CUSTOMER',
                                    'status' => 'PENDING',
                                    'account_id' => ktUser::getAccountId(),
                                    'reference_id' => $data['reference_id']
                                ];
                                DB::table('questionnarie_templates')->insert($insert_data);
                            }else{
                                $template = ktSettings::getQuestionnarie($data['template_id']);
                                unset($template->id);
                                $template->target = 'CUSTOMER';
                                $template->status = 'PENDING';
                                $template->type = 'QA';
                                $template->reference_id = $data['reference_id'];
                                DB::table('questionnarie_templates')->insert((array)$template);
                            }

                            return DB::getPdo()->lastInsertId();;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator').$e->getMessage());
                        }
                    });
                    return [
                        'status' => 'ok',
                        'id' => $result
                    ];
                    break;
                case 'new_client_user':
                    $users = DB::table('users')
                        ->where('email', '=', $data['email_address'])
                        ->get();

                    if(count($users) > 0){
                        return array(trans('application.email_registered'));
                    }

                    $result = DB::transaction(function () use ($data) {
                        try {
                            $password = Hash::make($data['password']);
                            $date = date("Y-m-d H:i:s");

                            DB::table('users')->insert([
                                'name'          => $data['first_name'] . ' ' . $data['last_name'],
                                'email'         => $data['email_address'],
                                'password'      => $password,
                                'created_at'  => $date,
                                'updated_at'    => $date
                            ]);

                            $user_id = DB::getPdo()->lastInsertId();

                            $account_id = ktUser::getAccountId();
                            if($account_id === false){
                                throw new \PDOException(trans('application.report_issue_to_administrator'));
                            }

                            DB::table('users_extended')->insert([
                                'user_id'       => $user_id,
                                'account_id'    => $account_id,
                                'first_name'    => $data['first_name'],
                                'last_name'     => $data['last_name'],
                                'user_level'    => 'CLIENT',
                                'customer_id'   => $data['customer_id']
                            ]);

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    break;
                case 'update_client_user':
                    $users = DB::table('users')
                        ->where('email', '=', $data['email'])
                        ->where('id', '<>', $data['_user_id'])
                        ->get();

                    if(count($users) > 0){
                        return array(trans('application.email_registered'));
                    }

                    $result = DB::transaction(function () use ($data) {
                        DB::table('users')
                            ->where('id', '=', $data['_user_id'])
                            ->update([
                                'email' => $data['email']
                            ]);

                        DB::table('users_extended')
                            ->where('user_id', '=', $data['_user_id'])
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->update([
                                'first_name' => $data['first_name'],
                                'last_name' => $data['last_name'],
                                'active' => $data['active']
                            ]);
                    });

                    break;
                case 'delete_client_user':

                    $result = DB::transaction(function () use ($data) {
                        try {

                            DB::table('users')->where([
                                'id'    => $data['_user_id']
                            ])->delete();

                            return false;

                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    return $result;
            }
            return false;
        }else{
            return [
                'status' => 'error',
                'messages' => $validator->messages()->all()
            ];
        }

    }

    public static function getCustomerData($id, $account_id = false){
        if($account_id === false){
            $account_id = ktUser::getAccountId();
        }
        try {
            $customer = DB::table('customers')
                ->where('id', '=', $id)
                ->where('account_id', '=', $account_id)
                ->first();
            return $customer;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getCustomersData(){
        try {
            $customer = DB::table('customers')
                ->where('account_id', '=', ktUser::getAccountId())
                ->get();
            return $customer;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

}