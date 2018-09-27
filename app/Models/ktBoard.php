<?php namespace App\Models;

define('DEFAULT_COLUMN', 1);

use DB;
use League\Flysystem\Exception;
use Validator;
use Hash;
use Auth;
use Crypt;
use App\Models\ktUser;
use Illuminate\Database\Eloquent\Model;
use App\Models\ktNotify;
use Config;

class ktBoard {

    public static  $rules = [
        'new_board' => [
            'name'            => 'required|min:3',
            'description'     => 'required|min:3',
            'template'        => 'required|numeric',
            'columns'         => 'required_if:template,0|min:3',
            'department_id'   => 'numeric',
            'date'            => ['regex:/\d{2}\/\d{2}\/\d{4}([ ]*)-([ ]*)\d{2}\/\d{2}\/\d{4}/']
        ],
        'new_child_board' => [
            'board_id'        => 'required|numeric',
            'department_id'   => 'required|numeric',
            'template'        => 'required|numeric',
            'columns'         => 'required_if:template,0|min:3',
        ],
        'edit_board' => [
            'board_id'        => 'required|numeric',
            'name'            => 'required|min:3',
            'description'     => 'required|min:3',
            'department_id'   => 'numeric',
            'columns'         => 'min:5',
            'date'            => ['regex:/\d{2}\/\d{2}\/\d{4}([ ]*)-([ ]*)\d{2}\/\d{2}\/\d{4}/']
        ],
        'new_task'  => [
            'hash'            => 'min:3',
            'subject'         => 'required|min:3',
            'description'     => 'required|min:3',
            'sizey'           => 'numeric',
            'estimate'        => ['regex:/([0-9]+y)?([0-9]+mo)?([0-9]+w)?([0-9]+d)?([0-9]+h)?([0-9]+m)?/'],
            'priority'        => 'numeric',
            'type'            => 'numeric',
            'user_id'         => 'numeric',
            'project_id'      => 'required|numeric',
            'board_id'        => 'min:3'
        ],
        'publish' => [
            'hash' => 'required'
        ],
        'unpublish' => [
            'hash' => 'required'
        ],
        'lock' => [
            'hash' => 'required'
        ],
        'default' => [
            'hash' => 'required'
        ],
        'undefault' => [
            'hash' => 'required'
        ],
        'unlock' => [
            'hash' => 'required'
        ],
        'state_update' => [
            'hash' => 'required',
            'ids'   => 'required|array'
        ],
        'get_task'   => [
            'task_id'   => 'required|numeric',
            'board_id'  => 'numeric'
        ],
        'subject_update' => [
            'task_id'   => 'required|numeric',
            'subject'   => 'required|min:3',
        ],
        'desc_update' => [
            'task_id'       => 'required|numeric',
            'description'   => 'required',
        ],
        'type_update' => [
            'task_id'       => 'required|numeric',
            'type'          => 'required'
        ],
        'project_update' => [
            'task_id'       => 'required|numeric',
            'project_id'    => 'required'
        ],
        'priority_update' => [
            'task_id'       => 'required|numeric',
            'priority'      => 'required'
        ],
        'manager_update' => [
            'task_id'       => 'required|numeric',
            'manager_id'    => 'required|numeric'
        ],
        'assignee_update' => [
            'task_id'       => 'required|numeric',
            'user_id'       => 'required|numeric',
            'hash'          => 'required'
        ],
        'estimate_update' => [
            'task_id'       => 'required|numeric',
            'estimate'      => ['required', 'regex:/([0-9]+y)?([0-9]+mo)?([0-9]+w)?([0-9]+d)?([0-9]+h)?([0-9]+m)?/']
        ],
        'board_update' => [
            'task_id'       => 'required|numeric',
            'board_hash'    => 'required'
        ],
        'completed_task' => [
            'task_id'       => 'required|numeric',
            'board_hash'    => 'required',
            'completed'     => 'required|in:0,1'
        ],
        'comment' => [
            'task_id'       => 'required|numeric',
            'comment'       => 'required|min:2'
        ],
        'avatar' => [

        ],
        'get_avatar' => [
            'image' => 'required'
        ],
        'get_system_logo' => [
            'image' => 'required'
        ],
        'invoice_logo' => [

        ],
        'system_logo' => [

        ],
        'column_rename' => [
            "hash" =>'required',
            "column" => 'required|min:1',
            "position" => 'required|numeric'
        ],
        'new_requirement' => [
            "project_id" =>'required|numeric',
            "subject" => 'required|min:1',
            "details" => 'required|min:1'
        ],
        'delete_board' => [
            'hash' => 'required'
        ],
        'delete_task' => [
            "task_id" =>'required|numeric',
        ],
        'delete_requirement' => [
            "requirement_id" =>'required|numeric',
        ]
    ];

    public static $rules_messages = [

    ];

    public static  $priority = [
        900 => 'low',    //gray
        800 => 'normal', //info
        700 => 'medium', //success
        600 => 'high',   //warning
        500 => 'urgent'  //danger
    ];

    public static  $priority_colors = [
        900 => '',
        800 => 'box-info', //info
        700 => 'box-success', //success
        600 => 'box-warning',   //warning
        500 => 'box-danger'  //danger
    ];

    public static  $task_type = [
        900 => 'feature',
        800 => 'fix',
        700 => 'research',
        600 => 'analysis'
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
                case 'new_board':

                    if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                        $result = DB::transaction(function () use ($data) {
                            try {

                                if($data['template'] == 0){
                                    $columns = $data['columns'];
                                }else {

                                    if ($template = self::getBoardTemplate($data['template'])) {
                                        $columns = $template->columns;
                                    }else{
                                        throw new \PDOException(trans('application.report_issue_to_administrator'));
                                    }
                                }

                                $public_hash = md5( ktUser::getAccountId() . '.' .Auth::id() . date("Y-m-d H:i:s:u") );

                                if(isset($data['date']) && !empty($data['date'])){
                                    list($from_date, $to_date) = explode("-", $data['date']);

                                    $dpf_date = date_parse_from_format('d/m/Y', trim($from_date));
                                    $from_date = $dpf_date['year'] . '-' . $dpf_date['month'] . '-' . $dpf_date['day'];

                                    $dpt_date = date_parse_from_format('d/m/Y', trim($to_date));
                                    $to_date = $dpt_date['year'] . '-' . $dpt_date['month'] . '-' . $dpt_date['day'];
                                }

                                DB::table('boards')->insert([
                                    'account_id'    => ktUser::getAccountId(),
                                    'name'          => $data['name'],
                                    'public_hash'   => $public_hash,
                                    'description'   => $data['description'],
                                    'columns'       => $columns,
                                    'department_id' => ($data['department_id'] != 0)?$data['department_id']:NULL,
                                    'start_date'    => @$from_date,
                                    'end_date'      => @$to_date
                                ]);

                                $board_id = DB::getPdo()->lastInsertId();

                                return [
                                    'status'    => 'ok',
                                    'response'  => [
                                        'board' => [
                                            'id' => $public_hash
                                        ]
                                    ]
                                ];
                            }catch (\PDOException $e) {
                                return array(trans('application.report_issue_to_administrator'));
                            }
                        });
                    }else{
                        $result = array(
                            'status' => 'error',
                            'message' => trans('application.unauthorized')
                        );
                    }
                    return $result;
                    break;
                case 'new_child_board':

                    if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                        $result = DB::transaction(function () use ($data) {
                            try {

                                if($data['template'] == 0){
                                    $columns = $data['columns'];
                                }else {

                                    if ($template = self::getBoardTemplate($data['template'])) {
                                        $columns = $template->columns;
                                    }else{
                                        throw new \PDOException(trans('application.report_issue_to_administrator'));
                                    }
                                }

                                $public_hash = md5( ktUser::getAccountId() . '.' .Auth::id() . date("Y-m-d H:i:s:u") );

                                $parentBoard = self::getGrandBoardData($data['board_id']);

                                DB::table('boards')->insert([
                                    'name' => $parentBoard->name,
                                    'default' => $parentBoard->default,
                                    'description' => $parentBoard->description,
                                    'start_date' => $parentBoard->start_date,
                                    'end_date' => $parentBoard->end_date,
                                    'account_id'    => ktUser::getAccountId(),
                                    'parent_board_id' => $data['board_id'],
                                    'public_hash' => $public_hash,
                                    'department_id' => $data['department_id'],
                                    'columns' => $columns,
                                ]);

                                $board_id = DB::getPdo()->lastInsertId();

                                return [
                                    'status'    => 'ok',
                                    'response'  => [
                                        'board' => [
                                            'id' => $public_hash
                                        ]
                                    ]
                                ];
                            }catch (\PDOException $e) {
                                return array(trans('application.report_issue_to_administrator').$e->getMessage());
                            }
                        });
                    }else{
                        $result = array(
                            'status' => 'error',
                            'message' => trans('application.unauthorized')
                        );
                    }
                    return $result;
                    break;
                case 'edit_board':
                    try{
                        $newColumsNumber = count(json_decode($data['columns']));
                        $minNewColumns = DB::table('task_positions')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('board_id', '=', $data['board_id'])
                            ->max('size_y');

                        if($newColumsNumber >= $minNewColumns){                        
                            if(isset($data['date']) && !empty($data['date'])){
                                list($from_date, $to_date) = explode("-", $data['date']);

                                $dpf_date = date_parse_from_format('d/m/Y', trim($from_date));
                                $from_date = $dpf_date['year'] . '-' . $dpf_date['month'] . '-' . $dpf_date['day'];

                                $dpt_date = date_parse_from_format('d/m/Y', trim($to_date));
                                $to_date = $dpt_date['year'] . '-' . $dpt_date['month'] . '-' . $dpt_date['day'];
                            }

                            $update_result = DB::table('boards')
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->where('id', '=', $data['board_id'])
                                ->update([
                                    'name' => $data['name'],
                                    'description' => $data['description'],
                                    'start_date' => $from_date,
                                    'department_id' => ($data['department_id'] != 0)?$data['department_id']:NULL,
                                    'end_date' => $to_date,
                                    'columns' => $data['columns']
                                ]);
                            }else{
                                return [
                                    'status' => 'error',
                                    'message' => [
                                        ' The number of columns is below a minimum ('.$minNewColumns.') for this board.',
                                        ' Make sure when you change the number of colums that you don\'t loose any tasks with actual states.'
                                    ]
                                ];       
                            }
                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];

                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'new_task':

                    $result = DB::transaction(function () use ($data) {
                        try {
                            if($data['hash'] != 'NULL'){
                                if(empty($data['board_id']) !== false && $board = self::getBoardData($data['hash'])) {
                                    //Nothing should happen...
                                }else if(empty($data['board_id']) === false && $board = self::getBoardData($data['board_id'])){
                                    //Nothing should happen...
                                }else{
                                    throw new \PDOException(trans('application.report_issue_to_administrator'));
                                }
                                $board_id = $board->id;
                            }else{
                                $board_id = NULL;
                            }

                            if(empty($data['user_id'])){
                                $user_id = NULL;
                            }else{
                                $user_id = $data['user_id'];
                            }

                            $account_id = ktUser::getAccountId();

                            DB::table('tasks')->insert([
                                'account_id'    => $account_id,
                                'manager_id'    => Auth::id(),
                                'project_id'    => @$data['project_id'],                                
                                'subject'       => $data['subject'],
                                'description'   => $data['description'],
                                'estimate'      => $data['estimate'],
                                'priority'      => @$data['priority'],
                                'type'          => @$data['type']
                            ]);

                            $task_id = DB::getPdo()->lastInsertId();

                            if(!empty($data['board_id'])) {
                                $user_id = (!empty($data['user_id']))?$data['user_id']:NULL;

                                $size_y = 2;
                                if(empty($board->parent_board_id)){
                                    $size_y = 1;
                                }

                                DB::table('task_positions')->insert([
                                    'account_id' => $account_id,
                                    'board_id' => $board_id,
                                    'user_id' => $user_id,
                                    'task_id' => $task_id,
                                    'size_y' => $size_y,
                                    'size_x' => 1,
                                ]);
                            }

                            return [
                                'status'    => 'ok',
                                'response'  => [
                                    'board' => [
                                        'id' => $data['hash']
                                    ],
                                    'task'  => [
                                        'id' => $task_id
                                    ]
                                ]
                            ];
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator').$e->getMessage());
                        }
                    });

                    return $result;
                    break;
                case 'unpublish':
                case 'publish':

                     if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                        $result = DB::transaction(function () use ($data, $event) {
                            try {

                                if($board = self::getBoardData($data['hash'])){
                                    //Nothing should happen...
                                }else{
                                    throw new \PDOException('Something is not right!');
                                }

                                $public_board = ($event == 'publish')
                                    ?1
                                    :0;

                                DB::table('boards')
                                    ->where('id', '=', $board->id)
                                    ->where('account_id', '=', ktUser::getAccountId())
                                    ->update([
                                        'public' => $public_board
                                    ]);

                                return [
                                    'status'    => 'ok',
                                ];
                            }catch (\PDOException $e) {
                                return array(trans('application.report_issue_to_administrator'));
                            }
                        });
                     }else{
                         $result = array(
                             'status' => 'error',
                             'message' => trans('application.unauthorized')
                         );
                     }
                    return $result;
                    break;
                case 'unlock':
                case 'lock':

                    if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                        $result = DB::transaction(function () use ($data, $event) {
                            try {

                                if ($board = self::getBoardData($data['hash'])) {
                                    //Nothing should happen...
                                } else {
                                    throw new \PDOException('Something is not right!');
                                }

                                $lock_board = ($event == 'lock')
                                    ? 1
                                    : 0;

                                self::provisionBoardUpdate(
                                    $board->id, [
                                    'lock' => $lock_board
                                ]);

                                return [
                                    'status' => 'ok',
                                ];
                            } catch (\PDOException $e) {
                                return array(trans('application.report_issue_to_administrator'));
                            }
                        });
                    }else{
                        $result = array(
                          'status' => 'error',
                          'message' => trans('application.unauthorized')
                        );
                    }

                    return $result;
                    break;
                case 'default':
                case 'undefault':

                    if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                        $result = DB::transaction(function () use ($data, $event) {
                            try {

                                if ($board = self::getBoardData($data['hash'])) {
                                    //Nothing should happen...
                                } else {
                                    throw new \PDOException('Something is not right!');
                                }

                                $default_board = ($event == 'default')
                                    ? 1
                                    : 0;

                                DB::table('boards')
                                    ->where('account_id', '=', ktUser::getAccountId())
                                    ->where('id', '=', $board->id)
                                    ->update([
                                        'default' => 0
                                    ]);

                                self::provisionBoardUpdate(
                                    $board->id, [
                                    'default' => $default_board
                                ]);

                                return [
                                    'status' => 'ok',
                                ];
                            } catch (\PDOException $e) {
                                return array(trans('application.report_issue_to_administrator'));
                            }
                        });
                    }else{
                        $result = array(
                            'status' => 'error',
                            'message' => trans('application.unauthorized')
                        );
                    }

                    return $result;
                    break;
                case 'state_update':

                    $result = DB::transaction(function () use ($data, $event) {
                        try {

                            if($board = self::getBoardData($data['hash'])){
                                //Nothing should happen...
                            }else{
                                throw new \PDOException('Something is not right!');
                            }
                            $task_new_col = 2;
                            if($board->lock == 0) {
                                if (Auth::user()->isAdmin() || Auth::user()->canManage()) {
                                    foreach ($data['ids'] as $id) {
                                        $update = DB::table('task_positions')
                                            ->where('task_id', '=', $id['task']['id'])
                                            ->where('account_id', '=', ktUser::getAccountId())
                                            ->where('size_y', '<>', $id['task']['col'])
                                            ->where('board_id', '=', $board->id)
                                            ->update([
                                                'size_y' => $id['task']['col']
                                            ]);
                                        if($update){
                                            $task_id = $id['task']['id'];
                                            $task_new_col = $id['task']['col'];
                                        }
                                    }
                                } else {
                                    foreach ($data['ids'] as $id) {
                                        $update = DB::table('task_positions')
                                            ->where('task_id', '=', $id['task']['id'])
                                            ->where('account_id', '=', ktUser::getAccountId())
                                            ->where('user_id', '=', Auth::id())
                                            ->where('size_y', '<>', $id['task']['col'])
                                            ->where('board_id', '=', $board->id)
                                            ->update([
                                                'size_y' => $id['task']['col']
                                            ]);
                                        if($update){
                                            $task_id = $id['task']['id'];
                                            $task_new_col = $id['task']['col'];
                                        }
                                    }
                                }
                            }else{
                                return [
                                    'status'    => 'error',
                                    'message'  => trans('application.board_locked')
                                ];
                            }

                            $user = ktUser::getUserData();
                            $columns = json_decode($board->columns);
                            if(isset($columns[$task_new_col - 2])){
                                $new_state = strtoupper($columns[$task_new_col - 2]);
                            }else{
                                $new_state = 'UNSORTED';
                            }

                            ktNotify::taskUpdateNotification([
                                    'subject'   => 'Notification Alert for Task #'.$task_id,
                                    'details'   => 'has changed the State',
                                    'changes'   => $new_state
                                ], 
                                $task_id,
                                'NOTE_STATE_UPDATE'
                            );

                            return [
                                'status'    => 'ok',
                            ];
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    return $result;
                    break;
                case 'get_task':
                    try {

                        $board_id = (isset($data['board_id']))?$data['board_id']:NULL;

                        if(empty($board_id)) {
                            $task = DB::table('tasks')
                                ->select('tasks.*', 'users_extended.last_name', 'users_extended.first_name', 'boards.public_hash', 'task_positions.user_id', 'task_positions.size_y')
                                ->leftJoin('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                                ->leftJoin('users_extended', 'tasks.manager_id', '=', 'users_extended.user_id')
                                ->leftJoin('boards', 'boards.id', '=', 'task_positions.board_id')
                                ->where('tasks.id', '=', $data['task_id'])
                                ->where('tasks.account_id', '=', ktUser::getAccountId())
                                ->orderBy('tasks.priority', 'asc')
                                ->orderBy('task_positions.user_id', 'asc')
                                ->first();
                        }else{
                            $task = DB::table('tasks')
                                ->select('tasks.*', 'users_extended.last_name', 'users_extended.first_name', 'boards.public_hash', 'task_positions.user_id', 'task_positions.size_y')
                                ->leftJoin('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                                ->leftJoin('users_extended', 'tasks.manager_id', '=', 'users_extended.user_id')
                                ->leftJoin('boards', 'boards.id', '=', 'task_positions.board_id')
                                ->where('tasks.id', '=', $data['task_id'])
                                ->where('tasks.account_id', '=', ktUser::getAccountId())
                                ->where('task_positions.board_id', '=', $board_id)
                                ->orderBy('tasks.priority', 'asc')
                                ->orderBy('task_positions.user_id', 'asc')
                                ->first();
                        }
                        $comments = DB::select("SELECT task_comments.id, task_comments.user_id, task_comments.comment, DATE_FORMAT(task_comments.comment_date, '%d %b %y %H:%i') as comment_date,
                            CONCAT_WS(' ', users_extended.first_name, SUBSTR(users_extended.last_name,1,1)) as author, users_extended.avatar
                            FROM  task_comments JOIN users_extended ON task_comments.user_id = users_extended.user_id
                            WHERE task_comments.account_id = ".ktUser::getAccountId()." AND task_comments.task_id = ".$data['task_id']."
                            ORDER BY task_comments.comment_date ASC");

                        return [
                            'status' => 'ok',
                            'response' => [
                                'task' => $task,
                                'comments' => $comments
                            ]
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'subject_update':
                    try{
                        $update_result = DB::table('tasks')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['task_id'])
                            ->update([
                                'subject' => $data['subject']
                            ]);

                        $user = ktUser::getUserData();

                        ktNotify::taskUpdateNotification([
                                'subject'   => 'Notification Alert for Task #'.$data['task_id'],
                                'details'   => 'has changed the Subject',
                                'changes'   => $data['subject']
                            ], 
                            $data['task_id'],
                            'NOTE_SUBJECT_UPDATE'
                        );

                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'desc_update':
                    try{
                        $update_result = DB::table('tasks')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['task_id'])
                            ->update([
                                'description' => $data['description']
                            ]);

                        ktNotify::taskUpdateNotification([
                                'subject'   => 'Notification Alert for Task #'.$data['task_id'],
                                'details'   => 'has changed the Description',
                                'changes'   => substr(strip_tags($data['description']), 0 ,255) . '...'
                            ], 
                            $data['task_id'],
                            'NOTE_DESCIPRITON_UPDATE'
                        );

                        return [
                            'status' => 'ok',
                            'result' => $update_result,
                            'raw_text' => strip_tags(@$data['description'])
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'type_update':
                    try{
                        $update_result = DB::table('tasks')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['task_id'])
                            ->update([
                                'type' => $data['type']
                            ]);

                        ktNotify::taskUpdateNotification([
                                'subject'   => 'Notification Alert for Task #'.$data['task_id'],
                                'details'   => 'has made some changes',
                                'changes'   => 'Task Type set to: ' . self::getTaskType($data['type'], 'Unknown')
                            ],
                            $data['task_id'],
                            'NOTE_TYPE_UPDATE'
                        );

                        return [
                            'status' => 'ok',
                            'result' => $update_result,
                            'new_type' => self::getTaskType($data['type'], 'Unknown')
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'project_update':
                    try{
                        $update_result = DB::table('tasks')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['task_id'])
                            ->update([
                                'project_id' => $data['project_id']
                            ]);


                        $project = ktProject::getProjectData($data['project_id']);

                        ktNotify::taskUpdateNotification([
                                'subject'   => 'Notification Alert for Task #'.$data['task_id'],
                                'details'   => 'has made some changes',
                                'changes'   => 'Related the Task to Project: ' . $project->project_name
                            ], 
                            $data['task_id'],
                            'NOTE_PROJECT_UPDATE'
                        );

                        return [
                            'status' => 'ok',
                            'result' => $update_result,
                            'new_type' => 'test'
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'priority_update':
                    try{
                        $update_result = DB::table('tasks')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['task_id'])
                            ->update([
                                'priority' => $data['priority']
                            ]);

                        $new_priority_label = ucfirst(self::getPriorityLabel($data['priority']));

                        ktNotify::taskUpdateNotification([
                                'subject'   => 'Notification Alert for Task #'.$data['task_id'],
                                'details'   => 'has made some changes',
                                'changes'   => 'New task Priority: '. $new_priority_label
                            ], 
                            $data['task_id'],
                            'NOTE_PRIORITY_UPDATE'
                        );

                        return [
                            'status' => 'ok',
                            'result' => $update_result,
                            'new_priority' => $new_priority_label
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'manager_update':
                    try{
                        $update_result = DB::table('tasks')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['task_id'])
                            ->update([
                                'manager_id' => $data['manager_id']
                            ]);

                        $new_manager = ktUser::getUserFullName($data['manager_id']);

                        ktNotify::taskUpdateNotification([
                                'subject'   => 'Notification Alert for Task #'.$data['task_id'],
                                'details'   => 'has changed the Manager to',
                                'changes'   => $new_manager
                            ], 
                            $data['task_id'],
                            'NOTE_MANAGER_UPDATE'
                        );

                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'assignee_update':
                    try{

                        if($board = self::getBoardData($data['hash'])){
                            //Nothing should happen...
                        }else{
                            throw new \PDOException('Something is not right!');
                        }

                        $update_result = DB::table('task_positions')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('task_id', '=', $data['task_id'])
                            ->where('board_id', '=', $board->id)
                            ->update([
                                'user_id' => $data['user_id']
                            ]);

                        $new_assigned = ktUser::getUserFullName($data['user_id']);
                          
                        ktNotify::taskUpdateNotification([
                                'subject'   => 'Notification Alert for Task #'.$data['task_id'],
                                'details'   => 'has changed the Assigne to',
                                'changes'   => $new_assigned
                            ], 
                            $data['task_id'],
                            'NOTE_OWNER_UPDATE'
                        );

                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'estimate_update':
                    try{
                        $update_result = DB::table('tasks')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['task_id'])
                            ->update([
                                'estimate' => $data['estimate']
                            ]);

                        ktNotify::taskUpdateNotification([
                                'subject'   => 'Notification Alert for Task #'.$data['task_id'],
                                'details'   => 'has made some changes',
                                'changes'   => 'Changed the Estimated Delivery Time: ' . $data['estimate']
                            ], 
                            $data['task_id'],
                            'NOTE_ESTIMATE_UPDATE'
                        );

                        return [
                            'status' => 'ok',
                            'result' => $update_result,
                            'new_estimate' => $data['estimate']
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'board_update':
                    try{
                        if($data['board_hash'] != 'NULL'){
                            if($board = self::getBoardData($data['board_hash'])){
                                $board_id = $board->id;
                                //Nothing should happen...
                            }else{
                                throw new \PDOException('Something is not right!');
                            }
                        }else{
                            $board_id = NULL;
                        }

                        $account_id = ktUser::getAccountId();

                        $task = DB::table('task_positions')
                            ->where('task_id', '=', $data['task_id'])
                            ->where('board_id', '=', $board->id)
                            ->where('account_id', '=', $account_id)
                            ->first();

                        $size_y = 2;
                        if(empty($board->parent_board_id)){
                            $size_y = 1;
                        }

                        if(!is_object($task)){
                            $result = DB::table('task_positions')->insert([
                                'account_id' =>  $account_id,
                                'task_id' => $data['task_id'],
                                'board_id' => $board->id,
                                'size_x'   => DEFAULT_COLUMN,
                                'size_y'   => $size_y,
                                'visible' => 1
                            ]);
                        }else{
                            $result = DB::table('task_positions')
                                ->where('account_id', '=', $account_id)
                                ->where('task_id', '=', $data['task_id'])
                                ->where('board_id', '=', $board_id)
                                ->update([
                                    'size_y'   => $size_y,
                                    'visible' => 1
                                ]);
                        }

                        return [
                            'status' => 'ok',
                            'result' => $result
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'completed_task':
                    try{
                        if($data['board_hash'] != 'NULL'){
                            if($board = self::getBoardData($data['board_hash'])){
                                $board_id = $board->id;
                                //Nothing should happen...
                            }else{
                                throw new \PDOException('Something is not right!');
                            }
                        }else{
                            $board_id = NULL;
                        }
                        $account_id = ktUser::getAccountId();

                        $task = DB::table('task_positions')
                            ->where('task_id', '=', $data['task_id'])
                            ->where('board_id', '=', $board->id)
                            ->where('account_id', '=', $account_id)
                            ->first();

                        $shift = (is_object($board->parent_board))?1:0;

                        if(!is_object($task)){
                            $result = DB::table('task_positions')->insert([
                                'account_id' =>  $account_id,
                                'task_id' => $data['task_id'],
                                'board_id' => $board->id,
                                'size_x'   => DEFAULT_COLUMN,
                                'size_y'   => DEFAULT_COLUMN + $shift,
                                'visible' => 1
                            ]);
                        }else{
                            $result = DB::table('task_positions')
                                ->where('account_id', '=', $account_id)
                                ->where('task_id', '=', $data['task_id'])
                                ->where('board_id', '=', $board->id)
                                ->update([
                                    'size_y'   => DEFAULT_COLUMN + $shift,
                                    'visible' => 1
                                ]);
                        }

                        return [
                            'status' => 'ok',
                            'result' => $result
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'comment':
                    $date = date("Y-m-d H:i:s");
                    $display_date = date("d M y H:i");
                    $result = DB::transaction(function () use ($data, $date, $display_date) {
                        try {
                            DB::table('task_comments')->insert([
                                'task_id'       => $data['task_id'],
                                'account_id'    => ktUser::getAccountId(),
                                'user_id'       => Auth::id(),
                                'comment'       => htmlspecialchars($data['comment']),
                                'comment_date'  => $date
                            ]);

                            $comment_id = DB::getPdo()->lastInsertId();

                            $author = self::getCommentAuthor($comment_id);

                            ktNotify::taskUpdateNotification([
                                    'subject'   => 'Notification Alert for Task #'.$data['task_id'],
                                    'details'   => 'has left a new comment',
                                    'changes'   => htmlspecialchars($data['comment'])
                                ], 
                                $data['task_id'],
                                'NOTE_COMMENT_UPDATE'
                            );

                            return [
                                'status'    => 'ok',
                                'response'  => [
                                    'id' => $comment_id,
                                    'comment' => htmlspecialchars($data['comment']),
                                    'date' => $display_date,
                                    'author' => ucfirst(strtolower($author->first_name)) . ' ' . ucfirst(substr($author->last_name, 0, 1)),
                                    'avatar' => $author->avatar
                                ]
                            ];
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return $result;
                    break;
                case 'avatar':

                    if(!Config::get('app.features.fileupload')){
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.file_upload_disabled')
                        ];
                    }

                    $target_dir = base_path(). '/accounts/'.ktUser::getAccountId();

                    if(!file_exists($target_dir)){
                        if(!mkdir($target_dir)){
                            return [
                                'status'    => 'error',
                                'message'   => trans('application.server_side_issue')
                            ];
                        }
                    }

                    $target_dir = $target_dir . '/';

                    $target_file = $target_dir . md5($_FILES["file"]["name"]);
                    $imageFileType = pathinfo(basename($_FILES["file"]["name"]) ,PATHINFO_EXTENSION);

                    $file_name = md5(Auth::id() . $_FILES["file"]["name"] . date("Y-h-m H:i:s:u")) . '.' . $imageFileType;
                    $target_file = $target_dir . $file_name;

                    if(file_exists($target_file)){
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.rename_file')
                        ];
                    }

                    // Check if image file is a actual image or fake image
                    $check = getimagesize($_FILES["file"]["tmp_name"]);
                    if($check !== false) {

                    } else {
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.not_image')
                        ];
                    }

                    // Check file size
                    if ($_FILES["file"]["size"] > 500000) {
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.file_size_limit')
                        ];
                    }

                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif" ) {
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.format_restriction')
                        ];
                    }

                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

                        $update_result = DB::table('users_extended')
                            ->where('user_id', '=', Auth::id())
                            ->update([
                                'avatar' => $file_name,
                            ]);

                        return [
                            'status'    => 'ok',
                            'image' => $file_name
                        ];

                    } else {
                        return [
                            'status' => 'error'
                        ];
                    }
                    break;
                case 'get_avatar':

                    $image_target = base_path(). '/accounts/'.ktUser::getAccountId() . '/' . $data['image'];

                    if(file_exists( $image_target)){
                        $imageFileType = pathinfo(basename($image_target) ,PATHINFO_EXTENSION);
                        switch($imageFileType){
                            case 'jpg':
                                $header_image = 'image/jpg';
                                break;
                            case 'jpeg':
                                $header_image = 'image/jpeg';
                                break;
                            case 'png':
                                $header_image = 'image/png';
                                break;
                            case 'gif':
                                $header_image = 'image/gif';
                                break;
                            default:
                                return [
                                    'status' => 'error'
                                ];
                        }
                        header('Content-type: ' . $header_image);
                        readfile($image_target);
                        exit();
                    }else{
                        $placeholder_targe = base_path(). '/public/assets/dist/img/profile-placeholder.png';
                        header('Content-type: image/jpeg');
                        readfile($placeholder_targe);
                        exit();
                    }


                    break;
                case 'get_system_logo':

                    $image_target = base_path(). '/accounts/'.ktUser::getAccountId() . '/' . $data['image'];

                    if(file_exists( $image_target)){
                        $imageFileType = pathinfo(basename($image_target) ,PATHINFO_EXTENSION);
                        switch($imageFileType){
                            case 'jpg':
                                $header_image = 'image/jpg';
                                break;
                            case 'jpeg':
                                $header_image = 'image/jpeg';
                                break;
                            case 'png':
                                $header_image = 'image/png';
                                break;
                            case 'gif':
                                $header_image = 'image/gif';
                                break;
                            default:
                                return [
                                    'status' => 'error'
                                ];
                        }
                        header('Content-type: ' . $header_image);
                        readfile($image_target);
                        exit();
                    }else{
                        $placeholder_targe = base_path(). '/public/assets/dist/img/profile-placeholder.png';
                        header('Content-type: image/jpeg');
                        readfile($placeholder_targe);
                        exit();
                    }


                    break;
                case 'invoice_logo':

                    $target_dir = base_path(). '/accounts/'.ktUser::getAccountId();

                    if(!file_exists($target_dir)){
                        if(!mkdir($target_dir)){
                            return [
                                'status'    => 'error',
                                'message'   => trans('application.server_side_issue')
                            ];
                        }
                    }

                    $target_dir = $target_dir . '/';

                    $target_file = $target_dir . md5($_FILES["file"]["name"]);
                    $imageFileType = pathinfo(basename($_FILES["file"]["name"]) ,PATHINFO_EXTENSION);

                    $file_name = md5(Auth::id() . $_FILES["file"]["name"] . date("Y-h-m H:i:s:u")) . '.' . $imageFileType;
                    $target_file = $target_dir . $file_name;

                    if(file_exists($target_file)){
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.rename_file')
                        ];
                    }

                    // Check if image file is a actual image or fake image
                    $check = getimagesize($_FILES["file"]["tmp_name"]);
                    if($check !== false) {

                        if($check[0] > 400){
                            return [
                                'status'    => 'error',
                                'message'   => trans('application.dimension_restriction')
                            ];
                        }

                    } else {
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.not_image')
                        ];
                    }

                    // Check file size
                    if ($_FILES["file"]["size"] > 500000) {
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.file_size_limit')
                        ];
                    }

                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif" ) {
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.format_restriction')
                        ];
                    }

                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

                        $update_result = DB::table('account')
                            ->where('id', '=', ktUser::getAccountId())
                            ->update([
                                'invoice_logo' => $file_name,
                            ]);

                        return [
                            'status'    => 'ok',
                            'image' => $file_name
                        ];

                    } else {
                        return [
                            'status' => 'error'
                        ];
                    }
                    break;
                case 'column_rename':

                    try{

                        if($board = self::getBoardData($data['hash'])){
                            //Nothing should happen...
                        }else{
                            throw new \PDOException(trans('application.report_issue_to_administrator'));
                        }

                        $columns = json_decode($board->columns);

                        if(count($columns) - 1 < $data['position']){
                            return array(trans('application.report_issue_to_administrator'));
                        }

                        $columns[$data['position']] = strtolower(trim($data['column']));

                        $update_result = DB::table('boards')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $board->id)
                            ->update([
                                'columns' => json_encode($columns)
                            ]);

                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];
                    }catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }

                    break;
                case 'new_requirement':

                    $result = DB::transaction(function () use ($data) {
                        try {

                            DB::table('project_requirements')->insert([
                                'account_id'    => ktUser::getAccountId(),
                                'project_id'    => $data['project_id'],
                                'subject'       => Crypt::encrypt($data['subject']),
                                'details'       => Crypt::encrypt($data['details']),
                            ]);

                            return [
                                'status'    => 'ok'
                            ];
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    return $result;
                    break;
                case 'delete_board':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            $result = DB::table('boards')->where([
                                'account_id'     => ktUser::getAccountId(),
                                'public_hash'    => $data['hash']
                            ])->delete();

                            return $result;

                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    return [
                        'status' => $result
                    ];
                    break;
                case 'delete_task':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                                $result = DB::table('tasks')
                                    ->where('id', '=', $data['task_id'])
                                    ->where('account_id', '=', ktUser::getAccountId())
                                    ->delete();
                            }
                            return $result;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    if($result == 1){
                        return [
                            'status'    => 'ok'
                        ];
                    }else{
                        return [
                            'status'   => 'error',
                            'messages' => trans('application.report_issue_to_administrator')
                        ];
                    }
                    break;
                case 'delete_requirement':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            if(Auth::user()->isAdmin() || Auth::user()->canManage()) {
                                $result = DB::table('project_requirements')
                                    ->where('id', '=', $data['requirement_id'])
                                    ->where('account_id', '=', ktUser::getAccountId())
                                    ->delete();
                            }
                            return $result;
                        }catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    if($result == 1){
                        return [
                            'status'    => 'ok'
                        ];
                    }else{
                        return [
                            'status'   => 'error',
                            'messages' => trans('application.report_issue_to_administrator')
                        ];
                    }
                    break;
                case 'system_logo':

                    $target_dir = base_path(). '/accounts/'.ktUser::getAccountId();

                    if(!file_exists($target_dir)){
                        if(!mkdir($target_dir)){
                            return [
                                'status'    => 'error',
                                'message'   => trans('application.server_side_issue')
                            ];
                        }
                    }

                    $target_dir = $target_dir . '/';

                    $target_file = $target_dir . md5($_FILES["file"]["name"]);
                    $imageFileType = pathinfo(basename($_FILES["file"]["name"]) ,PATHINFO_EXTENSION);

                    $file_name = md5(Auth::id() . $_FILES["file"]["name"] . date("Y-h-m H:i:s:u")) . '.' . $imageFileType;
                    $target_file = $target_dir . $file_name;

                    if(file_exists($target_file)){
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.rename_file')
                        ];
                    }

                    // Check if image file is a actual image or fake image
                    $check = getimagesize($_FILES["file"]["tmp_name"]);
                    if($check !== false) {

                        if($check[0] > 200){
                            return [
                                'status'    => 'error',
                                'message'   => trans('application.dimension_restriction')
                            ];
                        }

                        if($check[1] > 52){
                            return [
                                'status'    => 'error',
                                'message'   => trans('application.hdimension_restriction')
                            ];
                        }

                    } else {
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.not_image')
                        ];
                    }

                    // Check file size
                    if ($_FILES["file"]["size"] > 500000) {
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.file_size_limit')
                        ];
                    }

                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                        return [
                            'status'    => 'error',
                            'message'   => trans('application.format_restriction')
                        ];
                    }

                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

                        $update_result = DB::table('account')
                            ->where('id', '=', ktUser::getAccountId())
                            ->update([
                                'system_logo' => $file_name,
                            ]);

                        return [
                            'status'    => 'ok',
                            'image' => $file_name
                        ];

                    } else {
                        return [
                            'status' => 'error'
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

    public static function getBoardTemplate($id){
        try {
            $account_id = ktUser::getAccountId();
            $board_template = DB::table('board_templates')
                ->where('account_id', '=', $account_id)
                ->where('id', '=', $id)
                ->first();
            return $board_template;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getBoardData($hash){
        try {
            $board = DB::table('boards')
                ->select('boards.*', 'user_departments.name as department_name')
                ->leftJoin('user_departments', 'user_departments.id', '=', 'boards.department_id')
                ->where('boards.account_id', '=', ktUser::getAccountId())
                ->where('boards.public_hash', '=', $hash)
                ->first();

            if(!empty($board->parent_board_id)) {
                $parent_board = DB::table('boards')
                    ->select('boards.*', 'user_departments.name as department_name')
                    ->leftJoin('user_departments', 'user_departments.id', '=', 'boards.department_id')
                    ->where('boards.id', '=', $board->parent_board_id)
                    ->first();
                $board->parent_board = $parent_board;
            }else{
                $board->parent_board = false;
            }

            $child_board = DB::table('boards')
                ->select('boards.*', 'user_departments.name as department_name')
                ->leftJoin('user_departments', 'user_departments.id', '=', 'boards.department_id')
                ->where('boards.parent_board_id', '=', $board->id)
                ->first();
            if(is_object($child_board)) {
                $board->child_board = $child_board;
            }else{
                $board->child_board = false;
            }

            return $board;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getBoardFamily($id){
        try {

            $board = DB::table('boards')
                ->select('boards.*', 'user_departments.name as department_name')
                ->leftJoin('user_departments', 'user_departments.id', '=', 'boards.department_id')
                ->where('boards.id', '=', $id)
                ->first();

            if(!empty($board->parent_board_id)) {
                $parent_board = DB::table('boards')
                    ->select('boards.*', 'user_departments.name as department_name')
                    ->leftJoin('user_departments', 'user_departments.id', '=', 'boards.department_id')
                    ->first();
                $board->parent_board = $parent_board;
            }else{
                $board->parent_board = false;
            }

            $child_board = DB::table('boards')
                ->select('boards.*', 'user_departments.name as department_name')
                ->leftJoin('user_departments', 'user_departments.id', '=', 'boards.department_id')
                ->where('parent_board_id', '=', $board->id)
                ->first();
            if(is_object($child_board)) {
                $board->child_board = $child_board;
            }else{
                $board->child_board = false;
            }

            $boards = [$board];

            if(!empty($board->parent_board_id)){
                return  array_merge(self::getBoardFamily($board->parent_board_id), $boards);
            }

            return $boards;

        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getYoungestBoard($id){
        try {
            $child_board = DB::table('boards')
                ->where('boards.parent_board_id', '=', $id)
                ->first();

            if(is_object($child_board)){
                return self::getYoungestBoard($child_board->id);
            }

            return $id;

        }catch (\Exception $e) {
            return false;
        }

    }

    public static function getBoardTasks($hash, $select = false){
        try {
            if((Auth::user()->isAdmin() || Auth::user()->canManage()) && $select === false) {
                $board = DB::table('tasks')
                    ->select(
                        'tasks.*',
                        'task_positions.user_id',
                        'task_positions.board_id',
                        'task_positions.size_x',
                        'task_positions.size_y',
                        'users_extended.avatar')
                    ->leftJoin('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                    ->leftJoin('users_extended', 'task_positions.user_id', '=', 'users_extended.user_id')
                    ->where('tasks.account_id', '=', ktUser::getAccountId())
                    ->where('task_positions.board_id', '=', self::getBoardData($hash)->id)
                    ->where('task_positions.visible', '=', 1)
                    ->orderBy('tasks.priority', 'asc')
                    ->get();
            }else if(Auth::user()->isClient()){
                $client = ktUser::getUserData();
                $board = DB::table('tasks')
                    ->select(
                        'tasks.*',
                        'task_positions.user_id',
                        'task_positions.board_id',
                        'task_positions.size_x',
                        'task_positions.size_y',
                        'users_extended.avatar')
                    ->leftJoin('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                    ->leftJoin('users_extended', 'task_positions.user_id', '=', 'users_extended.user_id')
                    ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
                    ->where('tasks.account_id', '=', ktUser::getAccountId())
                    ->where('task_positions.board_id', '=', self::getBoardData($hash)->id)
                    ->where('projects.customer_id', '=', $client->customer_id)
                    ->where('task_positions.visible', '=', 1)
                    ->orderBy('tasks.priority', 'asc')
                    ->get();
            }else if($select === 'team') {
                $board = DB::table('tasks')
                    ->select(
                        'tasks.*',
                        'task_positions.user_id',
                        'task_positions.board_id',
                        'task_positions.size_x',
                        'task_positions.size_y',
                        'users_extended.avatar')
                    ->leftJoin('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                    ->leftJoin('users_extended', 'task_positions.user_id', '=', 'users_extended.user_id')
                    ->where('tasks.account_id', '=', ktUser::getAccountId())
                    ->where('task_positions.board_id', '=', self::getBoardData($hash)->id)
                    ->where('task_positions.visible', '=', 1)
                    ->orderBy('tasks.priority', 'asc')
                    ->get();
            }else if(is_numeric($select)){
                $board = DB::table('tasks')
                    ->select(
                        'tasks.*',
                        'task_positions.user_id',
                        'task_positions.board_id',
                        'task_positions.size_x',
                        'task_positions.size_y',
                        'users_extended.avatar')
                    ->leftJoin('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                    ->leftJoin('users_extended', 'task_positions.user_id', '=', 'users_extended.user_id')
                    ->where('tasks.account_id', '=', ktUser::getAccountId())
                    ->where('task_positions.board_id', '=', self::getBoardData($hash)->id)
                    ->where('task_positions.user_id', '=', $select)
                    ->where('task_positions.visible', '=', 1)
                    ->orderBy('tasks.priority', 'asc')
                    ->orderBy('task_positions.user_id', 'asc')
                    ->get();
            }else{
                $board = DB::table('tasks')
                    ->select(
                        'tasks.*',
                        'task_positions.user_id',
                        'task_positions.board_id',
                        'task_positions.size_x',
                        'task_positions.size_y',
                        'users_extended.avatar')
                    ->leftJoin('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                    ->leftJoin('users_extended', 'task_positions.user_id', '=', 'users_extended.user_id')
                    ->where('tasks.account_id', '=', ktUser::getAccountId())
                    ->where('task_positions.board_id', '=', self::getBoardData($hash)->id)
                    ->where('task_positions.user_id', '=', Auth::id())
                    ->orWhereNull('task_positions.user_id')
                    ->where('task_positions.visible', '=', 1)
                    ->orderBy('tasks.priority', 'asc')
                    ->orderBy('task_positions.user_id', 'asc')
                    ->get();
            }
                return $board;
        }catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function getProjectTasks($project_id, $boards = NULL){
        try {
            if($boards != NULL){
                $tasks = DB::table('tasks')
                    ->select('tasks.*', 'users_extended.avatar')
                    ->leftJoin('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                    ->leftJoin('users_extended', 'task_positions.user_id', '=', 'users_extended.user_id')
                    ->where('tasks.account_id', '=', ktUser::getAccountId())
                    ->where('tasks.project_id', '=', $project_id)
                    ->groupBy('tasks.id')
                    ->orderBy('tasks.priority', 'asc')
                    ->get();
            }else{
                $tasks = DB::table('tasks')
                    ->select('tasks.*', 'users_extended.avatar')
                    ->leftJoin('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                    ->leftJoin('users_extended', 'task_positions.user_id', '=', 'users_extended.user_id')
                    ->where('tasks.account_id', '=', ktUser::getAccountId())
                    ->where('tasks.project_id', '=', $project_id)
                    ->groupBy('tasks.id')
                    ->orderBy('tasks.priority', 'asc')
                    ->get();
            }
            return $tasks;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getBoards(){
        try {

            $boards = DB::table('boards')
                ->select('boards.*', 'user_departments.name as department_name')
                ->leftJoin('user_departments', 'user_departments.id', '=', 'boards.department_id')
                ->where('boards.account_id', '=', ktUser::getAccountId())
                ->orderBy('boards.end_date', 'desc')
                ->get();

            if(Auth::user()->isClient()){
                $client = ktUser::getUserData();
                foreach($boards as $key => $board){
                    $tasks = DB::table('tasks')
                        ->join('projects', 'projects.id', '=', 'tasks.project_id')
                        ->join('task_positions', 'task_positions.task_id', '=', 'tasks.id')
                        ->where('task_positions.board_id', '=', $board->id)
                        ->where('projects.customer_id', '=', $client->customer_id)
                        ->get();

                    if(count($tasks) < 1){
                        unset($boards[$key]);
                    }

                }
            }

            foreach($boards as $key => $board) {
                if (!empty($board->parent_board_id)) {
                    $parent_board = DB::table('boards')
                        ->where('id', '=', $board->parent_board_id)
                        ->first();
                    $board->parent_board = $parent_board;
                } else {
                    $board->parent_board = false;
                }

                $child_board = DB::table('boards')
                    ->where('parent_board_id', '=', $board->id)
                    ->first();
                if (is_object($child_board)) {
                    $board->child_board = $child_board;
                } else {
                    $board->child_board = false;
                }
            }

            return $boards;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getOwnerAndManager($task_id){
        try {
            $result = DB::table('task_positions')
                ->select('users_extended.user_id', 'users.email', 'users_extended.first_name', 'users_extended.last_name')
                ->join('users_extended',function($join)
                {
                    $join->on('users_extended.user_id', '=', 'task_positions.user_id');
                       // ->orOn('users_extended.user_id', '=', 'tasks.manager_id');
                })
                ->join('users', 'users.id', '=', 'users_extended.user_id')
                ->where('task_positions.task_id', '=', $task_id)
                ->distinct()
                ->get();
            return $result;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getPriorityLabel($prority_id){
        if(!isset(self::$priority[$prority_id])){
            return 'unknown';
        }
        return self::$priority[$prority_id];
    }

    public static function getPriorityColor($prority_id){
        if(!isset(self::$priority_colors[$prority_id])){
            return '';
        }
        return self::$priority_colors[$prority_id];
    }

    public static function getCommentAuthor($comment_id){
        try {
            $user = DB::table('task_comments')
                ->select('task_comments.*', 'users_extended.first_name', 'users_extended.last_name', 'users_extended.avatar')
                ->join('users_extended', 'users_extended.user_id', '=', 'task_comments.user_id')
                ->where('task_comments.id', '=', $comment_id)
                ->first();
            return $user;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getUser($user_id){
        try {
            $user = DB::table('users_extended')
                ->where('user_id', '=', $user_id)
                ->first();
            return $user;
        }catch (\Exception $e) {
            return false;
        }
    }

    public static function getTaskType($type_id, $subject){
        if(!isset(self::$task_type[$type_id])){
            return substr($subject, 0, 20) . '...';
        }
        return trans('board.task_type.' . self::$task_type[$type_id]);
    }

    public static function getGrandBoardData($id){
        $parentBoard = [];

        $q = DB::table('boards')
            ->select('boards.*', 'user_departments.name as department_name')
            ->leftJoin('user_departments', 'user_departments.id', '=', 'boards.department_id')
            ->where('boards.account_id', '=', ktUser::getAccountId())
            ->where('boards.id', '=', $id);;


        while($board = $q->first()){
            if(is_numeric($board->parent_board_id)){
                $bid = $board->parent_board_id;
                $q = DB::table('boards')
                    ->select('boards.*', 'user_departments.name as department_name')
                    ->leftJoin('user_departments', 'user_departments.id', '=', 'boards.department_id')
                    ->where('boards.account_id', '=', ktUser::getAccountId())
                    ->where('boards.id', '=', $bid);
            }else{
                $parentBoard = $board;
                break;
            }
        }

        return $parentBoard;

    }

    public static function provisionBoardUpdate($id, $data){

        $account_id = ktUser::getAccountId();
        $q = DB::table('boards')
            ->where('boards.account_id', '=', $account_id)
            ->where('boards.id', '=', $id);

        //Update up to the grand board
        while($board = $q->first()){

            DB::table('boards')
                ->where('account_id', '=', $account_id)
                ->where('id', '=', $board->id)
                ->update($data);

            //get the next parent.
            $q = DB::table('boards')
                ->where('boards.account_id', '=', $account_id)
                ->where('boards.id', '=', $board->parent_board_id);
        }

        $q = DB::table('boards')
            ->where('boards.account_id', '=', $account_id)
            ->where('boards.id', '=', $id);

        //Update up to the grand board
        while($board = $q->first()){
            DB::table('boards')
                ->where('account_id', '=', $account_id)
                ->where('id', '=', $board->id)
                ->update($data);

            //get the next child
            $q = DB::table('boards')
                ->where('boards.account_id', '=', $account_id)
                ->where('boards.parent_board_id', '=', $board->id);
        }

        return true;

    }

    public static function getDefaultBoards(){
        try {
            $boards = DB::table('boards')
                ->where('account_id', '=', ktUser::getAccountId())
                ->where('default', '=', 1);

            $user = ktUser::getUserData();
            if(!empty($user->department_id)) {
                $boards->where('department_id', '=', $user->department_id);
            }

            $board = $boards->first();

            if(!is_object($board)){
                $board = DB::table('boards')
                    ->where('account_id', '=', $user->account_id)
                    ->where('default', '=', 1)
                    ->whereNull('parent_board_id')
                    ->first();
            }

            return $board;
        }catch (\Exception $e) {
            return false;
        }
    }

}