<h4 style="margin: 0px !important;color:#909090;">{{trans('settings.general.title')}}</h4>
@include('val-errors')
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">

    <div class="tab-edit-item preview" id="system-label">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.general.system_label.title')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;">{{@$account->system_label}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>

    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="system-label-editor">
        <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
            <form role="form" method="POST" action="{{ url('/settings') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.general.system_label.system_label')}}" id="system_label" name="system_label" value="{{@$account->system_label}}" style="width:200px;"/></div>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.general.system_label.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="update_system_label" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="system-label" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
            </form>
        </div>
    </div>



    <div class="tab-edit-item preview" id="invoice-logo">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.general.system_logo.title')}}</div>
            <div style="display:inline-block;color:#BBB2B2;">{{trans('settings.general.system_logo.click_to_edit')}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="invoice-logo-editor">

        <div class="row" style="margin: 0px !important;font-size:13px;padding-left:10px;padding-top:15px;">
            <div class="col-md-12" style="padding-right:10px;padding-bottom:15px;">
                @if(@$account->system_logo != NULL )
                    <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image={{@$account->system_logo}}" class="invoice-logo" alt="Invoice Logo"/>
                @else
                    <img class="invoice-logo"/>
                @endif
                <form action="{{ url('/api') }}" class="dropzone dz-clickable" id="my-awesome-dropzone"  method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="event" name="event" value="system_logo">
                    <div class="dz-message" style="font-size:16px;">
                        {{trans('settings.general.system_logo.drop_here')}}<br>
                        <span class="dz-note"><?php echo trans('settings.general.system_logo.allowed_files'); ?></span>
                    </div>
                </form>
            </div><br/>
            <form role="form" method="POST" action="{{ url('/settings') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div><button style="float:left;" id="action" name="action" value="remove_system_logo"  class="cancel"/>{{trans('settings.button.remove')}}</button></div>
                <div><button onclick="javascript:return false;" value="cancel" style="float:right;" name="invoice-logo" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
            </form>
        </div>

    </div>

    <script>
        Dropzone.options.myAwesomeDropzone = {
            maxFile: 1,
            init: function() {
                this.on("complete", function() {
                    // If all files have been uploaded
                    if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                        var _this = this;
                        // Remove all files
                        _this.removeAllFiles();
                    }
                });
            },
            success: function(file, response){
                if(response.status == 'ok'){
                    $(".invoice-logo").attr('src','{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image=' + response.image)
                }else{
                    ktNotification('{{trans('settings.error')}}',response.message,2000, false);
                }
            }
        };
    </script>

    <div class="tab-edit-item preview" id="account-company-name">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.general.account_holder.title')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;">{{@$account->company_name}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>

    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="account-company-name-editor">
        <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
            <form role="form" method="POST" action="{{ url('/settings') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.general.account_holder.input.name')}}" id="company_name" name="company_name" value="{{@$account->company_name}}" style="width:200px;"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.general.account_holder.input.vat')}}"  style="width:200px;" id="vat" name="vat" value="{{@$account->vat}}"/></div>
                <div style="padding-bottom:4px;">
                    <select style="width:200px;"style="width:200px;" id="country" name="country"  placeholder="Country">
                        <option value="">{{trans('settings.general.account_holder.input.country')}}</option>
                        @foreach(\App\Models\ktLang::$countryList as $iso => $country_name)
                            <option value="{{$iso}}" {{ ($iso == @$account->country) ? ' selected' : '' }}>{{$country_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.general.account_holder.input.city')}}"  style="width:200px;" id="city" name="city" value="{{@$account->city}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.general.account_holder.input.address')}}"  style="width:200px;" id="address" name="address" value="{{@$account->address}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.general.account_holder.input.zip')}}"  style="width:200px;" id="postal_code" name="postal_code" value="{{@$account->postal_code}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.general.account_holder.input.phone')}}"  style="width:200px;" id="phone_number" name="phone_number" value="{{@$account->phone_number}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('settings.general.account_holder.input.email')}}"  style="width:200px;" id="email" name="email" value="{{@$account->email}}"/></div>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.general.account_holder.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="update_account_details" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="account-company-name" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
            </form>
        </div>
    </div>

    <div class="tab-edit-item preview" id="timezone">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.general.timezone.title')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;">{{$account->timezone}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>

    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="timezone-editor">
        <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
            <form role="form" method="POST" action="{{ url('/settings') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div style="padding-bottom:4px;">
                    <select style="width:200px;" id="timezone" name="timezone"  placeholder="Timezone">
                        <option value="">{{trans('settings.general.timezone.select')}}</option>
                        <?php $timezone_list = DateTimeZone::listIdentifiers(DateTimeZone::ALL); ?>
                        @foreach($timezone_list as $iso => $timezone)
                            <option value="{{$timezone}}" {{ ($timezone == @$account->timezone) ? ' selected' : '' }}>{{$timezone}}</option>
                        @endforeach
                    </select>
                </div>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.general.timezone.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="update_timezone" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="timezone" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
            </form>
        </div>
    </div>


    <div class="tab-edit-item preview" id="invoice-color">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.general.layout_color.title')}}</div>
            <div style="width:300px;display:inline-block;color:#BBB2B2;">
                @if(!empty($account->invoice_layout_color) && !empty($account->system_layout_text_color))
                    Layout ({{@$account->invoice_layout_color}}) and Text ({{@$account->system_layout_text_color}})
                @else
                    Default Colors
                @endif
            </div>
        </div>
        <div style="float:right;display:inline-block;">
            Edit
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="invoice-color-editor">
        <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
            <div style="padding-bottom:4px;">
                <form role="form" method="POST" action="{{ url('/settings') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group my-colorpicker2 colorpicker-element">
                        <input type="text" class="form-control" value="{{@$account->system_layout_color}}" name="system_layout_color" id="system_layout_color">
                        <div class="input-group-addon">
                            <i style="background-color: rgb(132, 67, 67);"></i>
                        </div>
                    </div>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.general.layout_color.explanation'); ?></h6></div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group my-colorpicker2 colorpicker-element">
                        <input type="text" class="form-control" value="{{@$account->system_layout_text_color}}" name="system_layout_text_color" id="system_layout_text_color">
                        <div class="input-group-addon">
                            <i style="background-color: rgb(132, 67, 67);"></i>
                        </div>
                    </div>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.general.layout_color.explanation_text'); ?></h6></div>


                    <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                    <div><button id="action" name="action" value="update_system_layout_color" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button value="cancel" style="float:left;" name="invoice-color" class="cancel" onclick="javascript:return false;"/>{{trans('settings.button.cancel')}}</button></div>
                </form>
            </div>
        </div>
    </div>

</div>