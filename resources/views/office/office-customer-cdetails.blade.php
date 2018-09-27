@include('val-errors')
<h4 style="margin: 0px !important;color:#909090;">{{trans('office.customer.details.title')}}</h4>
<div style="border-bottom:1px solid #AEAEAE;border-top:1px solid #AEAEAE;margin-top:20px;color:gray;">
    <div class="tab-edit-item preview" id="contact-details">
        <div style="display:inline-block;">
            <div style="width:200px;display:inline-block;">{{trans('office.customer.details.contact')}}</div>
            <div style="width:200px;display:inline-block;color:#BBB2B2;">{{@$customer->customer_name}}</div>
            <div style="width:150px;display:inline-block;color:#BBB2B2;">{{@$customer->phone_number}}</div>
            <div style="width:150px;display:inline-block;color:#BBB2B2;"></div>
        </div>
        <div style="float:right;display:inline-block;">
            {{trans('office.edit')}}
        </div>
    </div>
    <div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="contact-details-editor">
        <form role="form" method="POST" action="{{ url('/office/customer/'.@$customer->id) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="contact_id" name="contact_id"  value="{{ @$customer->id }}">
            <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
                <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.customer_name')}}"  style="width:200px;" id="customer_name" name="customer_name" value="{{@$customer->customer_name}}"/></div>
                <div style="padding-bottom:4px;">
                    <select style="width:200px;"style="width:200px;" id="country" name="country"  placeholder="{{trans('office.customer.details.input.country')}}">
                        <option value="">{{trans('office.customer.details.select_country')}}</option>
                        @foreach(\App\Models\ktLang::$countryList as $iso => $country_name)
                            <option value="{{$iso}}" {{ ($iso == @$customer->country) ? ' selected' : '' }}>{{$country_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.city')}}"  style="width:200px;" id="city" name="city" value="{{@$customer->city}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.address')}}"  style="width:200px;" id="address" name="address" value="{{@$customer->address}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.zip')}}"  style="width:200px;" id="postal_code" name="postal_code" value="{{@$customer->postal_code}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.full_name')}}"  style="width:200px;" id="contact_full_name" name="contact_full_name" value="{{@$customer->contact_full_name}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.phone_number')}}"  style="width:200px;" id="phone_number" name="phone_number" value="{{@$customer->phone_number}}"/></div>
                <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.email_address')}}"  style="width:200px;" id="email" name="email" value="{{@$customer->email}}"/></div>
                <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('office.customer.details.explanation'); ?></h6></div>
                <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
                <div><button id="event" name="event" value="update_contact" style="float:left;margin-right:10px;"/>{{trans('office.customer.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="contact-details" class="cancel"/>{{trans('office.customer.button.cancel')}}</button></div>
            </div>
        </form>
    </div>

<div class="tab-edit-item preview" id="billing-details">
    <div style="display:inline-block;">
        <div style="width:200px;display:inline-block;">{{trans('office.customer.details.billing')}}</div>
        <div style="width:200px;display:inline-block;color:#BBB2B2;">{{@$customer->b_customer_name}}</div>
        <div style="width:150px;display:inline-block;color:#BBB2B2;">{{@$customer->b_phone_number}}</div>
        <div style="width:150px;display:inline-block;color:#BBB2B2;"></div>
    </div>
    <div style="float:right;display:inline-block;">
        {{trans('office.edit')}}
    </div>
</div>
<div class="tab-edit-item editor" style="background-color:#E8E8E8 !important;text-align:center;cursor:default;display:none;" id="billing-details-editor">
    <form role="form" method="POST" action="{{ url('/office/customer/'.@$customer->id) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="contact_id" name="contact_id"  value="{{ @$customer->id }}">
        <div style="display:inline-block;background-color:#E8E8E8 !important;text-align:center;padding-top:10px;padding-bottom:10px;width:300px;">
            <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.customer_name')}}"  style="width:200px;" id="b_customer_name" name="b_customer_name" value="{{@$customer->b_customer_name}}"/></div>
            <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.company_number')}}"  style="width:200px;" id="company_number" name="company_number" value="{{@$customer->company_number}}"/></div>
            <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.vat')}}"  style="width:200px;" id="b_vat" name="b_vat" value="{{@$customer->b_vat}}"/></div>
            <div style="padding-bottom:4px;">
                <select style="width:200px;"style="width:200px;" id="b_country" name="b_country"  placeholder="{{trans('office.customer.details.input.country')}}">
                    <option value="">{{trans('office.customer.details.select_country')}}</option>
                    @foreach(\App\Models\ktLang::$countryList as $iso => $country_name)
                        <option value="{{$iso}}" {{ ($iso == @$customer->b_country) ? ' selected' : '' }}>{{$country_name}}</option>
                    @endforeach
                </select>
            </div>
            <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.city')}}"  style="width:200px;" id="b_city" name="b_city" value="{{@$customer->b_city}}"/></div>
            <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.address')}}"  style="width:200px;" id="b_address" name="b_address" value="{{@$customer->b_address}}"/></div>
            <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.zip')}}"  style="width:200px;" id="b_postal_code" name="b_postal_code" value="{{@$customer->b_postal_code}}"/></div>
            <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.full_name')}}"  style="width:200px;" id="b_contact_full_name" name="b_contact_full_name" value="{{@$customer->b_contact_full_name}}"/></div>
            <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.phone_number')}}"  style="width:200px;" id="b_phone_number" name="b_phone_number" value="{{@$customer->b_phone_number}}"/></div>
            <div style="padding-bottom:4px;"><input placeholder="{{trans('office.customer.details.input.email_address')}}"  style="width:200px;" id="b_email" name="b_email" value="{{@$customer->b_email}}"/></div>
            <div style="padding-bottom:4px;text-align:left;"><h6 style="font-weight: 300 !important;"><?php echo trans('office.customer.details.explanation'); ?></h6></div>
            <div style="border-bottom:1px solid #D4D7DA;margin-bottom:10px;"></div>
            <div><button id="event" name="event" value="update_billing_contact" style="float:left;margin-right:10px;"/>{{trans('office.customer.button.save')}} </button>&nbsp;<button onclick="javascript:return false;" value="cancel" style="float:left;" name="billing-details" class="cancel"/>{{trans('office.customer.button.cancel')}}</button></div>
        </div>
    </form>
</div>

</div>