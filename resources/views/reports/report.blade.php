@extends('app')

@section('content')
    <style>
    #canvas-holder1 {
        width: 300px;
        margin: 20px auto;
    }
    #canvas-holder2 {
        width: 50%;
        margin: 20px 25%;
    }
    #chartjs-tooltip {
        opacity: 1;
        position: absolute;
        background: rgba(0, 0, 0, .7);
        color: white;
        padding: 3px;
        border-radius: 3px;
        -webkit-transition: all .1s ease;
        transition: all .1s ease;
        pointer-events: none;
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
    }
    .chartjs-tooltip-key{
      display:inline-block;
      width:10px;
      height:10px;
    }
    </style>
<body class="skin-blue layout-boxed sidebar-collapse">
<div class="se-pre-con"></div>
<div id="main-content-wraper">
<!-- Site wrapper -->
<div style="background-color:#909090;">
    @include('menu')
</div>
<!-- =============================================== -->
@include('reports.report-menu')
<div class="wrapper">
    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            
            <div class="row">
                <div class="col-md-12">
                  @include('val-errors')
                    <div class="box box-default">
                                
                      <!-- Stats Top Graph Bot -->
                      <div class="panel" id="pchart8" style="border:0px;">
                         <canvas id="canvas" height="80" width="300"></canvas><div id="chartjs-tooltip"></div>
                         <div class="panel-menu" style="width:100%;">
                          <div class="chart-legend" data-chart-id="#high-line3" style="border-top:1px solid #ccc;">

                            <form role="form" method="POST" action="{{ url('/invoice/reports') }}">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                  <!-- Sidebar toggle button-->

                                  <div style="float:left;padding-top:6px;padding-left:10px;">
                                      <div class="form-group has-feedback" style="margin-bottom:0px;">
                                        <span class="ion ion-ios-calendar-outline form-control-feedback" style="color:gray;font-size:20px;right:120px;padding-top:4px;"></span>                    
                                        <input type="text" value="{{@$_POST['date_from']}}" class="form-control pull-right" name="date_from" id="date_from" style="height:28px;padding-right:0px;width:150px;border-radius:5px !important; border:0px solid #B8B8B8;background-color:#ecf0f5 !important;text-align:center;" placeholder="{{trans('report.finance.input.from_date')}}"/>
                                      </div>
                                  </div>      

                                  <div style="float:left;padding-top:6px;padding-left:10px;">
                                      <div class="form-group has-feedback" style="margin-bottom:0px;">
                                        <span class="ion ion-ios-calendar-outline form-control-feedback" style="color:gray;font-size:20px;right:120px;padding-top:4px;"></span>                    
                                        <input type="text" value="{{@$_POST['date_to']}}" class="form-control pull-right" name="date_to" id="date_to" style="height:28px;padding-right:0px;width:150px;border-radius:5px !important; border:0px solid #B8B8B8;background-color:#ecf0f5 !important;text-align:center;" placeholder="{{trans('report.finance.input.to_date')}}"/>
                                      </div>
                                  </div>   

                                  <div style="float:left;padding-left:10px;padding-top:6px;padding-right:5px;">
                                      <div class="form-group has-feedback timesheet" style="margin-bottom:0px;">                 
                                          <select class="breakdown" id="breakdown" name="breakdown" style="padding-right:0px;width:250px;border-radius:5px !important; border:0px solid #B8B8B8 !important;background-color:#ecf0f5 !important;text-align:center;">
                                              <option></option>
                                          </select>
                                      </div>
                                  </div>

                                  <div style="float:left;padding-left:10px;padding-top:6px;padding-right:5px;" id="curr" >
                                      <div class="form-group has-feedback timesheet" style="margin-bottom:0px;">                 
                                          <select class="currency" id="currency" name="currency" style="padding-right:0px;width:100px;border-radius:5px !important; border:0px solid #B8B8B8 !important;background-color:#ecf0f5 !important;text-align:center;">
                                              <option></option>
                                          </select>
                                      </div>
                                  </div>

                                  <div class="btn-group" style="margin:5px;">
                                      <button  type="submit" class="btn btn-sm btn-sm mr10" value="get_report" id="event" name="event">{{trans('report.finance.button.refresh')}}</button>
                                     <!--  <button type="button" class="btn btn-sm btn-sm mr10 dropdown-toggle" data-toggle="dropdown">
                                          <span class="caret"></span>
                                          <span class="sr-only">Toggle Dropdown</span>
                                      </button>
                                      <ul class="dropdown-menu" role="menu">
                                          <li><a download href="#">Export as PDF</a></li>
                                          <li><a href="#" class="archive" id="#">Export as CSV</a></li>                                          
                                      </ul> -->
                                  </div>
                                  <div style='display:inline-block;float:right;padding-top:10px;padding-right:10px;'>
                                    <div style="height:10px;width:10px;background-color:#00ACE6;display:inline-block;padding-right:5px;">&nbsp;</div> {{trans('report.finance.revenue')}} &nbsp;
                                    <div style="height:10px;width:10px;background-color:#F75138;display:inline-block;padding-right:5px;">&nbsp;</div> {{trans('report.finance.expenses')}} &nbsp;
                                    <div style="height:10px;width:10px;background-color:#8DCD91;display:inline-block;padding-right:5px;">&nbsp;</div> {{trans('report.finance.estimates')}} &nbsp;
                                  </div>
                                </form>
                          </div>
                        </div>
                      </div>
                
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                        <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="">
                                {{trans('report.finance.col.date')}}
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="">
                                {{trans('report.finance.col.invoice_number')}}
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="">
                                {{trans('report.finance.col.customer_name')}}
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" >
                                {{trans('report.finance.col.taxable_amount')}}
                            </th>
                            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" >
                                {{trans('report.finance.col.tax')}}
                            </th>   
                            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" >
                                {{trans('report.finance.col.total')}}
                            </th>   
                            <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" >
                                {{trans('report.finance.col.status')}}
                            </th>                             
                        </tr>
                        </thead>
                        <tbody>
                            @if(isset($report))
                              @foreach($report as $entity)
                                <tr role="row" class="odd" id="">
                                    <td>{{$entity->invoice_date}}</td>
                                    <td>{{$entity->invoice_number}}</td>
                                    <td>{{@$entity->customer_name}}</td>
                                    <td>{{sprintf(\App\Models\ktLang::$currencyList[$entity->currency]['format'], number_format($entity->taxable,2, ".", ""))}}</td>
                                    <td>{{sprintf(\App\Models\ktLang::$currencyList[$entity->currency]['format'], number_format($entity->tax,2, ".", ""))}}</td>
                                    <td>{{sprintf(\App\Models\ktLang::$currencyList[$entity->currency]['format'], number_format($entity->total,2, ".", ""))}}</td>
                                    <td>{{ucfirst(strtolower(trans('finance.invoice_state.'.$entity->status)))}}</td>
                                </tr>
                              @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
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

<script src="{{ asset('/assets/plugins/datatables/jquery.dataTables.js') }}" type="text/javascript"></script>

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

<script src="{{ asset('/assets/plugins/chartjs/Chart.js') }}"></script>

<script type="text/javascript">

$(function(){

        $('#date_to').daterangepicker({
            singleDatePicker: true, 
            format: 'DD/MM/YYYY',
            maxDate: '{{date("d/m/Y")}}'
        });


        $('#date_from').daterangepicker({
                singleDatePicker: true, 
                format: 'DD/MM/YYYY',
                maxDate: '{{date("d/m/Y")}}',
            },
            function(start) {
                $('#date_to').data('daterangepicker').setOptions({
                    singleDatePicker: true, 
                    format: 'DD/MM/YYYY',
                    maxDate: '{{date("d/m/Y")}}',
                    minDate: start.format('DD/MM/YYYY')
                });
            }
        );

        $('.currency').select2({
            data:[
                 @foreach(@$currency as $cur)
                 {id:'{{$cur->currency}}',text:'{{$cur->currency}}', value:'{{$cur->currency}}'},
                @endforeach
            ],
            dropdownCssClass: "bigdrop",
            placeholder: "Currency",
            allowClear: false,
            escapeMarkup: function (m) { return m; }
        });

        $('.breakdown').select2({
            data:[
                {id:'INVOICE',text:'{{trans('report.finance.revenue_report')}}', value:''},
                {id:'QUOTE',text:'{{trans('report.finance.estimates_report')}}', value:''},
                {id:'EXPENSE',text:'{{trans('report.finance.expenses_report')}}', value:''},
            ],
            dropdownCssClass: "bigdrop",
            placeholder: "Select the Report Breakdown",
            allowClear: false,
            escapeMarkup: function (m) { return m; }
        });

        $('.breakdown').select2('val', '{{ @$_POST["breakdown"] }}')
        $('.currency').select2('val', '{{ @$default_currency }}')

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

        Chart.defaults.global.pointHitDetectionRadius = 1;

        var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
        var lineChartData = {
          labels : [
            @foreach($graph['profit'] as $entity)
              "{{ $entity->month }}",
            @endforeach
          ],
          bezierCurve : true,
          datasets : [
            {
              label: "{{trans('report.finance.revenue')}}",
              fillColor : "rgba(220,220,220,0.2)",
              strokeColor : "#00ACE6",
              pointColor : "#00ACE6",
              pointStrokeColor : "#fff",
              pointHighlightFill : "#fff",
              pointHighlightStroke : "#00ACE6",
              data : [
                  @foreach($graph['profit'] as $entity)
                    {{ $entity->total }},
                  @endforeach
              ]
            },
            {
              label: "{{trans('report.finance.expenses')}}",
              fillColor: "rgba(151,187,205,0.2)",
              strokeColor: "#F75138",
              pointColor: "#F75138",
              pointStrokeColor: "#fff",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "#F75138",
              data : [
                  @foreach($graph['expenses'] as $entity)
                    {{ $entity->total }},
                  @endforeach
              ]
            },
            {
              label: "{{trans('report.finance.estimates')}}",
              fillColor : "rgba(220,220,220,0.2)",
              strokeColor : "#8DCD91",
              pointColor : "#8DCD91",
              pointStrokeColor : "#fff",
              pointHighlightFill : "#fff",
              pointHighlightStroke : "#8DCD91",
              data : [
                  @foreach($graph['estimates'] as $entity)
                    {{ $entity->total }},
                  @endforeach
              ]
            }

          ]

        };

        window.onload = function(){
          var ctx = document.getElementById("canvas").getContext("2d");
          window.myLine = new Chart(ctx).Line(lineChartData, {
            responsive: true
          });
        }

      $('#curr').qtip({ // Grab some elements to apply the tooltip to
          content: {
              text: '{{trans('report.finance.tooltip.applies_only')}}'
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
