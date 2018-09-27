<?php namespace App\Models;

use DB;
use League\Flysystem\Exception;
use Validator;
use Hash;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Mail;
use Log;

class ktNotify {

    static public function taskUpdateNotification($data, $task_id, $config){
        $usersToNotify = ktBoard::getOwnerAndManager($task_id);

        $user = ktUser::getUserData();
        $data['details'] = $user->first_name. ' ' . $user->last_name . ' ' . $data['details'];
        foreach ($usersToNotify as $user) {
            if(ktNotify::notificationState($user->user_id, $config) && is_object($user)){
                Mail::send('emails.notification', $data, function($message) use ($data, $user)
                {
                    $message->to($user->email, $user->first_name. ' ' . $user->last_name )->subject('AgileTeam Task Notification');
                });
            }
        }
    }

    static public function getNotificationsSettings(){
      	$settings = DB::table('module_params_map')
            ->select('module_params_map.param', 'users_config.value')
            ->leftJoin('users_config', function($join)
		        {
		            $join->on('users_config.config', '=', 'module_params_map.param')
		               	->where('users_config.user_id', '=',  Auth::id());

		        })  
            ->where('module_params_map.module', '=', 'NOTIFICATIONS')
            ->get();

        foreach ($settings as $pobject) {
        	$settings_map[$pobject->param] = $pobject->value;
        }

        return $settings_map;
    }

    static public function notificationState($user_id, $config){
        $user = ktUser::getUserData();

        if($user->user_id == $user_id) {
            return false;
        }

        $settings = DB::table('users_config')
            ->select('users_config.value')
            ->where('users_config.user_id', '=', $user_id)
            ->where('users_config.config', '=', $config)
            ->first();

        if(is_object($settings)){
            return (!empty($settings->value))? true : false;
        }

        return false;
    }

}