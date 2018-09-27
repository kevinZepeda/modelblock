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
                @include('settings.settings-menu')
                <div class="col-md-10 tab-right" style="min-height:600px;">
                    @if(isset($block))
                        <div class="tab">
                            @include("settings.settings-$block")
                        </div>
                        <script>
                            $(document).ready(function(){
                                $(".tab-edit-item, .tab-edit-item-last").click(function(){
                                    var id = $(this).attr('id');
                                    if($("#"+id+"-editor").length > 0) {
                                        $(".editor").hide();
                                        $(".preview").show();
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
                            });
                        </script>
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
<script src="{{ asset('/assets/plugins/bootstrap-growl/growl.js') }}" type="text/javascript"></script>

<!-- Color Picker -->
<script src="{{ asset('/assets/plugins/colorpicker/bootstrap-colorpicker.min.js') }}" type="text/javascript"></script>
<!-- FastClick -->
<script src='{{ asset('/assets/plugins/fastclick/fastclick.min.js') }}'></script>
<!-- AdminLTE App -->
<script src="{{ asset('/assets/dist/js/app.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/select2/select2.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/qtip/jquery.qtip.js') }}" type="text/javascript"></script>

<script>
    $(function() {
        $( ".tabs li" ).click(function(){
            $(".tabs li").removeClass( "tab-li-active" );
            $(this).addClass( "tab-li-active" );
        });

        $(".my-colorpicker2").colorpicker();

        $(".js-example-tags").select2({
            tags: true,
            placeholder: "{{trans('settings.column_names')}}"
        });


        var table = $('#example1').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bSort": true,
            "bInfo": true,
            "bPaging": true,
            "bAutoWidth": false,
            "searchHighlight": true
        });

        $(".delete_qa").click(function(){
            var cnf = confirm("{{trans('settings.questionnaire.qa_input_message')}}");
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
                            ktNotification('{{trans('settings.questionnaire.deleted')}}' , '', 500, true);
                            $("#td"+id).remove();    
                            table.draw();            
                        }else{
                            ktNotification('{{trans('settings.questionnaire.failure')}}' , '{{trans('settings.questionnaire.failure_message')}}', 3600, false);
                        }
                    },
                    'json'
                );
            }
        });

        $("#invoice_number_format").change(function(){
            if($(this).val() == 'NUMBERFORMAT'){
                $("#invoice_prefix").show();
            }else{
                $("#invoice_prefix").hide();
            }
        })

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
