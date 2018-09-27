    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

                <section class="invoice" style="padding-left:40px;padding-right:40px;">
                    @if(in_array($invoice->type, array('INVOICE', 'DRAFT')))
                        <form role="form" method="POST" action="{{ url('/office/finance/invoice/'.$invoice_id) }}">
                    @elseif(in_array($invoice->type, array('RECURRING')))
                        <form role="form" method="POST" action="{{ url('/office/finance/subscription/'.$invoice_id) }}">
                    @else
                        <form role="form" method="POST" action="{{ url('/office/finance/quote/'.$invoice_id) }}">
                    @endif
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice_id}}" />
                        <!-- title row -->
                        <div class="row">
                            <div class="col-xs-6">
                                @if(!empty($account->invoice_logo))
                                    <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image={{@$account->invoice_logo}}" class="invoice-logo" alt="Invoice Logo"/>
                                @else
                                    <h2 class="">
                                        {{@$account->company_name}}
                                    </h2>
                                @endif
                            </div><!-- /.col -->
                            <div class="col-xs-6">
                                <div style="text-align:right;">
                                    <p class="lead" id="invoice_no">
                                        @if(isset($invoice->invoice_number) && !empty($invoice->invoice_number))
                                            {{$invoice->invoice_number}}
                                            @if(in_array($invoice->type, array('INVOICE')))
                                                 <small id="invc" style="margin:0px;padding:0px;display:inline;font-size:12px;display:block;">
                                                    <select id="{{$invoice_id}}" style="width:40%;" class="invoice-state">

                                                    </select>
                                                </small>
                                            @endif  
                                        @elseif($invoice->type == 'QUOTE' || $block == 'quotes')
                                            {{trans('finance.quote.title')}}
                                        @elseif($invoice->type == 'RECURRING')
                                            RECURRING SUBSCRIPTION
                                        @elseif($invoice->type == 'INVOICE')
                                            INVOICE NUMBER
                                        @else
                                            DRAFT
                                        @endif
                                    </p>
                                    <p>
                                        <?php
                                            if(isset($invoice->invoice_date) && !empty($invoice->invoice_date)){
                                                $invoice_date_tmp = date_parse_from_format('Y-m-d', $invoice->invoice_date);
                                                $invoice->invoice_date = str_pad($invoice_date_tmp['day'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . str_pad($invoice_date_tmp['month'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . $invoice_date_tmp['year'];
                                            }

                                            if(isset($invoice->due_date) && !empty($invoice->due_date)){
                                                $invoice_date_tmp = date_parse_from_format('Y-m-d', $invoice->due_date);
                                                $invoice->due_date = str_pad($invoice_date_tmp['day'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . str_pad($invoice_date_tmp['month'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . $invoice_date_tmp['year'];
                                            }
                                            ?>
                                        @if(in_array($invoice->type, array('INVOICE', 'DRAFT')))
                                            {{trans('finance.invoice_date')}} <input type="text" class="form-control pull-right" id="invoice_date" name="invoice_date" style="text-align:right;height:20px;width:100px;display:inline;border:0px;" placeholder="{{trans('finance.select_date')}}" value="{{$invoice->invoice_date}}"/><br/>
                                            {{trans('finance.due_date')}} <input type="text" class="form-control pull-right" id="due_date" name="due_date" style="text-align:right;height:20px;width:100px;display:inline;border:0px;" placeholder="{{trans('finance.select_date')}}" value="{{$invoice->due_date}}"/><br/>
                                        @elseif(in_array($invoice->type, array('RECURRING')))    

                                            <?php
                                            if(isset($invoice->r_next_date) && !empty($invoice->r_next_date)){
                                                $invoice_date_tmp = date_parse_from_format('Y-m-d', $invoice->r_next_date);
                                                $invoice->r_next_date = str_pad($invoice_date_tmp['day'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . str_pad($invoice_date_tmp['month'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . $invoice_date_tmp['year'];
                                            }

                                            if(isset($invoice->r_end_date) && !empty($invoice->r_end_date)){
                                                $invoice_date_tmp = date_parse_from_format('Y-m-d', $invoice->r_end_date);
                                                $invoice->r_end_date = str_pad($invoice_date_tmp['day'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . str_pad($invoice_date_tmp['month'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . $invoice_date_tmp['year'];
                                            }
                                            ?>

                                            {{trans('finance.new_invoicing_date')}} <input type="text" class="form-control pull-right" id="r_next_date" name="r_next_date" style="text-align:center;height:20px;width:100px;display:inline;border:0px;" placeholder="{{trans('finance.select_date')}}" value="{{$invoice->r_next_date}}"/><br/>
                                            {{trans('finance.due_days')}} <input type="text" class="form-control pull-right" id="r_due_days" name="r_due_days" style="text-align:center;height:20px;width:100px;display:inline;border:0px;" placeholder="{{trans('finance.enter_days')}}" value="{{$invoice->r_due_days}}"/><br/>
                                            {{trans('finance.subscription_end')}} <input type="text" class="form-control pull-right" id="r_end_date" name="r_end_date" style="text-align:center;height:20px;width:100px;display:inline;border:0px;" placeholder="{{trans('finance.select_date')}}" value="{{$invoice->r_end_date}}"/><br/>
                                            {{trans('finance.billing_occurance')}} <label style="width:100px;font-weight:normal;text-align:right;height:20px;padding: 0px 12px;">
                                                    <select style="width:80px;" id="r_due_period" name="r_due_period">
                                                       @foreach(\App\Models\ktLang::$subscriptionPeriods as $key => $value)
                                                            <option value="{{$key}}" @if($key == $invoice->r_due_period) selected @endif>{{$value}}</option>
                                                       @endforeach;
                                                    </select>
                                                </label><br/>
                                            @else
                                            {{trans('finance.invoicing_currency')}} <input type="text" class="form-control pull-right" id="invoice_date" name="invoice_date" style="text-align:center;height:20px;width:100px;display:inline;border:0px;" placeholder="{{trans('finance.select_date')}}" value="{{$invoice->invoice_date}}"/><br/>
                                        @endif

                                        @if(!in_array($invoice->type, array('INVOICE')))
                                                {{trans('finance.invoicing_currency')}} <label style="width:100px;font-weight:normal;text-align:right;padding: 0px 12px;">
                                                <select style="width:80px;" id="currency" name="currency" class="icurrency" placeholder="Select a Currency">
                                                    @foreach(\App\Models\ktLang::$currencyList as $code => $currency)
                                                        <option value="{{$code}}" {{ ($code == $account->currency) ? ' selected' : '' }}>{{trim(str_replace('%s','',$code))}}</option>
                                                    @endforeach
                                                </select>
                                            </label><br/>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                <div style="text-align:left;">
                                    <div style="text-decoration: underline;color:gray;padding-bottom:5px;">{{trans('finance.our_info')}}</div>
                                    <address>
                                        @if(!empty($account->company_name))
                                            <strong>{{$account->company_name}}</strong><br>
                                        @endif
                                        @if(!empty($account->address))
                                                {{$account->address}}<br>
                                        @endif

                                        @if(isset(\App\Models\ktLang::$countryList[$account->country]))
                                            {{\App\Models\ktLang::$countryList[$account->country]}}
                                        @endif
                                        @if(!empty($account->country))
                                            , {{$account->country}}
                                        @endif
                                        @if(!empty($account->postal_code))
                                            {{$account->postal_code}}
                                        @endif

                                        @if(!empty($account->phone_number))
                                            <br/>{{trans('finance.phone')}} {{$account->phone_number}}
                                        @endif
                                        @if(!empty($account->email))
                                            <!-- <br/>Email: {{$account->email}} -->
                                        @endif

                                        @if(!empty($account->vat))
                                            <br/>{{trans('finance.vat')}} {{$account->vat}}
                                        @endif
                                    </address>
                                </div>
                            </div><!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                                <div style="text-align:right;">
                                    <div style="text-decoration: underline;color:gray;padding-bottom:5px;">
                                        @if(in_array($invoice->type, array('INVOICE', 'DRAFT')))
                                            {{trans('finance.bill_to')}}
                                        @else
                                            {{trans('finance.quote_to')}}
                                        @endif
                                    </div>
                                    <address>
                                        <div id="invc">
                                            <select id="customers-list" name="customer_id" style="width:80%;">

                                            </select>
                                        </div>
                                        <address id="customer-address">
                                            @if(@!empty($customer->company_name))
                                                <strong>{{$customer->company_name}}</strong><br>
                                            @endif
                                            @if(@!empty($customer->b_address))
                                                {{$customer->b_address}}<br>
                                            @endif

                                            @if(@isset(\App\Models\ktLang::$countryList[$customer->b_country]))
                                                {{\App\Models\ktLang::$countryList[$customer->b_country]}}
                                            @endif
                                            @if(@!empty($customer->b_country))
                                                , {{$customer->b_country}}
                                            @endif
                                            @if(@!empty($customer->b_postal_code))
                                                {{$customer->b_postal_code}}
                                            @endif

                                            @if(@!empty($customer->b_phone_number))
                                                <br/>{{trans('finance.phone')}} {{$customer->b_phone_number}}
                                            @endif
                                            @if(@!empty($customer->b_email))
                                                <!-- <br/>Email: {{$customer->b_email}} -->
                                            @endif

                                            @if(@!empty($customer->b_vat))
                                                <br/>{{trans('finance.vat')}} {{$customer->b_vat}}
                                            @endif
                                        </address>
                                    </address>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-xs-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width:7%;">{{trans('finance.slip.col.units')}}</th>
                                        <th style="width:1%%;">&nbsp;</th>
                                        <th style="width:30%;">{{trans('finance.slip.col.product_service')}}</th>
                                        <th style="width:52%;">{{trans('finance.slip.col.description')}}</th>
                                        <th style="width:10%;" id="items_currency">{{trans('finance.slip.col.price')}} ({{\App\Models\ktLang::$currencyList[$account->currency]['symbol']}})</th>
                                        <th >&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody class="invoice-items">
                                        @foreach($items['ITEM'] as $item)
                                            <tr id="r{{$item['id']}}" class="item-line">
                                                <td style="width:7%;">
                                                    <input placeholder="{{trans('finance.slip.qty')}}" style="width:100%;border: 0px;background-color: transparent;" class="item_q" id="iq{{$item['id']}}" name="iq{{$item['id']}}" value="{{$item['quantity']}}" />
                                                </td>
                                                <td style="width:1%%;">x</td>
                                                <td style="width:30%;"><input placeholder="{{trans('finance.slip.product_service')}}" style="width:100%;border: 0px;background-color: transparent;" id="is{{$item['id']}}" name="is{{$item['id']}}" value="{{$item['label_1']}}"></td>
                                                <td style="width:52%;"><input placeholder="{{trans('finance.slip.description')}}" style="width:100%;border: 0px;background-color: transparent;" id="id{{$item['id']}}" name="id{{$item['id']}}" value="{{$item['label_2']}}"></td>
                                                <td style="width:10%;"><input placeholder="{{trans('finance.slip.price')}}" style="width:100%;border: 0px;background-color: transparent;" class="item_cost" id="{{$item['id']}}" name="i{{$item['id']}}" value="{{$item['item_cost']}}" /></td>
                                                <td>
                                                    @if(empty($invoice->invoice_number))
                                                        <button type="button" class="btn btn-xs delete" style="float:right;" id="{{$item['id']}}"><i class="fa fa-trash"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if(empty($invoice->invoice_number))
                                    <button type="button" class="btn btn-xs new-invoice-item" style="float:right;"><i class="fa fa-plus"></i> {{trans('finance.slip.add_item')}}</button>
                                @endif
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                        <br/>
                        <div class="row">
                            <!-- accepted payments column -->

                            <div class="col-xs-5">
                                <p class="lead">{{trans('finance.slip.legal_notes')}}</p>
                                <textarea  rows="4" style="width:100%;background-color:#f5f5f5;border:1px solid #ccc ;resize: vertical; border-radius: 5px;" name="legal_notes" id="legal_notes">{{$invoice->legal_notes}}</textarea>
                                <small style="float:right;">{{trans('finance.slip.legal_notes_text')}}</small>
                                <p class="lead">{{trans('finance.slip.notes')}}</p>
                                <textarea  rows="4" style="width:100%;background-color:#f5f5f5;border:1px solid #ccc ;resize: vertical; border-radius: 5px;" name="notes" id="notes">{{$invoice->notes}}</textarea>
                            </div><!-- /.col -->
                            <div class="col-xs-1">&nbsp;</div>
                            <div class="col-xs-6">
                                <p class="lead">{{trans('finance.slip.amount')}}</p>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th style="width:20%;text-decoration: underline;">{{trans('finance.slip.subtotals')}}</th>
                                                <td class="tax">&nbsp;</td>
                                                <td id="subtotals" style="float:right;">0.00 {{\App\Models\ktLang::$currencyList[$account->currency]['symbol']}}</td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            @foreach($items['PRE-TAX'] as $item)
                                                <tr id="r{{$item['id']}}" class="discount-line">
                                                    <th style="width:30%;"><input id="{{$item['id']}}" name="pd{{$item['id']}}"  style="width:100%;border: 0px;background-color: transparent;" placeholder="{{trans('finance.slip.line_description')}}"  value="{{$item['label_1']}}" /></th>
                                                    <td style="width:30%;"><input id="{{$item['id']}}" name="pf{{$item['id']}}" style="width:100%;border: 0px;background-color: transparent;" class="invoice_discount" placeholder="{{trans('finance.slip.discount_credit')}}"  value="{{$item['factor']}}"></td>
                                                    <td style="float:right;" id="d{{$item['id']}}">- 0.00</td>
                                                    <td>
                                                        @if(empty($invoice->invoice_number))
                                                            <button type="button" class="btn btn-xs delete" style="float:right;" id="{{$item['id']}}"><i class="fa fa-trash"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if(empty($invoice->invoice_number))
                                                <tr class="discount-ref">
                                                    <td colspan="4" style="text-align:right;"><small>{{trans('finance.slip.new_pre_tax')}}</small>&nbsp;&nbsp;<button type="button" class="btn btn-xs new-discount-line"><i class="fa fa-plus"></i></button></td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <th style="text-decoration: underline;">{{trans('finance.slip.taxable')}}</th>
                                                <td>&nbsp;</td>
                                                <td style="float:right;" id="taxable">0.00 {{\App\Models\ktLang::$currencyList[$account->currency]['symbol']}}</td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            @foreach($items['TAX'] as $item)
                                                <tr id="r{{$item['id']}}" class="tax-line">
                                                    <th style="width:30%;"><input id="{{$item['id']}}" name="td{{$item['id']}}" style="width:100%;border: 0px;background-color: transparent;" placeholder="{{trans('finance.slip.line_description')}}" value="{{$item['label_1']}}" /></th>
                                                    <td style="width:30%;"><input id="{{$item['id']}}" name="tf{{$item['id']}}" style="width:100%;border:0px;background-color: transparent;"  placeholder="{{trans('finance.slip.tax_credit')}}" class="invoice_tax" value="{{$item['factor']}}" /></td>
                                                    <td id="t{{$item['id']}}" style="float:right;">+ 0.00</td>
                                                    <td>
                                                        @if(empty($invoice->invoice_number))
                                                            <button type="button" class="btn btn-xs delete" style="float:right;" id="{{$item['id']}}"><i class="fa fa-trash" ></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if(empty($invoice->invoice_number))
                                            <tr class="tax-ref">
                                                <td colspan="4" style="text-align:right;"><small>{{trans('finance.slip.new_tax_line')}}</small>&nbsp;&nbsp;<button type="button" class="btn btn-xs new-tax-line"><i class="fa fa-plus"></i></button></td>
                                            </tr>
                                            @endif
                                            
                                            <tr>
                                                <th style="width:20%;text-decoration: underline;">{{trans('finance.slip.total_due')}}</th>
                                                <td class="tax">&nbsp;</td>
                                                <td id="totals" style="float:right;">0.00 {{\App\Models\ktLang::$currencyList[$account->currency]['symbol']}}</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-xs-12">
                                @if(empty($invoice->invoice_number))
                                    <button class="btn btn pull-right" type="submit" style="margin-right: 5px;" id="event" name="event" value="invoice_change"><i class="fa fa-download"></i> {{trans('finance.slip.button.save')}}</button>
                                @endif
                                @if(in_array($invoice->type, array('INVOICE', 'DRAFT','QUOTE')))
                                    <button type="button" onclick="javascript:window.open('{{ url('/api') }}?event=download_invoice&_token={{ csrf_token() }}&invoice_id={{$invoice->id}}', '_blank');" class="btn btn pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> {{trans('finance.slip.button.download')}}</button>
                                    @if(in_array($invoice->type, array('INVOICE')))
                                        <button type="button" class="btn btn pull-left re-send-invoice" style="margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-paper-plane"></i> {{trans('finance.slip.button.resend')}}</button>
                                    @endif
                                @elseif(in_array($invoice->type, array('RECURRING')))
                                    <button type="button" onclick="javascript:window.open('{{ url('/api') }}?event=download_invoice&_token={{ csrf_token() }}&invoice_id={{$invoice->id}}', '_blank');" class="btn btn pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> {{trans('finance.slip.button.preview')}}</button>
                                    @if($invoice->r_ready == 0)
                                        <button type="button" class="btn btn pull-left activate-subscription" style="margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> {{trans('finance.slip.button.activate_subscription')}}</button>
                                        <button type="button"  class="btn btn pull-left de-activate-subscription" style="display:none;margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> {{trans('finance.slip.button.de_activate_subscription')}}</button>
                                    @else
                                        <button type="button" class="btn btn pull-left activate-subscription" style="display:none;margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> {{trans('finance.slip.button.activate_subscription')}}</button>
                                        <button type="button"  class="btn btn pull-left de-activate-subscription" style="margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> {{trans('finance.slip.button.de_activate_subscription')}}</button>
                                    @endif                                
                                @endif

                                @if(in_array($invoice->type, array('DRAFT')) && $invoice->invoice_number == NULL)
                                    <button type="button" class="btn btn pull-left issue-invoice" style="margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> {{trans('finance.slip.button.issue_send')}}</button>
                                @endif

                            </div>
                        </div>
                    </form>
                </section>
        </div>
    </div>

<div style="background-color:#fff">

    <div class="wrapper">

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <?php echo Config::get('app.copyright.terms'); ?>
            </div>
            <?php echo Config::get('app.copyright.html'); ?>
        </footer>

    </div><!-- ./wrapper -->

</div>


