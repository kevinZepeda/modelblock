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
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 40%;">
                                        {{trans('finance.subscription.col.customer')}}
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 20%;">
                                        {{trans('finance.subscription.col.totals')}}
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 25%;">
                                        {{trans('finance.subscription.col.invoice_date')}}
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 15%;">
                                        {{trans('finance.subscription.col.action')}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($subscriptions as $subscription)
                                <tr role="row" class="odd"  id="i{{$subscription->id}}">
                                    <td>
                                        @if(!empty($subscription->customer_name))
                                            {{$subscription->customer_name}}
                                        @else
                                            <div style="color:#ccc;">{{trans('finance.subscription.not_assigned')}}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($subscription->totals))
                                            {{sprintf(\App\Models\ktLang::$currencyList[$subscription->currency]['format'], number_format($subscription->invoice_subtotals - $subscription->invoice_pre_tax + $subscription->invoice_tax,2, ".", ""))}}
                                        @else
                                           <div style="color:#ccc;">
                                                {{sprintf(\App\Models\ktLang::$currencyList[$subscription->currency]['format'], '0.00')}}
                                           </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($subscription->invoice_date))
                                            <?php
                                                $draft_tmp = date_parse_from_format('Y-m-d', $subscription->invoice_date);
                                                echo str_pad($draft_tmp['day'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . str_pad($draft_tmp['month'], 2, "0", STR_PAD_LEFT)
                                                        . '/' . $draft_tmp['year'];
                                            ?>
                                        @else
                                            <div style="color:#ccc;">{{trans('finance.subscription.no_date')}}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button onclick="location.href='{{ url('/office/finance/subscription/'.$subscription->id) }}'" type="submit" class="btn btn-xs">{{trans('finance.subscription.button.edit')}}</button>
                                            <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu" style="left:-50px;">
                                                <li><a download href="{{ url('/api') }}?event=download_invoice&_token={{ csrf_token() }}&invoice_id={{$subscription->id}}">{{trans('finance.subscription.button.download')}}</a></li>
                                                <li><a href="#" class="invoice-clone" id="{{$subscription->id}}">{{trans('finance.subscription.button.clone_subscription')}}</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#" class="delete-invoice" id="{{$subscription->id}}">{{trans('finance.subscription.button.delete_permanently')}}</a></li>
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