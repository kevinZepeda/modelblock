<?php namespace App\Models;

use DB;
use League\Flysystem\Exception;
use Validator;
use Hash;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Config;

class ktUser {

    public static  $rules = [
        'full_name' => [
          'first_name'    => 'required|min:3',
          'last_name'     => 'required|min:3'
        ],
        'password' => [
          'current_password'        => 'required|min:6',
          'new_password'            => 'required|min:6',
          'repeat_new_password'     => 'required|min:6'
        ],
        'language' => [
            'language'      => 'required|min:3|max:3'
        ],
        'new_user'  => [
            'first_name'        => 'required|min:3',
            'last_name'         => 'required|min:3',
            'email_address'     => 'required|email|min:5',
            'password'          => 'required|min:6',
            'user_level'        => 'in:USER,ADMIN,MANAGER'
        ],
        'user_update' => [
            'first_name'        => 'required|min:3',
            'last_name'         => 'required|min:3',
            'email'             => 'required|email|min:5',
            'user_level'        => 'in:USER,ADMIN,MANAGER',
            'active'            => 'in:0,1',
            'department'        => 'numeric',
            '_user_id'          => 'required|numeric'
        ],
        'new_board' => [
            'board_name' => 'required|min:3',
            'board_columns' =>  ['required','regex:/\[(.)*\]/']
        ],
        'update_board_template' => [
            '_bc_id'        => 'required|numeric|min:3',
            'board_name'    => 'required|min:3',
            'board_columns' =>  ['required','regex:/\[(.)*\]/']
        ],
        'update_account_details' => [
            'company_name'          => 'required|min:3',
            'vat'                   => 'min:3',
            'country'               => 'min:2',
            'city'                  => 'min:3',
            'address'               => 'min:3',
            'postal_code'           => 'min:3',
            'phone_number'          => 'min:3',
            'email'                 => 'min:5|email'
        ],
        'update_currency' => [
            'currency' => 'required|min:3|max:3'
        ],
        'update_system_label' => [
            'system_label' => 'required|min:3'
        ],
        'update_notes' => [
            'invoice_note' => 'min:5',
            'invoice_legal_note' => 'min:5'
        ],
        'update_invoice_format' => [
            'invoice_prefix'     => 'min:1',
            'invoice_padding'    => 'numeric',
            'invoice_number_format' => 'in:NUMBERFORMAT,DATEFORMAT',
        ],
        'update_invoice_layout_color' => [
            'invoice_layout_color' => ['min:2', 'regex:/#(.)*/']
        ],
        'update_language' => [
            'invoice_language' => 'required|min:3|max:3'
        ],
        'delete_user' => [
            '_user_id' => 'required|numeric'
        ],
        'notifications_update' => [
            'NOTE_TYPE_UPDATE' => 'required|in:0,1',
            'NOTE_SUBJECT_UPDATE'=> 'required|in:0,1',
            'NOTE_STATE_UPDATE'=> 'required|in:0,1',
            'NOTE_PROJECT_UPDATE'=> 'required|in:0,1',
            'NOTE_PRIORITY_UPDATE'=> 'required|in:0,1',
            'NOTE_OWNER_UPDATE'=> 'required|in:0,1',
            'NOTE_MANAGER_UPDATE'=> 'required|in:0,1',
            'NOTE_ESTIMATE_UPDATE'=> 'required|in:0,1',
            'NOTE_DESCIPRITON_UPDATE'=> 'required|in:0,1',
            'NOTE_COMMENT_UPDATE'=> 'required|in:0,1'
        ],
        'new_questionnarie' => [
            'name' => 'required|in:0,1',
            'data' => 'required|in:0,1'
        ],
        'new_category' => [
            'name' => 'required|min:1'
        ],
        'delete_category' => [
            '_category_id' => 'required|numeric'
        ],
        'update_category' => [
            'name'        => 'required|min:1',
            '_category_id'    => 'required|numeric'
        ],
        'update_timezone' => [
            'timezone' => 'required|timezone'
        ],
        'update_system_layout_color' => [
            'system_layout_color' => ['min:2', 'regex:/#(.)*/'],
            'system_layout_text_color' => ['min:2', 'regex:/#(.)*/'],
        ],
        'remove_system_logo' => [

        ],
        'new_department' => [
            'department_name' => 'required|min:3'
        ],
        'update_department' => [
            'department_name'        => 'required|min:1',
            '_department_id'    => 'required|numeric'
        ],
        'delete_department' => [
            '_department_id' => 'required|numeric'
        ],
    ];

    public static function update($request)
    {
        if($request->has('action')){
            if(isset(self::$rules[$request->input('action')])){
                $action = $request->input('action');
                $rules = self::$rules[$action];
            }else{
                return array(trans('application.intruder'));
            }
        }

        $data = array();
        foreach($rules as $field => $rule){
            if($field == 'board_columns') {
                $data[$field] = json_encode($request->input($field));
            }else{
                $data[$field] = trim($request->input($field));
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails() === false){
            switch($action) {
                case 'new_user':

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

                                $account_id = self::getAccountId();
                                if($account_id === false){
                                    throw new \PDOException(trans('application.report_issue_to_administrator'));
                                }

                                DB::table('users_extended')->insert([
                                    'user_id'       => $user_id,
                                    'account_id'    => $account_id,
                                    'first_name'    => $data['first_name'],
                                    'last_name'     => $data['last_name'],
                                    'user_level'    => $data['user_level']
                                ]);

                                return false;
                            }catch (\PDOException $e) {
                                return array(trans('application.report_issue_to_administrator'));
                            }
                        });

                        return $result;
                    break;
                case 'user_update':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            $account_id = self::getAccountId();

                            $existing_user = DB::table('users')
                                ->where('id', '<>', $data['_user_id'])
                                ->where('email', '=', $data['email'])
                                ->get();

                            if(count($existing_user) > 0){
                                throw new \PDOException("USER_EXISTS");
                            }

                            if(Auth::user()->isAdmin()) {
                                DB::table('users')
                                    ->where('id', '=', $data['_user_id'])
                                    ->update([
                                        'email' => $data['email']
                                    ]);

                                DB::table('users_extended')
                                    ->where('user_id', '=', $data['_user_id'])
                                    ->where('account_id', '=', $account_id)
                                    ->update([
                                        'first_name' => $data['first_name'],
                                        'last_name' => $data['last_name'],
                                        'user_level' => $data['user_level'],
                                        'active' => $data['active'],
                                        'department_id' => (($data['department'] != 0)?$data['department']:NULL)
                                    ]);
                            }else if(Auth::user()->canManage()){

                                $user = DB::table('users_extended')
                                    ->where('user_id', '<>', $data['_user_id'])
                                    ->first();

                                if($user->user_level == 'USER') {
                                    DB::table('users')
                                        ->where('id', '=', $data['_user_id'])
                                        ->update([
                                            'email' => $data['email']
                                        ]);
                                    DB::table('users_extended')
                                        ->where('user_id', '=', $data['_user_id'])
                                        ->where('account_id', '=', $account_id)
                                        ->update([
                                            'first_name' => $data['first_name'],
                                            'last_name' => $data['last_name'],
                                            'user_level' => $data['user_level'],
                                            'active' => $data['active'],
                                            'department_id' => (($data['department'] != 0)?$data['department']:NULL)
                                        ]);
                                }
                            }

                            return false;
                        }catch (\PDOException $e) {
                            if($e->getMessage() == 'USER_EXISTS'){
                                return array(trans('application.email_registered'));
                            }else{
                                return array(trans('application.report_issue_to_administrator'));
                            }
                        }
                    });
                    return $result;
                    break;
                case 'new_board':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $account_id = self::getAccountId();
                            if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                                DB::table('board_templates')
                                    ->insert([
                                        'account_id'    => $account_id,
                                        'name'          => $data['board_name'],
                                        'columns'       => $data['board_columns']
                                    ]);
                            }

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    return $result;
                    break;
                case 'update_board_template':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            $account_id = self::getAccountId();

                            if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                                DB::table('board_templates')
                                    ->where('id', '=', $data['_bc_id'])
                                    ->where('account_id', '=', $account_id)
                                    ->update([
                                        'name' => $data['board_name'],
                                        'columns' => $data['board_columns']
                                    ]);
                            }

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'update_account_details':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            if(Auth::user()->isAdmin()) {
                                DB::table('account')
                                    ->where('id', '=', ktUser::getAccountId())
                                    ->update($data);
                            }

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'update_currency':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            if(Auth::user()->isAdmin()) {
                                DB::table('account')
                                    ->where('id', '=', ktUser::getAccountId())
                                    ->update($data);
                            }

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'update_system_label':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            if(Auth::user()->isAdmin()) {
                                DB::table('account')
                                    ->where('id', '=', ktUser::getAccountId())
                                    ->update($data);
                            }

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'update_notes':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            if(Auth::user()->isAdmin()) {
                                DB::table('account')
                                    ->where('id', '=', ktUser::getAccountId())
                                    ->update($data);
                            }

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'update_invoice_format':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            if(Auth::user()->isAdmin()) {
                                DB::table('account')
                                    ->where('id', '=', ktUser::getAccountId())
                                    ->update($data);
                            }
                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'update_language':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            if(Auth::user()->isAdmin()) {
                                DB::table('account')
                                    ->where('id', '=', ktUser::getAccountId())
                                    ->update($data);
                            }
                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'update_invoice_layout_color':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            if(Auth::user()->isAdmin()) {
                                DB::table('account')
                                    ->where('id', '=', ktUser::getAccountId())
                                    ->update($data);
                            }

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'delete_user':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            $result = DB::table('users')->where([
                                'id'    => $data['_user_id']
                            ])->delete();

                            return false;

                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    return $result;
                    break;   
                case 'notifications_update':
                
                    $result = DB::transaction(function () use ($data) {
                        try {
                            foreach ($data as $param => $value) {

                                $pobject = DB::table('users_config')
                                    ->where('user_id', '=', Auth::id())
                                    ->where('config', '=', $param)
                                    ->first();

                                if(is_object($pobject)){
                                    $update = DB::table('users_config')
                                        ->where('user_id', '=', Auth::id())
                                        ->where('config', '=', $param)
                                        ->update([
                                            'value' => $value    
                                        ]);
                                }else{  
                                     DB::table('users_config')
                                        ->insert([
                                            'user_id'  => Auth::id(),
                                            'config'    => $param,
                                            'value'    => $value
                                        ]);
                                }
                                
                            }
                            return false;

                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
          
                    return $result;
                    break;   
            case 'new_category':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $account_id = self::getAccountId();
                            if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                                DB::table('invoice_category')
                                    ->insert([
                                        'account_id'    => self::getAccountId(),
                                        'name'          => $data['name']
                                    ]);
                            }
                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
            case 'delete_category':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            $result = DB::table('invoice_category')->where([
                                'id'    => $data['_category_id']
                            ])->delete();

                            return false;

                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;   
            case 'update_category':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                                DB::table('invoice_category')
                                    ->where('id', '=', $data['_category_id'])
                                    ->where('account_id', '=', self::getAccountId())
                                    ->update([
                                        'name' => $data['name']
                                    ]);
                            }
                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
            case 'password':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            DB::table('users')
                                ->where('id', '=', Auth::id())
                                ->update([
                                    'password' => Hash::make($data['repeat_new_password'])
                                ]);
                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
            case 'update_timezone':
                $result = DB::transaction(function () use ($data) {
                    try {
                        if(Auth::user()->isAdmin()) {
                            DB::table('account')
                                ->where('id', '=', ktUser::getAccountId())
                                ->update([
                                    'timezone' => $data['timezone']
                                ]);
                        }
                        return false;
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                });
                return $result;
                break;
            case 'update_system_layout_color':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            if(Auth::user()->isAdmin()) {
                                DB::table('account')
                                    ->where('id', '=', ktUser::getAccountId())
                                    ->update($data);
                            }

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
            case 'remove_system_logo':
                $account = ktUser::getAccountData();
                if(!empty($account->system_logo)) {
                    $image_target = base_path() . '/accounts/' . ktUser::getAccountId() . '/' . $account->system_logo;
                    if (file_exists($image_target)) {
                        if (unlink($image_target)) {
                            $update_result = DB::table('account')
                                ->where('id', '=', $account->id)
                                ->update([
                                    'system_logo' => NULL,
                                ]);
                        }
                    }
                    return false;
                }
                return array(trans('application.report_issue_to_administrator'));
                break;
            case 'new_department':
                $result = DB::transaction(function () use ($data) {
                    try {
                        $account_id = self::getAccountId();
                        if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                            DB::table('user_departments')
                                ->insert([
                                    'account_id'    => self::getAccountId(),
                                    'name'          => $data['department_name']
                                ]);
                        }
                        return false;
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                });
                return $result;
                break;
            case 'update_department':
                $result = DB::transaction(function () use ($data) {
                    try {
                        if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                            DB::table('user_departments')
                                ->where('id', '=', $data['_department_id'])
                                ->where('account_id', '=', self::getAccountId())
                                ->update([
                                    'name' => $data['department_name']
                                ]);
                        }
                        return false;
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                });
                return $result;
                break;
            case 'delete_department':
                $result = DB::transaction(function () use ($data) {
                    try {

                        $result = DB::table('user_departments')->where([
                            'id'    => $data['_department_id'],
                            'account_id' => self::getAccountId()
                        ])->delete();

                        return false;

                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                });
                return $result;
                break;
            default:
                DB::transaction(function () use ($data) {
                    DB::table('users_extended')->where('user_id' , '=', Auth::id())->update($data);
                });
                break;
            }
            return false;
        }else{
            return $validator->messages()->all();
        }

    }

    public static function getAccountId(){
        if(Config::get('app.single')){
            $user = DB::table('users_extended')
                ->select('account_id')
                ->whereNotNull('account_id')
                ->first();
        }else {
            $user = DB::table('users_extended')
                ->select('account_id')
                ->where('user_id', '=', Auth::id())
                ->whereNotNull('account_id')
                ->first();
        }
        if (!$user) {
            throw new \Exception('Cant find the account_id.');
        }
        return $user->account_id;
    }

    public static function getUsers($list_only = false){
        try {
            $account_id = self::getAccountId();

            if(Auth::user()->isAdmin() && $list_only === false) {
                $users = DB::table('users_extended')
                    ->select('users_extended.first_name', 'users_extended.last_name', 'users.email', 'users_extended.active', 'users_extended.user_level', 'users.id', 'users_extended.department_id')
                    ->join('users', 'users.id', '=', 'users_extended.user_id')
                    ->where('users_extended.account_id', '=', $account_id)
                    ->where('users_extended.user_id', '<>', Auth::id())
                    ->whereIn('users_extended.user_level', ['USER', 'MANAGER', 'ADMIN'])
                    ->get();
            }else if(Auth::user()->canManage() && $list_only === false) {
                $users = DB::table('users_extended')
                    ->select('users_extended.first_name', 'users_extended.last_name', 'users.email', 'users_extended.active', 'users_extended.user_level', 'users.id', 'users_extended.department_id')
                    ->join('users', 'users.id', '=', 'users_extended.user_id')
                    ->where('users_extended.account_id', '=', $account_id)
                    ->where('users_extended.user_id', '<>', Auth::id())
                    ->where('users_extended.user_level', '=', 'USER')
                    ->get();
            }else if($list_only){
                $users = DB::table('users_extended')
                    ->select('users_extended.first_name', 'users_extended.last_name', 'users_extended.user_level', 'users.id', 'users_extended.department_id')
                    ->join('users', 'users.id', '=', 'users_extended.user_id')
                    ->where('users_extended.account_id', '=', $account_id)
                    //->where('users_extended.user_id', '<>', Auth::id())
                    ->whereIn('users_extended.user_level', ['USER', 'MANAGER', 'ADMIN'])
                    ->get();
            }
            return $users;
        }catch (\Exception $e) {
            return array('message' => 'Report this issue the to the local administrator ('.$e->getCode().')...');
        }
    }

    public static function getClientUsers(){
        try {
            $account_id = self::getAccountId();

            $users = DB::table('users_extended')
                ->select('users_extended.first_name', 'users_extended.last_name', 'users.email', 'users_extended.active', 'users_extended.user_level', 'users.id')
                ->join('users', 'users.id', '=', 'users_extended.user_id')
                ->where('users_extended.account_id', '=', $account_id)
                ->whereIn('users_extended.user_level', ['CLIENT'])
                ->get();

            return $users;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getUserData(){
        try {
            $user = DB::table('users_extended')
                ->select('users_extended.*', 'users.email')
                ->join('users', 'users.id', '=' , 'users_extended.user_id')
                ->where('users_extended.user_id', '=', Auth::id())
                ->first();
            return $user;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }


    public static function getAccountData(){
        try {
            $account = DB::table('account')
                ->where('id', '=', ktUser::getAccountId())
                ->first();
            return $account;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getAccountDataById($id){
        try {
            $account = DB::table('account')
                ->where('id', '=', $id)
                ->first();
            return $account;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getAccountTimeZone(){
        try {
            $account = self::getAccountData();
            if(empty($account->timezone)){
                return Config::get('app.timezone');
            }
            return $account->timezone;
        }catch (\Exception $e) {
            return Config::get('app.timezone');
        }
    }

    public static function getBoardTemplates(){
        try {
            $account_id = self::getAccountId();
            $board_templates = DB::table('board_templates')
                ->where('account_id', '=', $account_id)
                ->get();
            return $board_templates;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getUserFullName($user_id){
        try {
            $user = DB::table('users_extended')
                ->select('users_extended.*', 'users.email')
                ->join('users', 'users.id', '=' , 'users_extended.user_id')
                ->where('users_extended.user_id', '=', $user_id)
                ->first();
            if($user){
                return strtoupper($user->first_name . ' ' . $user->last_name);
            }
            return '';
        }catch (\Exception $e) {
            return 'Unable to resolve owner';
        }
    }

    public static function getSystemLogo(){
        try {
            $account = DB::table('account')
                ->select('system_logo')
                ->first();
            return ($account->system_logo != 'NULL')?$account->system_logo: false;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getSystemLabel(){
        try {
            $account = DB::table('account')
                ->select('system_label')
                ->first();
            if(empty($account->system_label)){
                return Config::get('app.name');
            }
            return $account->system_label;
        }catch (\Exception $e) {
            return Config::get('app.name');
        }
    }

    public static function getUserSystemLabel($id){
        try {
            $account = DB::table('account')
                ->select('system_label')
                ->where('id', $id)
                ->first();
            if(empty($account->system_label)){
                return Config::get('app.name');
            }
            return $account->system_label;
        }catch (\Exception $e) {
            return Config::get('app.name');
        }
    }

    public static function getExpenseCategories(){
        try {
            $account_id = self::getAccountId();
            $board_templates = DB::table('invoice_category')
                ->where('account_id', '=', $account_id)
                ->get();
            return $board_templates;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getDepartments(){
        try {
            $account_id = self::getAccountId();
            $departments = DB::table('user_departments')
                ->where('account_id', '=', $account_id)
                ->get();
            return $departments;
        }catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

}