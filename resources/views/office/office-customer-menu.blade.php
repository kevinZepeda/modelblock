<div class="col-md-2" style="padding-right:0px;"  id="top-menu-bar">

    <ul class="tabs">
        <li class="{{ (isset($subblock) && strpos($subblock,'details') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/customer/".$customer->id) }}"  class="tab-item"><i class="ion ion-ios-information"></i>{{trans('office.customer.side_menu.customer_details')}}</a></li>
    </ul>

    @if(!Auth::user()->isClient())
    <div style="border-bottom:1px solid #D5D5D5;"></div>
    <ul class="tabs">
        <li class="{{ (isset($subblock) && strpos($subblock,'quote') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/customer/questionnaries/".$customer->id) }}" class="tab-item"><i class="ion ion-ios-list"></i>{{trans('office.customer.side_menu.questionnaries')}}</a></li>
        <li class="new-quest"><a id="tab_id" href="#" class="tab-item"><i class="ion ion-plus"></i>{{trans('office.customer.side_menu.new_quest')}}</a></li>
    </ul>
    <div style="border-bottom:1px solid #D5D5D5;"></div>
    <ul class="tabs">
        <li class="{{ (isset($subblock) && strpos($subblock,'manage_client_users') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/customer/users/".$customer->id) }}" class="tab-item"><i class="ion ion-person-stalker"></i>Manage Client Users</a></li>
        <li class="{{ (isset($subblock) && strpos($subblock,'new_user') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/customer/new-user/".$customer->id) }}" class="tab-item"><i class="ion ion-person-add"></i>New Client User</a></li>
    </ul>
    @endif

    @if(Auth::user()->isClient())
        <div style="border-bottom:1px solid #D5D5D5;"></div>
        <ul class="tabs">
            <li class="{{ (isset($subblock) && strpos($subblock,'new_project') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="#" class="tab-item project-request"><i class="ion ion-cube"></i>Start a New Project</a></li>
        </ul>
        <div style="border-bottom:1px solid #D5D5D5;"></div>
        <ul class="tabs">
            <li class="{{ (isset($subblock) && strpos($subblock,'client_invoices') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/customer/invoices/".$customer->id) }}" class="tab-item"><i class="ion ion-android-arrow-forward"></i>Invoices</a></li>
            <li class="{{ (isset($subblock) && strpos($subblock,'client_quotes') !== false ) ? 'tab-li-active'  : '' }}"><a id="tab_id" href="{{ url("/office/customer/quotes/".$customer->id) }}" class="tab-item"><i class="ion ion-ios-paper"></i>Quotes</a></li>
        </ul>
    @endif

   <!--  <div style="border-bottom:1px solid #D5D5D5;"></div>

    <ul class="tabs">
        <li ><a id="tab_id" class="tab-item"><i class="ion ion-android-arrow-forward"></i>Customers Invoices</a></li>
    </ul> -->

</div>