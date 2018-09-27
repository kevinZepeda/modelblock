<div class="col-md-2" style="padding-right:0px;"  id="top-menu-bar">

    <ul class="tabs">
        <li class="{{ (isset($subblock) && strpos($subblock,'details') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/project/".$project->id) }}" class="tab-item"><i class="ion ion-ios-information"></i>{{trans('office.project.side_menu.project_detials')}}</a></li>
        <li><a id="tab_id" href="/office/project/{{$project->id}}/wiki/home" class="tab-item"><i class="ion ion-ios-world"></i>Wiki Documentation</a></li>
        <li class="{{ (isset($subblock) && strpos($subblock,'workstream') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/project/workstream/".$project->id) }}" class="tab-item"><i class="ion ion-chatbox-working"></i>{{trans('office.project.side_menu.workstream')}}</a></li>
        {{--<li><a id="tab_id" href="#" class="tab-item"><i class="ion ion-ios-grid-view"></i>Tasks</a></li>--}}
    </ul>
    <div style="border-bottom:1px solid #D5D5D5;"></div>

    <ul class="tabs">
        <li class="{{ (isset($subblock) && strpos($subblock,'backlog') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/project/backlog/".$project->id) }}" class="tab-item"><i class="ion ion-easel"></i>{{trans('office.project.side_menu.project_backlog')}}</a></li>
        <li><a href="#" class="tab-item new-task"><i class="ion ion-android-add-circle"></i>{{trans('office.project.side_menu.new_task_note')}}</a></li>
    </ul>
    <div style="border-bottom:1px solid #D5D5D5;"></div>

    <ul class="tabs">
        <li class="{{ (isset($subblock) && strpos($subblock,'sharepoint') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/project/files/".$project->id) }}" class="tab-item"><i class="ion ion-android-cloud-circle"></i>{{trans('office.project.side_menu.file_share')}}</a></li>
    </ul>
    <div style="border-bottom:1px solid #D5D5D5;"></div>

    <ul class="tabs">
        <li class="{{ (isset($subblock) && strpos($subblock,'requirements') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/project/requirements/".$project->id) }}" class="tab-item"><i class="ion ion-ios-checkmark"></i>{{trans('office.project.side_menu.requirements')}}</a></li>
        <li><a id="tab_id" href="#" class="tab-item new-requirement"><i class="ion ion-android-add-circle"></i>{{trans('office.project.side_menu.new_requirement')}}</a></li>
    </ul>
</div>