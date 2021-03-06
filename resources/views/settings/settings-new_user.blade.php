<h4 style="margin: 0px !important;color:#909090;">{{trans('settings.new_user.title')}}</h4>
@include('val-errors')
@if ($validation_messages !== false)
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">
    <form role="form" method="POST" action="{{ url('/settings/new-user') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="tab-edit-item" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;">
            <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.new_user.input.first_name')}}" style="width:200px;" id="first_name" name="first_name" value="{{@$request['first_name']}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.new_user.input.last_name')}}" style="width:200px;" id="last_name" name="last_name" value="{{@$request['last_name']}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.new_user.input.email_address')}}"  style="width:200px;" id="email_address" name="email_address" value="{{@$request['email_address']}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.new_user.input.password')}}" type="password"  style="width:200px;" id="password" name="password" value="{{@$request['password']}}"/></div>
                <div style="padding-bottom:4px;">
                    <select style="width:200px;" placeholder="User Type" id="user_level" name="user_level">
                        <option>{{trans('settings.new_user.user_level')}}</option>
                        <option value="USER"{{ @$request['user_level'] == 'USER' ? ' selected' : '' }}>{{trans('settings.manage_users.user')}}</option>
                        <option value="ADMIN"{{ @$request['user_level'] == 'ADMIN' ? ' selected' : '' }}>{{trans('settings.manage_users.admin')}}</option>
                        <option value="MANAGER"{{ @$request['user_level'] == 'MANAGER' ? ' selected' : '' }}>{{trans('settings.manage_users.manager')}}</option>
                    </select>
                </div>
                <div style="padding-bottom:4px;text-align:left;">
                    <h6 style="font-weight: 300 !important;">
                        <?php echo trans('settings.new_user.explanation1'); ?>
                    </h6>
                    <h6 style="font-weight: 300 !important;">
                        <?php echo trans('settings.new_user.explanation2'); ?>
                    </h6>
                    <h6 style="font-weight: 300 !important;">
                        <?php echo trans('settings.new_user.explanation3'); ?>
                    </h6>
                </div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="new_user" type="submit" style="float:left;margin-right:10px;"/>{{trans('settings.button.create')}}</button></div>
            </div>
        </div>
    </form>
</div>
@endif