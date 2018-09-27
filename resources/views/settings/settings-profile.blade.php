
<h4 style="margin: 0px !important;color:#909090;">{{trans('settings.profile.title')}}</h4>

@include('val-errors')

<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">

    <div class="tab-edit-item preview" id="profile-avatar">
        <div style="display:inline-block;">
            <div style="width:250px;display:inline-block;">{{trans('settings.profile.avatar.title')}}</div>
            <div style="display:inline-block;color:#BBB2B2;">{{trans('settings.profile.avatar.click_to_edit_preview')}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="profile-avatar-editor">

        <div class="row" style="margin: 0px !important;font-size:13px;padding-left:10px;padding-top:15px;">
            <div class="col-md-4" style="padding-right:10px;padding-bottom:15px;">
                @if($data->avatar != NULL)
                    <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image={{$data->avatar}}" class="user-image-board profile-image" alt="User Image"/>
                @else
                    <img src="{{ asset('/assets/dist/img/profile-placeholder.png') }}" class="user-image-board profile-image" alt="User Image"/>
                @endif
            </div>
            <div class="col-md-8" style="padding-right:10px;padding-bottom:15px;">
                <form action="{{ url('/api') }}" class="dropzone dz-clickable" id="my-awesome-dropzone"  method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="event" name="event" value="avatar">
                    <div class="dz-message">
                        {{trans('settings.profile.avatar.dropzone')}}<br>
                        <span class="dz-note"><?php echo trans('settings.profile.avatar.dropzone_restrictions'); ?></span>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <div class="tab-edit-item preview" id="profile-full-name">
        <div style="display:inline-block;">
            <div style="width:250px;display:inline-block;">{{trans('settings.profile.full_name.title')}}</div>
            <div style="display:inline-block;color:#BBB2B2;">{{ $data->first_name }} {{ $data->last_name }}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="profile-full-name-editor">
        <form role="form" method="POST" action="{{ url('/settings/profile') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.profile.full_name.input.first_name')}}" style="width:200px;" id="first_name" name="first_name"  value="{{ $data->first_name }}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.profile.full_name.input.last_name')}}"  style="width:200px;" id="last_name" name="last_name"  value="{{ $data->last_name }}"/></div>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.profile.full_name.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="full_name" style="float:left;margin-right:10px;" type="submit"/>{{trans('settings.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" style="float:left;" name="profile-full-name" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
            </div>
        </form>
    </div>

    <div class="tab-edit-item preview">
        <div style="display:inline-block;">
            <div style="width:250px;display:inline-block;">{{trans('settings.profile.email.title')}}</div>
            <div style="display:inline-block;color:#BBB2B2;">{{ $data->email }}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.profile.email.message')}}
        </div>
    </div>

    <div class="tab-edit-item preview" id="profile-password">
        <div style="display:inline-block;">
            <div style="width:250px;display:inline-block;">{{trans('settings.profile.password.title')}}</div>
            <div style="display:inline-block;color:#BBB2B2;">{{trans('settings.profile.password.message')}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="profile-password-editor">
        <form role="form" method="POST" action="{{ url('/settings/profile') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.profile.password.input.current_password')}}" style="width:200px;" id="current_password" name="current_password" type="password"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.profile.password.input.new_password')}}"  style="width:200px;" id="new_password" name="new_password" type="password"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.profile.password.input.re_new_password')}}"  style="width:200px;" id="repeat_new_password" name="repeat_new_password" type="password"/></div>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.profile.password.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="password" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button onclick="javascript:return false;"  value="cancel" style="float:left;" name="profile-password" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
            </div>
        </form>
    </div>

    <div class="tab-edit-item preview"  id="profile-language">
        <div style="display:inline-block;">
            <div style="width:250px;display:inline-block;">{{trans('settings.profile.language.title')}}</div>
            <div style="display:inline-block;color:#BBB2B2;">
                @foreach(\App\Models\ktLang::$appLanguages as $key)
                    @if($data->language == $key)
                        {{trans('settings.profile.languages.'.$key)}}
                    @endif
                @endforeach
            </div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="profile-language-editor">
        <form role="form" method="POST" action="{{ url('/settings/profile') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                <div style="padding-bottom:4px;">
                    <select style="width:200px;" id="language" name="language">
                        @foreach(\App\Models\ktLang::$appLanguages as $key)
                            <option value="{{$key}}"{{ $data->language == $key ? ' selected' : ''}}>{{trans('settings.profile.languages.'.$key)}}</option>
                        @endforeach
                    </select>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.profile.language.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="language" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="profile-language" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
            </div>
        </form>
    </div>


</div>