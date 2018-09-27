<h4 style="margin: 0px !important;color:#909090;">{{trans('settings.email_notifications.title')}}</h4>
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">

    <div class="tab-edit-item preview" id="notifications" style="border:0px;">
        <div style="display:inline-block;">
            <div style="width:400px;display:inline-block;">{{trans('settings.email_notifications.note')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;"></div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>

        @foreach($settings as $key => $value)
            <div class="tab-edit-item">
                <div style="display:inline-block;">
                    <div style="width:400px;display:inline-block;padding-left:20px;padding-left:20px;">{{trans('settings.email_notifications.notifications_types.'.$key)}}</div>
                    <div style="width:200px;display:inline-block;color:#BBB2B2;">{{(!empty($value))?trans('settings.email_notifications.on'):trans('settings.email_notifications.off')}}</div>
                </div>
            </div>
        @endforeach
        
    </div>

    <div class="editor" id="notifications-editor" style="display:none;">

        <div style="display:inline-block;">
            <div style="width:400px;display:inline-block;">{{trans('settings.email_notifications.note')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;"></div>
        </div>

        <form role="form" method="POST" action="{{ url('/settings/notifications') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @foreach($settings as $key => $value)
                <div class="tab-edit-item">
                    <div style="display:inline-block;">
                        <div style="width:400px;display:inline-block;padding-left:20px;padding-left:20px;">{{trans('settings.email_notifications.notifications_types.'.$key)}}</div>
                           <div style="width:200px;display:inline-block;color:#BBB2B2;">
                            <input type="radio" name="{{$key}}" value="1" {{(!empty($value))?'checked':''}}>&nbsp;{{trans('settings.email_notifications.on')}}
                            <input type="radio" name="{{$key}}" value="0" style="margin-left:20px;" {{(!empty($value))?'':'checked'}}>&nbsp;{{trans('settings.email_notifications.off')}}
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="tab-edit-item">
                <div style="display:inline-block;">
                    <div>
                        <button id="action" name="action" value="notifications_update" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;
                        <button onclick="javascript:return false;" value="cancel" style="float:left;" name="notifications" class="cancel"/>{{trans('settings.button.cancel')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
