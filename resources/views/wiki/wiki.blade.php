@extends('app')

@section('content')
    <body class="skin-blue layout-boxed sidebar-collapse" style="background-color: #ecf0f5 !important;">
    <div class="se-pre-con"></div>
    <div id="main-content-wraper">
        @include('menu')
        @include('wiki.wiki-menu')
        <div>
            <div class="wrapper">
                <div class="content-wrapper">
                <section class="content" style="padding-top:0px;padding-bottom:0px;background-color:white !important;">
                    <div class="row">
                        <div class="col-md-20 tab-right" id="wiki-content" style="min-height:600px;">
                            <h2 style="border-bottom:1px solid #DEE2E7;padding-bottom:20px;margin-top:0px;">{{ucfirst(strtolower($page->title))}}</h2>
                            <?php echo $page->content; ?>
                        </div>
                    </div>
                </section>
                    </div>
                <!-- /.content -->
            </div>
        </div>
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
    <script src="{{ asset('/assets/plugins/input-mask/jquery.inputmask.extensions.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('/assets/plugins/input-mask/jquery.inputmask.regex.extensions.js') }}"
            type="text/javascript"></script>

    <script src="{{ asset('/assets/dist/js/sprymap.js') }}" type="text/javascript"></script>

    <!-- block ui -->
    <script src="{{ asset('/assets/plugins/jquery-blockui/jquery.blockUI.js') }}" type="text/javascript"></script>

    <!-- Gridster -->
    <script src="{{ asset('/assets/plugins/gridster/jquery.gridster.js') }}?{{md5(date("Y-m-d h:i:s"))}}"
            type="text/javascript"></script>


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
        $(function() {

            $('#pages-list').select2({
                data: [
                    {id:'',text:'',value:''}
                    @foreach($pages as $tmp_page) ,{id: '{{urlencode(strtolower($tmp_page->page_title))}}',text: '<div>{{$tmp_page->page_title}}</div>', value: '{{urlencode(strtolower($tmp_page->page_title))}}'}@endforeach
                    ],
                dropdownCssClass: "bigdrop",
                placeholder: "{{trans('wiki.find')}} {{$project->project_name}}",
                allowClear: false,
                escapeMarkup: function (m) { return m; }
            });

            $("#pages-list").change(function(){
                window.location.href = "{{url('/office/project/') }}" + '/{{$project->id}}/wiki/' + $(this).val();
            });

            $('#search-page').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('wiki.tooltip.search')}}'
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

            $('#wiki-home').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('wiki.tooltip.home')}}'
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

            $('#go-to-project').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('wiki.tooltip.project')}}'
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

            $('#edit-wiki').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('wiki.tooltip.edit')}}'
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

            $('#delete-page').qtip({ // Grab some elements to apply the tooltip to
                content: {
                    text: '{{trans('wiki.tooltip.edit')}}'
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

            $("#search-page").click(function(){
                if ($("#board-search").css("display") == "none") {
                    $("#board-no-search").hide();
                    $("#board-search").show();
                }else{
                    $("#board-search").hide();
                    $("#board-no-search").show();
                }
            });

            $("#delete-page").click(function(){
                var cnf = confirm("{{trans('wiki.delete')}}");
                if (cnf == true) {
                    $("#delete-page-form").submit();
                }
            });

        });

    </script>

    <form role="form" method="POST" action="/office/project/{{$project->id}}/wiki/{{urlencode(strtolower($page->title))}}" id="delete-page-form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="event" id="event" value="delete_page">
        <input type="hidden" name="project_id" id="project_id" value="{{$project->id}}">
        <input type="hidden" name="page_id" id="page_id" value="{{$page->id}}">
    </form>

    </body>
@endsection