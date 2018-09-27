<?php namespace App\Http\Controllers;

use App\Models\WikipageRepository;
use View;
use App\Models\ktUser;
use App\Models\ktProject;
use App\Http\Requests;
use Illuminate\Http\Request;

class WikiController extends Controller {

    public function __construct(WikipageRepository $pages)
    {
        $this->pages = $pages;
    }

    public function showPage($project, $page, Request $request)
    {
        $page = $this->pages->getPage($project, $page);
        $data = ktUser::getUserData();
        $project = ktProject::getProjectData($project);

        if($request->isMethod('post')){
            if(ktProject::triggerEvent($request) === false){
                $page_name = urlencode(strtolower($request->input('page_title')));
                $redirect_to = '/office/project/'.$project->id.'/wiki/'.$page_name;
                return redirect($redirect_to);
            }
        }

        $pages = [];
        $pages = ktProject::getProjectWikiPages(ktUser::getAccountId(), $project->id);
        return View::make('wiki.wiki', [
            'page' => $page,
            'data' => @$data,
            'project' => @$project,
            'pages' => $pages
        ]);
    }

    public function editPage($project, $page, Request $request)
    {
        $page_name = urlencode(strtolower($page));
        $page = ktProject::getWikiPage(ktUser::getAccountId(), $project, $page_name);

        $data = ktUser::getUserData();
        $project = ktProject::getProjectData($project);

        $page->title = $page->page_title;

        $pages = [];
        $pages = ktProject::getProjectWikiPages(ktUser::getAccountId(), $project->id);

        return View::make('wiki.edit', [
            'page' => $page,
            'data' => @$data,
            'project' => @$project,
            'edit' => true,
            'pages' => $pages
        ]);
    }
}