<?php namespace App\Http\Controllers;

use App\Models\ktFinance;
use Illuminate\Http\Request;
use Auth;
use Response;
use Session;
use Input;
use App\Models\ktBoard;
use App\Models\User;
use App\Models\ktCustomer;
use App\Models\ktProject;
use App\Models\ktTime;
use App\Models\ktSettings;
use App\Models\ktQuote;

class ApiController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | API Controller handles the AJAX calls from the application
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's renders the API calls
    | and sends back a json response depending of the case..
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
     * Each case is a separated api call rendered by the Models.
     *
     * @return void
     */
    public function index(Request $request){

        if ( Session::token() !== $request->input( '_token' ) ) {
            return Response::json( array(
                'msg' => trans('application.intruder_api')
            ) );
        }

        switch($request->input('event')){
            case 'new_board':
            case 'edit_board':            
            case 'new_task':
            case 'publish':
            case 'unpublish':
            case 'lock':
            case 'unlock':
            case 'state_update':
            case 'get_task':
            case 'subject_update':
            case 'desc_update':
            case 'type_update' :
            case 'priority_update':
            case 'manager_update':
            case 'assignee_update':
            case 'estimate_update':
            case 'board_update':
            case 'comment':
            case 'avatar' :
            case 'invoice_logo':
            case 'system_logo':
            case 'get_avatar':
            case 'get_system_logo':
            case 'project_update':
            case 'column_rename':
            case 'new_requirement':  
            case 'delete_board':
            case 'delete_task':
            case 'delete_requirement':
            case 'default':
            case 'undefault':
            case 'new_child_board':
            case 'completed_task':
                $response = ktBoard::triggerEvent($request);
                break;
            case 'new_customer':
            case 'delete_customer':
            case 'new_customer_questionnarie':
            case 'update_client_user':
            case 'delete_client_user':
                if(Auth::user()->canManage() || Auth::user()->isAdmin()) {
                    $response = ktCustomer::triggerEvent($request);
                }
                break;
            case 'get_requirement':
            case 'sharepoint':
            case 'share_download':
            case 'share_delete':
            case 'r_subject_update':
            case 'r_details_update':
            case 'files':
            case 'workstream_post':
            case 'p_subject_update':
            case 'p_desc_update':
            case 'delete_comment':
                $response = ktProject::triggerEvent($request);
                break;         
            case 'new_project':
            case 'delete_project':
                if(Auth::user()->canManage() || Auth::user()->isAdmin()) {
                    $response = ktProject::triggerEvent($request);
                }
                break;
            case 'new_item':
            case 'new_pre_tax':
            case 'new_tax':
            case 'delete_item':
            case 'get_customer_address':
            case 'download_invoice':
            case 'delete_invoice':
            case 'subscription_ready':
            case 'issue_invoice':
            case 'archive_invoice':
            case 'invoice_state_update':
            case 'clone_invoice':
            case 'finance_search':
            case 'mail_invoice':
                if(Auth::user()->canManage() || Auth::user()->isAdmin()) {
                    $response = ktFinance::triggerEvent($request);
                }
                break;
            case 'new_time_entry':
            case 'start_stopwatch':
            case 'stop_stopwatch':
            case 'get_time_entry':
            case 'update_time_entry':
            case 'delete_entry':
                $response = ktTime::triggerEvent($request);
                break; 
            case 'new_questionnarie':
            case 'edit_questionnarie':
            case 'publish_questionnarie':
                $response = ktSettings::triggerEvent($request);
                break;   
            case 'delete_questionnaire':
                $response = ktQuote::triggerEvent($request);
                break;
            case 'client_download_invoice':
                if(Auth::user()->isClient()) {
                    $response = ktFinance::triggerEvent($request);
                }
            default:
                 $response = [
                    'status' => 'error'
                 ]; 
        }

        return Response::json( $response );
    }

}
