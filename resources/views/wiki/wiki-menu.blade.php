<div style="width:100%;text-align:center;background-color:#DEE2E7   !important;" id="top-menu-bar">
    <header class="main-header"
            style="max-width: 1100px;margin: 0 auto;text-align:left;background-color:#DEE2E7   !important;z-index:500;">
        <nav class="navbar navbar-static-top" role="navigation"
             style="background-color:#DEE2E7   !important;margin-left:0px;">

            <div style="float:left;padding-top:5px;" id="board-no-search">
                <h4>Project: {{substr($project->project_name,0 ,30)}}</h4>
            </div>

            <div style="float:left;padding-top:5px;padding-top:12px;display:none;" id="board-search">
                <select id="pages-list" style="width:800px;height:100px;">
                    <option></option>
                </select>
            </div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav" style="font-size:15px;">
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle"
                           style="color:gray !important;" id="search-page">
                            <i class="ion-ios-search-strong"></i></a>
                    </li>
                    <li class="dropdown messages-menu">
                        <a href="/office/project/{{$project->id}}/wiki/home" class="dropdown-toggle"
                           style="color:gray !important;" id="wiki-home">
                            <i class="ion-ios-home"></i></a>
                    </li>
                    <li class="dropdown messages-menu">
                        <a href="/office/project/{{$project->id}}" class="dropdown-toggle"
                           style="color:gray !important;" id="go-to-project">
                            <i class="ion-briefcase"></i></a>
                    </li>

                    @if(is_object($page))
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle"
                           style="color:gray !important;" id="delete-page">
                            <i class="ion-ios-trash"></i></a>
                    </li>

                    @if(isset($edit))
                        <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" style="color:gray !important;" id="save-page">
                                <i class="ion-android-done"></i></a>
                        </li>
                    @else

                        <li class="dropdown messages-menu">
                            <a href="/office/project/{{$project->id}}/wiki/edit/{{urlencode(strtolower($page->title))}}" class="dropdown-toggle" style="color:gray !important;" id="edit-wiki">
                                <i class="ion-edit"></i></a>
                        </li>
                    @endif
                    @endif
                </ul>
            </div>
        </nav>
    </header>
    <form>

    </form>
</div>