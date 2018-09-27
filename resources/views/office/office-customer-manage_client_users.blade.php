<h4 style="margin: 0px !important;color:#909090;">Manage Client Users ({{$users_count}})</h4>
@include('val-errors')
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">
    <?php $cnt = 0; ?>
    @foreach($users as $user)

        <div class="tab-edit-item{{ $cnt++ == $users_count ? '-last' : '' }} preview" id="user-{{ $user->id }}">

            <div style="display:inline-block;">
                <div style="width:200px;display:inline-block;">{{ $user->first_name . ' ' . $user->last_name  }}</div>
                <div style="width:200px;display:inline-block;color:#BBB2B2;">{{ $user->email }}</div>
                <div style="width:150px;display:inline-block;color:#BBB2B2;">{{ $user->active == 1 ? trans('settings.manage_users.active') : trans('settings.manage_users.inactive') }}</div>
                <div style="width:150px;display:inline-block;color:#BBB2B2;">{{ ucfirst(trans('settings.manage_users.'.strtolower($user->user_level))) }}</div>
            </div>
            <div style="float:right;display:inline-block;">
                {{trans('settings.button.edit')}}
            </div>
        </div>
        <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="user-{{ $user->id }}-editor">
            <form role="form" method="POST" action="{{ url("/office/customer/users/".$customer->id) }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_user_id" id="_user_id" value="{{ $user->id }}">
                <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                    <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.manage_users.input.first_name')}}"  style="width:200px;" id="first_name" name="first_name" value="{{$user->first_name}}"/></div>
                    <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.manage_users.input.last_name')}}"  style="width:200px;" id="last_name" name="last_name" value="{{$user->last_name}}"/></div>
                    <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.manage_users.input.email_address')}}"  style="width:200px;" id="email" name="email" value="{{$user->email}}"/></div>
                    <div style="padding-bottom:4px;">
                        <select style="width:200px;" placeholder="User Type" id="active" name="active">
                            <option value="1"{{ $user->active == 1 ? ' selected' : '' }}>{{trans('settings.manage_users.active')}}</option>
                            <option value="0"{{ $user->active == 0 ? ' selected' : '' }}>{{trans('settings.manage_users.inactive')}}</option>
                        </select>
                    </div>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"></h6><?php echo trans('settings.manage_users.explanation'); ?></div>
                    <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                    <div><button id="event" name="event" value="update_client_user" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button id="event" onclick="javascript:return confirm('{{trans('settings.manage_users.delete_user_message')}}');" name="event" value="delete_client_user" style="float:left;margin-right:10px;"/>{{trans('settings.button.delete')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="user-{{ $user->id }}" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
                </div>
            </form>
        </div>

    @endforeach
</div>
