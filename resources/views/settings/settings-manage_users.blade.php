@if($departments_count > 0)
<h4 style="margin: 0px !important;color:#909090;">{{trans('settings.manage_users.title')}} ({{$users_count}})</h4>
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
            <form role="form" method="POST" action="{{ url('/settings/manage-users') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_user_id" id="_user_id" value="{{ $user->id }}">
                <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                    <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.manage_users.input.first_name')}}"  style="width:200px;" id="first_name" name="first_name" value="{{$user->first_name}}"/></div>
                    <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.manage_users.input.last_name')}}"  style="width:200px;" id="last_name" name="last_name" value="{{$user->last_name}}"/></div>
                    <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.manage_users.input.email_address')}}"  style="width:200px;" id="email" name="email" value="{{$user->email}}"/></div>
                    <div style="padding-bottom:4px;">
                        <select style="width:200px;" placeholder="Department" id="department" name="department">
                            <option value="0">Default</option>
                            @foreach($departments as $department)
                                <option value="{{$department->id}}" @if($department->id == $user->department_id) selected @endif>{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="padding-bottom:4px;">
                        <select style="width:200px;" placeholder="User Type" id="user_level" name="user_level">
                            <option value="USER"{{ $user->user_level == 'USER' ? ' selected' : '' }}>{{trans('settings.manage_users.user')}}</option>
                            <option value="ADMIN"{{ $user->user_level == 'ADMIN' ? ' selected' : '' }}>{{trans('settings.manage_users.admin')}}</option>
                            <option value="MANAGER"{{ $user->user_level == 'MANAGER' ? ' selected' : '' }}>{{trans('settings.manage_users.manager')}}</option>
                        </select>
                    </div>
                    <div style="padding-bottom:4px;">
                        <select style="width:200px;" placeholder="User Type" id="active" name="active">
                            <option value="1"{{ $user->active == 1 ? ' selected' : '' }}>{{trans('settings.manage_users.active')}}</option>
                            <option value="0"{{ $user->active == 0 ? ' selected' : '' }}>{{trans('settings.manage_users.inactive')}}</option>
                        </select>
                    </div>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"></h6><?php echo trans('settings.manage_users.explanation'); ?></div>
                    <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                    <div><button id="action" name="action" value="user_update" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button id="action" onclick="javascript:return confirm('{{trans('settings.manage_users.delete_user_message')}}');" name="action" value="delete_user" style="float:left;margin-right:10px;"/>{{trans('settings.button.delete')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="user-{{ $user->id }}" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
                </div>
            </form>
        </div>

    @endforeach
</div>
@endif
@if($departments_count > 0)
<h4 style="padding-top:20px; margin: 0px !important;color:#909090;">{{trans('settings.manage_departments.title')}} ({{$departments_count}})</h4>
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">
    @foreach($departments as $department)

        <div class="tab-edit-item{{ $cnt++ == $departments_count ? '-last' : '' }} preview" id="department-{{ $department->id }}">

            <div style="display:inline-block;">
                <div style="width:200px;display:inline-block;">{{ $department->name }}</div>
            </div>
            <div style="float:right;display:inline-block;">
                {{trans('settings.button.edit')}}
            </div>
        </div>
        <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="department-{{ $department->id }}-editor">
            <form role="form" method="POST" action="{{ url('/settings/manage-users') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_department_id" id="_department_id" value="{{ $department->id }}">
                <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                    <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.manage_departments.department_name')}}"  style="width:200px;" id="department_name" name="department_name" value="{{$department->name}}"/></div>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.manage_departments.explanation'); ?></h6></div>
                    <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                    <div><button id="action" name="action" value="update_department" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button id="action" onclick="javascript:return confirm('{{trans('settings.manage_departments.message')}}');" name="action" value="delete_department" style="float:left;margin-right:10px;"/>{{trans('settings.button.delete')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="department-{{ $department->id }}" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
                </div>
            </form>
        </div>

    @endforeach
</div>
@endif
