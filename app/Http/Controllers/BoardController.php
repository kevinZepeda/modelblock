<?php namespace App\Http\Controllers;

use App\Models\ktBoard;
use Illuminate\Http\Request;
use Auth;
use App\Models\ktUser;
use App\Models\User;
use App\Models\ktProject;
use Route;

class BoardController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Board Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
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
     * Show the application board view to the user.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $default_board = ktBoard::getDefaultBoards();

        if(Route::input('board_url_hash') !== null){
            $hash = Route::input('board_url_hash');
            $select = false;

            if(Route::input('board_query')){
                $select = trim(Route::input('board_query'));
            }
            $board = ktBoard::getBoardData($hash);
            $tasks = ktBoard::getBoardTasks($hash, $select);
            $hash = '/'.$hash;
        }else if(is_object($default_board)) {
            $hash = $default_board->public_hash;
            $select = false;

            $board = ktBoard::getBoardData($hash);
            $tasks = ktBoard::getBoardTasks($hash, $select);
            $hash = '/'.$hash;
        }

        $board_templates = ktUser::getBoardTemplates();
        $boards_list = ktBoard::getBoards();
        $departments = ktUser::getDepartments();
        if(Auth::user()->isClient() && count($boards_list) < 1){
            $board = array();
        }
        $users_list = ktUser::getUsers(true);
        $projects_list = ktProject::getProjectsData();
        $data = ktUser::getUserData();

        if(isset($board_templates['message'])){
            $messages = $board_templates;
            $board_templates = array();
        }

        $boards_map = [];
        if(isset($board)) {
            if (is_object($board)) {
                $young = ktBoard::getYoungestBoard($board->id);
                $boards_map = ktBoard::getBoardFamily($young);
            }
        }

        return view('board.board',
            [
                'hash'                  => @$hash,
                'select'                => @$select,
                'board'                 => @$board,
                'bards_map'             => @$boards_map,
                'tasks'                 => @$tasks,
                'departments'           => @$departments,
                'users'                 => @$users_list,
                'projects'              => @$projects_list,
                'boards_list'           => $boards_list,
                'board_templates'       => $board_templates,
                'data'                  => $data
            ]
        );
    }

}
