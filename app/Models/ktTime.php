<?php namespace App\Models;

use DB;
use League\Flysystem\Exception;
use Validator;
use Hash;
use Auth;
use Crypt;
use Illuminate\Database\Eloquent\Model;
use App\Models\ktUser;

class ktTime {

    public static  $rules = [
        'new_time_entry' => [
            'value'     => 'required',
            'time'      => ['required','regex:/^((0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$|^(([1-9]|0[1-9]|1[0-9]|2[0-3])\\.([0-9]?[0-9]))$|^[0-9][0-9]:[0-9][0-9]:[0-9][0-9]$/'],
            'date'      => 'date_format:d/m/Y',
            'comment'   => 'min:1',
        ],
        'start_stopwatch' => [
        ],
        'stop_stopwatch' => [
        ],
        'get_time_entry' => [
            'entry_id' => 'required|numeric'
        ],
        'update_time_entry' => [
            'entry_id'  => 'required|numeric',
            'comment'   => 'min:1',
            'value'     => 'required|required',
            'time'      => ['required','regex:/^((0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$|^(([1-9]|0[1-9]|1[0-9]|2[0-3])\\.([0-9]?[0-9]))$|^[0-9][0-9]:[0-9][0-9]:[0-9][0-9]$/'],
            'date'      => 'required|date_format:d/m/Y'
        ],
        'delete_entry' => [
            'entry_id' => 'required|numeric'
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
                case 'new_time_entry':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            if(strpos($data['time'], '.') !== false){
                                $data['time'] = self::decimal_to_time($data['time']);
                            }else if(substr_count($data['time'], ':') > 1){
                                list($hours, $minutes, $seconds) = explode(':', $data['time'], 3);
                                $data['time'] = $hours . ':' . $minutes . ':00';
                            }else{
                                $data['time'] = $data['time'] . ':00';
                            }

                            if(isset($data['date']) && !empty($data['date'])){
                                $tmp_date = date_parse_from_format('d/m/Y', $data['date']);
                                $data['date'] = $tmp_date['year'] . '-' . $tmp_date['month'] . '-' . $tmp_date['day'] . date(" H:i:s");
                            }else{
                                date_default_timezone_set(ktUser::getAccountTimeZone());
                                $data['date'] = date("Y-m-d H:i:s");
                            }

                            $data['account_id'] = ktUser::getAccountId();
                            $data['user_id']    = Auth::id();      
                            DB::table('time_users')->insert($data);

                            $update = DB::table('users_extended')
                                ->where('user_id', '=', Auth::id())
                                ->update([
                                    'stopwatch_start' => NULL
                                ]);
                            $data['id'] = DB::getPdo()->lastInsertId();

                            return $data;
                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return [
                        'status' => 'ok',
                        'entry'  => $data
                    ];
                    break;
                case 'start_stopwatch':
                    date_default_timezone_set(ktUser::getAccountTimeZone());
                    $result = DB::transaction(function () use ($data) {
                        try {  
                            $update = DB::table('users_extended')
                                ->where('user_id', '=', Auth::id())
                                ->update([
                                    'stopwatch_start' => date("Y-m-d H:i:s")
                                ]);
                            return $update;
                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return [
                        'status' => 'ok',
                        'start'  => $result
                    ];
                    break;
                case 'stop_stopwatch':
                    date_default_timezone_set(ktUser::getAccountTimeZone());
                    $result = DB::transaction(function () use ($data) {
                        try {  
                            $update = DB::table('users_extended')
                                ->where('user_id', '=', Auth::id())
                                ->update([
                                    'stopwatch_start' => NULL
                                ]);
                            return $update;
                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return [
                        'status' => 'ok',
                        'stop'  => $result
                    ];
                    break;
                case 'get_time_entry':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $time = DB::table('time_users')
                                ->where('id', '=', $data['entry_id'])
                                ->where('user_id', '=', Auth::id())                                
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->first();

                            $time->date = date("d/m/Y", strtotime($time->date));

                            return $time;
                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });       
                    if(is_object($result)){
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
                case 'update_time_entry':
                    try{

                        if(strpos($data['time'], '.') !== false){
                            $data['time'] = self::decimal_to_time($data['time']);
                        }else if(substr_count($data['time'], ':') > 1){
                            list($hours, $minutes, $seconds) = explode(':', $data['time'], 3);
                            $data['time'] = $hours . ':' . $minutes . ':00';
                        }else{
                            $data['time'] = $data['time'] . ':00';
                        }

                        if(isset($data['date']) && !empty($data['date'])){
                            $tmp_date = date_parse_from_format('d/m/Y', $data['date']);
                            $data['date'] = $tmp_date['year'] . '-' . $tmp_date['month'] . '-' . $tmp_date['day'] . date(" H:i:s");
                        }else{
                            date_default_timezone_set(ktUser::getAccountTimeZone());
                            $data['date'] = date("Y-m-d H:i:s");
                        }
                        $update_result = DB::table('time_users')
                            ->where('id', '=', $data['entry_id'])
                            ->where('account_id', '=', ktUser::getAccountId())                            
                            ->where('user_id', '=', Auth::id())                            
                            ->update([
                                'value' => $data['value'],
                                'comment' => $data['comment'],
                                'time' => $data['time'],
                                'date' => $data['date']
                            ]);
                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'delete_entry':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $result = DB::table('time_users')->where([
                                'id'    => $data['entry_id'],
                                'user_id'   => Auth::id(),
                                'account_id' => ktUser::getAccountId()
                            ])->delete();

                            return false;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator').$e->getMessage());
                        }
                    });

                    return [
                        'status' => 'ok',
                        'result' => $result
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

    static function getLastSevenDays(){
        date_default_timezone_set(ktUser::getAccountTimeZone());
        $entries = DB::select("SELECT tu.id, tu.comment, tu.user_id, tu.value, tu.time, DATE_FORMAT(tu.date, '%d.%b.%y') as date, ue.*, p.project_name FROM time_users as tu LEFT JOIN users_extended as ue ON tu.user_id = ue.user_id AND tu.account_id = ".ktUser::getAccountId()."
            LEFT JOIN projects as p ON p.id = tu.value AND p.account_id = ".ktUser::getAccountId()."
            WHERE tu.date BETWEEN DATE(NOW()) - INTERVAL 7 DAY AND DATE(NOW()) + INTERVAL 1 YEAR
            ORDER BY tu.date DESC"
        );
        return $entries;
    }

    static function getTimeRange($request){
        date_default_timezone_set(ktUser::getAccountTimeZone());

        $user = $request->input('user');
        $filter = $request->input('project');
        $date_range = $request->input('dater');

        if(!empty($date_range)){
            list($from,$to) = explode('-', $date_range);

            $tmp_from_date = date_parse_from_format('d/m/Y', $from);
            $from = $tmp_from_date['year'] . '-' . $tmp_from_date['month'] . '-' . $tmp_from_date['day'];

            $tmp_to_date = date_parse_from_format('d/m/Y', $to);
            $to = $tmp_to_date['year'] . '-' . $tmp_to_date['month'] . '-' . $tmp_to_date['day'];

            $date1 = \DateTime::createFromFormat('Y-m-d', $from);
            $date2 = \DateTime::createFromFormat('Y-m-d', $to);
            $interval = $date1->diff($date2);
            $diff = $interval->format('%m');

            if($diff > 6){
                //Find a way to infrom the user that we do not allow a query of 6 months range...
                return array();
            }

        } else {
            return array();
        }

        if(!empty($user)){
            $user = "tu.user_id = $user AND ue.user_id = $user AND";
        }else{
            $user = 'tu.user_id = ue.user_id AND';
        }

        if(!empty($filter)){
            $filter = "AND tu.value = '$filter'";
        }else{
            $filter = '';
        }

        $entries = DB::select("SELECT tu.id, tu.comment, tu.user_id, tu.value, tu.time, DATE_FORMAT(tu.date, '%d.%b.%y') as date, ue.*, p.project_name FROM time_users as tu JOIN users_extended as ue ON $user tu.account_id = ".ktUser::getAccountId()." $filter
            LEFT JOIN projects as p ON p.id = tu.value AND p.account_id = ".ktUser::getAccountId()."
            WHERE tu.date BETWEEN '$from' AND '$to' + INTERVAL 1 DAY
            ORDER BY tu.date DESC"
        );

        return $entries;
    }

    static function getStopWatchState(){
        $state = DB::select("SELECT TIME_FORMAT(TIMEDIFF(UTC_TIMESTAMP(), stopwatch_start),'%H') as hours, TIME_FORMAT(TIMEDIFF(UTC_TIMESTAMP(), stopwatch_start),'%i') as mins, TIME_FORMAT(TIMEDIFF(UTC_TIMESTAMP(), stopwatch_start),'%s') as secs  FROM users_extended WHERE user_id = ".Auth::id()." AND account_id = ".ktUser::getAccountId());
        if(isset($state[0])){
            return $state[0];
        }
        return false;
    }

    static function getUserTotalTime(){
        date_default_timezone_set(ktUser::getAccountTimeZone());
        $user_time = DB::select("SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(time))),'%H:%i') as time FROM time_users
            WHERE user_id = ".Auth::id());
        return $user_time[0];
    }

    static function getAccounTotalTime(){
        date_default_timezone_set(ktUser::getAccountTimeZone());
        $user_time = DB::select("SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(time))),'%H:%i') as time FROM time_users
            WHERE account_id = ".ktUser::getAccountId());
        return $user_time[0];
    }

    static public function time_to_decimal($time) {
      $decTime = 0;
      if (preg_match("/^([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $time, $matches)) {
         $decTime = ($matches[1]*60) + ($matches[2]) + ($matches[3]/60);
      }   
      return $decTime;
    }

    static public function decimal_to_time($decimal) {
        $hours = floor((int)$decimal / 60);
        $minutes = floor((int)$decimal % 60);
        $seconds = $decimal - (int)$decimal; 
        $seconds = round($seconds * 60); 
     
        //return str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT);
        return str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":" . str_pad($seconds, 2, "0", STR_PAD_LEFT) . ":00";        
    }

}