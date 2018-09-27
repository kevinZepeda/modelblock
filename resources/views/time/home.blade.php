@extends('app')

@section('content')
<body class="skin-blue layout-boxed sidebar-collapse">
<div class="se-pre-con"></div>
<div id="main-content-wraper">
<!-- Site wrapper -->
<div style="background-color:#909090;">
    @include('menu')
</div>
<!-- =============================================== -->
@include('time.home-menu')
<div class="wrapper" style="overflow: visible !important;">
    <!-- =============================================== -->

    @if(count($entries) < 1)
    <div class="content-wrapper" style="background:none;text-align:center;font-size:30px;color:gray;padding-top:300px;opacity:0.4;">
        <i class="ion-arrow-up-c"></i> {{trans('timesheets.no_entries')}}<i class="ion-arrow-up-c"></i>

    </div><!-- /.content-wrapper -->
    @else

    <!-- Content Wrapper. Contains page content -->
    
    <div class="content-wrapper" style="padding-top:40px;"><br/>
        <!-- Main content -->
        <section class="content">

                <div class="row">

                <?php 
                $d_state = ''; 
                $jump = false;
                $today = date("d.M.y");
                ?>
                @foreach($entries as $entry)
                    @if($d_state != $entry->date)
                        <?php $d_state = $entry->date; ?>
                            @if($jump)
                                <?php $jump = false; ?>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <?php $jump = true; ?>
                            <div class="col-md-12">
                                <div>
                                    <div class="box-header ui-sortable-handle" style="background-color:white;">
                                        <i class="fa fa-calendar-o"></i>
                                        <h3 class="box-title" style="font-size:15px;">
                                            @if($today == $entry->date)
                                                {{trans('timesheets.weekdays.'.date("l"))}} ({{trans('timesheets.today')}})
                                            @else 
                                                {{trans('timesheets.weekdays.'.date("l", strtotime($entry->date)))}}
                                            @endif
                                        </h3>
                                        <div class="pull-right">
                                            {{date("d M Y", strtotime($entry->date))}}
                                        </div>
                                    </div>
                                    <div class="box-body chat box eg-{{$entry->date}}" id="chat-box" style="overflow: hidden; width: auto;">
                    @endif
                                    @if($entry->user_id == Auth::id())
                                    <div class="editor-icon te-{{$entry->id}}">
                                        <div class="editor-icon-set" style="position:absolute;width:100%;display:none;text-align:center;padding-top:22px;font-size:25px;z-index: 1000;">
                                            <a href="#" style="color:#626262;padding-right:20px;" class="gs-w edit-entry" id="{{$entry->id}}" name="{{$entry->id}}"><i class="ion-edit iedit" style="font-style:none;"></i></a>
                                            <a href="#" style="color:#626262;padding-right:20px;" id="{{$entry->id}}" name="{{$entry->id}}" class="delete-entry"><i class="ion-trash-a trash" style="font-style:none;"></i></a>
<!--                                             <a href="#" style="color:#626262;padding-right:20px;" id="{{$entry->id}}" name="{{$entry->id}}"><i class="ion-ios-checkmark approve" style="font-style:none;"></i></a>
                                            <a href="#" style="color:#626262;padding-right:20px;" id="{{$entry->id}}" name="{{$entry->id}}"><i class="ion-help-circled explain" style="font-style:none;"></i></a> -->
                                        </div>
                                    @else
                                    <div>
                                    @endif

                                        <div class="item">
                                            @if(!empty($entry->avatar))
                                                <img src="{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image={{$entry->avatar}}" alt="user image" class="offline"/>
                                            @else
                                                <img src="{{ asset('/assets/dist/img/profile-placeholder.png') }}" alt="user image" class="offline"/>
                                            @endif
                                            <p class="message">
                                                <a class="name">
                                                    <small class="text-muted pull-right" style="font-size:15px;"><i class="ion-ios-stopwatch-outline"></i> {{date("G\hi", strtotime($entry->time))}}min</small>
                                                    {{$entry->first_name . ' ' . $entry->last_name}}
                                                </a>
                                                @if(is_numeric($entry->value))
                                                    <strong>{{sprintf(trans('timesheets.select_types.PROJECT'), $entry->project_name)}}
                                                @else
                                                    <strong>{{trans('timesheets.select_types.'.$entry->value)}}
                                                @endif
                                                @if(!empty($entry->comment))
                                                    :</strong> {{$entry->comment}}
                                                @else
                                                    </strong>
                                                @endif
                                            </p>
                                        </div><!-- /.item -->
                                    </div>
                @endforeach                

                @if($jump)
                    <?php $jump = false; ?>
                        </div>
                    </div>
                </div>
                @endif
                </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @endif
</div><!-- ./wrapper -->


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

</div>

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
<!-- FastClick -->
<script src='{{ asset('/assets/plugins/fastclick/fastclick.min.js') }}'></script>
<!-- AdminLTE App -->
<script src="{{ asset('/assets/dist/js/app.min.js') }}" type="text/javascript"></script>

<script src='{{ asset('/assets/plugins/sticky/sticky.js') }}' type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/select2/select2.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/qtip/jquery.qtip.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/jQueryUI/jquery.ui.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/foggy/foggy.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/stopwatch/stopwatch.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    $(function(){

        $("#top-menu-bar").sticky({topSpacing:0});

        $("#switch-to-report").click(function(){
            $("#new-entry").hide();
            $("#time-report").show();
        });

        $("#switch-to-entry").click(function(){
            $("#time-report").hide();
            $("#new-entry").show();            
        });

        $(document).on('click', '.gs-w', function(){
            $( "#edit-widget" ).dialog('open');
            return false;
        });

        $(".edit-entry").click(function(){
            var id = $(this).attr('id');
            var post_data = {
                "_token": '{{ csrf_token() }}',
                "event": 'get_time_entry',
                "entry_id": id
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
                    if(data.status == 'ok'){
                        $("#entry_id").val(data.result.id);
                        $('.edescription').select2('val', data.result.value);
                        $("#etime").val(data.result.time);
                        $("#enote").val(data.result.comment);
                        $("#edate").val(data.result.date);                    
                    }else{
                        $( "#edit-widget" ).dialog('close');
                        ktNotification('{{trans('timesheets.notification.error')}}', '', 4000, false)
                    }
                    $(".ui-dialog").unblock();
                },
                'json'
            );
        });


        $( "#edit-widget" ).dialog({
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
            width: 420,
            height: 300,
            resizable: false,
            dialogClass: 'main-dialog-class',
            hide: { effect: "fadein", duration: 1000 },
            buttons: [
                {
                    text: "{{trans('timesheets.widget_general.close')}}",
                    click: function() {
                        if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                            $('#main-content-wraper').foggy(false);
                        }

                        $( this ).dialog( "close" ).position();;
                    }
                },
                {
                    text: "{{trans('timesheets.widget_general.save')}}",
                    style: 'padding-right:0px;',
                    click: function() {

                        var post_data = {
                            "_token": '{{ csrf_token() }}',
                            "event": 'update_time_entry',
                            "entry_id": $("#entry_id").val(),
                            "time": $("#etime").val(),
                            "date": $("#edate").val(),
                            "value": $(".edescription").val(),
                            "comment": $("#enote").val()
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
                                if(data.status == 'ok'){
                                    ktNotification('{{trans('timesheets.changes_saved')}}', '', 4000, true)
                                    window.location = '{{url('/home')}}';
                                }else{
                                    ktNotification('{{trans('timesheets.notification.error')}}', data.messages, 4000, false)
                                }
                                $(".ui-dialog").unblock();
                            },
                            'json'
                        );
                    }
                }
            ],
            close: function(){
                if(/chrome|safari/.test( navigator.userAgent.toLowerCase() )){
                    $('#main-content-wraper').foggy(false);
                }
            }
        });

        $('.description').select2({
            data:[
                {id:'',text:'&nbsp;', value:''},
                @foreach(\App\Models\ktLang::$timeDescriptionList as $key => $message)
                {id:'{{$key}}',text:'<div>{{trans('timesheets.select_types.'.$key)}}</div>', value:'{{$key}}'},
                @endforeach

                @foreach(\App\Models\ktLang::$timeProjectDescriptionList as $key => $message)
                    @foreach($projects as $project)
                    {id:'{{$project->id}}',text:'<div><?php printf(trans('timesheets.select_types.'.$key), $project->project_name);?></div>', value:'{{$project->id}}'},
                    @endforeach
                @endforeach
            ],
            dropdownCssClass: "bigdrop",
            placeholder: "{{trans('timesheets.toolbar.description_or_project')}}",
            allowClear: false,
            escapeMarkup: function (m) { return m; }
        });

        $('.edescription').select2({
            data:[
                {id:'',text:'&nbsp;', value:''},
                @foreach(\App\Models\ktLang::$timeDescriptionList as $key => $message)
                {id:'{{$key}}',text:'<div>{{$message}}</div>', value:'{{$key}}'},
                @endforeach

                @foreach(\App\Models\ktLang::$timeProjectDescriptionList as $key => $message)
                    @foreach($projects as $project)
                    {id:'{{$project->id}}',text:'<div><?php printf(trans('timesheets.select_types.'.$key), $project->project_name);?></div>', value:'{{$project->id}}'},
                    @endforeach
                @endforeach
            ],
            dropdownCssClass: "bigdrop",
            placeholder: "{{trans('timesheets.toolbar.description_or_project')}}",
            allowClear: false,
            escapeMarkup: function (m) { return m; }
        });

        $('.users').select2({
            data:[
                {id:'',text:'&nbsp;', value:''},
                @foreach($users as $user)
                {id:'{{$user->id}}',text:'<div>{{$user->first_name}} {{$user->last_name}}</div>', value:'{{$user->id}}'},
                @endforeach
            ],
            dropdownCssClass: "bigdrop",
            placeholder: "{{trans('timesheets.toolbar.user')}}",
            allowClear: false,
            escapeMarkup: function (m) { return m; }
        });

        $('.projects').select2({
            data:[
                {id:'',text:'&nbsp;', value:''},
                @foreach(\App\Models\ktLang::$timeDescriptionList as $key => $message)
                {id:'{{$key}}',text:'<div>{{trans('timesheets.select_types.'.$key)}}</div>', value:'{{$key}}'},
                @endforeach
                
                @foreach(\App\Models\ktLang::$timeProjectDescriptionList as $key => $message)
                    @foreach($projects as $project)
                    {id:'{{$project->id}}',text:'<div><?php printf($message, $project->project_name);?></div>', value:'{{$project->id}}'},
                    @endforeach
                @endforeach
            ],
            dropdownCssClass: "bigdrop",
            placeholder: "{{trans('timesheets.toolbar.description_or_project')}}",
            allowClear: false,
            escapeMarkup: function (m) { return m; }
        });

        $("#reset-report").click(function(){
            $("#dater").val('');
            $('.users').select2('val', '');
            $('.projects').select2('val', '');
        });

        $('#edate').daterangepicker({
            singleDatePicker: true, 
            format: 'DD/MM/YYYY'
        });

        $('#date').daterangepicker({
            singleDatePicker: true, 
            format: 'DD/MM/YYYY',
            maxDate: '{{date("d/m/Y")}}'
        });

        $('#dater').daterangepicker({
            "opens": "right",
            "autoapply": true,
            format: 'DD/MM/YYYY',
            "maxDate": '{{date("d/m/Y")}}',
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
        });

        @if(isset($_POST['user']))
        $('.users').select2('val', '{{ $_POST['user'] }}');
        @endif

        @if(isset($_POST['project']))
        $('.projects').select2('val', '{{ $_POST['project'] }}');
        @endif

        $("#refresh-report").click(function(){
            $("#refresh-form").submit();
        });

        $(".delete-entry").click(function(){
            var id = $(this).attr('id');
            var post_data = {
                "_token": '{{ csrf_token() }}',
                "event": 'delete_entry',
                "entry_id":  id
            };
            $.post(
                    '{{ url('/api') }}',
                    post_data,
                    function( data ) {
                        if(data.status == 'ok'){
                            $(".te-"+id).remove();
                            ktNotification('{{trans('timesheets.notification.time_entry_deleted')}}', '', 500, true)
                        }else{
                            ktNotification('{{trans('timesheets.notification.error')}}', data.messages, 4000, false)
                        }
                    },
                    'json'
            );
        });

        $("#add-time-entry").click(function(){
            var post_data = {
                "_token": '{{ csrf_token() }}',
                "event": 'new_time_entry',
                "value": $(".description").val(),               
                "time":  $("#time").val(),               
                "date":  $("#date").val(),               
                "comment":  $("#comment").val()
            };
            $.post(
                '{{ url('/api') }}',
                post_data,
                function( data ) {
                    //console.log(data);
                    if(data.status == 'ok'){
                        ktNotification('{{trans('timesheets.notification.time_entry_added')}}', '', 500, true)
                        window.location = '{{url('/home')}}';
                    }else{
                        ktNotification('{{trans('timesheets.notification.error')}}', data.messages, 4000, false)
                    }
                },
                'json'
            );
        });

        $("#time").inputmask("Regex", {
            regex: "^((0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$|^(([1-9]|0[1-9]|1[0-9]|2[0-3])\\.([0-9][0-9]))$"
        });
        $("#etime").inputmask("Regex", {
            regex: "^((0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$|^(([1-9]|0[1-9]|1[0-9]|2[0-3])\\.([0-9][0-9]))$"
        });

        $('#time').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '<div style="font-size:12px;"><strong>{{trans('timesheets.example')}}:</strong> 08:30 {{trans('timesheets.or')}} 8.5<br/></div>'
            },
            show: { ready: false, event: 'focus'},
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
            hide: {
                event: 'blur'
            }
        });

        // $('#explain-more').qtip({ // Grab some elements to apply the tooltip to
        //     content: {
        //         text: '<div style="font-size:10px;">(1) You have a request<br/>for some time entries.</div>'
        //     },
        //     show: { ready: true},
        //     position: {
        //         my: 'top center',  // Position my top left...
        //         at: 'bottom center', // at the bottom right of...
        //     },
        //     style: {
        //         classes: 'qtip-light qtip-shadow'
        //     }
        // });

        $('#start-timer').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.stopwatch')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('#stop-timer').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.stopwatch_stop')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('#add-time-entry').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.save_time_entry')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('.explain').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: 'Request Details'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('.iedit').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.edit_time_entry')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('.trash').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.delete_time_entry')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('#reset-report').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.reset')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('#refresh-report').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.apply_filter')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('#dater').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.range_limit')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('#switch-to-entry').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.timesheet')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $('.approve').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: '{{trans('timesheets.tooltip.approve_entry')}}'
            },
            position: {
                my: 'top center',  // Position my top left...
                at: 'bottom center', // at the bottom right of...
            },
            style: {
                classes: 'qtip-light qtip-shadow'
            },
        });

        $("#start-timer").click(function(){
            var post_data = {
                "_token": '{{ csrf_token() }}',
                "event": 'start_stopwatch'
            };
            $.post(
                '{{ url('/api') }}',
                post_data,
                function( data ) {
                    //console.log(data);
                    if(data.status == 'ok' && data.start == 1){
                        if(Stopwatch.settings.stop == 0){
                            Stopwatch.init();
                        }else{
                             Stopwatch.restart();               
                        }
                        $("#start-timer").hide();
                        $("#stop-timer").show();
                    }
                },
                'json'
            );
        });

        $("#stop-timer").click(function(){
            var post_data = {
                "_token": '{{ csrf_token() }}',
                "event": 'stop_stopwatch'
            };
            $.post(
                '{{ url('/api') }}',
                post_data,
                function( data ) {
                    //console.log(data);
                    if(data.status == 'ok' && data.stop == 1){
                        Stopwatch.stop();           
                        $("#stop-timer").hide();
                        $("#start-timer").show();
                    }
                },
                'json'
            );
        });

        @if(!empty($stopwatch))
        @if(!empty($stopwatch->hours) && !empty($stopwatch->mins) && !empty($stopwatch->secs))
        $("#time").inputmask("Regex", {
            regex: "^[0-9][0-9]:[0-9][0-9]:[0-9][0-9]$"
        });       
        Stopwatch.continue({{(int)$stopwatch->hours}},{{(int)$stopwatch->mins}},{{(int)$stopwatch->secs}});
        $("#start-timer").hide();
        $("#stop-timer").show();
        @endif
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

<div id="edit-widget" title="{{trans('timesheets.edit_title')}}">
    <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
        <input type="hidden" id="entry_id" name="entry_id" value=""/>
        <div class="col-md-6" style="padding-right: 0px;">
            <input type="text" class="form-control" name="etime" id="etime" value="" placeholder="{{trans('timesheets.toolbar.time_input')}}" style="height:28px;padding-right:0px;width:90%;border-radius:5px !important; border:1px solid #B8B8B8;background-color:white !important;text-align:center;">
        </div>
        <div class="col-md-6" style="padding-right: 0px;">
             <input type="text" class="form-control pull-right" name="edate" id="edate" style="height:28px;padding-right:0px;width:90%;border-radius:5px !important; border:1px solid #B8B8B8;background-color:white !important;text-align:center;" placeholder="{{trans('timesheets.toolbar.date')}}"/>
        </div>
    </div>
    <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
        <div class="col-md-12" style="padding-right: 0px;">
            <select class="edescription" style="padding-right:0px;width:100%;border-radius:5px !important; border:0px solid #B8B8B8 !important;background-color:#ecf0f5 !important;text-align:center;">
                <option></option>
            </select>
        </div>
    </div>
    <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
        <div class="col-md-12" style="padding-right: 0px;">
            <textarea style="width:100%;height:80px; resize:none;"rows="7" id="enote" name="enote" placeholder="{{trans('timesheets.toolbar.comment')}}"></textarea>
        </div>
    </div>
</div>
</div>
</body>
@endsection
