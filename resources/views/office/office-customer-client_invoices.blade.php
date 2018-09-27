<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
    <thead>
    <tr role="row">
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 30%;">
            {{trans('finance.invoice.col.invoice_no')}}
        </th>
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 30%;">
            {{trans('finance.invoice.col.status')}}
        </th>
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 30%;">
            {{trans('finance.invoice.col.totals')}}
        </th>
        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 10%;">
            {{trans('finance.invoice.col.action')}}
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        <tr role="row" class="odd" id="i{{$invoice->id}}">
            <td>{{$invoice->invoice_number}}</td>
            <td>{{trans('finance.invoice_state.'.$invoice->status)}}</td>
            <td>{{sprintf(\App\Models\ktLang::$currencyList[$invoice->currency]['format'], number_format($invoice->invoice_subtotals - $invoice->invoice_pre_tax + $invoice->invoice_tax,2, ".", ""))}}</td>
            <td>
                <div class="btn-group">
                    <button onclick="location.href='{{ url('/api') }}?event=client_download_invoice&_token={{ csrf_token() }}&invoice_id={{$invoice->id}}'" type="submit" class="btn btn-xs">{{trans('finance.invoice.button.download')}}</button>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
