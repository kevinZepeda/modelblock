<h4 style="margin: 0px !important;color:#909090;">{{trans('settings.finance.title')}}</h4>
@include('val-errors')
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">
    <div class="tab-edit-item preview" id="invoice-logo">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.finance.logo.title')}}</div>
            <div style="display:inline-block;color:#BBB2B2;">{{trans('settings.finance.logo.click_to_edit')}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="invoice-logo-editor">

        <div class="row" style="margin: 0px !important;font-size:13px;padding-left:10px;padding-top:15px;">
            <div class="col-md-12" style="padding-right:10px;padding-bottom:15px;">
                @if(@$account->invoice_logo != NULL )
                    <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image={{@$account->invoice_logo}}" class="invoice-logo" alt="Invoice Logo"/>
                @else
                    <img class="invoice-logo"/>
                @endif
                <form action="{{ url('/api') }}" class="dropzone dz-clickable" id="my-awesome-dropzone"  method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="event" name="event" value="invoice_logo">
                    <div class="dz-message">
                        {{trans('settings.finance.logo.drop_here')}}<br>
                        <span class="dz-note"><?php echo trans('settings.finance.logo.allowed_files'); ?></span>
                    </div>
                </form>
            </div><br/>
            <div><button onclick="javascript:return false;" value="cancel" style="float:left;" name="invoice-logo" class="cancel"/>{{trans('settings.button.remove')}}</button></div>
            <div><button onclick="javascript:return false;" value="cancel" style="float:right;" name="invoice-logo" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
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

    <div class="tab-edit-item preview" id="notes">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.finance.notes.title')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;">{{trans('settings.finance.notes.click_to_edit')}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>

    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="notes-editor">
        <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
            <form role="form" method="POST" action="{{ url('/settings/finance') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div style="padding-bottom:4px;">
                    <textarea placeholder="{{trans('settings.finance.notes.legal_notes')}}" style="width:400px;resize: vertical;" id="invoice_legal_note" name="invoice_legal_note">{{@$account->invoice_legal_note}}</textarea><br/>
                    <textarea placeholder="{{trans('settings.finance.notes.other_notes')}}" style="width:400px;resize: vertical;" id="invoice_note" name="invoice_note">{{@$account->invoice_note}}</textarea>
                </div>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.finance.notes.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="update_notes" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="notes" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
            </form>
        </div>
    </div>

    <div class="tab-edit-item preview" id="invoice-prefix">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.finance.number_format.title')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;">
                @if(isset($account->invoice_prefix) && !empty($account->invoice_prefix))
                    {{trans('settings.finance.number_format.prefix')}} {{$account->invoice_prefix}}
                @else
                    {{trans('settings.finance.number_format.prefix_not_set')}}
                @endif      
                @if(isset($account->invoice_prefix) && !empty($account->invoice_prefix) && isset($account->invoice_padding) && !empty($account->invoice_padding))
                        {{trans('settings.finance.number_format.and')}}
                @endif
                @if(isset($account->invoice_padding) && !empty($account->invoice_padding))
                    {{$account->invoice_padding}} {{trans('settings.finance.number_format.padding_digits')}}
                @endif  
            </div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('settings.button.edit')}}
        </div>
    </div>

    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="invoice-prefix-editor">
        <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
            <form role="form" method="POST" action="{{ url('/settings/finance') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div style="padding-bottom:4px;">
                    <select style="width:200px;" id="invoice_number_format" name="invoice_number_format">
                        <option @if($account->invoice_number_format == 'DATEFORMAT') selected @endif value="DATEFORMAT">Date Format (YYYYMMN)...</option>
                        <option @if($account->invoice_number_format == 'NUMBERFORMAT') selected @endif value="NUMBERFORMAT">Number Format (N)...</option>
                    </select>
                    @if($account->invoice_number_format == 'NUMBERFORMAT')
                        <input placeholder="{{trans('settings.finance.number_format.invoice_prefix')}}" id="invoice_prefix" name="invoice_prefix" value="{{@$account->invoice_prefix}}" style="width:200px;"/><br/>
                    @else
                        <input placeholder="{{trans('settings.finance.number_format.invoice_prefix')}}" id="invoice_prefix" name="invoice_prefix" value="{{@$account->invoice_prefix}}" style="width:200px;display:none;"/><br/>
                    @endif
                    <input placeholder="{{trans('settings.finance.number_format.post_len')}}" id="invoice_padding" name="invoice_padding" value="{{@$account->invoice_padding}}" style="width:200px;"/>
                </div>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.finance.number_format.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="action" name="action" value="update_invoice_format" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="invoice-prefix" class="cancel"/>{{trans('settings.button.cancel')}}</button></div>
            </form>
        </div>
    </div>

    <div class="tab-edit-item preview" id="invoice-color">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.finance.layout_color.title')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;">
                @if(!empty($account->invoice_layout_color))
                    {{@$account->invoice_layout_color}}
                @else
                    #000000
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
                <form role="form" method="POST" action="{{ url('/settings/finance') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-group my-colorpicker2 colorpicker-element">
                      <input type="text" class="form-control" value="{{@$account->invoice_layout_color}}" name="invoice_layout_color" id="invoice_layout_color">
                      <div class="input-group-addon">
                        <i style="background-color: rgb(132, 67, 67);"></i>
                      </div>
                    </div>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.finance.layout_color.explanation'); ?></h6></div>
                    <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                    <div><button id="action" name="action" value="update_invoice_layout_color" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button value="cancel" style="float:left;" name="invoice-color" class="cancel" onclick="javascript:return false;"/>{{trans('settings.button.cancel')}}</button></div>
               </form>
            </div>
        </div>
    </div>

    <div class="tab-edit-item preview" id="account-currency">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.finance.currency.title')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;">{{@$account->currency}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            Edit
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="account-currency-editor">
        <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
            <div style="padding-bottom:4px;">
                <form role="form" method="POST" action="{{ url('/settings/finance') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <select style="width:200px;"style="width:200px;" id="currency" name="currency"  placeholder="Select a Currency">
                        <option value="">{{trans('settings.finance.currency.select_currency')}}</option>
                        @foreach(\App\Models\ktLang::$currencyList as $code => $currency)
                            <option value="{{trim(str_replace('%s','',$code))}}" {{ ($code == @$account->currency) ? ' selected' : '' }}>{{trim(str_replace('%s','',$code))}}</option>
                        @endforeach
                    </select>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.finance.currency.explanation'); ?></h6></div>
                    <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                    <div><button id="action" name="action" value="update_currency" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button value="cancel" style="float:left;" name="account-currency" class="cancel" onclick="javascript:return false;"/>{{trans('settings.button.cancel')}}</button></div>
               </form>
            </div>
        </div>
    </div>


    <div class="tab-edit-item preview" id="account-language">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('settings.finance.language.title')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;">{{@\App\Models\ktLang::$invoiceLang[@$account->invoice_language]}}</div>
        </div>
        <div style="float:right;display:inline-block;">
            Edit
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="account-language-editor">
        <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
            <div style="padding-bottom:4px;">
                <form role="form" method="POST" action="{{ url('/settings/finance') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <select style="width:200px;"style="width:200px;" id="invoice_language" name="invoice_language"  placeholder="Select a Language">
                        <option value="">{{trans('settings.finance.language.select_language')}}</option>
                        @foreach(\App\Models\ktLang::$invoiceLang as $code => $language)
                            <option value="{{$code}}" {{ ($code == @$account->invoice_language) ? ' selected' : '' }}>{{$language}}</option>
                        @endforeach
                    </select>
                    <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('settings.finance.language.explanation'); ?></h6></div>
                    <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                    <div><button id="action" name="action" value="update_language" style="float:left;margin-right:10px;"/>{{trans('settings.button.save')}} </button>&nbsp;<button value="cancel" style="float:left;" name="account-language" class="cancel" onclick="javascript:return false;"/>{{trans('settings.button.cancel')}}</button></div>
               </form>
            </div>
        </div>
    </div>

</div>