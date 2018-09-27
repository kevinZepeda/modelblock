<!-- =============================================== -->
<div>
    <div class="wrapper">
        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content" style="padding-top:0px;">
                <div class="row">
                    @include('finance.finance-invoice-menu')
                    <div class="col-md-10 tab-right" style="min-height:600px;">
                        <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                            <thead>
                            <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 20%;">
                                    {{trans('finance.invoice.col.invoice_no')}}
                                </th>
                                <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 25%;">
                                    {{trans('finance.invoice.col.customer')}}
                                </th>
                                <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 12%;">
                                    {{trans('finance.invoice.col.status')}}
                                </th>
                                <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 15%;">
                                    {{trans('finance.invoice.col.totals')}}
                                </th>
                                <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 15%;">
                                    {{trans('finance.invoice.col.action')}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoices as $invoice)
                                <tr role="row" class="odd" id="i{{$invoice->id}}">
                                    <td>{{$invoice->invoice_number}}</td>
                                    <td>{{$invoice->customer_name}}</td>
                                    <td>{{trans('finance.invoice_state.'.$invoice->status)}}</td>
                                    <td>{{sprintf(\App\Models\ktLang::$currencyList[$invoice->currency]['format'], number_format($invoice->invoice_subtotals - $invoice->invoice_pre_tax + $invoice->invoice_tax,2, ".", ""))}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button onclick="location.href='{{ url('/office/finance/invoice/'.$invoice->id) }}'" type="submit" class="btn btn-xs">Preview</button>
                                            <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu" style="left:-50px;">
                                                <li><a download href="{{ url('/api') }}?event=download_invoice&_token={{ csrf_token() }}&invoice_id={{$invoice->id}}">{{trans('finance.invoice.button.download')}}</a></li>
                                                <li><a href="#" class="archive" id="{{$invoice->id}}">{{trans('finance.invoice.button.archive')}}</a></li>
                                                <li><a href="#" class="invoice-clone" id="{{$invoice->id}}">{{trans('finance.invoice.button.clone_as_draft')}}</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#" class="delete-invoice" id="{{$invoice->id}}">{{trans('finance.invoice.button.delete_permanently')}}</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->

    </div><!-- ./wrapper -->
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