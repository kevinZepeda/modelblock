@extends('app')

@section('content')
    <body class="skin-blue layout-boxed sidebar-collapse" style="background-color: #ecf0f5 !important;">
    <div class="se-pre-con"></div>
    <div id="main-content-wraper">

        @include('menu')

        @if(isset($block))
            @include('finance.finance-'.$block)
        @else
            @include('finance.finance-clean')
        @endif

                    <!-- jQuery 2.1.3 -->
            <script src="{{ asset('/assets/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
            <!-- Bootstrap 3.3.2 JS -->

            <script src="{{ asset('/assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('/assets/plugins/bootstrap-growl/growl.js') }}" type="text/javascript"></script>
            <!-- date-range-picker -->
            <script src="{{ asset('/assets/plugins/daterangepicker/moment.js') }}" type="text/javascript"></script>
            <script src="{{ asset('/assets/plugins/daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>

            <!-- InputMask -->
            <script src="{{ asset('/assets/plugins/input-mask/jquery.inputmask.js') }}" type="text/javascript"></script>
            <script src="{{ asset('/assets/plugins/input-mask/jquery.inputmask.extensions.js') }}" type="text/javascript"></script>
            <script src="{{ asset('/assets/plugins/input-mask/jquery.inputmask.regex.extensions.js') }}" type="text/javascript"></script>

            <script src="{{ asset('/assets/plugins/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>

            <!-- block ui -->
            <script src="{{ asset('/assets/plugins/jquery-blockui/jquery.blockUI.js') }}" type="text/javascript"></script>

            <!-- Gridster -->
            <script src="{{ asset('/assets/plugins/gridster/jquery.gridster.js') }}" type="text/javascript"></script>

            <script src="{{ asset('/assets/plugins/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
            <script src="{{ asset('/assets/plugins/ckeditor/adapters/jquery.js') }}" type="text/javascript"></script>

            <!-- FastClick -->
            <script src='{{ asset('/assets/plugins/fastclick/fastclick.min.js') }}'></script>
            <!-- AdminLTE App -->
            <script src="{{ asset('/assets/dist/js/app.min.js') }}" type="text/javascript"></script>

            <script src='{{ asset('/assets/plugins/sticky/sticky.js') }}' type="text/javascript"></script>

            <script src="{{ asset('/assets/plugins/select2/select2.js') }}" type="text/javascript"></script>

            <script src="{{ asset('/assets/plugins/qtip/jquery.qtip.js') }}" type="text/javascript"></script>

            <script src="{{ asset('/assets/plugins/jQueryUI/jquery.ui.js') }}" type="text/javascript"></script>

            <script src="{{ asset('/assets/plugins/foggy/foggy.js') }}" type="text/javascript"></script>
            <script>
                $(document).ready(function() {

                    var currency_s = '{{\App\Models\ktLang::$currencyList[$account->currency]['format']}}';
                    var currency_formats = JSON.parse('<?php echo json_encode(\App\Models\ktLang::$currencyList); ?>');

                    $(".icurrency").change(function(){
                        currency_s = currency_formats[$(this).val()]['format'];
                        $("#items_currency").html('Price (' + currency_formats[$(this).val()]['symbol'] + ')');
                        invoiceRecalculate();
                    });

                    var table = $('#example1').DataTable({
                        "responsive": true,
                        "bPaginate": true,
                        "bLengthChange": true,
                        "bSort": true,
                        "bInfo": true,
                        "bPaging": true,
                        "bAutoWidth": false,
                        "searchHighlight": true
                    });

                    @if(isset($invoice))
                        @if($invoice->type != 'EXPENSE')
                            $('#customers-list').select2({
                                @if(isset($customers))
                                data: [
                                    {id:'0',text:'{{trans('finance.no_customer')}}',value:'NULL'}
                                    @foreach($customers as $s_customer) ,{id: '{{$s_customer->id}}',text: '<div>{{$s_customer->customer_name}}</div>', value: '{{$s_customer->id}}'}@endforeach
                                ],
                                @endif

                                placeholder: "{{trans('finance.select_customer')}}",
                                allowClear: false,
                                escapeMarkup: function (m) {
                                    return m;
                                }

                            });
                        @endif
                    @endif

                    $("#finance-list").select2({
                        @if(isset($invoices))
                        data: [
                            {id:'',text:'',value:''}
                            @foreach($invoices as $tmp_invoice) ,{id: '{{$tmp_invoice->id}}',text: '<div  class="res">{{strtoupper($tmp_invoice->invoice_number)}}</div><div style="color:black;font-size:12px;">{{$tmp_invoice->customer_name}}</div>', value: '{{$tmp_invoice->id}}'}@endforeach
                        ],
                        @endif
                        placeholder: "{{trans('finance.select_or_search_invoice')}}",
                        allowClear: false,
                        escapeMarkup: function (m) {
                            return m;
                        }
                    });

                    $("#finance-list").change(function(){
                        window.location.href = "{{url('/office/finance/invoice') }}" + '/' + $(this).val() + '{{@$hash}}';
                    });

                    $(".invoice-state").select2({
                        data: [
                            {id:'', text:'', value:''},
                            @foreach(\App\Models\ktLang::$invoiceStates as $state => $description) {id: '{{$state}}',text: '<div>{{$description}}</div>', value: '{{$state}}'},@endforeach
                        ],
                        placeholder: "{{trans('finance.select_invoice_status')}}",
                        allowClear: false,
                        escapeMarkup: function (m) {
                            return m;
                        }
                    });

                    @if(isset($invoice->invoice_number))
                    $('.invoice-state').select2('val', '{{$invoice->status}}')
                    @endif

                    @if(isset($invoice))
                        @if(isset($invoice->customer_id) && $invoice->type != 'EXPENSE')
                        $('#customers-list').select2('val', '{{ $invoice->customer_id }}')
                        @endif
                    @endif
                    $(".invoice_discount, .invoice_tax").inputmask("Regex", {
                        regex: "(?-)([0-9]+)\.([0-9]+)(%?)$|([0-9]+)\.([0-9]+)$"
                    });

                    function invoiceRecalculate(){
                        var subtotals = 0;
                        var discount = 0;
                        var tax = 0;
                        var taxable = 0;
                        $(".item_cost").each(function( index ) {
                            if($.isNumeric($(this).val())){
                                var qty = $("#iq"+$(this).attr('id')).val();
                                if($.isNumeric(qty) == false){
                                    qty = 1;
                                }
                                subtotals = subtotals + parseFloat($(this).val()) * qty;
                            }
                        });

                        $(".invoice_discount").each(function( index ) {
                            var percentage_exists = $(this).val().indexOf('%');
                            var tmp_discount = 0;
                            if(percentage_exists > 0) {
                                var percentage = $(this).val().split('%');
                                percentage = percentage[0];
                                tmp_discount = parseFloat((subtotals / 100) * percentage);
                                discount =  discount + tmp_discount;
                            }else if($.isNumeric($(this).val())){
                                tmp_discount = parseFloat($(this).val())
                                discount = discount + tmp_discount;
                            }
                            $("#d"+$(this).attr('id')).html(currency_s.replace('%s', '- ' + tmp_discount.toFixed(2)));
                        });

                        $(".invoice_tax").each(function( index ) {
                            var percentage_exists = $(this).val().indexOf('%');
                            var tmp_tax = 0;
                            if(percentage_exists > 0) {
                                var percentage = $(this).val().split('%');
                                percentage = percentage[0];
                                tmp_tax = parseFloat(((subtotals - discount) / 100) * percentage);
                                tax =  tax + tmp_tax;
                            }else if($.isNumeric($(this).val())){
                                tmp_tax = parseFloat($(this).val());
                                tax = tax + tmp_tax;
                            }
                            $("#t"+$(this).attr('id')).html(currency_s.replace('%s', '+ ' + tmp_tax.toFixed(2)));
                        });

                        $("#subtotals").html(currency_s.replace('%s', subtotals.toFixed(2)));

                        var taxable = subtotals - discount;

                        $("#taxable").html(currency_s.replace('%s', taxable.toFixed(2)));

                        var totals = taxable + tax;
                        $("#totals").html(currency_s.replace('%s', totals.toFixed(2)));
                    }

                    $("body").on('change', ".item_cost, .item_q, .invoice_discount, .invoice_tax", function(){
                        invoiceRecalculate();
                    });
                    
                    var tmp = 800;
                    $(".new-tax-line").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'new_tax',
                            "invoice_id": '{{ @$invoice_id }}'
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        var new_line_html = '<tr id="r'+data.item_id+'" class="tax-line"><th style="width:30%;"><input style="width:100%;border: 0px;background-color: transparent;" value="Tax" placeholder="{{trans('finance.slip.line_description')}}" name="td'+data.item_id+'" id="'+data.item_id+'" /></th> <td style="width:30%;"><input id="'+data.item_id+'" name="tf'+data.item_id+'" style="width:100%;border:0px;background-color: transparent;"  placeholder="{{trans('finance.slip.tax_credit')}}" class="invoice_tax" /></td><td id="t'+data.item_id+'" style="float:right;">+ 0.00</td> <td><button type="button" class="btn btn-xs delete" style="float:right;" id="'+data.item_id+'"><i class="fa fa-trash"></i></button></td></tr>';
                                        $(new_line_html).insertBefore(".tax-ref");
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $(".new-discount-line").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'new_pre_tax',
                            "invoice_id": '{{ @$invoice_id }}'
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        var new_line_html = '<tr id="r'+data.item_id+'" class="discount-line"><th style="width:30%;"><input style="width:100%;border: 0px;background-color: transparent;" value="Discount / Credit" placeholder="{{trans('finance.slip.line_description')}}" id="'+data.item_id+'" name="pd'+data.item_id+'" /></th> <td style="width:30%;"><input id="'+data.item_id+'"  name="pf'+data.item_id+'" style="width:100%;border: 0px;background-color: transparent;" class="invoice_discount" placeholder="{{trans('finance.slip.discount_credit')}}"/></td> <td style="float:right;" id="d'+data.item_id+'">- 0.00</td><td><button type="button" class="btn btn-xs delete" style="float:right;" id="'+data.item_id+'"><i class="fa fa-trash"></i></button></td></tr>';
                                        $(new_line_html).insertBefore(".discount-ref");
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $(".new-invoice-item").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'new_item',
                            "invoice_id": '{{ @$invoice_id }}'
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        var new_line_html = '<tr id="r'+data.item_id+'" class="item-line"><td style="width:7%;"><input placeholder="{{trans('finance.slip.qty')}}" style="width:100%;border: 0px;background-color: transparent;" class="item_q" id="iq'+data.item_id+'" name="iq'+data.item_id+'" /></td><td style="width:1%%;">x</td> <td style="width:30%;"><input placeholder="{{trans('finance.slip.product_service')}}"  style="width:100%;border: 0px;background-color: transparent;" id="is'+data.item_id+'" name="is'+data.item_id+'"></td> <td style="width:52%;"><input  placeholder="{{trans('finance.slip.description')}}" style="width:100%;border: 0px;background-color: transparent;" id="id'+data.item_id+'" name="id'+data.item_id+'"></td> <td style="width:10%;"><input placeholder="{{trans('finance.slip.price')}}" style="width:100%;border: 0px;background-color: transparent;" class="item_cost" id="'+data.item_id+'" name="i'+data.item_id+'" /></td><td><button type="button" class="btn btn-xs delete" style="float:right;" id="'+data.item_id+'"><i class="fa fa-trash"></i></button></td></tr>';
                                        $(".invoice-items").append(new_line_html);
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $("body").on('click', ".delete", function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'delete_item',
                            "item_id": $(this).attr('id')
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        $("#r"+data.id).remove();
                                        invoiceRecalculate();
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $(".delete-invoice").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'delete_invoice',
                            "invoice_id": $(this).attr('id')
                        };
                        if(confirm('{{trans('finance.invoice_delete_message')}}')){
                            $.post(
                                    '{{ url('/api') }}',
                                    post_data,
                                    function( data ) {
                                        //console.log(data);
                                        if(data.status == 'ok' && data.result == 1){
                                            $("#i"+data.id).remove();
                                            ktNotification('{{trans('finance.notifications.invoice_deleted')}}', '', 2000, true);
                                        }else{
                                            ktNotification('{{trans('finance.notifications.error')}}', 'Uppss, something is not right', 2000, false);
                                        }
                                    },
                                    'json'
                            );
                        }
                    });

                    $(".activate-subscription").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'subscription_ready',
                            "r_ready": 1,
                            "invoice_id": $(this).attr('id')
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        $(".activate-subscription").hide();
                                        ktNotification('{{trans('finance.notifications.subscription_activated')}}', '', 2000, true);
                                        $(".de-activate-subscription").show();
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $(".re-send-invoice").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'mail_invoice',
                            "invoice_id": $(this).attr('id')
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        ktNotification('{{trans('finance.notifications.sent')}}', '', 2000, true);
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', '', 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $(".de-activate-subscription").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'subscription_ready',
                            "r_ready": 0,
                            "invoice_id": $(this).attr('id')
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok'){
                                        $(".de-activate-subscription").hide();
                                        ktNotification('{{trans('finance.notifications.subscription_deactivated')}}', '', 2000, true);
                                        $(".activate-subscription").show();
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $(".issue-invoice").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'issue_invoice',
                            "invoice_id": $(this).attr('id')
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        location.reload();
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', 'Something is wrong', 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    @if(empty($invoice->invoice_number))
                        $('#invoice_date, #due_date,#r_end_date ,#r_next_date').daterangepicker({singleDatePicker: true,format: 'DD/MM/YYYY'});
                        $("#invoice_date, #due_date").inputmask("Regex", {
                            regex: "^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$"
                        });
                    @endif  

                    $("#r_due_days").inputmask("Regex", {
                        regex: "[1-9]{2}"
                    });

                    invoiceRecalculate();

                    $('#customers-list').change(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'get_customer_address',
                            "customer_id": $(this).val()
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok'){
                                        $("#customer-address").html(data.html);
                                    }
                                },
                                'json'
                        );
                    });

                    @if(!empty($invoice->invoice_number))
                        $('input').prop('readonly', 'readonly');
                        $('textarea').prop('disabled', true);
                        $('#customers-list').prop("disabled", true);
                    @endif  

                    $(".archive").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'archive_invoice',
                            "invoice_id": $(this).attr('id'),
                            "archived": 1
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        $("#i"+data.invoice_id).hide();
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $(".un-archive").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'archive_invoice',
                            "invoice_id": $(this).attr('id'),
                            "archived": 0
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        $("#i"+data.invoice_id).hide();
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $(".invoice-state").change(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'invoice_state_update',
                            "invoice_id": $(this).attr('id'),
                            "status": $(this).val()
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                        ktNotification('{{trans('finance.notifications.state_updated')}}', '', 2000, true);
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    $(".invoice-clone").click(function(){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'clone_invoice',
                            "invoice_id": $(this).attr('id')
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok'){
                                       window.location = '{{ url('/office/finance/invoice') }}' + '/' + data.invoice_id
                                    }else{
                                        ktNotification('{{trans('finance.notifications.error')}}', data.message, 2000, false);
                                    }
                                },
                                'json'
                        );
                    });

                    /* SELECT2 FIX*/
                    if ($.ui && $.ui.dialog && $.ui.dialog.prototype._allowInteraction) {
                        var ui_dialog_interaction = $.ui.dialog.prototype._allowInteraction;
                        $.ui.dialog.prototype._allowInteraction = function(e) {
                            if ($(e.target).closest('.select2-dropdown').length) return true;
                            return ui_dialog_interaction.apply(this, arguments);
                        };
                    }
                });
            </script>

    </div>
    </body>
@endsection
