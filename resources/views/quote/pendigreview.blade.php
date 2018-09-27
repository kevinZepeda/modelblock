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
                <div class="col-md-12 tab-right" style="min-height:900px;">
                    <div class="tab">
                        <h4 style="margin: 0px !important;color:#909090;">{{trans('quote.pending_review.title')}}</h4>
                        <div style="margin-top:20px;">
                            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 10%;">
                                        {{trans('quote.pending_review.col.date')}}
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 35%;">
                                        {{trans('quote.pending_review.col.questionnarie_name')}}
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 15%;">
                                        {{trans('quote.pending_review.col.in_relation')}}
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 15%;">
                                        Type
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 15%;">
                                        {{trans('quote.pending_review.col.status')}}
                                    </th>
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 10%;">
                                        {{trans('quote.pending_review.col.action')}}
                                    </th>
                                </tr>
                                </thead>
                                <tbody> 
                                    @foreach($questionnaries as $item)
                                        <tr role="row" class="odd" id="td{{$item->id}}">
                                            <td>{{date("m.d.Y", strtotime($item->submission_date))}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>
                                            @if($item->target !== 'NULL')
                                                {{\App\Models\ktQuote::getSubmitter($item->target, $item->reference_id)}}
                                            @else
                                                Unknown
                                            @endif
                                            </td>
                                            <td>
                                                @if($item->target !== 'PROJECT')
                                                    Project Related
                                                @elseif($item->target !== 'CUSTOMER')
                                                    New Order
                                                @endif
                                            </td>
                                            <td>{{trans('quote.pending_review.status.'.$item->status)}}</td>
                                            <td>    
                                                <div class="btn-group">
                                                    <button onclick="location.href='{{url('/quote/review/'.$item->id)}}'" type="submit" class="btn btn-xs">{{trans('quote.pending_review.button.review')}}</button>
                                                    <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" style="left:-50px;">
                                                        @if($item->reference_id !== NULL && $item->target == 'CUSTOMER')
                                                            <li><a  href="{{url('/office/customer/questionnaries/'.$item->reference_id)}}">{{trans('quote.pending_review.button.go_to_customer')}}</a></li>
                                                        @endif
                                                        <li><a  target="_blank" id="{{$item->id}}" class="delete_qa">{{trans('quote.pending_review.button.delete')}}</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                  </div>
                </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

</div><!-- ./wrapper -->

<!-- jQuery 2.1.3 -->
<script src="{{ asset('/assets/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset('/assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('/assets/plugins/formbuilder/vendor.js') }}"></script>

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


        $(".delete_qa").click(function(){
            var cnf = confirm("{{trans('quote.pending_review.delete_message')}}");
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
                            ktNotification('{{trans('quote.pending_review.deleted_message')}}' , '', 500, true);
                            $("#td"+id).remove();
                        }else{
                            ktNotification('{{trans('quote.pending_review.error')}}' , '{{trans('quote.pending_review.error_message')}}', 3600, false);
                        }
                    },
                    'json'
                );
            }
        });

    }); 

</script>

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

</body>
@endsection