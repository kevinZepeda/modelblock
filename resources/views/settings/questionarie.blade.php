@extends('app')

@section('content')
<body class="skin-blue layout-boxed sidebar-collapse">
<div class="se-pre-con"></div>
<!-- Site wrapper -->
<div style="background-color:#909090;" >
    @include('menu')
</div>
<!-- =============================================== -->

<div class="wrapper">
    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content" style="padding-top:0px;">

            <div class="row">
                <div class="col-md-12 tab-right" style="min-height:600px;padding:0px;">
                    @if(isset($block))
                        <div class="tab">
                            @include("settings.questionnarie-$block")
                        </div>
                    @endif
                </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

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
<!-- jQuery 2.1.3 -->
<script src="{{ asset('/assets/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset('/assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/formbuilder/vendor.js') }}"></script>
<script src="{{ asset('/assets/plugins/formbuilder/formbuilder.js') }}"></script>

<script src="{{ asset('/assets/plugins/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/bootstrap-growl/growl.js') }}" type="text/javascript"></script>

<!-- Color Picker -->
<script src="{{ asset('/assets/plugins/colorpicker/bootstrap-colorpicker.min.js') }}" type="text/javascript"></script>
<!-- FastClick -->
<script src='{{ asset('/assets/plugins/fastclick/fastclick.min.js') }}'></script>
<!-- AdminLTE App -->

<script src="{{ asset('/assets/plugins/select2/select2.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/qtip/jquery.qtip.js') }}" type="text/javascript"></script>

<script>
    $(function(){
        @if(isset($template))

            fb = new Formbuilder({
                selector: '.questionnarie',
                bootstrapData: <?php echo (empty($template->template))?'[]':$template->template;?>
            });

            fb.on('save', function(payload){
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "id": {{$template->id}},
                    "event": 'edit_questionnarie',
                    "name": $("#name").val(),                
                    "data": payload
                };
                $.post(
                    '{{ url('/api') }}',
                    post_data,
                    function( data ) {
                        if(data.status == 'ok' && data.result > 0){
                            ktNotification('{{trans('settings.questionnaire.saved_changes')}}', '', 500, true);
                        }else if(data.status == 'ok'){

                        }else{
                            ktNotification('{{trans('settings.questionnaire.ups')}}', data.message, 500, true);
                        }   
                    },
                    'json'
                );
            });   

        var template_state = {{($template->public)?0:1}};
        $(".publish").click(function(){
            var post_data = {
                "_token": '{{ csrf_token() }}',
                 "id": {{$template->id}},
                "event": 'publish_questionnarie',
                "state": template_state
            };
            $.post(
                '{{ url('/api') }}',
                post_data,
                function( data ) {
                    if(data.status == 'ok'){
                        if(template_state == 1){
                            ktNotification('{{trans('settings.questionnaire.share_preview_url')}}', '', 500, true);
                            $(".publish").text('{{trans('settings.questionnaire.unpublish')}}');
                            template_state = 0;
                        } else {
                            ktNotification('{{trans('settings.questionnaire.share_upublished')}}', '', 500, true);
                            $(".publish").text('{{trans('settings.questionnaire.publish')}}');
                            template_state = 1;
                        }
                    }else{
                        $(".se-pre-con").fadeOut("slow");                        
                        ktNotification('{{trans('settings.questionnaire.ups')}}', data.message, 500, true);
                    }
                },
                'json'
            );
        });

        @else

            fb = new Formbuilder({
                selector: '.questionnarie',
                bootstrapData: []
            });

            fb.on('save', function(payload){
                var post_data = {
                    "_token": '{{ csrf_token() }}',
                    "event": 'new_questionnarie',
                    "name": $("#name").val(),                
                    "data": payload
                };
                $(".se-pre-con").fadeIn("slow");
                $.post(
                    '{{ url('/api') }}',
                    post_data,
                    function( data ) {
                        if(data.status == 'ok'){
                            ktNotification('{{trans('settings.questionnaire.saved')}}', '', 500, true);
                            window.location.href = "{{url('/settings/questionnarie/edit') }}" + '/' + data.id;
                        }else{
                            $(".se-pre-con").fadeOut("slow");                        
                            ktNotification('{{trans('settings.questionnaire.ups')}}', data.message, 500, true);
                        }
                    },
                    'json'
                );
            });
        @endif

        $(".save").click(function(){
            $(".js-save-form").click();
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
</body>
@endsection
