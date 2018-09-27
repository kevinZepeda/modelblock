@if(!Auth::user()->isClient())
<div id="new-customer" title="{{trans('office.customer.details.new_customer')}}">
    <form role="form" method="POST" action="{{ url('/api/new-board') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <div class="col-md-6" id="customer-details" style="padding-right: 0px !important;">

            <div class="col-md-12" style="padding-right: 0px;text-align:center;">
                <h4>{{trans('office.customer.details.contact')}}</h4>
            </div>

            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.customer_name')}}" id="w_customer_name" name="w_customer_name"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.company_number')}}" id="w_company_number" name="w_company_number"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <select style="width:100%;color:gray !important;text-align: center !important;" id="w_country" name="w_country"  placeholder="{{trans('office.customer.details.input.country')}}" class="c_country">
                    </select>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.city')}}" id="w_city" name="w_city"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.address')}}" id="w_address" name="w_address"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.zip')}}" id="w_postal_code" name="w_postal_code"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.full_name')}}" id="w_contact_full_name" name="w_contact_full_name"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.phone_number')}}" id="w_phone_number" name="w_phone_number"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.email_address')}}" id="w_email" name="w_email"/>
                </div>
            </div>
        </div>

        <div class="col-md-6" id="invoice-details" style="padding-right: 0px !important;">
            <div class="col-md-12" style="padding-right: 0px;text-align:center;">
                <h4>{{trans('office.customer.details.billing')}}</h4>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.customer_name')}}" id="w_b_customer_name" name="w_b_customer_name"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.vat')}}" id="w_b_vat" name="w_b_vat"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <select style="width:100%;height:24px;" id="w_b_country" name="w_b_city"  placeholder="{{trans('office.customer.details.input.country')}}" class="c_country">
                    </select>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.city')}}" id="w_b_city" name="w_b_city"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.address')}}" id="w_b_address" name="w_b_address"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.zip')}}" id="w_b_postal_code" name="w_b_postal_code"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.full_name')}}" id="w_b_contact_full_name" name="w_b_contact_full_name"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.phone_number')}}" id="w_b_phone_number" name="w_b_phone_number"/>
                </div>
            </div>
            <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
                <div class="col-md-12" style="padding-right: 0px;">
                    <input style="width:100%;text-align:center;" placeholder="{{trans('office.customer.details.input.email_address')}}" id="w_b_email" name="w_b_email"/>
                </div>
            </div>
        </div>
    </form>
</div>
@endif

@if(Auth::user()->isClient())
<div id="project-request" title="New Project Order">
    <form role="form" method="POST" action="{{ url('/api/new-board') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <select style="width:100%;color:gray !important;text-align: center !important;" id="c_order" name="c_order"  placeholder="{{trans('office.questionnarie.input.questionnarie')}}" class="c_order">
                </select>
            </div>
        </div>

    </form>
</div>
@endif

@if(is_object(@$customer) && !Auth::user()->isClient())
<div id="new-questionnarie" title="{{trans('office.questionnarie.title')}}">
    <form role="form" method="POST" action="{{ url('/api/new-board') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
            <div class="col-md-12" style="padding-right: 0px;">
                <select style="width:100%;color:gray !important;text-align: center !important;" id="w_country" name="c_questionnaries"  placeholder="{{trans('office.questionnarie.input.questionnarie')}}" class="c_questionnaries">
                </select>
            </div>
        </div>

    </form>
</div>
@endif