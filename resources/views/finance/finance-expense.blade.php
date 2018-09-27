    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

                <section class="invoice" style="padding-left:40px;padding-right:40px;">
                    @if(in_array($invoice->type, array('INVOICE', 'DRAFT')))
                        <form role="form" method="POST" action="{{ url('/office/finance/invoice/'.$invoice_id) }}">
                    @elseif(in_array($invoice->type, array('EXPENSE')))
                        <form role="form" method="POST" action="{{ url('/office/finance/expense/'.$invoice_id) }}">                            
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
    
                                    <h2 class="">
                                        {{trans('finance.expense.title')}}
                                    </h2>

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
                                        @elseif($invoice->type == 'EXPENSE' || $block == 'EXPENSE')
                                                     
                                        @elseif($invoice->type == 'QUOTE' || $block == 'quotes')
                                            QUOTE
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
                                            Invoice Date: <input type="text" class="form-control pull-right" id="invoice_date" name="invoice_date" style="text-align:right;height:20px;width:100px;display:inline;border:0px;" placeholder="Select a date" value="{{$invoice->invoice_date}}"/><br/>
                                            Due Date: <input type="text" class="form-control pull-right" id="due_date" name="due_date" style="text-align:right;height:20px;width:100px;display:inline;border:0px;" placeholder="Select a date" value="{{$invoice->due_date}}"/><br/>
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

                                            Next Invoicing Date: <input type="text" class="form-control pull-right" id="r_next_date" name="r_next_date" style="text-align:center;height:20px;width:100px;display:inline;border:0px;" placeholder="Select a date" value="{{$invoice->r_next_date}}"/><br/>
                                            Invoice Due Days: <input type="text" class="form-control pull-right" id="r_due_days" name="r_due_days" style="text-align:center;height:20px;width:100px;display:inline;border:0px;" placeholder="Enter days" value="{{$invoice->r_due_days}}"/><br/>
                                            Subscription End: <input type="text" class="form-control pull-right" id="r_end_date" name="r_end_date" style="text-align:center;height:20px;width:100px;display:inline;border:0px;" placeholder="Select a date" value="{{$invoice->r_end_date}}"/><br/>
                                            Billing Occurance: <label style="width:100px;font-weight:normal;text-align:right;height:20px;padding: 0px 12px;">
                                                    <select style="width:80px;" id="r_due_period" name="r_due_period">
                                                       @foreach(\App\Models\ktLang::$subscriptionPeriods as $key => $value)
                                                            <option value="{{$key}}" @if($key == $invoice->r_due_period) selected @endif>{{$value}}</option>
                                                       @endforeach;
                                                    </select>
                                                </label><br/>
                                        @elseif(in_array($invoice->type, array('QUOTE')))   
                                            Quote Date: <input type="text" class="form-control pull-right" id="invoice_date" name="invoice_date" style="text-align:center;height:20px;width:100px;display:inline;border:0px;" placeholder="Select a date" value="{{$invoice->invoice_date}}"/><br/>
                                        @else
                                            {{trans('finance.expense.expense_date')}} <input type="text" class="form-control pull-right" id="invoice_date" name="invoice_date" style="text-align:center;height:20px;width:100px;display:inline;border:0px;" placeholder="Select a date" value="{{$invoice->invoice_date}}"/><br/>
                                            {{trans('finance.expense.expense_category')}} <label style="width:100px;font-weight:normal;text-align:right;height:20px;padding: 0px 12px;">
                                                    <select style="width:80px;" id="category_id" name="category_id">
                                                        <option>None</option>
                                                       @foreach($category_expenses as $category)
                                                            <option value="{{$category->id}}" @if($category->id == $invoice->category_id) selected @endif>{{$category->name}}</option>
                                                       @endforeach;
                                                    </select>
                                                </label><br/>
                                        @endif
                                        @if(!in_array($invoice->type, array('INVOICE')))
                                                {{trans('finance.expense.slip.expense_currency')}} <label style="width:100px;font-weight:normal;text-align:right;padding: 0px 12px;">
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
                        
                        <!-- Table row -->
                        <div class="row">
                            <div class="col-xs-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width:7%;">{{trans('finance.expense.slip.col.units')}}</th>
                                        <th style="width:1%%;">&nbsp;</th>
                                        <th style="width:30%;">{{trans('finance.expense.slip.col.product_service')}}</th>
                                        <th style="width:52%;">{{trans('finance.expense.slip.col.description')}}</th>
                                        <th style="width:10%;" id="items_currency">{{trans('finance.expense.slip.col.price')}} ({{\App\Models\ktLang::$currencyList[$account->currency]['symbol']}})</th>
                                        <th >&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody class="invoice-items">
                                        @foreach($items['ITEM'] as $item)
                                            <tr id="r{{$item['id']}}" class="item-line">
                                                <td style="width:7%;">
                                                    <input placeholder="{{trans('finance.expense.slip.qty')}}" style="width:100%;border: 0px;background-color: transparent;" class="item_q" id="iq{{$item['id']}}" name="iq{{$item['id']}}" value="{{$item['quantity']}}" />
                                                </td>
                                                <td style="width:1%%;">x</td>
                                                <td style="width:30%;"><input placeholder="{{trans('finance.expense.slip.product_service')}}" style="width:100%;border: 0px;background-color: transparent;" id="is{{$item['id']}}" name="is{{$item['id']}}" value="{{$item['label_1']}}"></td>
                                                <td style="width:52%;"><input placeholder="{{trans('finance.expense.slip.description')}}" style="width:100%;border: 0px;background-color: transparent;" id="id{{$item['id']}}" name="id{{$item['id']}}" value="{{$item['label_2']}}"></td>
                                                <td style="width:10%;"><input placeholder="{{trans('finance.expense.slip.price')}}" style="width:100%;border: 0px;background-color: transparent;" class="item_cost" id="{{$item['id']}}" name="i{{$item['id']}}" value="{{$item['item_cost']}}" /></td>
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
                                    <button type="button" class="btn btn-xs new-invoice-item" style="float:right;"><i class="fa fa-plus"></i> {{trans('finance.expense.slip.add_item')}}</button>
                                @endif
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                        <br/>
                        <div class="row">
                            <!-- accepted payments column -->

                            <div class="col-xs-5">
                                @if(!in_array($invoice->type, array('EXPENSE')))
                                    <p class="lead">Legal Notes:</p>
                                    <textarea  rows="4" style="width:100%;background-color:#f5f5f5;border:1px solid #ccc ;resize: vertical; border-radius: 5px;" name="legal_notes" id="legal_notes">{{$invoice->legal_notes}}</textarea>
                                    <small style="float:right;">(appears below the totals on the invoice)</small>
                                    <p class="lead">Notes:</p>
                                    <textarea  rows="4" style="width:100%;background-color:#f5f5f5;border:1px solid #ccc ;resize: vertical; border-radius: 5px;" name="notes" id="notes">{{$invoice->notes}}</textarea>
                                @else
                                    <p class="lead">{{trans('finance.expense.slip.expense_notes')}}</p>
                                    <textarea  rows="4" style="width:100%;background-color:#f5f5f5;border:1px solid #ccc ;resize: vertical; border-radius: 5px;" name="notes" id="notes">{{$invoice->notes}}</textarea>
                                @endif
                            </div><!-- /.col -->
                            <div class="col-xs-1">&nbsp;</div>
                            <div class="col-xs-6">
                                <p class="lead">{{trans('finance.slip.amount')}}</p>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th style="width:20%;text-decoration: underline;">{{trans('finance.expense.slip.subtotals')}}</th>
                                                <td class="tax">&nbsp;</td>
                                                <td id="subtotals" style="float:right;">0.00 {{\App\Models\ktLang::$currencyList[$account->currency]['symbol']}}</td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            @foreach($items['PRE-TAX'] as $item)
                                                <tr id="r{{$item['id']}}" class="discount-line">
                                                    <th style="width:30%;"><input id="{{$item['id']}}" name="pd{{$item['id']}}"  style="width:100%;border: 0px;background-color: transparent;" placeholder="{{trans('finance.expense.slip.line_description')}}"  value="{{$item['label_1']}}" /></th>
                                                    <td style="width:30%;"><input id="{{$item['id']}}" name="pf{{$item['id']}}" style="width:100%;border: 0px;background-color: transparent;" class="invoice_discount" placeholder="{{trans('finance.expense.slip.discount_credit')}}"  value="{{$item['factor']}}"></td>
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
                                                    <td colspan="4" style="text-align:right;"><small>{{trans('finance.expense.slip.new_pre_tax')}}</small>&nbsp;&nbsp;<button type="button" class="btn btn-xs new-discount-line"><i class="fa fa-plus"></i></button></td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <th style="text-decoration: underline;">{{trans('finance.expense.slip.taxable')}}</th>
                                                <td>&nbsp;</td>
                                                <td style="float:right;" id="taxable">0.00 {{\App\Models\ktLang::$currencyList[$account->currency]['symbol']}}</td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            @foreach($items['TAX'] as $item)
                                                <tr id="r{{$item['id']}}" class="tax-line">
                                                    <th style="width:30%;"><input id="{{$item['id']}}" name="td{{$item['id']}}" style="width:100%;border: 0px;background-color: transparent;" placeholder="{{trans('finance.expense.slip.line_description')}}" value="{{$item['label_1']}}" /></th>
                                                    <td style="width:30%;"><input id="{{$item['id']}}" name="tf{{$item['id']}}" style="width:100%;border:0px;background-color: transparent;"  placeholder="{{trans('finance.expense.slip.tax_credit')}}" class="invoice_tax" value="{{$item['factor']}}" /></td>
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
                                                <td colspan="4" style="text-align:right;"><small>{{trans('finance.expense.slip.new_tax_line')}}</small>&nbsp;&nbsp;<button type="button" class="btn btn-xs new-tax-line"><i class="fa fa-plus"></i></button></td>
                                            </tr>
                                            @endif
                                            
                                            <tr>
                                                <th style="width:20%;text-decoration: underline;">{{trans('finance.expense.slip.total_due')}}</th>
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
                                    <button class="btn btn pull-right" type="submit" style="margin-right: 5px;" id="event" name="event" value="invoice_change"><i class="fa fa-download"></i> Save</button>
                                @endif
                                @if(in_array($invoice->type, array('INVOICE', 'DRAFT','QUOTE')))
                                    <button type="button" onclick="javascript:window.open('{{ url('/api') }}?event=download_invoice&_token={{ csrf_token() }}&invoice_id={{$invoice->id}}', '_blank');" class="btn btn pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Download</button>
                                @elseif(in_array($invoice->type, array('RECURRING')))
                                    <button type="button" onclick="javascript:window.open('{{ url('/api') }}?event=download_invoice&_token={{ csrf_token() }}&invoice_id={{$invoice->id}}', '_blank');" class="btn btn pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Preview</button>
                                    @if($invoice->r_ready == 0)
                                        <button type="button" class="btn btn pull-left activate-subscription" style="margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> Activate Subscription</button>
                                        <button type="button"  class="btn btn pull-left de-activate-subscription" style="display:none;margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> De-activate Subscription</button>
                                    @else
                                        <button type="button" class="btn btn pull-left activate-subscription" style="display:none;margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> Activate Subscription</button>
                                        <button type="button"  class="btn btn pull-left de-activate-subscription" style="margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> De-activate Subscription</button>
                                    @endif                                
                                @endif

                                @if(in_array($invoice->type, array('DRAFT')) && $invoice->invoice_number == NULL)
                                    <button type="button" class="btn btn pull-left issue-invoice" style="margin-right: 5px;" id="{{$invoice->id}}"><i class="fa fa-bank"></i> Issue the invoice</button>
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


