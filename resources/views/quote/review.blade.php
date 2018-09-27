@extends('app')

@section('content')
<body class="login-page board" style="background-color:#eaeaea;">

  <div class="login-box" style="width:700px;margin-top:25px;margin-bottom:25px;" id="main-content-wraper">
    <div class="login-logo">
      @if(!isset($questionarie))
      @if(Config::get('app.single'))
      <?php echo \App\Models\ktUser::getSystemLabel(); ?>
      @else
      <?php echo Config::get('app.name'); ?>
      @endif
      @else
      <?php echo \App\Models\ktUser::getUserSystemLabel($questionarie->account_id); ?>
      @endif
      <div style="font-size:14px;">{{$questionarie->name}}</div>
    </div><!-- /.login-logo -->
    <div class="login-box-body">       
      <div class="form-group">
        @foreach($qa as $question => $answer)
          <label style="display:block;font-size:15px;">{{$question}}</label>
          <div style="padding-bottom:25px;border-top:1px solid #f4f4f4;padding-top:10px">
            @if(empty($answer))
              <span style="color:#d1d1d1;">{{trans('quote.review.no_input')}}</span>
            @elseif(is_array($answer))
              @if(ctype_xdigit(str_replace('#','', $answer[0])))
                &nbsp;
                @foreach($answer as $color)
                  <span title="{{$color}}" style="border:1px solid black;width:16px;height:16px;background-color:{{$color}};float:left;margin-right:8px;padding-top:0px;">&nbsp;</span>
                @endforeach
              @else
                {{implode(', ', $answer)}}
              @endif
            @else
              {{$answer}}
            @endif
          </div>
        @endforeach
      </div>
      <div class="row" style="margin:0px;">
        <div class="col-xs-12">
          @if($questionarie->reference_id == NULL)
            <button type="button" class="btn btn-block btn-flat assign">{{trans('quote.review.assign')}}</button>
          @else
          <div class="col-xs-5">
              @if($questionarie->reference_id != NULL && $questionarie->target == 'CUSTOMER')    
                  <button onclick="window.location='{{url('/office/customer/questionnaries/'.$questionarie->reference_id)}}';" type="button" class="btn btn-block btn-flat"><i class="ion ion-arrow-left-c">&nbsp;</i>&nbsp;{{trans('quote.review.go_to_customer')}}</button>
              @endif          
           </div>
           <div class="col-xs-2">
           </div>
            <div class="col-xs-5" style="text-align:center;">
            @if($questionarie->status != 'REVIEWED') 
            <form role="form" method="POST" action="{{ url('/quote/review/'. $questionarie->id) }}" id="assign-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="id" value="{{$questionarie->id}}" />
                <input type="hidden" name="event" value="reviewed" />                
                <button type="submit" class="btn btn-block btn-flat"><i class="ion ion-checkmark-round">&nbsp;</i>&nbsp;{{trans('quote.review.mark_reviewed')}}</button>
            </form>
            @else
                {{trans('quote.review.reviewed_by')}} {{$questionarie->first_name . ' ' . $questionarie->last_name}}<br/>{{trans('quote.review.on')}} {{date("d F Y", strtotime($questionarie->submission_date))}}
            @endif
          </div>
          @endif
        </div><!-- /.col -->       
      </div>
  </div><!-- /.login-box-body --><br/>
  <p class="login-box-msg" style="color:gray;">Copyright © 2015 @if(Config::get('app.single'))
    <?php echo \App\Models\ktUser::getSystemLabel(); ?>
    @else
    <?php echo Config::get('app.name'); ?>
    @endif <br/>
    <?php echo Config::get('app.copyright.terms'); ?>
  </p>
  </div><!-- /.login-box -->

  <div id="assign-customer" title="{{trans('quote.review.widget.title')}}">
       <form role="form" method="POST" action="{{ url('/quote/review/'. $questionarie->id) }}" id="assign-form">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <input type="hidden" name="id" value="{{$questionarie->id}}" />
          <input type="hidden" name="event" value="assign_customer" />
          <div class="row" style="margin: 0px !important;padding:10px !important;font-size:13px;">
              <div class="col-md-12" style="padding-right: 0px;">
                  <select style="width:100%;color:gray !important;text-align: center !important;" id="customer_id" name="customer_id"  placeholder="" class="c_questionnaries">
                  </select>
              </div>
          </div>

      </form>
  </div>


  <!-- jQuery 2.1.3 -->
  <script src="{{ asset('/assets/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
  <!-- Bootstrap 3.3.2 JS -->
  <script src="{{ asset('/assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>

  <!-- Color Picker -->
  <script src="{{ asset('/assets/plugins/colorpicker/bootstrap-colorpicker.min.js') }}" type="text/javascript"></script>

  <script src="{{ asset('/assets/plugins/jQueryUI/jquery.ui.js') }}" type="text/javascript"></script>

  <script src="{{ asset('/assets/plugins/select2/select2.js') }}" type="text/javascript"></script>

  <script src="{{ asset('/assets/plugins/foggy/foggy.js') }}" type="text/javascript"></script>

  <script>
  $(function () {

    $(".my-colorpicker2").colorpicker();

    $('#customer_id').select2({
        data: [
            @foreach($customers as $s_customer) {id: '{{$s_customer->id}}',text: '<div>{{$s_customer->customer_name}}</div>', value: '{{$s_customer->id}}'},@endforeach
        ],
        dropdownCssClass: "bigdrop",
        placeholder: "{{trans('quote.review.widget.select_customer')}}",
        allowClear: false,
        escapeMarkup: function (m) {
            return m;
        }
    });

    $(".assign").click(function(){
        $( "#assign-customer" ).dialog('open');
    });

    $( "#assign-customer" ).dialog({
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
        width: 500,
        height:140,
        resizable: false,
        dialogClass: 'main-dialog-class',
        buttons: [
            {
                text: "{{trans('quote.review.widget.close')}}",
                click: function() {
                    $('#main-content-wraper').foggy(false);
                    $( this ).dialog( "close" ).position();;
                }
            },
            {
                text: "{{trans('quote.review.widget.assign')}}",
                click: function () {
                    $("#assign-form").submit();
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