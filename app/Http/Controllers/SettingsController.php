<?php namespace App\Http\Controllers;

use App\Models\Lang;
use Illuminate\Http\Request;
use Auth;
use App\Models\ktUser;
use App\Models\User;
use App\Models\ktNotify;
use App\Models\ktBoard;
use App\Models\ktSettings;
use Route;

class SettingsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Settings Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "Settings" view for users that
	| are authenticated.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the general settings view to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $data = ktUser::getUserData();
        $account = ktUser::getAccountData();

        return view('settings.settings', [
            'block' => 'general',
            'validation_messages'   => $messages,
            'data'  => $data,
            'account' => $account

        ]);
	}

    /**
     * Show the finance settings view to the user.
     *
     * @return Response
     */
    public function finance(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $data = ktUser::getUserData();
        $account = ktUser::getAccountData();

        return view('settings.settings', [
            'block' => 'finance',
            'validation_messages'   => $messages,
            'data'  => $data,
            'account' => $account

        ]);
    }

    /**
     * Show the profile settings to the user.
     *
     * @return Response
     */
    public function profile(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $data = ktUser::getUserData();

        //var_dump($data);

        return view('settings.settings',
            [
                'block'                 => 'profile',
                'validation_messages'   => $messages,
                'data'                  => $data
            ]
        );
    }

    /**
     * Show the notification settings to the user.
     *
     * @return Response
     */
    public function notifications(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $data = ktUser::getUserData();
        $settings = ktNotify::getNotificationsSettings();

        return view('settings.settings',
            [
                'settings' => @$settings,
                'block' => 'notifications',
                'validation_messages' => $messages,
                'data'  => $data

            ]
        );
    }

    /**
     * Show the boards template list to the user.
     *
     * @return Response
     */
    public function manageBoards(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $board_templates = ktUser::getBoardTemplates();
        $data = ktUser::getUserData();

        if(isset($board_templates['message'])){
            $messages = $board_templates;
            $board_templates = array();
        }

        return view('settings.settings',
            [
                'block'                 => 'manage_columns',
                'validation_messages'   => $messages,
                'board_counts'          => count($board_templates),
                'board_templates'       => $board_templates,
                'data'                  => $data
            ]
        );
    }


    /**
     * Show the expenses category list to the user.
     *
     * @return Response
     */
    public function category(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $categories = ktUser::getExpenseCategories();
        $data = ktUser::getUserData();

        if(isset($categories['message'])){
            $messages = $users;
        }

        return view('settings.settings',
            [
                'block'                 => 'categories',
                'validation_messages'   => $messages,
                'users_count'           => count($categories),
                'categories'            => $categories,
                'data'                  => $data
            ]
        );
    }

    /**
     * Show the account users list to the user.
     *
     * @return Response
     */
    public function manageUsers(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $users = ktUser::getUsers();
        $data = ktUser::getUserData();
        $departments = ktUser::getDepartments();

        if(isset($users['message'])){
            $messages = $users;
            $users = array();
        }

        return view('settings.settings',
            [
                'block' => 'manage_users',
                'validation_messages'   => $messages,
                'users_count'           => count($users),
                'departments_count'     => count($departments),
                'users'                 => $users,
                'data'                  => $data,
                'departments'           => @$departments,

            ]
        );
    }

    /**
     * Show the create new user for to the user.
     *
     * @return Response
     */
    public function newUser(Request $request)
    {

        $messages = array();
        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $data = ktUser::getUserData();

        return view('settings.settings',
            [
                'block'                 => 'new_user',
                'validation_messages'   => $messages,
                'data'                  => $data,
                'request'               => $request->input()
            ]
        );

    }

  /**
     * Show the new expense category form to the user.
     *
     * @return Response
     */
    public function newCategory(Request $request)
    {

        $messages = array();
        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $data = ktUser::getUserData();

        return view('settings.settings',
            [
                'block'                 => 'new_category',
                'validation_messages'   => $messages,
                'data'                  => $data,
                'request'               => $request->input()
            ]
        );

    }

    /**
     * Show the new board template form to the user.
     *
     * @return Response
     */
    public function newBoard(Request $request)
    {

        $messages = array();

        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $data = ktUser::getUserData();

        return view('settings.settings',
            [
                'block'                 => 'new_columns',
                'validation_messages'   => @$messages,
                'data'                  => $data,
                'request'               => @$request->input()
            ]
        );

    }

    /**
     * Show the QA list to the user.
     *
     * @return Response
     */
    public function manageQuestionLists(Request $request)
    {
        $messages = array();

        $data = ktUser::getUserData();
        $list = ktSettings::getQuestionnaries();

        return view('settings.settings',
            [
                'block'                 => 'questionnaire_list',
                'validation_messages'   => $messages,
                'list'                  => $list,
                'data'                  => $data
            ]
        );
    }

    /**
     * Show the New QA form to the user.
     *
     * @return Response
     */
    public function newQuestionnarie(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $data = ktUser::getUserData();

        if(isset($board_templates['message'])){
            $messages = $board_templates;
            $board_templates = array();
        }

        return view('settings.questionarie',
            [
                'block'                 => 'new',
                'validation_messages'   => $messages,
                'data'                  => $data
            ]
        );
    }


    /**
     * Show the editing of the QA to the user.
     *
     * @return Response
     */
    public function editQuestionnarie(Request $request)
    {
        $messages = array();

        $data = ktUser::getUserData();
        $template = ktSettings::getQuestionnarie(Route::input('questionnarie_id'));
        

        if(in_array($template->status,['SUBMITED','REVIEWED'])){
            return redirect('/quote/review/'.$template->id);
        }


        return view('settings.questionarie',
            [
                'block'                 => 'edit',
                'validation_messages'   => $messages,
                'data'                  => $data,
                'template'              => $template
            ]
        );
    }

    /**
     * Show the new expense category form to the user.
     *
     * @return Response
     */
    public function newDepartment(Request $request)
    {

        $messages = array();
        if($request->isMethod('post')){
            $messages = ktUser::update($request);
        }

        $data = ktUser::getUserData();

        return view('settings.settings',
            [
                'block'                 => 'new_department',
                'validation_messages'   => $messages,
                'data'                  => $data,
                'request'               => $request->input()
            ]
        );

    }


}
