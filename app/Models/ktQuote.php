<?php namespace App\Models;

use DB;
use League\Flysystem\Exception;
use Validator;
use Hash;
use Auth;
use Crypt;
use Config;
use App\Models\ktUser;
use Illuminate\Database\Eloquent\Model;
use App\Models\ktNotify;

class ktQuote {

    public static  $rules = [
        'submit_questionnarie' => [

        ],
        'customer_questionnarie' => [
            'id' => 'required|numeric'
        ],
        'assign_customer' => [
            'id' => 'required|numeric',
            'customer_id' => 'required|numeric'
        ],
        'reviewed' => [
            'id' => 'required|numeric'
        ],
        'delete_questionnaire' => [
            'id' => 'required|numeric'
        ]
    ];

    public static $rules_messages = [

    ];

    public static function triggerEvent($request, $inject_rule = array(), $quote = false)
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

        if(count($inject_rule) > 0){
            $rules = array_merge($rules, $inject_rule);
        }

        $data = array();
        
        foreach($rules as $field => $rule){
            $data[$field] = $request->input($field);
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails() === false){
            switch($event) {
                case 'submit_questionnarie':
                    $result = DB::transaction(function () use ($data, $quote) {
                        try {

                            $qa = [];
                            $fields = json_decode($quote->template);
                            foreach ($fields as $field) {
                                $value = $data[@$field->cid];
                                if(empty($value)){
                                    $value = '';
                                }else if(is_array($value)){
                                    $value = array_values(array_filter($value));
                                }
                                $qa[$field->label] = $value;
                            }
                            $qa = json_encode($qa);

                            if(Auth::user()->isClient()){
                                $client = ktUser::getUserData();
                                $reference_id = $client->customer_id;
                                $target = 'CUSTOMER';
                            }else{
                                $reference_id = NULL;
                                $target = NULL;
                            }

                            DB::table('questionnarie_templates')->insert([
                                'template'      => $qa,
                                'account_id'    => $quote->account_id,
                                'name'          => $quote->name,
                                'type'          => 'QA',
                                'target'        => $target,
                                'reference_id'  => $reference_id,
                                'status'        => 'SUBMITTED'
                            ]);
                            return true;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;       
                    break;      
                case 'customer_questionnarie':
                    $result = DB::transaction(function () use ($data, $quote) {
                        try {

                            $qa = [];
                            $fields = json_decode($quote->template);
                            foreach ($fields as $field) {
                                $value = $data[@$field->cid];
                                if(empty($value)){
                                    $value = '';
                                }else if(is_array($value)){
                                    $value = array_values(array_filter($value));
                                }
                                $qa[$field->label] = $value;
                            }
                            $qa = json_encode($qa);

                            DB::table('questionnarie_templates')
                                ->where('id', '=',  $data['id'])
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->update([
                                    'template'      => $qa,
                                    'status'        => 'SUBMITTED',
                                    'public'        => 0
                                ]);
                            return true;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;       
                    break;             
                case 'assign_customer':
                    $result = DB::transaction(function () use ($data, $quote) {
                        try {
                            DB::table('questionnarie_templates')
                                ->where('id', '=', $data['id'])
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->update([                  
                                    'target'         => 'CUSTOMER',                 
                                    'reference_id'   => $data['customer_id']
                                ]);
                            return true;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;       
                    break;             
                case 'reviewed':
                    $result = DB::transaction(function () use ($data, $quote) {
                        try {
                            DB::table('questionnarie_templates')
                                ->where('id', '=', $data['id'])
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->update([                  
                                    'status'         => 'REVIEWED',                 
                                    'reviewer_id'    => Auth::id()
                                ]);
                            return true;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;       
                    break;   
               case 'delete_questionnaire':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            $result = DB::table('questionnarie_templates')
                                ->where('id', '=', $data['id'])
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->delete();

                            return $result;

                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    if($result){
                        return [
                            'status' => 'ok',
                            'result' => $result
                        ];
                    }else{
                        return [
                            'status' => 'error',
                            'result' => $result
                        ];    
                    }
                    break;
            }
            return false;
        }else{
            return [
                'status' => 'error',
                'message' => $validator->messages()->all()
            ];
        }

    }

    public static function fetchQuoteValidationRules($fields){
        $rules_set = [];
        $rules = [];
        foreach($fields as $field){
            $rules = [];
            if($field->required && $field->field_type != 'color'){
                $rules[] = 'required';
                if(in_array($field->field_type, ['checkboxes', 'radio'])){
                    $rules[] = 'notemptyarray';
                }
            }
            $rules_set[$field->cid] = implode('|', $rules);
        }
        if(Config::get('app.reCaptcha.enabled')){
            $rules_set['g-recaptcha-response'] = 'required|accepted';
        }

        return $rules_set;
    }
 
    public static function getQuestionnarieForReview($id){
        try {
            $q = DB::table('questionnarie_templates')
                ->where('account_id', '=', ktUser::getAccountId())
                ->where('type', '=', 'QA')                       
                ->where('id', '=', $id)
                ->first();
            return $q;
        }catch (\Exception $e) {
            return false;
        }
    }
 
    public static function reviewQuestionnarie($id){
        try {
            $q = DB::table('questionnarie_templates')
                ->select('questionnarie_templates.*', 'users_extended.first_name', 'users_extended.last_name')
                ->leftJoin('users_extended', 'users_extended.user_id', '=', 'questionnarie_templates.reviewer_id')
                ->where('questionnarie_templates.account_id', '=', ktUser::getAccountId())
                ->where('questionnarie_templates.type', '=', 'QA')
                ->where('questionnarie_templates.id', '=', $id)
                ->first();
            return $q;
        }catch (\Exception $e) {
            return false;
        }
    }


    public static function countQuestionnariesWithSubmittedState(){
        try {

            $q = DB::table('questionnarie_templates')
                    ->where('account_id', '=', ktUser::getAccountId())
                    ->where('type', '=', 'QA')
                    ->where('status', '=', 'SUBMITTED');
            return $q;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getSubmitter($target, $reference_id){
        if($target == 'CUSTOMER') {
            $submitter = DB::table('customers')
                ->where('id', '=', $reference_id)
                ->first();
            return $submitter->customer_name;
        }else if($target == 'PROJECT'){
            $submitter = DB::table('projects')
                ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
                ->where('projects.id', '=', $reference_id)
                ->first();
            return $submitter->customer_name;
        }
    }

}

Validator::extend('notemptyarray', function($attribute, $value, $parameters)
{
    if(count($value) > 0){
        if(empty($value[0])){
            return false;
        }
        return true;
    } else {
        return false;
    }
});