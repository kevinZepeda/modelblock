<div class="col-md-2 kt-tab" style="padding-right:0px;">

    <ul class="tabs">
        <li class="{{ (isset($block) && strpos($block,'invoices') !== false ) ? 'tab-li-active'  : '' }}"><a href="{{ url("/office/finance/invoices") }}" id="tab_id" class="tab-item"><i class="ion ion-android-arrow-forward"></i>{{trans('finance.side_menu.invoices')}}</a></li>
        <li class="{{ (isset($block) && strpos($block,'drafts') !== false ) ? 'tab-li-active'  : '' }}"><a href="{{ url("/office/finance/invoices/drafts") }}" id="tab_id"  class="tab-item" href="#"><i class="ion ion-archive"></i>{{trans('finance.side_menu.draft_invoices')}}</a></li>
        <li><a id="tab_id"  class="tab-item" href="{{ url("/office/finance/invoice?event=new_draft") }}"><i class="ion ion-android-add-circle"></i>{{trans('finance.side_menu.new_invoice')}}</a></li>
    </ul>
    <div style="border-bottom:1px solid #D5D5D5;"></div>

    <ul class="tabs">
        <li class="{{ (isset($block) && strpos($block,'archive') !== false ) ? 'tab-li-active'  : '' }}"><a href="{{ url("/office/finance/archive") }}" id="tab_id" class="tab-item"><i class="ion ion-ios-box"></i>{{trans('finance.side_menu.invoice_archive')}}</a></li>
    </ul>
    <div style="border-bottom:1px solid #D5D5D5;"></div>

    <ul class="tabs">
        <li class="{{ (isset($block) && strpos($block,'subscription') !== false ) ? 'tab-li-active'  : '' }}"><a href="{{ url("/office/finance/subscriptions") }}" id="tab_id" class="tab-item"><i class="ion ion-ios-loop-strong"></i>{{trans('finance.side_menu.subscriptions')}}</a></li>
        <li><a id="tab_id"  class="tab-item" href="{{ url("/office/finance/subscription?event=new_draft&type=RECURRING") }}"><i class="ion ion-android-add-circle"></i>{{trans('finance.side_menu.new_subscription')}}</a></li>
    </ul>

    <div style="border-bottom:1px solid #D5D5D5;"></div>

    <ul class="tabs">
        <li class="{{ (isset($block) && strpos($block,'quotes') !== false ) ? 'tab-li-active'  : '' }}"><a href="{{ url("/office/finance/quotes") }}" id="tab_id" class="tab-item"><i class="ion ion-ios-paper"></i>{{trans('finance.side_menu.quotes')}}</a></li>
        <li><a id="tab_id"  class="tab-item" href="{{ url("/office/finance/quote?event=new_draft&type=QUOTE") }}"><i class="ion ion-android-add-circle"></i>{{trans('finance.side_menu.new_quote')}}</a></li>
    </ul>
    <div style="border-bottom:1px solid #D5D5D5;"></div>

    <ul class="tabs">
        <li class="{{ (isset($block) && strpos($block,'expenses') !== false ) ? 'tab-li-active'  : '' }}"><a href="{{ url("/office/finance/expenses") }}" id="tab_id" class="tab-item"><i class="ion ion-android-arrow-back"></i>{{trans('finance.side_menu.expenses')}}</a></li>
        <li><a id="tab_id" href="{{ url("/office/finance/expense?event=new_draft&type=EXPENSE") }}" class="tab-item"><i class="ion ion-android-add-circle"></i>{{trans('finance.side_menu.new_expense')}}</a></li>
    </ul>

</div>