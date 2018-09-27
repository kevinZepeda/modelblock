<?php namespace App\Http\Controllers;

use App\Models\ktUser;
use App\Models\ktProject;
use App\Models\ktTime;
use App\Models\ktFinance;
use Illuminate\Http\Request;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "Time Tracking" view for users that
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
	 * Show the time tracking dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		//echo ktTime::decimal_to_time('8.50');
		date_default_timezone_set(ktUser::getAccountTimeZone());
		$stopwatch = ktTime::getStopWatchState();

		$team_time = ktTime::getAccounTotalTime();
		$user_time = ktTime::getUserTotalTime();

		$users_list = ktUser::getUsers(true);

		$profit = ktFinance::getProfit();

        $data = ktUser::getUserData();

        if($request->isMethod('post')){
            $entries = ktTime::getTimeRange($request);
        }else{
        	$entries = ktTime::getLastSevenDays();
        }

        $projects = ktProject::getProjectsData();
		return view('time.home', [
                'data' => $data,
                'projects' => @$projects,
                'users'	  => @$users_list,
                'entries' => @$entries,
                'stopwatch' => @$stopwatch,
                'user_time' => @$user_time,
                'team_time' => @$team_time,
                'profit'	=> @$profit
            ]
        );
	}

}
