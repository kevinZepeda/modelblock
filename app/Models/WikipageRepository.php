<?php  namespace App\Models;

use App\Models\ktUser;
use App\Http\Requests;
use Parsedown;
use App\Modules\ParsedownExtension;
use App\Models\ktProject;

class WikipageRepository {

    protected $datapath;

    public function __construct()
    {
        $this->datapath = base_path() . '/accounts/'. $user = ktUser::getAccountId();
        ;
    }

    public function setDatapath($newpath)
    {
        $this->datapath = $newpath;
    }

    public function getPage($project, $page)
    {
        $account_id = ktUser::getAccountId();

        $page_name = urlencode(strtolower($page));
        $link = '/office/project/'.$project.'/wiki/'.$page_name.'?create=1&t='.csrf_token();

        $page_object = ktProject::getWikiPage($account_id,$project, $page_name);

        $id = '';

        if(!is_object($page_object)) {
            $content = "#### Page not found\n\nIt looks like this page is not yet created. [To create the new page click here >>]($link)\n\n";
        }else{
            $content = $page_object->content;
            $id = $page_object->id;
        }

        if(!ktProject::pageExists($account_id,$project, $page_name) && @$_GET['create'] == 1 && @$_GET['t'] == csrf_token()){
            ktProject::createEmptyWikiPage($account_id,$project, $page_name);
            $page_object = ktProject::getWikiPage($account_id,$project, $page_name);
            $content = $page_object->content;
            $id = $page_object->id;
        }

        $Parsedown = new ParsedownExtension($project, ktUser::getAccountId());
        $pagehtml = $Parsedown->text($content);

        $page = new Wikipage($page, $pagehtml, $id);
        return $page;
    }
}