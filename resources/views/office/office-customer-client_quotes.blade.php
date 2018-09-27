<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
    <thead>
    <tr role="row">
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 20%;">
            {{trans('finance.quote.col.totals')}}
        </th>
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 25%;">
            {{trans('finance.quote.col.quote_date')}}
        </th>
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 15%;">
            {{trans('finance.quote.col.action')}}
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($quotes as $quote)
        <tr role="row" class="odd" id="i{{$quote->id}}">
            <td>
                @if(!empty($quote->invoice_subtotals))
                    {{sprintf(\App\Models\ktLang::$currencyList[$quote->currency]['format'], number_format($quote->invoice_subtotals - $quote->invoice_pre_tax + $quote->invoice_tax,2, ".", ""))}}
                @else
                    <div style="color:#ccc;">
                        {{sprintf(\App\Models\ktLang::$currencyList[$quote->currency]['format'], '0.00')}}
                    </div>
                @endif
            </td>
            <td>
                @if(!empty($quote->invoice_date))
                    <?php
                    $draft_tmp = date_parse_from_format('Y-m-d', $quote->invoice_date);
                    echo str_pad($draft_tmp['day'], 2, "0", STR_PAD_LEFT)
                            . '/' . str_pad($draft_tmp['month'], 2, "0", STR_PAD_LEFT)
                            . '/' . $draft_tmp['year'];
                    ?>
                @else
                    <div style="color:#ccc;">{{trans('finance.quote.no_date')}}</div>
                @endif
            </td>
            <td>
                <div class="btn-group">
                    <button onclick="location.href='{{ url('/api') }}?event=client_download_invoice&_token={{ csrf_token() }}&invoice_id={{$quote->id}}'" type="submit" class="btn btn-xs">{{trans('finance.quote.button.download')}}</button>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
