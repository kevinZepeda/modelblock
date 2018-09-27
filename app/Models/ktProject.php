<?php namespace App\Models;

use DB;
use League\Flysystem\Exception;
use Validator;
use Hash;
use Auth;
use Crypt;
use Illuminate\Database\Eloquent\Model;
use Config;

class ktProject
{

    public static $rules = [
        'new_project' => [
            'customer_id' => 'required|numeric',
            'project_name' => 'required|min:3',
            'project_description' => 'required|min:2',
        ],
        'sharepoint' => [
            'project_id' => 'required|numeric'
        ],
        'share_delete' => [
            'file' => 'required',
            'project_id' => 'required'
        ],
        'get_requirement' => [
            'project_id' => 'required|numeric',
            'requirement_id' => 'required|numeric'
        ],
        'r_subject_update' => [
            'project_id' => 'required|numeric',
            'requirement_id' => 'required|numeric',
            'subject' => 'required|min:1'
        ],
        'r_details_update' => [
            'project_id' => 'required|numeric',
            'requirement_id' => 'required|numeric',
            'details' => 'required|min:1'
        ],
        'delete_project' => [
            'project_id' => 'required|numeric'
        ],
        'files' => [
            'project_id' => 'required|numeric'
        ],
        'workstream_post' => [
            'project_id' => 'required|numeric',
            'comment' => 'required|min:1'
        ],
        'p_subject_update' => [
            'project_id' => 'required|numeric',
            'subject' => 'required|min:1'
        ],
        'p_desc_update' => [
            'project_id' => 'required|numeric',
            'description' => 'required|min:1'
        ],
        'delete_comment' => [
            'comment_id' => 'required|numeric'
        ],
        'update_wiki' => [
            'page_title' => 'required',
            'page_content' => 'required',
            'project_id' => 'required|numeric',
            'page_id' => 'required|numeric'
        ],
        'delete_page' => [
            'project_id' => 'required|numeric',
            'page_id' => 'required|numeric'
        ]
    ];

    public static $rules_messages = [

    ];

    public static function triggerEvent($request)
    {
        if ($request->has('event')) {
            if (isset(self::$rules[$request->input('event')])) {
                $event = $request->input('event');
                $rules = self::$rules[$event];
                $messages = (isset(self::$rules_messages[$event]))
                    ? self::$rules_messages[$event]
                    : array();
            } else {
                return array(trans('application.intruder'));
            }
        }

        $data = array();
        foreach ($rules as $field => $rule) {
            $data[$field] = trim($request->input($field));
        }

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails() === false) {
            switch ($event) {
                case 'new_project':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $data['account_id'] = ktUser::getAccountId();
                            DB::table('projects')->insert($data);
                            return DB::getPdo()->lastInsertId();;
                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    return [
                        'status' => 'ok',
                        'id' => $result
                    ];
                    break;
                case 'sharepoint':

                    if (!Config::get('app.features.fileupload')) {
                        return [
                            'status' => 'error',
                            'message' => trans('application.file_upload_disabled')
                        ];
                    }

                    $target_dir = base_path() . '/accounts/' . ktUser::getAccountId() . '/project/' . trim($data['project_id']);

                    if (!file_exists($target_dir)) {
                        if (!mkdir($target_dir, 0777, true)) {
                            return [
                                'status' => 'error',
                                'message' => trans('application.server_side_issue')
                            ];
                        }
                    }

                    $target_dir = $target_dir . '/';

                    $target_file = $target_dir . md5($_FILES["file"]["name"]);
                    $imageFileType = pathinfo(basename($_FILES["file"]["name"]), PATHINFO_EXTENSION);

                    $file_name = $_FILES["file"]["name"];
                    $target_file = $target_dir . $file_name;

                    if (file_exists($target_file)) {
                        return [
                            'status' => 'error',
                            'message' => trans('application.file_exists')
                        ];
                    }

                    // Check file size
                    if ($_FILES["file"]["size"] > 100000000) {
                        return [
                            'status' => 'error',
                            'message' => trans('application.big_file_limit')
                        ];
                    }

                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

                        return [
                            'status' => 'ok',
                            'image' => $file_name
                        ];

                    } else {
                        return [
                            'status' => 'error'
                        ];
                    }
                    break;
                case 'share_delete':
                    $file_path = base_path() . '/accounts/' . ktUser::getAccountId() . '/project/' . $data['project_id'] . '/' . $data['file'];

                    $action = false;
                    if (file_exists($file_path)) {
                        $action = unlink($file_path);
                    }

                    return [
                        'status' => 'ok',
                        'action' => $action
                    ];
                    break;
                case 'get_requirement':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $requirement = DB::table('project_requirements')
                                ->where('id', '=', $data['requirement_id'])
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->where('project_id', '=', $data['project_id'])
                                ->first();
                            return $requirement;
                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    $result->subject = Crypt::decrypt($result->subject);
                    $result->details = Crypt::decrypt($result->details);

                    return [
                        'status' => 'ok',
                        'result' => $result
                    ];
                    break;
                case 'r_subject_update':
                    try {
                        $update_result = DB::table('project_requirements')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['requirement_id'])
                            ->where('project_id', '=', $data['project_id'])
                            ->update([
                                'subject' => Crypt::encrypt($data['subject'])
                            ]);
                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];
                    } catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'r_details_update':
                    try {
                        $update_result = DB::table('project_requirements')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['requirement_id'])
                            ->where('project_id', '=', $data['project_id'])
                            ->update([
                                'details' => Crypt::encrypt($data['details'])
                            ]);
                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];
                    } catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'delete_project':
                    $result = DB::transaction(function () use ($data) {
                        try {

                            $result = DB::table('projects')
                                ->where('id', '=', $data['project_id'])
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->delete();

                            return $result;

                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    return [
                        'status' => $result
                    ];
                    break;
                case 'files':
                    $dir = base_path() . '/accounts/' . ktUser::getAccountId() . '/project/' . $data['project_id'] . '/';
                    $files = array();
                    // Is there actually such a folder/file?
                    if (file_exists($dir)) {
                        foreach (scandir($dir) as $f) {
                            if (!$f || $f[0] == '.') {
                                continue; // Ignore hidden files
                            }
                            $files[] = array(
                                "name" => $f,
                                "type" => "file",
                                "path" => '/',
                                "size" => filesize($dir . '/' . $f) // Gets the size of this file
                            );
                        }
                    }
                    return [
                        "name" => "files",
                        "type" => "folder",
                        "path" => '/',
                        "items" => $files
                    ];
                    break;
                case 'workstream_post':
                    $display_date = date("d M y H:i");
                    $author = ktBoard::getUser(Auth::id());
                    $result = DB::transaction(function () use ($data, $author, $display_date) {
                        try {
                            DB::table('task_comments')->insert([
                                'account_id' => ktUser::getAccountId(),
                                'user_id' => Auth::id(),
                                'project_id' => $data['project_id'],
                                'comment' => $data['comment'],
                                'comment_date' => date("Y-m-d H:i:s")
                            ]);
                            $comment_id = DB::getPdo()->lastInsertId();
                            return $comment_id;
                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }

                    });

                    if (is_numeric($result)) {
                        $comment_id = $result;
                        $result = true;
                    } else {
                        $result = false;
                    }

                    if ($result === true) {
                        return [
                            'status' => 'ok',
                            'response' => [
                                'id' => $comment_id,
                                'comment' => htmlspecialchars($data['comment']),
                                'date' => $display_date,
                                'author' => ucfirst(strtolower(@$author->first_name)) . ' ' . ucfirst(substr(@$author->last_name, 0, 1)),
                                'avatar' => $author->avatar
                            ],
                            'resutl' => $result
                        ];

                    } else {
                        return [
                            'status' => 'error',
                            'message' => 'Failure',
                            'resutl' => $result
                        ];

                    }
                    break;
                case 'p_subject_update':
                    try {
                        $update_result = DB::table('projects')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['project_id'])
                            ->update([
                                'project_name' => $data['subject']
                            ]);

                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];
                    } catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'p_desc_update':
                    try {
                        $update_result = DB::table('projects')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('id', '=', $data['project_id'])
                            ->update([
                                'project_description' => $data['description']
                            ]);

                        return [
                            'status' => 'ok',
                            'result' => $update_result
                        ];
                    } catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'delete_comment':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            if (Auth::user()->isAdmin()) {
                                $result = DB::table('task_comments')
                                    ->where('id', '=', $data['comment_id'])
                                    ->where('account_id', '=', ktUser::getAccountId())
                                    ->delete();
                            } else {
                                $result = DB::table('task_comments')
                                    ->where('id', '=', $data['comment_id'])
                                    ->where('user_id', '=', Auth::id())
                                    ->where('account_id', '=', ktUser::getAccountId())
                                    ->delete();
                            }
                            return $result;
                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });
                    if ($result == 1) {
                        return [
                            'status' => 'ok'
                        ];
                    } else {
                        return [
                            'status' => 'error',
                            'messages' => trans('application.report_issue_to_administrator')
                        ];
                    }
                case 'update_wiki':
                    try {
                        $update_result = DB::table('projects_wiki')
                            ->where('account_id', '=', ktUser::getAccountId())
                            ->where('project_id', '=', $data['project_id'])
                            ->where('id', '=', $data['page_id'])
                            ->update([
                                'page_title' => $data['page_title'],
                                'content' => $data['page_content']
                            ]);
                    } catch (\PDOException $e) {
                        return array(trans('application.report_issue_to_administrator'));
                    }
                    break;
                case 'delete_page':
                    $result = DB::transaction(function () use ($data) {
                        try {
                            $result = DB::table('projects_wiki')
                                ->where('account_id', '=', ktUser::getAccountId())
                                ->where('project_id', '=', $data['project_id'])
                                ->where('id', '=', $data['page_id'])
                                ->delete();

                            return $result;
                        } catch (\PDOException $e) {
                            return array(trans('application.report_issue_to_administrator'));
                        }
                    });

                    if ($result == 1) {
                        redirect('/office/project/'.$data['project_id'].'/wiki/home');
                        exit;
                    } else {
                        return [
                            'status' => 'error',
                            'messages' => trans('application.report_issue_to_administrator')
                        ];
                    }
            }
            return false;
        } else {
            return [
                'status' => 'error',
                'messages' => $validator->messages()->all()
            ];
        }

    }

    public static function getWorkstream($id)
    {
        try {
            $stream = DB::select("SELECT task_comments.id, task_comments.user_id, task_comments.comment, DATE_FORMAT(task_comments.comment_date, '%d %b %y %H:%i') as comment_date,
                            CONCAT_WS(' ', users_extended.first_name, SUBSTR(users_extended.last_name,1,1)) as author, users_extended.avatar
                            FROM  task_comments JOIN users_extended ON task_comments.user_id = users_extended.user_id
                            WHERE task_comments.account_id = " . ktUser::getAccountId() . " AND task_comments.project_id = " . $id . "
                            ORDER BY task_comments.comment_date ASC");
            return $stream;
        } catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getProjectData($id)
    {
        try {
            $project = DB::table('projects')
                ->where('id', '=', $id)
                ->where('account_id', '=', ktUser::getAccountId())
                ->first();
            return $project;
        } catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getProjectRequirements($id)
    {
        try {
            $project = DB::table('project_requirements')
                ->where('project_id', '=', $id)
                ->where('account_id', '=', ktUser::getAccountId())
                ->get();
            return $project;
        } catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getProjectsData()
    {
        try {
            if (Auth::user()->isClient()) {
                $customer = ktUser::getUserData();
                $projects = DB::table('projects')
                    ->where('account_id', '=', ktUser::getAccountId())
                    ->where('customer_id', '=', $customer->customer_id)
                    ->get();
            } else {
                $projects = DB::table('projects')
                    ->where('account_id', '=', ktUser::getAccountId())
                    ->get();
            }
            return $projects;
        } catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function getShareFiles($project_id)
    {
        try {
            $shares = DB::table('sharepoint')
                ->where('account_id', '=', ktUser::getAccountId())
                ->where('project_id', '=', $project_id)
                ->get();
            return $shares;
        } catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function createEmptyWikiPage($account_id, $project_id, $page_title)
    {
        try {
            $text = trans('wiki.empty');

            DB::table('projects_wiki')->insert([
                'account_id' => $account_id,
                'project_id' => $project_id,
                'page_title' => $page_title,
                'content'    => $text
            ]);
        } catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

    public static function pageExists($account_id, $project_id, $page_title)
    {
        try {
            $page = DB::table('projects_wiki')
                ->where('account_id', '=', $account_id)
                ->where('project_id', '=', $project_id)
                ->where('page_title', '=', $page_title)
                ->first();
            if(is_object($page)){
                return $page;
            }
            return false;
        } catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }


    public static function getWikiPage($account_id, $project_id, $page_title)
    {
        try {
            $page = DB::table('projects_wiki')
                ->where('account_id', '=', $account_id)
                ->where('project_id', '=', $project_id)
                ->where('page_title', '=', $page_title)
                ->first();
            return $page;
        } catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }


    public static function getProjectWikiPages($account_id, $project_id)
    {
        try {
            $pages = DB::table('projects_wiki')
                ->where('account_id', '=', $account_id)
                ->where('project_id', '=', $project_id)
                ->get();
            return $pages;
        } catch (\Exception $e) {
            return array('message' => trans('application.report_issue_to_administrator'));
        }
    }

}