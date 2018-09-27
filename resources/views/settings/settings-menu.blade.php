<div class="col-md-2 kt-tab" style="padding-right:0px;">
    <ul class="tabs">
        <li class="{{ (isset($block) && strpos($block,'profile') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_1" href="{{ url("/settings/profile") }}" class="tab-item"><i class="ion ion-person"></i>{{trans('settings.left_menu.user_profile')}}</a></li>
    </ul>
    @if(Auth::user()->isAdmin())
        <div style="border-bottom:1px solid #D5D5D5;"></div>
        <ul class="tabs">
            <li class="{{ (isset($block) && strpos($block,'general') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings") }}" class="tab-item"><i class="ion ion-ios-gear"></i>{{trans('settings.left_menu.general')}}</a></li>
        </ul>
        <div style="border-bottom:1px solid #D5D5D5;"></div>
    @endif
    @if(Auth::user()->isAdmin() || Auth::user()->canManage())
        <ul class="tabs">
             <li class="{{ (isset($block) && strpos($block,'finance') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/finance") }}" class="tab-item"><i class="ion ion-card"></i>{{trans('settings.left_menu.finance')}}</a></li>
        </ul>
        <div style="border-bottom:1px solid #D5D5D5;"></div>
        <ul class="tabs">
             <li class="{{ (isset($block) && strpos($block,'categories') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/finance/category") }}" class="tab-item"><i class="ion ion-folder"></i>{{trans('settings.left_menu.expense_category')}}</a></li>
                    <li class="{{ (isset($block) && strpos($block,'new_category') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/new-category") }}" class="tab-item"><i class="ion ion-plus-circled"></i>{{trans('settings.left_menu.new_category')}}</a></li>
        </ul>
        <div style="border-bottom:1px solid #D5D5D5;"></div>
        <ul class="tabs">
            <li class="{{ (isset($block) && strpos($block,'manage_users') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/manage-users") }}" class="tab-item"><i class="ion ion-person-stalker"></i>{{trans('settings.left_menu.manage_users')}}</a></li>
            <li class="{{ (isset($block) && strpos($block,'new_user') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/new-user") }}" class="tab-item"><i class="ion ion-person-add"></i>{{trans('settings.left_menu.new_user')}}</a></li>
            <li class="{{ (isset($block) && strpos($block,'new_department') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/new-department") }}" class="tab-item"><i class="ion ion ion-plus-circled"></i>{{trans('settings.left_menu.new_department')}}</a></li>
        </ul>
        <div style="border-bottom:1px solid #D5D5D5;"></div>

        <ul class="tabs">
            <li class="{{ (isset($block) && strpos($block,'manage_columns') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/manage-board-templates") }}" class="tab-item"><i class="ion ion-ios-albums"></i>{{trans('settings.left_menu.manage_boards')}}</a></li>
            <li class="{{ (isset($block) && strpos($block,'new_columns') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/new-board-template") }}" class="tab-item"><i class="ion ion-plus-circled"></i>{{trans('settings.left_menu.new_board')}}</a></li>
        </ul>
        <div style="border-bottom:1px solid #D5D5D5;"></div>

        <ul class="tabs">
            <li class="{{ (isset($block) && strpos($block,'questionnaire_list') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/qunestionnaries") }}" class="tab-item"><i class="ion ion-ios-list"></i>{{trans('settings.left_menu.manage_questionnaries')}}</a></li>
            <li class="{{ (isset($block) && strpos($block,'new_questionnaire') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/questionnarie/new") }}" class="tab-item"><i class="ion ion-plus-circled"></i>{{trans('settings.left_menu.new_questionnaries')}}</a></li>
        </ul>
        <div style="border-bottom:1px solid #D5D5D5;"></div>
    @endif
    @if(!Auth::user()->isClient())
    <ul class="tabs">
        <li class="{{ (isset($block) && strpos($block,'notifications') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/settings/notifications") }}" class="tab-item"><i class="ion ion-android-notifications"></i>{{trans('settings.left_menu.notifications')}}</a></li>
    </ul>
    @endif

</div>