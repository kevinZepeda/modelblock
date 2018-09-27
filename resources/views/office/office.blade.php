@extends('app')

@section('content')
    <body class="skin-blue layout-boxed sidebar-collapse" style="background-color: #ecf0f5 !important;">
    <div class="se-pre-con"></div>
    <div id="main-content-wraper">

        @include('menu')
        @include('office.office-menu')

        @if(isset($block))
            @include('office.office-'.$block)
        @else
            @include('office.office-clean')
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

        <!-- block ui -->
        <script src="{{ asset('/assets/plugins/jquery-blockui/jquery.blockUI.js') }}" type="text/javascript"></script>

        <!-- Gridster -->
        <script src="{{ asset('/assets/plugins/gridster/jquery.gridster.js') }}" type="text/javascript"></script>

        <script src="{{ asset('/assets/plugins/summernote/summernote.min.js') }}" type="text/javascript"></script>

        <script src="{{ asset('/assets/plugins/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>

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
            $(function() {

                $(".tab-edit-item, .tab-edit-item-last").click(function(){
                    var id = $(this).attr('id');
                    if($("#"+id+"-editor").length > 0) {
                        $(".editor").hide();
                        $(".preview").show();
                        //console.log(id);
                        $(this).hide();
                        $("#" + id + "-editor").show();
                    }
                });

                $(".cancel").click(function(){
                    var id = $(this).attr('name');
                    //console.log(id);
                    $("#"+id+"-editor").hide();
                    $("#"+id).show();
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

                @if(Auth::user()->isClient())
                $( "#project-request" ).dialog({
                    "open": function(event, ui) {
                        $("#work_stream").html('');
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy({
                                blurRadius: 8,          // In pixels.
                                opacity: 1,           // Falls back to a filter for IE.
                                cssFilterSupport: true  // Use "-webkit-filter" where available.
                            });
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    },
                    dialogClass: "no-close",
                    autoOpen: false,
                    modal: true,
                    width: 300,
                    height:140,
                    resizable: false,
                    dialogClass: 'main-dialog-class',
                    buttons: [
                        {
                            text: "{{trans('office.button.close')}}",
                            click: function() {
                                $('#main-content-wraper').foggy(false);
                                $( this ).dialog( "close" ).position();;
                            }
                        },
                        {
                            text: "{{trans('office.button.create')}}",
                            click: function () {
                                var order_hash = ($("#c_order").val())
                                if(order_hash != 'NULL') {
                                    window.location.href = "{{url('quote/request') }}" + '/' + order_hash;
                                }else{
                                    alert('Please select an order form!')
                                }
                            }
                        }
                    ],
                    hide: { effect: "fadein", duration: 1000 },
                    close: function(){
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy(false);
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    }
                });
                $(".project-request").click(function(){
                    $( "#project-request" ).dialog('open');
                });

                $('#c_order').select2({
                    data: [
                        {id:'NULL',text:'SELECT AN ORDER FORM', value:'NULL'},
                            @foreach($orders as $order) @if($order->public == 1) {id: '{{md5(Config::get('app.salt.qa') . $order->id)}}',text: '<div>{{$order->name}}</div>', value: '{{$order->name}}'}, @endif @endforeach
                        ],
                    dropdownCssClass: "bigdrop",
                    minimumResultsForSearch: Infinity,
                    placeholder: "SELECT AN ORDER FORM",
                    allowClear: false,
                    escapeMarkup: function (m) {
                        return m;
                    }
                });

                @endif

                @if(!Auth::user()->isClient())
                $('#customers-list').select2({
                    data: [
                        @foreach($customers as $s_customer) {id: '{{$s_customer->id}}',text: '<div>{{$s_customer->customer_name}}</div>', value: '{{$s_customer->id}}'},@endforeach
                    ],
                    dropdownCssClass: "bigdrop",
                    placeholder: "{{trans('office.search_or_select_customer')}}",
                    allowClear: false,
                    escapeMarkup: function (m) {
                        return m;
                    }
                });

                $('#pcustomers-list').select2({
                    data: [
                        @foreach($customers as $s_customer) {id: '{{$s_customer->id}}',text: '<div>{{$s_customer->customer_name}}</div>', value: '{{$s_customer->id}}'},@endforeach
                    ],
                    dropdownCssClass: "bigdrop",
                    placeholder: "{{trans('office.search_or_select_customer')}}",
                    allowClear: false,
                    escapeMarkup: function (m) {
                        return m;
                    }
                });

                $('.c_country').select2({
                    data: [
                        {id:'',text:'{{trans('office.select_country')}}', value:''},
                        @foreach(\App\Models\ktLang::$countryList as $iso => $country_name) {id: '{{$iso}}',text: '<div>{{$country_name}}</div>', value: '{{$iso}}'},@endforeach
                    ],
                    dropdownCssClass: "bigdrop",
                    placeholder: "{{trans('office.select_country')}}",
                    allowClear: false,
                    escapeMarkup: function (m) {
                        return m;
                    }
                });
                @endif
                @if(isset($questionnarie_templates))
                    $('.c_questionnaries').select2({
                        data: [
                            {id:'NULL',text:'{{trans('office.empty_template')}}', value:'NULL'},
                            @foreach($questionnarie_templates as $q_template) {id: '{{$q_template->id}}',text: '<div>{{$q_template->name}}</div>', value: '{{$q_template->name}}'},@endforeach
                        ],
                        dropdownCssClass: "bigdrop",
                        minimumResultsForSearch: Infinity,
                        placeholder: "{{trans('office.choose_quest')}}",
                        allowClear: false,
                        escapeMarkup: function (m) {
                            return m;
                        }
                    });
                @endif
                @if(!Auth::user()->isClient())
                $('#projects-list').select2({
                    data: [
                        @foreach($projects as $s_project) {id: '{{$s_project->id}}',text: '<div>{{$s_project->project_name}}</div>', value: '{{$s_project->id}}'},@endforeach
                    ],
                    dropdownCssClass: "bigdrop",
                    placeholder: "{{trans('office.search_or_select_project')}}",
                    allowClear: false,
                    escapeMarkup: function (m) {
                        return m;
                    }
                });
                @endif
                @if(!Auth::user()->isClient())
                    @if(is_object(@$project))
                    $('#projects-list').select2('val', '{{ $project->id }}');
                    @endif

                    @if(is_object(@$customer))
                    $('#customers-list').select2('val', '{{ $customer->id }}');
                    @endif
                @endif
                @if(!Auth::user()->isClient())
                $("#customers-list").change(function(){
                    window.location.href = "{{url('/office/customer') }}" + '/' + $(this).val();
                });

                $("#projects-list").change(function(){
                    window.location.href = "{{url('/office/project/') }}" + '/' + $(this).val();
                });

                $(".list-customers").click(function(){
                    $("#info-label").html("{{trans('office.select_or_create_customer')}}");
                    $("#projects").hide();
                    $("#customers").show();
                });

                $(".list-projects").click(function(){
                    $("#info-label").html("{{trans('office.select_or_create_project')}}");
                    $("#customers").hide();
                    $("#projects").show();
                });

                $("#add-new-customer").click(function(){
                    $( "#new-customer" ).dialog('open');
                });

                $( "#new-customer" ).dialog({
                    "open": function(event, ui) {
                        $("#work_stream").html('');
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy({
                                blurRadius: 8,          // In pixels.
                                opacity: 1,           // Falls back to a filter for IE.
                                cssFilterSupport: true  // Use "-webkit-filter" where available.
                            });
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    },
                    dialogClass: "no-close",
                    autoOpen: false,
                    modal: true,
                    width: 700,
                    resizable: false,
                    dialogClass: 'main-dialog-class',
                    buttons: [
                        {
                            text: "{{trans('office.button.close')}}",
                            click: function() {
                                $('#main-content-wraper').foggy(false);
                                $( this ).dialog( "close" ).position();;
                            }
                        },
                        {
                            text: "{{trans('office.button.create')}}",
                            click: function () {
                                var post_data = {
                                    "_token": '{{ csrf_token() }}',
                                    "event": 'new_customer',
                                    'customer_name': $('#w_customer_name').val(),
                                    'company_number': $('#w_company_number').val(),
                                    'country': $('#w_country').val(),
                                    'city': $('#w_city').val(),
                                    'address': $('#w_address' ).val(),
                                    'postal_code': $('#w_postal_code' ).val(),
                                    'contact_full_name' : $('#w_contact_full_name').val(),
                                    'phone_number': $('#w_phone_number').val(),
                                    'email': $('#w_email').val(),
                                    'b_customer_name': $('#w_b_customer_name').val(),
                                    'b_vat': $('#w_b_vat' ).val(),
                                    'b_country': $('#w_b_country' ).val(),
                                    'b_city': $('#w_b_city' ).val(),
                                    'b_address': $('#w_b_address' ).val(),
                                    'b_postal_code' : $('#w_b_postal_code' ).val(),
                                    'b_contact_full_name': $('#w_b_contact_full_name').val(),
                                    'b_phone_number': $('#w_b_phone_number').val(),
                                    'b_email': $('#w_b_email').val()
                                };

                                $(".ui-dialog").block({
                                    message: '<img src="{{ asset('/assets/loading.gif') }}"/>',
                                    css: {
                                        backgroundColor: 'none',
                                        opacity: 0.9,
                                        border: '0px'
                                    },
                                    overlayCSS: {
                                        backgroundColor: 'white',
                                        opacity: 0.9,
                                    }
                                });

                                $.post(
                                        '{{ url('/api') }}',
                                        post_data,
                                        function( data ) {
                                            //console.log(data);
                                            if(data.status == 'ok'){
                                                window.location.href = "{{url('/office/customer') }}" + '/' + data.id;
                                            }else{
                                                $(".ui-dialog").unblock();
                                                ktNotification('{{trans('office.failure')}}' , data.messages, 3600, false);
                                            }
                                        },
                                        'json'
                                );
                            }
                        }
                    ],
                    hide: { effect: "fadein", duration: 1000 },
                    close: function(){
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy(false);
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    }
                });
                @if(is_object(@$customer))
                $( "#new-questionnarie" ).dialog({
                    "open": function(event, ui) {
                        $("#work_stream").html('');
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy({
                                blurRadius: 8,          // In pixels.
                                opacity: 1,           // Falls back to a filter for IE.
                                cssFilterSupport: true  // Use "-webkit-filter" where available.
                            });
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    },
                    dialogClass: "no-close",
                    autoOpen: false,
                    modal: true,
                    width: 300,
                    height:140,
                    resizable: false,
                    dialogClass: 'main-dialog-class',
                    buttons: [
                        {
                            text: "{{trans('office.button.close')}}",
                            click: function() {
                                $('#main-content-wraper').foggy(false);
                                $( this ).dialog( "close" ).position();;
                            }
                        },
                        {
                            text: "{{trans('office.button.create')}}",
                            click: function () {
                                 var post_data = {
                                    '_token': '{{ csrf_token() }}',
                                    'event': 'new_customer_questionnarie',
                                    'template_id': $('.c_questionnaries').val(),
                                    'reference_id': {{$customer->id}}
                                };

                                $(".ui-dialog").block({
                                    message: '<img src="{{ asset('/assets/loading.gif') }}"/>',
                                    css: {
                                        backgroundColor: 'none',
                                        opacity: 0.9,
                                        border: '0px'
                                    },
                                    overlayCSS: {
                                        backgroundColor: 'white',
                                        opacity: 0.9,
                                    }
                                });

                                $.post(
                                        '{{ url('/api') }}',
                                        post_data,
                                        function( data ) {
                                            //console.log(data);
                                            if(data.status == 'ok'){
                                                $(".ui-dialog").unblock();
                                                ktNotification('{{trans('office.ok')}}' , '', 3600, false);
                                                window.location.href = "{{url('/office/customer/questionnarie/edit/') }}" + '/' + data.id;
                                            }else{
                                                $(".ui-dialog").unblock();
                                                ktNotification('{{trans('office.failure')}}' , data.messages, 3600, false);
                                            }
                                        },
                                        'json'
                                );
                            }
                        }
                    ],
                    hide: { effect: "fadein", duration: 1000 },
                    close: function(){
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy(false);
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    }
                });


                $(".delete_qa").click(function(){
                    var cnf = confirm("{{trans('office.delete_quest')}}");
                    if (cnf == true) {
                        var id = $(this).attr('id');
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'delete_questionnaire',
                            "id": $(".delete_qa").attr('id')
                        };
                        $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok' && data.result > 0){
                                    ktNotification('{{trans('office.questionnarie_deleted')}}' , '', 500, true);
                                    $("#td"+id).remove();
                                    table.draw();
                                }else{
                                    ktNotification('{{trans('office.failure')}}' , '{{trans('office.failure_message')}}', 3600, false);
                                }
                            },
                            'json'
                        );
                    }
                });

                @endif

                $(".new-quest").click(function(){
                    $( "#new-questionnarie" ).dialog('open');
                });

                $( "#new-project" ).dialog({
                    "open": function() {
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy({
                                blurRadius: 8,          // In pixels.
                                opacity: 1,           // Falls back to a filter for IE.
                                cssFilterSupport: true  // Use "-webkit-filter" where available.
                            });
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    },
                    dialogClass: "no-close",
                    autoOpen: false,
                    modal: true,
                    width: 800,
                    height:600,
                    resizable: false,
                    dialogClass: 'main-dialog-class',
                    hide: { effect: "fadein", duration: 1000 },
                    buttons: [
                        {
                            text: "{{trans('office.button.close')}}",
                            click: function() {
                                $('#main-content-wraper').foggy(false);
                                $( this ).dialog( "close" ).position();;
                            }
                        },
                        {
                            text: "{{trans('office.button.create')}}",
                            style: 'padding-right:0px;',
                            click: function() {
                                var post_data = {
                                    "_token": '{{ csrf_token() }}',
                                    "event": 'new_project',
                                    'customer_id': $('#pcustomers-list').val(),
                                    'project_name': $('#project_name').val(),
                                    'project_description': $('#project_description').val()
                                };

                                $(".ui-dialog").block({
                                    message: '<img src="{{ asset('/assets/loading.gif') }}"/>',
                                    css: {
                                        backgroundColor: 'none',
                                        opacity: 0.9,
                                        border: '0px'
                                    },
                                    overlayCSS: {
                                        backgroundColor: 'white',
                                        opacity: 0.9,
                                    }
                                });

                                $.post(
                                        '{{ url('/api') }}',
                                        post_data,
                                        function( data ) {
                                            //console.log(data);
                                            if(data.status == 'ok'){
                                                window.location.href = "{{url('/office/project/') }}" + '/' + data.id;
                                            }else{
                                                $(".ui-dialog").unblock();
                                                ktNotification('{{trans('office.failure')}}' , data.messages, 3600, false);
                                            }
                                        },
                                        'json'
                                );
                            }
                        }
                    ],
                    close: function(){
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy(false);
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    }
                });

                $("#add-new-project").click(function(){
                    $( "#new-project" ).dialog('open');
                });

                var config = {
                    toolbar:
                            [
                                ['Print'],
                                ['Bold','Italic','Underline'],
                                ['TextColor','BGColor'],
                                ['FontSize'],
                                ['NumberedList','BulletedList','Blockquote'],
                                ['SpellChecker'],
                                ['Maximize'],
                                ['About']
                            ],
                    removePlugins: 'elementspath',
                    resize_enabled: false,
                    height: 400,
                };

                $(".delete").click(function(){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'share_delete',
                        'file_hash': $(this).attr('id'),
                    };

                    $.post(
                        '{{ url('/api') }}',
                        post_data,
                        function( data ) {
                            if(data.status == 'ok'){
                                $("#"+data.id).remove();
                                ktNotification('File Removed' , '', 3600, true);
                            }else{
                                ktNotification('{{trans('office.failure')}}' , data.message, 3600, false);
                            }
                        },
                        'json'
                    );
                });

                @if(is_object(@$customer))
                    $("#delete-customer").click(function(){
                        var cnf = confirm("{{trans('office.delete_customer')}}");
                        if (cnf == true) {
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "event": 'delete_customer',
                                "customer_id": {{$customer->id}}
                            };
                            $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 1){
                                        window.location.href = "{{url('/office/customer') }}";
                                    }else{
                                        ktNotification('{{trans('office.failure')}}' , '{{trans('office.delete_customer_failure')}}', 3600, false);
                                    }
                                },
                                'json'
                            );
                        }
                    });
                @endif

                @if(is_object(@$project))
                    $("#delete-project").click(function(){
                        var cnf = confirm("{{trans('office.delete_project')}}");
                        if (cnf == true) {
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "event": 'delete_project',
                                "project_id": {{$project->id}}
                            };
                            $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 1){
                                        window.location.href = "{{url('/office/project') }}";
                                    }else{
                                        ktNotification('{{trans('office.failure')}}' , '{{trans('office.failure_message')}}', 3600, false);
                                    }
                                },
                                'json'
                            );
                        }
                    });
                @endif

                Dropzone.options.myAwesomeDropzone = {
                    success: function(file, response){
                        if(response.status == 'ok'){
                            location.reload();
                        }else{
                            ktNotification('{{trans('office.error')}}',response.message,2000, false);
                        }
                    }
                };

                @if(isset($project))
                    @if($subblock == 'backlog')
                    var gridster = $(".gridster > ul").gridster({
                        widget_margins: [10,25],
                        widget_base_dimensions: [150, 100],
                        min_cols: 5,
                        max_cols: 5,
                        animate: true,
                        resize: {
                            enabled: false
                        },
                        serialize_params: function($w, wgd) {
                            return { id: $w.prop('id'), col: wgd.col, row: wgd.row, size_x: wgd.size_x, size_y: wgd.size_y }
                        },
                        avoid_overlapped_widgets: true,
                        autogenerate_stylesheet: true
                    }).data('gridster');
                    gridster.disable();
                    @endif


                    @if($subblock == 'requirements')
                    var gridster = $(".gridster > ul").gridster({
                        widget_margins: [10,10],
                        widget_base_dimensions: [150, 30],
                        min_cols: 5,
                        max_cols: 5,
                        animate: true,
                        resize: {
                            enabled: false
                        },
                        serialize_params: function($w, wgd) {
                            return { id: $w.prop('id'), col: wgd.col, row: wgd.row, size_x: wgd.size_x, size_y: wgd.size_y }
                        },
                        avoid_overlapped_widgets: true,
                        autogenerate_stylesheet: true
                    }).data('gridster');
                    gridster.disable();
                    @endif
                @endif
                var requirement = $( "#requirement-preview" ).dialog({
                    "open": function(event, ui) {
                        $("#work_stream").html('');
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy({
                                blurRadius: 8,          // In pixels.
                                opacity: 1,           // Falls back to a filter for IE.
                                cssFilterSupport: true  // Use "-webkit-filter" where available.
                            });
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                        $(".ui-dialog").block({
                            message: '<img src="{{ asset('/assets/loading.gif') }}"/>',
                            css: {
                                backgroundColor: 'none',
                                opacity: 0.9,
                                border: '0px'
                            },
                            overlayCSS: {
                                backgroundColor: 'white',
                                opacity: 0.9,
                            }
                        });
                    },
                    dialogClass: "no-close",
                    autoOpen: false,
                    modal: true,
                    width: 800,
                    resizable: false,
                    dialogClass: 'main-dialog-class',
                    hide: { effect: "fadein", duration: 1000 },
                    close: function(){
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy(false);
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    }
                });

                var note = $( "#note-preview" ).dialog({
                    "open": function(event, ui) {
                        $("#work_stream").html('');
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy({
                                blurRadius: 8,          // In pixels.
                                opacity: 1,           // Falls back to a filter for IE.
                                cssFilterSupport: true  // Use "-webkit-filter" where available.
                            });
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                        $(".ui-dialog").block({
                            message: '<img src="{{ asset('/assets/loading.gif') }}"/>',
                            css: {
                                backgroundColor: 'none',
                                opacity: 0.9,
                                border: '0px'
                            },
                            overlayCSS: {
                                backgroundColor: 'white',
                                opacity: 0.9,
                            }
                        });
                    },
                    dialogClass: "no-close",
                    autoOpen: false,
                    modal: true,
                    width: 800,
                    resizable: false,
                    dialogClass: 'main-dialog-class',
                    hide: { effect: "fadein", duration: 1000 },
                    close: function(){
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy(false);
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    }
                });
                @if(isset($project))
                    @if($subblock == 'backlog')
                    $(document).on('dblclick', '.gs-w', function(){
                        note.dialog('open');
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'get_task',
                            "task_id": $(this).attr('id')
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok') {
                                        var task = data.response.task;
                                        //console.log(data);
                                        $("#note-preview").dialog('option', 'title', '{{trans('office.task_note')}} ' + task.id);
                                        $("#task_description_preview").summernote('code',task.description);
                                        $(".subject_preview").html(task.subject);
                                        $("#subject_preview").val(task.subject);
                                        $("#task_id_h").val(task.id);
                                        $(".remove-task").attr('id',task.id);
                                        if(task.board_id != '' && task.board_id != null && task.board_id != 0) {
                                            $("#board_edit").val(task.board_id);
                                            $(".board_edit").html($("#board_edit option:selected").text());
                                        }else{
                                            $(".board_edit").html("{{trans('office.backlog')}}");
                                            $("#board_edit").val('NULL');
                                        }

                                        if(task.project_id != '' && task.project_id != null && task.project_id != 0) {
                                            $("#project_edit").val(task.project_id);
                                            $(".project_edit").html($("#project_edit option:selected").text());
                                        }else{
                                            $(".project_edit").html("{{trans('office.unknown_project')}}");
                                            $("#project_edit").val('');
                                        }

                                        if(task.type != '' && task.type != null && task.type != 0) {
                                            //console.log(task);
                                            $("#type_edit").val(task.type);
                                            $(".type_edit").html($("#type_edit option:selected").text());
                                        }else{
                                            $(".type_edit").html("{{trans('office.unknown_type')}}");
                                            $("#type_edit").val('');
                                        }

                                        if(task.priority != '' && task.priority != null && task.priority != 0) {
                                            $("#priority_edit").val(task.priority);
                                            $(".priority_edit").html($("#priority_edit option:selected").text());
                                        }else{
                                            $(".priority_edit").html("{{trans('office.not_prioritized')}}");
                                            $("#priority_edit").val('');
                                        }

                                        if(task.estimate != '' && task.estimate != null && task.estimate != 0) {
                                            $(".estimate_preview").html(task.estimate);
                                            $("#estimate_input_task_change").val(task.estimate);
                                        }else{
                                            $(".estimate_preview").html("{{trans('office.no_estimate')}}");
                                            $("#estimate_input_task_change").val('');
                                        }

                                        if(task.first_name != '' && task.first_name != null && task.first_name != 0 ||
                                            task.last_name != '' && task.last_name != null && task.last_name != 0
                                            ) {
                                            $("#manager").html(task.first_name + ' ' + task.last_name);
                                            $("#manager_edit").val(task.manager_id);
                                        }else{
                                            $(".manager").html("{{trans('no_manager')}}");
                                            $("#manager_edit").val('');
                                        }

                                        $.each(data.response.comments, function( index, comment ) {
                                            //console.log(comment)
                                            $("#work_stream").prepend('<div id="comment_'+comment.id+'" class="row" style="margin: 0px !important;font-size:13px;border-bottom: 1px solid #DBDBDB;padding:20px;text-align:justify;"> <div class="col-md-12" > <div class="box-tools pull-left" style="padding-right:20px;"> <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image='+ comment.avatar +'" class="user-image-task" alt="User Image"></div><div><strong>' + comment.author + '.</strong>: '+ comment.comment + '</div><span class="direct-chat-timestamp pull-right" style="padding-left:10px;"><a href="#" class="delwscomment" style="color:gray;" title="{{trans('office.delete_comment')}}" id="'+comment.id+'"><i class="ion-trash-a"></i></a></span><span class="direct-chat-timestamp pull-right">'+comment.comment_date+'</span></div></div>')
                                        });

                                        $(".ui-dialog").unblock();

                                    }else{
                                        $( "#note-preview" ).dialog('close');
                                        $.notify({
                                            icon: '{{asset('/assets/error.png')}}',
                                            title: '{{trans('office.error')}}',
                                            message: '{{trans('office.task_loading_failure')}}'
                                        },{
                                            placement: {
                                                from: "bottom",
                                                align: "right"
                                            },
                                            type: 'minimalist',
                                            delay: 5000,
                                            newest_on_top: true,
                                            allow_dismiss: true,
                                            z_index: 99999999999,
                                            animate: {
                                                enter: 'animated fadeInUp',
                                                exit: 'animated fadeOutUp'
                                            },
                                            icon_type: 'image',
                                            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                            '<img data-notify="icon" class="img-circle pull-left">' +
                                            '<span data-notify="title">{1}</span>' +
                                            '<span data-notify="message">{2}</span>' +
                                            '</div>'
                                        });
                                    }
                                },
                                'json'
                        );
                    });
                    @endif

                    @if($subblock == 'requirements')
                    $(document).on('dblclick', '.gs-w', function(){
                        requirement.dialog('open');
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'get_requirement',
                            "project_id": '{{$project->id}}',
                            "requirement_id": $(this).attr('id')
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok') {
                                        $("#req_id_h").val(data.result.id);
                                        $(".r_subject").val(data.result.subject);
                                        $(".r_details").summernote('code',data.result.details);
                                    }else{
                                        $( "#note-preview" ).dialog('close');
                                        $.notify({
                                            icon: '{{asset('/assets/error.png')}}',
                                            title: '{{trans('office.error')}}',
                                            message: '{{trans('office.requirement_loading_failure')}}'
                                        },{
                                            placement: {
                                                from: "bottom",
                                                align: "right"
                                            },
                                            type: 'minimalist',
                                            delay: 5000,
                                            newest_on_top: true,
                                            allow_dismiss: true,
                                            z_index: 99999999999,
                                            animate: {
                                                enter: 'animated fadeInUp',
                                                exit: 'animated fadeOutUp'
                                            },
                                            icon_type: 'image',
                                            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                            '<img data-notify="icon" class="img-circle pull-left">' +
                                            '<span data-notify="title">{1}</span>' +
                                            '<span data-notify="message">{2}</span>' +
                                            '</div>'
                                        });
                                        requirement.dialog('close');
                                    }
                                                                      $(".ui-dialog").unblock();
                                },
                                'json'
                        );
                    });
                    @endif

                    @if($subblock == 'backlog' || $block = 'project')

                     var note_toolbar = [
                        ['font', ['bold', 'underline']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', []],
                        ['view', []]];

                    $('textarea#project_description').summernote({
                        height: 375,                 // set editor height
                        minHeight: null,             // set minimum height of editor
                        maxHeight: null,             // set maximum height of editor
                        focus: true,                  // set focus to editable area after initializing summernote
                        toolbar: note_toolbar
                    });

                   $('textarea#task_description').summernote({
                        height: 200,                 // set editor height
                        minHeight: null,             // set minimum height of editor
                        maxHeight: null,             // set maximum height of editor
                        focus: true,                  // set focus to editable area after initializing summernote
                        toolbar: note_toolbar
                    });

                    $('textarea#task_description_preview').summernote({
                        height: 200,                 // set editor height
                        minHeight: null,             // set minimum height of editor
                        maxHeight: null,             // set maximum height of editor
                        focus: true,                  // set focus to editable area after initializing summernote
                        toolbar: note_toolbar
                    });

                    $('textarea#n_details').summernote({
                        height: 200,                 // set editor height
                        minHeight: null,             // set minimum height of editor
                        maxHeight: null,             // set maximum height of editor
                        focus: true,                  // set focus to editable area after initializing summernote
                        toolbar: note_toolbar
                    });

                    $('textarea#req_description_preview').summernote({
                        height: 200,                 // set editor height
                        minHeight: null,             // set minimum height of editor
                        maxHeight: null,             // set maximum height of editor
                        focus: true,                  // set focus to editable area after initializing summernote
                        toolbar: note_toolbar
                    });

                    @endif
                    @if($subblock == 'backlog')
                    $(document).keypress( function(e)
                    {
                        if(e.keyCode == 13 && e.ctrlKey == true && $('.note-editable').is(":focus") == true)
                        {
                            event.preventDefault();
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "event": 'desc_update',
                                "task_id": $("#task_id_h").val(),
                                "description": $('#task_description_preview').val()
                            };
                            $.post(
                                    '{{ url('/api') }}',
                                    post_data,
                                    function( data ) {
                                        //console.log(data);
                                        if(data.status == 'ok'){
                                            ktNotification('{{trans('office.description_saved')}}', '', 2000, true);
                                            $("#"+$("#task_id_h").val()+" #t_desc").html(data.raw_text.substr(0, 200)+'...');
                                        }else{
                                            ktNotification('{{trans('office.error')}}', data.message, 2000, false);
                                        }
                                    },
                                    'json'
                            );
                        }
                    });
                    @endif

                    $("#p_subject_preview").keyup(function( event ){
                        if(event.which == 13 && event.ctrlKey == true){
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "event": 'p_subject_update',
                                "project_id": {{$project->id}},
                                "subject": $(this).val()
                            };
                            $.post(
                                    '{{ url('/api') }}',
                                    post_data,
                                    function( data ) {
                                        if(data.status == 'ok'){
                                            ktNotification('{{trans('office.project_name_saved')}}', '', 2000, true);
                                        }else{
                                            ktNotification('{{trans('office.ups')}}', data.messages, 2000, true);
                                        }
                                    },
                                    'json'
                            );
                        }
                    });
                    @if($subblock == 'details')
                    $(document).keypress( function(e)
                    {
                        if(e.keyCode == 13 && e.ctrlKey == true && $('.note-editable').is(":focus") == true)
                        {
                            event.preventDefault();
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "event": 'p_desc_update',
                                "project_id": {{$project->id}},
                                "description": $('#pd').val()
                            };
                            $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    //console.log(data);
                                    if(data.status == 'ok' && data.result > 0){
                                        ktNotification('{{trans('office.requirements_saved')}}', '', 2000, true);
                                    }else{
                                        ktNotification('{{trans('office.ups')}}', data.messages, 2000, true);
                                    }
                                },
                                'json'
                            );
                        }
                    });
                    @endif
                @endif

                $("#subject_preview").keyup(function( event ){
                    if(event.which == 13 && event.ctrlKey == true){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'subject_update',
                            "hash": $("#boards-list").val(),
                            "task_id": $("#task_id_h").val(),
                            "subject": $(this).val()
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok'){
                                        ktNotification('{{trans('office.subject_updated')}}', '', 2000, true);
                                    }else{
                                        ktNotification('{{trans('office.ups')}}', data.messages, 2000, true);
                                    }
                                },
                                'json'
                        );
                    }
                });

                $("#estimate_input_task_change").keyup(function( event ){
                    if(event.which == 13 && event.ctrlKey == true){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'estimate_update',
                            "hash": $("#boards-list").val(),
                            "task_id": $("#task_id_h").val(),
                            "estimate": $(this).val()
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok' && data.result > 0) {
                                        ktNotification('{{trans('office.task_estimate_updated')}}', '', 2000, true);
                                        $(".estimate_preview").html(data.new_estimate);
                                        $("#" + $("#task_id_h").val() + " #t_estimate").html(data.new_estimate);
                                        $("#estimate_change").hide();
                                        $(".estimate_preview").show();
                                    }else if(data.status == 'ok'){
                                    }else{
                                        ktNotification('{{trans('office.invalid_estimate')}}', '', 2000, false);
                                        $("#estimate_change").hide();
                                        $("#estimate_input_task_change").val('');
                                        $(".estimate_preview").show();
                                    }
                                },
                                'json'
                        );
                    }
                });

                $("#comment").keydown(function( event ) {
                    if (event.which == 13 && event.ctrlKey == true) {
                        event.preventDefault();
                        return false;
                    }
                });

                $("#comment").keyup(function( event ){
                    if(event.which == 13 && event.ctrlKey == true){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'comment',
                            "task_id": $("#task_id_h").val(),
                            "comment": $(this).val()
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok') {
                                        ktNotification('{{trans('office.comment_posted')}}', '', 2000, true)
                                        $("#comment").val('');
                                        $("#work_stream").append('<div id="comment_'+data.response.id+'" class="row" style="margin: 0px !important;font-size:13px;border-bottom: 1px solid #DBDBDB;padding:20px;text-align:justify;"> <div class="col-md-12" > <div class="box-tools pull-left" style="padding-right:20px;"> <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image='+ data.response.avatar +'" class="user-image-task" alt="User Image"></div><div><strong>' + data.response.author + '.</strong>: '+ data.response.comment + '</div><span class="direct-chat-timestamp pull-right" style="padding-left:10px;"><a href="#" class="delwscomment" style="color:gray;" title="{{trans('office.delete_comment')}}" id="'+data.response.id+'"><i class="ion-trash-a"></i></a></span><span class="direct-chat-timestamp pull-right">'+data.response.date+'</span></div></div>');
                                        $("#work_stream").scrollTop($(document).height());
                                    }else{
                                        ktNotification('{{trans('office.comment_post_failed')}}', '', 2000, false)
                                    }
                                },
                                'json'
                        );
                    }
                });
                @if(isset($project))
                $("#work_stream").scrollTop($(document).height());
                $(".workstream_post").keyup(function( event ){
                    if(event.which == 13 && event.ctrlKey == true){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'workstream_post',
                            "project_id": {{$project->id}},
                            "comment": $(this).val()
                        };
                        $(".workstream_post").val('');
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok') {
                                        ktNotification('{{trans('office.comment_posted')}}', '', 2000, true);
                                        $("#work_stream").append('<div id="comment_'+data.response.id+'" class="row" style="margin: 0px !important;font-size:13px;border-bottom: 1px solid #DBDBDB;padding:20px;text-align:justify;"> <div class="col-md-12" > <div class="box-tools pull-left" style="padding-right:20px;"> <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image='+ data.response.avatar +'" class="user-image-task" alt="User Image"></div><div><strong>' + data.response.author + '.</strong>: '+ data.response.comment + '</div><span class="direct-chat-timestamp pull-right" style="padding-left:10px;"><a href="#" class="delwscomment" style="color:gray;" title="{{trans('office.delete_comment')}}" id="'+data.response.id+'"><i class="ion-trash-a"></i></a></span><span class="direct-chat-timestamp pull-right">'+data.response.date+'</span></div></div>');
                                        $("#work_stream").scrollTop($(document).height());
                                    }else{
                                        ktNotification('{{trans('office.comment_post_failed')}}', '', 2000, false)
                                    }
                                },
                                'json'
                        );
                    }
                });
                $("#workstream_post").click(function( event ){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'workstream_post',
                            "project_id": {{$project->id}},
                            "comment": $(this).val()
                        };
                        $(".workstream_post").val('');
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok') {
                                        ktNotification('{{trans('office.comment_posted')}}', '', 2000, true)
                                        $("#work_stream").append('<div id="comment_'+data.response.id+'" class="row" style="margin: 0px !important;font-size:13px;border-bottom: 1px solid #DBDBDB;padding:20px;text-align:justify;"> <div class="col-md-12" > <div class="box-tools pull-left" style="padding-right:20px;"> <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image='+ data.response.avatar +'" class="user-image-task" alt="User Image"></div><div><strong>' + data.response.author + '.</strong>: '+ data.response.comment + '</div><span class="direct-chat-timestamp pull-right" style="padding-left:10px;"><a href="#" class="delwscomment" style="color:gray;" title="{{trans('office.delete_comment')}}" id="'+data.response.id+'"><i class="ion-trash-a"></i></a></span><span class="direct-chat-timestamp pull-right">'+data.response.date+'</span></div></div>');
                                        $("#work_stream").scrollTop($(document).height());
                                    }else{
                                        ktNotification('{{trans('office.comment_post_failed')}}', '', 2000, false)
                                    }
                                },
                                'json'
                        );
                });
                @endif

                $("#post_comment").click(function( event ){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'comment',
                        "task_id": $("#task_id_h").val(),
                        "comment": $("#comment").val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok') {
                                    ktNotification('{{trans('office.comment_posted')}}', '', 2000, true)
                                    $("#comment").val('');
                                    $("#work_stream").prepend('<div class="row" style="margin: 0px !important;font-size:13px;border-bottom: 1px solid #DBDBDB;padding:20px;text-align:justify;"> <div class="col-md-12" > <div class="box-tools pull-left" style="padding-right:20px;"> <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image='+ data.response.avatar +'" class="user-image-task" alt="User Image"></div><div><strong>' + data.response.author + '.</strong>: '+ data.response.comment + '</div><span class="direct-chat-timestamp pull-right">'+data.response.date+'</span></div></div>');
                                }else{
                                    ktNotification('{{trans('office.comment_post_failed')}}', '', 2000, false)
                                }
                            },
                            'json'
                    );
                });

                $("#estimate_input_task_change").focusout(function( event ){
                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'estimate_update',
                            "hash": $("#boards-list").val(),
                            "task_id": $("#task_id_h").val(),
                            "estimate": $(this).val()
                        };
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function( data ) {
                                    if(data.status == 'ok' && data.result > 0) {
                                        ktNotification('{{trans('office.task_estimate_updated')}}', '', 2000, true)
                                        $(".estimate_preview").html(data.new_estimate);
                                        $("#" + $("#task_id_h").val() + " #t_estimate").html(data.new_estimate);
                                        $("#estimate_change").hide();
                                        $(".estimate_preview").show();
                                    }else if(data.status == 'ok'){
                                    }else{
                                        ktNotification('{{trans('office.invalid_estimate')}}', '', 2000, false)
                                        $("#estimate_change").hide();
                                        $("#estimate_input_task_change").val('');
                                        $(".estimate_preview").show();
                                    }
                                },
                                'json'
                        );
                });

                $("#type_edit").change(function( event ){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'type_update',
                        "hash": $("#boards-list").val(),
                        "task_id": $("#task_id_h").val(),
                        "type": $("#type_edit").val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok'){
                                    ktNotification('{{trans('office.task_type_updated')}}', '', 500, true);
                                    $("#"+$("#task_id_h").val()+" #t_type").html('Task: ' + data.new_type);
                                    $(".type_edit").html(data.new_type);
                                    $("#type_change").hide();
                                    $(".type_edit").show();
                                }
                            },
                            'json'
                    );
                });

                $("#project_edit").change(function( event ){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'project_update',
                        "hash": $("#boards-list").val(),
                        "task_id": $("#task_id_h").val(),
                        "project_id": $("#project_edit").val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                //console.log(data);
                                if(data.status == 'ok'){
                                    ktNotification('{{trans('office.project_set_to_task')}}', '', 500, true);
                                    $("#"+$("#task_id_h").val()+" #t_project").html('Project: ' + data.new_type);
                                    $(".project_edit").html($("#project_edit :selected").text());
                                    $("#project_change").hide();
                                    $(".project_edit").show();
                                }
                            },
                            'json'
                    );
                });

                $("#board_edit").change(function( event ){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'board_update',
                        "task_id": $("#task_id_h").val(),
                        "board_hash": $("#board_edit").val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok'){
                                    ktNotification('{{trans('office.task_moved_to_board')}}', '', 500, true);
                                    $("#board_change").hide();
                                    $(".board_change").show();
                                    $( "#note-preview" ).dialog('close');
                                    gridster.remove_widget($('#'+ $("#task_id_h").val()));
                                }
                            },
                            'json'
                    );
                });


                $("#priority_edit").change(function( event ){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'priority_update',
                        "hash": $("#boards-list").val(),
                        "task_id": $("#task_id_h").val(),
                        "priority": $("#priority_edit").val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok'){
                                    ktNotification('{{trans('office.task_priority_updated')}}', '', 500, true);
                                    $("#"+$("#task_id_h").val()+" #t_priority").html(data.new_priority);
                                    $(".priority_edit").html(data.new_priority);
                                    $("#priority_change").hide();
                                    $(".priority_edit").show();
                                }
                            },
                            'json'
                    );
                });

                $("#manager_edit").change(function( event ){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'manager_update',
                        "hash": $("#boards-list").val(),
                        "task_id": $("#task_id_h").val(),
                        "manager_id": $("#manager_edit").val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok'){
                                    ktNotification('{{trans('office.task_manger_updated')}}', '', 500, true);
                                    $(".manager_edit").html($("#manager_edit option:selected").text());
                                    $("#manager_change").hide();
                                    $(".manager_edit").show();
                                }
                            },
                            'json'
                    );
                });

                $("#user_edit").change(function( event ){
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'assignee_update',
                        "hash": $("#boards-list").val(),
                        "task_id": $("#task_id_h").val(),
                        "user_id": $("#user_edit").val()
                    };
                    $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                if(data.status == 'ok'){
                                    ktNotification('{{trans('office.task_re_assigned')}}', '', 500, true);
                                    $(".user_edit").html($("#user_edit option:selected").text());
                                    $("#"+$("#task_id_h").val()+" #t_assignee").html($("#user_edit option:selected").text().toUpperCase());
                                    $("#assignee_change").hide();
                                    $(".user_edit").show();
                                }
                            },
                            'json'
                    );
                });

                @if(isset($project))
                var new_note = $( "#new-note" ).dialog({
                    "open": function() {
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy({
                                blurRadius: 8,          // In pixels.
                                opacity: 1,           // Falls back to a filter for IE.
                                cssFilterSupport: true  // Use "-webkit-filter" where available.
                            });
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    },
                    dialogClass: "no-close",
                    autoOpen: false,
                    modal: true,
                    width: 800,
                    resizable: false,
                    dialogClass: 'main-dialog-class',
                    hide: { effect: "fadein", duration: 1000 },
                    buttons: [
                        {
                            text: "{{trans('office.button.close')}}",
                            click: function() {
                                $('#main-content-wraper').foggy(false);
                                $( this ).dialog( "close" ).position();;
                            }
                        },
                        {
                            text: "{{trans('office.button.create')}}",
                            style: 'padding-right:0px;',
                            click: function() {
                                $.notifyClose();
                                var post_data = {
                                    "_token": '{{ csrf_token() }}',
                                    "event": 'new_task',
                                    "hash": 'NULL',
                                    "subject": $("#task_subject").val(),
                                    "estimate": $("#estimate_input_n_task").val(),
                                    "description": $("#task_description").val(),
                                    "priority": $("#task_priority option:selected").val(),
                                    "type":     $("#task_type option:selected").val(),
                                    "board_id":  $("#board_hash option:selected").val(),
                                    "project_id":  '{{$project->id}}'
                                };

                                $.post(
                                        '{{ url('/api') }}',
                                        post_data,
                                        function( data ) {
                                            //console.log(data);
                                            if(data.status == 'error'){
                                                var messages = '';
                                                var count = 1;
                                                $.each(data.message, function( index, message ) {
                                                    messages = messages + (count++) + '.' + message + '<br/>';
                                                });
                                                $.notify({
                                                    icon: '{{asset('/assets/error.png')}}',
                                                    title: '{{trans('office.failure')}}',
                                                    message: messages
                                                },{
                                                    placement: {
                                                        from: "bottom",
                                                        align: "right"
                                                    },
                                                    type: 'minimalist',
                                                    delay: 30000,
                                                    newest_on_top: true,
                                                    allow_dismiss: true,
                                                    z_index: 99999999999,
                                                    animate: {
                                                        enter: 'animated fadeInUp',
                                                        exit: 'animated fadeOutUp'
                                                    },
                                                    icon_type: 'image',
                                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                                    '<span data-notify="title">{1}</span>' +
                                                    '<span data-notify="message">{2}</span>' +
                                                    '</div>'
                                                });
                                            }else if(data.status == 'ok'){
                                                window.location.href = "{{url('/office/project/backlog') }}" + '/' + '{{$s_project->id}}';
                                            }
                                        },
                                        'json'
                                );
                            }
                        }
                    ],
                    close: function(){
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy(false);
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    }
                });

                var new_requirement = $( "#new-requirement" ).dialog({
                    "open": function() {
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy({
                                blurRadius: 8,          // In pixels.
                                opacity: 1,           // Falls back to a filter for IE.
                                cssFilterSupport: true  // Use "-webkit-filter" where available.
                            });
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    },
                    dialogClass: "no-close",
                    autoOpen: false,
                    modal: true,
                    width: 800,
                    resizable: false,
                    dialogClass: 'main-dialog-class',
                    hide: { effect: "fadein", duration: 1000 },
                    buttons: [
                        {
                            text: "{{trans('office.button.cancel')}}",
                            click: function() {
                                $('#main-content-wraper').foggy(false);
                                $( this ).dialog( "close" ).position();;
                            }
                        },
                        {
                            text: "{{trans('office.button.create')}}",
                            style: 'padding-right:0px;',
                            click: function() {
                                $.notifyClose();
                                var post_data = {
                                    "_token": '{{ csrf_token() }}',
                                    "event": 'new_requirement',
                                    "project_id": '{{$project->id}}',
                                    "subject": $(".s_subject").val(),
                                    "details": $("#n_details").val()
                                };
                                $.post(
                                        '{{ url('/api') }}',
                                        post_data,
                                        function( data ) {
                                            //console.log(data);
                                            if(data.status == 'error'){
                                                var messages = '';
                                                var count = 1;
                                                $.each(data.message, function( index, message ) {
                                                    messages = messages + (count++) + '.' + message + '<br/>';
                                                });
                                                $.notify({
                                                    icon: '{{asset('/assets/error.png')}}',
                                                    title: '{{trans('office.failure')}}',
                                                    message: messages
                                                },{
                                                    placement: {
                                                        from: "bottom",
                                                        align: "right"
                                                    },
                                                    type: 'minimalist',
                                                    delay: 30000,
                                                    newest_on_top: true,
                                                    allow_dismiss: true,
                                                    z_index: 99999999999,
                                                    animate: {
                                                        enter: 'animated fadeInUp',
                                                        exit: 'animated fadeOutUp'
                                                    },
                                                    icon_type: 'image',
                                                    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                                    '<img data-notify="icon" class="img-circle pull-left">' +
                                                    '<span data-notify="title">{1}</span>' +
                                                    '<span data-notify="message">{2}</span>' +
                                                    '</div>'
                                                });
                                            }else if(data.status == 'ok'){
                                                window.location.href = "{{url('/office/project/requirements') }}" + '/' + '{{$project->id}}';
                                            }
                                        },
                                        'json'
                                );
                            }
                        }
                    ],
                    close: function(){task_description
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy(false);
                        }else{
                            if(!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')){
                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                            }
                        }
                    }
                });
                @endif

                $(document).on('click', '.new-task', function(){
                    new_note.dialog('open');
                });

                $(document).on('click', '.new-requirement', function(){
                    new_requirement.dialog('open');
                });

                $(document).on('dblclick', '.task_edit', function(){
                    $(this).hide();
                    $('#' + $(this).attr('id') + '_change' ).show();
                });

                $('.gs-w').mousedown(function(){
                    $(this).qtip("hide");
                });

                $('.gs-w').qtip({ // Grab some elements to apply the tooltip to
                    content: {
                        text: '{{trans('office.double_click_to_edit')}}'
                    },
                    show: { ready: false },
                    position: {
                        my: 'top center',  // Position my top left...
                        at: 'bottom center', // at the bottom right of...
                    },
                    hide: {
                        when: { event: 'click' },
                        effect: { type: 'slide' }
                    },
                    style: {
                        classes: 'qtip-light qtip-shadow'
                    }
                });

                $(".estimate_input").inputmask("Regex", {
                    regex: "([0-9]+y)?([0-9]+mo)?([0-9]+w)?([0-9]+d)?([0-9]+h)?([0-9]+m)?"
                });

                $("#estimate_input_task_change").inputmask("Regex", {
                    regex: "([0-9]+y)?([0-9]+mo)?([0-9]+w)?([0-9]+d)?([0-9]+h)?([0-9]+m)?"
                });

                @if(isset($project))

                $(".r_subject").keyup(function( event ){
                    if(event.which == 13 && event.ctrlKey == true){
                         var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'r_subject_update',
                            "project_id": '{{$project->id}}',
                            "requirement_id": $("#req_id_h").val(),
                            "subject": $("#r_subject").val()
                        };
                        $.post(
                            '{{ url('/api') }}',
                            post_data,
                            function( data ) {
                                //console.log(data);
                                if(data.status == 'ok' && data.result > 0){
                                    ktNotification('{{trans('office.requirement_subject_updated')}}', '', 2000, true)
                                }
                            },
                            'json'
                        );
                    }
                });

                @if($subblock == 'requirements')

                    $(document).keypress( function(e)
                    {
                        if(e.keyCode == 13 && e.ctrlKey == true && $('.note-editable').is(":focus") == true)
                        {
                            event.preventDefault();
                            var post_data = {
                                "_token": '{{ csrf_token() }}',
                                "event": 'r_details_update',
                                "project_id": '{{$project->id}}',
                                "requirement_id": $("#req_id_h").val(),
                                "details": $("#req_description_preview").val()
                            };
                            $.post(
                                    '{{ url('/api') }}',
                                    post_data,
                                    function( data ) {
                                        //console.log(data);
                                        if(data.status == 'ok' && data.result > 0){
                                            ktNotification('{{trans('office.requirement_details_updated')}}', '', 2000, true)
                                        }
                                    },
                                    'json'
                            );
                        }
                    });
                @endif
            
                @endif

                $('#add-new-customer').qtip({ // Grab some elements to apply the tooltip to
                    content: {
                        text: '{{trans('office.tooltip.add_new_customer')}}'
                    },
                    show: { ready: false },
                    position: {
                        my: 'top center',  // Position my top left...
                        at: 'bottom center', // at the bottom right of...
                    },
                    hide: {
                        when: { event: 'click' },
                        effect: { type: 'slide' }
                    },
                    style: {
                        classes: 'qtip-light qtip-shadow'
                    }
                });

                $('#delete-customer').qtip({ // Grab some elements to apply the tooltip to
                    content: {
                        text: '{{trans('office.tooltip.delete_customer')}}'
                    },
                    show: { ready: false },
                    position: {
                        my: 'top center',  // Position my top left...
                        at: 'bottom center', // at the bottom right of...
                    },
                    hide: {
                        when: { event: 'click' },
                        effect: { type: 'slide' }
                    },
                    style: {
                        classes: 'qtip-light qtip-shadow'
                    }
                });

                $('#add-new-project').qtip({ // Grab some elements to apply the tooltip to
                    content: {
                        text: '{{trans('office.tooltip.add_new_project')}}'
                    },
                    show: { ready: false },
                    position: {
                        my: 'top center',  // Position my top left...
                        at: 'bottom center', // at the bottom right of...
                    },
                    hide: {
                        when: { event: 'click' },
                        effect: { type: 'slide' }
                    },
                    style: {
                        classes: 'qtip-light qtip-shadow'
                    }
                });

                $('#delete-project').qtip({ // Grab some elements to apply the tooltip to
                    content: {
                        text: '{{trans('office.tooltip.delete_project')}}'
                    },
                    show: { ready: false },
                    position: {
                        my: 'top center',  // Position my top left...
                        at: 'bottom center', // at the bottom right of...
                    },
                    hide: {
                        when: { event: 'click' },
                        effect: { type: 'slide' }
                    },
                    style: {
                        classes: 'qtip-light qtip-shadow'
                    }
                });

                $(".delwscomment").qtip({ // Grab some elements to apply the tooltip to
                    content: {
                        text: '{{trans('office.tooltip.delete_comment')}}'
                    },
                    show: { ready: false },
                    position: {
                        my: 'top center',  // Position my top left...
                        at: 'bottom center', // at the bottom right of...
                    },
                    hide: {
                        when: { event: 'click' },
                        effect: { type: 'slide' }
                    },
                    style: {
                        classes: 'qtip-light qtip-shadow'
                    }
                });

                $(document).on('click', ".delwscomment", function(){
                    var id = $(this).attr('id');
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'delete_comment',
                        "comment_id": id
                    };
                    if(confirm('{{trans('office.comment_delete_message')}}')) {
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function (data) {
                                    if (data.status == 'ok') {
                                        $("#comment_" + id).remove();
                                    } else {
                                        ktNotification('{{trans('office.ups')}}', '{{trans('office.failure_message')}}', 2000, true)
                                    }
                                },
                                'json'
                        );
                    }
                });

                $(document).on('click', ".remove-task", function(){
                    var id = $(this).attr('id');
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'delete_task',
                        "task_id": id
                    };
                    if(confirm('{{trans('office.task_delete_message')}}')) {
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function (data) {
                                    if (data.status == 'ok') {
                                        gridster.remove_widget("#" + id);
                                        $("#note-preview").dialog('close');
                                        if (/chrome|safari/.test(navigator.userAgent.toLowerCase())) {
                                            $('#main-content-wraper').foggy(false);
                                        } else {
                                            if (!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')) {
                                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                                            }
                                        }
                                    } else {
                                        ktNotification('{{trans('office.ups')}}', '{{trans('office.failure_message')}}', 2000, true)
                                    }
                                },
                                'json'
                        );
                    }
                });

                $(document).on('click', ".remove-req", function(){
                    var id = $("#req_id_h").val();
                    var post_data = {
                        "_token": '{{ csrf_token() }}',
                        "event": 'delete_requirement',
                        "requirement_id": id
                    };
                    if(confirm('{{trans('office.requirement_delete_message')}}')) {
                        $.post(
                                '{{ url('/api') }}',
                                post_data,
                                function (data) {
                                    if (data.status == 'ok') {
                                        gridster.remove_widget("#" + id);
                                        requirement.dialog('close');
                                        if (/chrome|safari/.test(navigator.userAgent.toLowerCase())) {
                                            $('#main-content-wraper').foggy(false);
                                        } else {
                                            if (!$('.ui-widget-overlay').hasClass('ui-widget-overlay-imp')) {
                                                $('.ui-widget-overlay').addClass('ui-widget-overlay-imp');
                                            }
                                        }
                                    } else {
                                        ktNotification('{{trans('office.ups')}}', '{{trans('office.failure_message')}}', 2000, true)
                                    }
                                },
                                'json'
                        );
                    }
                });
                @endif

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
    @include('office.office-customer-widgets')
    @include('office.office-project-widgets')
    </body>
@endsection
