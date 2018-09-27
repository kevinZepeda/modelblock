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

class ktSettings {

    public static  $rules = [
        'new_questionnarie' => [
            'name' => 'required|min:2',
            'data' => ['required', 'regex:/\{"fields"\:\[\{(.)*\}\]/']
        ],
        'edit_questionnarie' => [
            'id' => 'required|numeric',
            'name' => 'required|min:2',
            'data' => ['required', 'regex:/\{"fields"\:\[\{(.)*\}\]/']
        ],
        'publish_questionnarie' => [
            'id' => 'required|numeric',
            'state' => 'required|in:0,1'
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
            if($field == 'columns') {
                $data[$field] = json_encode($request->input($field));
            }else if($field == 'ids'){
                $data[$field] = $request->input($field);
            }else{
                $data[$field] = trim($request->input($field));
            }
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails() === false){

            switch($event) {
                case 'new_questionnarie':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $fields = json_decode($data['data']); 
                            $fields = json_encode($fields->fields);

                            DB::table('questionnarie_templates')->insert([
                                'account_id'    => ktUser::getAccountId(),
                                'name'          => $data['name'],
                                'template'      => $fields
                            ]);
                            $template_id = DB::getPdo()->lastInsertId();
                            return [
                                'status'    => 'ok',
                                'id' => $template_id
                            ];
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'edit_questionnarie':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            $fields = json_decode($data['data']); 
                            $fields = json_encode($fields->fields);

                            $update_result = DB::table('questionnarie_templates')
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->where('id', '=', $data['id'])
                                ->update([
                                    'name' => $data['name'],
                                    'template' => $fields
                                ]);

                            return [
                                'status' => 'ok',
                                'result' => $update_result
                            ];      
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;       
                    break;      
                case 'publish_questionnarie':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $update_result = DB::table('questionnarie_templates')
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->where('id', '=', $data['id'])
                                ->update([
                                    'public' => $data['state']
                                ]);

                            return [
                                'status' => 'ok',
                                'result' => $update_result
                            ];      
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
                'message' => $validator->messages()->all()
            ];
        }

    }

    public static function getCustomerQuestionnaries($id){
        try {
            $q = DB::table('questionnarie_templates')
                ->where('account_id', '=', ktUser::getAccountId())
                ->where('reference_id', '=', $id)           
                ->where('type', '=', 'QA')                   
                ->get();
            return $q;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getQuestionnaries(){
        try {
            $q = DB::table('questionnarie_templates')
                ->where('account_id', '=', ktUser::getAccountId())
                ->where('type', '=', 'TEMPLATE')                   
                ->get();
            return $q;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getSubmitedQuestionnaries(){
        try {
            $q = DB::table('questionnarie_templates')
                ->where('questionnarie_templates.account_id', '=', ktUser::getAccountId())
                ->where('questionnarie_templates.type', '=' , 'QA')
                ->where('questionnarie_templates.status', '=', 'SUBMITTED')
                ->orWhere(function($query)
                {
                    $query->whereNull('questionnarie_templates.reference_id')
                          ->where('questionnarie_templates.account_id', '=', ktUser::getAccountId())
                          ->where('questionnarie_templates.type', '=' , 'QA');
                })             
                ->get();
            return $q;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getQuestionnarie($id){
        try {
            $q = DB::table('questionnarie_templates')
                ->where('account_id', '=', ktUser::getAccountId())
                ->where('id', '=', $id)
                ->first();
            return $q;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getPublicQuestionnarie($id){
        try {
            $sql = "SELECT * 
                FROM questionnarie_templates
                WHERE MD5(CONCAT('".Config::get('app.salt.qa')."',id)) = '".$id."'";
            $quote = DB::select($sql);
            
            return $quote;
        }catch (\Exception $e) {
            return false;
        }
    }    

    public static function getCaptcha($captcha){
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.Config::get('app.reCaptcha.secret')
            .'&remoteip='.$_SERVER['REMOTE_ADDR']
            .'&response='.$captcha;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        $reCaptcha = json_decode($curl_response);

        return $reCaptcha->success;
    }

}