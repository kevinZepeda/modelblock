<?php namespace App\Http\Controllers;

use App\Models\ktBoard;
use App\Models\ktCustomer;
use Illuminate\Http\Request;
use Auth;
use App\Models\ktUser;
use App\Models\User;
use App\Models\ktProject;
use App\Models\ktSettings;
use Route;
use App\Models\ktFinance;

class OfficeController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Board Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "Office" view for users that
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
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data = ktUser::getUserData();
        $customers = ktCustomer::getCustomersData();

        return view('office.office',
            [
                'data' => $data,
                'customers' => $customers
            ]
        );
    }

    /**
     * Show the projects list to the user.
     *
     * @return Response
     */
    public function project(Request $request)
    {
        $data = ktUser::getUserData();
        $customers = ktCustomer::getCustomersData();

        $project = ktProject::getProjectData(Route::input('project_id'));
        $projects = ktProject::getProjectsData();
        $boards_list = ktBoard::getBoards();
        $users_list = ktUser::getUsers(true);


        $block  ='project';
        if(!is_object($project)){
            $block  ='clean';
        }

        return view('office.office',
            [
                'data' => $data,
                'customers' => $customers,
                'block' => $block,
                'subblock' => 'details',
                'users' => @$users_list,
                'boards_list' => $boards_list,
                'project' => @$project,
                'projects' => @$projects
            ]
        );
    }

    /**
     * Show the customers list to the user.
     *
     * @return Response
     */
    public function customer(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktCustomer::triggerEvent($request);
            if(isset($messages['messages'])) {
                $messages = $messages['messages'];
            }
        }

        $data = ktUser::getUserData();
        $customer = ktCustomer::getCustomerData(Route::input('customer_id'));
        $projects = ktProject::getProjectsData();
        $questionnarie_templates = ktSettings::getQuestionnaries();
        $questionnaries = ktSettings::getCustomerQuestionnaries(@$customer->id);

        $orders = ktSettings::getQuestionnaries();

        $customers = ktCustomer::getCustomersData();
        $boards_list = ktBoard::getBoards();

        $block  ='customer';
        if(!is_object($customer)){
            $block  ='clean';
        }

        return view('office.office',
            [
                'data' => $data,
                'qlist' => array(),
                'block' => $block,
                'customers' => $customers,
                'projects' => @$projects,
                'questionnarie_templates' => @$questionnarie_templates,
                'questionnaries' => @$questionnaries,
                'validation_messages' => $messages,
                'customer' => @$customer,
                'subblock' => 'cdetails',
                'orders' => $orders
            ]
        );
    }

    /**
     * Shows the customer questionnaries to the the user.
     *
     * @return Response
     */
    public function questionnaries(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktCustomer::triggerEvent($request);
            if(isset($messages['messages'])) {
                $messages = $messages['messages'];
            }
        }

        $data = ktUser::getUserData();
        $customer = ktCustomer::getCustomerData(Route::input('customer_id'));
        $projects = ktProject::getProjectsData();
        $questionnarie_templates = ktSettings::getQuestionnaries();
        $questionnaries = ktSettings::getCustomerQuestionnaries($customer->id);
        $customers = ktCustomer::getCustomersData();
        $boards_list = ktBoard::getBoards();

        $block  ='customer';
        if(!is_object($customer)){
            $block  ='clean';
        }

        return view('office.office',
            [
                'data' => $data,
                'qlist' => array(),
                'block' => $block,
                'customers' => $customers,
                'questionnarie_templates' => $questionnarie_templates,
                'questionnaries' => $questionnaries,
                'projects' => @$projects,
                'validation_messages' => $messages,
                'customer' => @$customer,
                'subblock' => 'quote'
            ]
        );
    }


    /**
     * Show the project workstream to the user.
     *
     * @return Response
     */
    public function workstream(Request $request)
    {

        $data = ktUser::getUserData();
        $customers = ktCustomer::getCustomersData();

        $project = ktProject::getProjectData(Route::input('project_id'));
        $projects = ktProject::getProjectsData();

        $workstream = ktProject::getWorkstream(Route::input('project_id'));
        $boards_list = ktBoard::getBoards();
        $users_list = ktUser::getUsers(true);

        $block  ='project';
        if(!is_object($project)){
            $block  ='clean';
        }

        return view('office.office',
            [
                'data' => $data,
                'customers' => $customers,
                'workstream' => @$workstream,
                'boards_list' => $boards_list,
                'block' => $block,
                'users' => @$users_list,
                'project' => @$project,
                'projects' => @$projects,
                'subblock' => 'workstream'
            ]
        );
    }

    /**
     * Show the project back log to the user.
     *
     * @return Response
     */
    public function backlog(Request $request)
    {

        $data = ktUser::getUserData();
        $customers = ktCustomer::getCustomersData();

        $project = ktProject::getProjectData(Route::input('project_id'));
        $projects = ktProject::getProjectsData();
        $backlog = ktBoard::getProjectTasks(Route::input('project_id'), NULL);

        $board_templates = ktUser::getBoardTemplates();
        $boards_list = ktBoard::getBoards();
        $users_list = ktUser::getUsers(true);

        $block  ='project';
        if(!is_object($project)){
            $block  ='clean';
        }

        return view('office.office',
            [
                'data' => $data,
                'customers' => $customers,
                'tasks' => @$backlog,
                'users' => @$users_list,
                'boards_list' => $boards_list,
                'block' => $block,
                'project' => @$project,
                'projects' => @$projects,
                'subblock' => 'backlog'
            ]
        );
    }

    /**
     * Show the project file sharepoint to the user.
     *
     * @return Response
     */
    public function browse(Request $request)
    {

        $data = ktUser::getUserData();
        $block  ='project';
        $project = ktProject::getProjectData(Route::input('project_id'));
        $users_list = ktUser::getUsers(true);
        
        return view('office.files',
            [
                'data' => $data,
                'block' => $block,
                'project' => @$project,
                'subblock' => 'files'
            ]
        );
    }

    /**
     * And action to download a file from the project file sharepoint..
     *
     * @return Response
     */
    public function download(Request $request)
    {
        if($request->isMethod('post') || $request->isMethod('get')){

            $file = $request->input('file');

            $file_path = base_path() . '/accounts/' . ktUser::getAccountId() . '/project/'.Route::input('project_id'). '/' . $file;
            if(file_exists($file_path)){
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($file));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                readfile($file_path);
            }else{
                echo json_encode(['error' => 'file does not exist']);
            }
        }
        exit();
    }


     /**
     * Show the project requirements to the user.
     *
     * @return Response
     */
    public function requirements(Request $request)
    {

        $data = ktUser::getUserData();
        $customers = ktCustomer::getCustomersData();

        $project = ktProject::getProjectData(Route::input('project_id'));
        $projects = ktProject::getProjectsData();
        $backlog = ktBoard::getProjectTasks(Route::input('project_id'), NULL);

        $board_templates = ktUser::getBoardTemplates();
        $boards_list = ktBoard::getBoards();
        $users_list = ktUser::getUsers(true);

        $project_requirements = ktProject::getProjectRequirements(Route::input('project_id'));

        $block  ='project';
        if(!is_object($project)){
            $block  ='clean';
        }

        return view('office.office',
            [
                'data' => $data,
                'customers' => $customers,
                'tasks' => @$backlog,
                'users' => @$users_list,
                'boards_list' => $boards_list,
                'block' => $block,
                'project' => @$project,
                'requirements' => @$project_requirements,
                'projects' => @$projects,
                'subblock' => 'requirements'
            ]
        );
    }

    public function newClientUser(Request $request)
    {

        $messages = array();

        if($request->isMethod('post')){
            $messages = ktCustomer::triggerEvent($request);
        }

        $data = ktUser::getUserData();
        $customers = ktCustomer::getCustomersData();
        $projects = ktProject::getProjectsData();
        $customer = ktCustomer::getCustomerData(Route::input('customer_id'));
        $orders = ktSettings::getQuestionnaries();

        return view('office.office',
            [
                'data' => $data,
                'validation_messages' => $messages,
                'customer' => $customer,
                'projects' => @$projects,
                'customers' => $customers,
                'block' => 'customer',
                'subblock' => 'new_user',
                'orders' => $orders
            ]
        );
    }

    public function listClientUsers(Request $request)
    {
        $messages = array();

        if($request->isMethod('post')){
            $messages = ktCustomer::triggerEvent($request);
        }

        $data = ktUser::getUserData();
        $customers = ktCustomer::getCustomersData();
        $projects = ktProject::getProjectsData();
        $customer = ktCustomer::getCustomerData(Route::input('customer_id'));
        $users = ktUser::getClientUsers();
        $orders = ktSettings::getQuestionnaries();

        return view('office.office',
            [
                'data' => $data,
                'validation_messages' => $messages,
                'customer' => $customer,
                'projects' => @$projects,
                'users' => @$users,
                'customers' => $customers,
                'block' => 'customer',
                'subblock' => 'manage_client_users',
                'users_count' => count($users),
                'orders' => $orders
            ]
        );
    }

    public function customerInvoices(Request $request)
    {
        $data = ktUser::getUserData();
        $invoices = ktFinance::getInvoices(['INVOICE']);
        $account = ktUser::getAccountData();
        $customer = ktCustomer::getCustomerData(Route::input('customer_id'));
        $orders = ktSettings::getQuestionnaries();

        return view('office.office', [
            'block' => 'customer',
            'customer' => $customer,
            'subblock' => 'client_invoices',
            'customers' => array(),
            'account' => @$account,
            'validation_messages'   => @$messages,
            'invoices' => @$invoices,
            'data'  => @$data,
            'orders' => $orders
        ]);

    }

    public function customerQuotes(Request $request)
    {
        $data = ktUser::getUserData();
        $quotes = ktFinance::getInvoices(['QUOTE']);
        $account = ktUser::getAccountData();
        $customer = ktCustomer::getCustomerData(Route::input('customer_id'));
        $orders = ktSettings::getQuestionnaries();

        return view('office.office', [
            'block' => 'customer',
            'customer' => $customer,
            'subblock' => 'client_quotes',
            'customers' => array(),
            'account' => @$account,
            'validation_messages'   => @$messages,
            'quotes' => @$quotes,
            'data'  => @$data,
            'orders' => $orders
        ]);

    }

}
