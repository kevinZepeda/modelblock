@extends('app')

@section('content')
<body class="login-page board" style="background-color:#eaeaea;">

  <div class="login-box qa" style="width:700px;margin-top:25px;margin-bottom:25px;">
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

    @if ($validation_messages !== true && count($validation_messages) > 0 && !empty($validation_messages))
    <div class="alert alert-danger alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h5><i class="icon fa fa-ban"></i> {{trans('quote.request.validation.failure')}} @if(Config::get('app.reCaptcha.enabled')){{trans('quote.request.validation.captcha_failure')}} @endif...</h5>
    </div>
    @endif

    <div class="login-box-body">
      <form role="form" method="POST" action="{{ url('/quote/request/'.md5(Config::get('app.salt.qa') . $questionarie->id)) }}" id="form-request">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if($questionarie->type == 'QA')
          <input type="hidden" name="event" value="customer_questionnarie">   
          <input type="hidden" name="id" value="{{$questionarie->id}}">  
        @else
          <input type="hidden" name="event" value="submit_questionnarie">      
        @endif

        @if (count($errors) > 0)
        <div class="row" style="margin:0px;">
          <div class="col-xs-12"> 
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div><!-- /.col -->
        </div>
        @endif  
        <?php $s=0; ?>
        @foreach($fields as $field)
        <?php $style = ($s > 0)? 'border-top:1px solid #f4f4f4;padding-top:10px;':''; ?>
        <div class="form-group" style="{{$style}}">
          <label style="display:block;">{{$field->label}}{{($field->required)?' * ':''}} <label for="{{$field->cid}}{{(in_array($field->field_type, ['radio','checkboxes','color']))?'[]':''}}" class="error" style="display:none;font-size:10px;color:red;"></label></label> 
          @if($field->field_type == 'text')
          <input type="text" class="form-control" name="{{$field->cid}}" id="{{$field->cid}}" value="{{@$data[@$field->cid]}}" placeholder=""/>
          @elseif($field->field_type == 'checkboxes')	
          <?php $j=0; 
            $possbileStates = array();
          ?>
          @foreach($field->field_options->options as $option)
          <div class="checkbox" style="display:inline;padding-right:10px;">
           <label>
            <input type="checkbox" name="{{$field->cid}}[]" value="{{$option->label}}" {{(@in_array($option->label, @$data[@$field->cid]))?'checked':''}} >
             {{$option->label}}
             <?php $possbileStates[] = $option->label;?>
           </label>
         </div>
         @endforeach
         <?php
          if(isset($data[$field->cid])){
            $other = implode(', ', array_diff(@$data[$field->cid], $possbileStates));
          }else{
            $other = '';
          } 
          ?>
         @if(isset($field->field_options->include_other_option))
         @if($field->field_options->include_other_option)
         <div style="display:inline;">Other: <input type="text" value ="{{$other}}"  class="form-control" name="{{$field->cid}}[]" style="display:inline;width:200px;height:25px;"/></div>
         @endif
         @endif
         @elseif($field->field_type == 'radio')	
         <?php $possbileStates = array(); ?>
         @foreach($field->field_options->options as $option)
         <div class="radio" style="display:inline;padding-right:10px;">
           <label>
             <input type="radio" name="{{$field->cid}}[]" value="{{$option->label}}" {{(@in_array($option->label, @$data[$field->cid]))?'checked':''}}>
             {{$option->label}}
             <?php $possbileStates[] = $option->label;?>
           </label>
         </div>
         @endforeach
         <?php
          if(isset($data[$field->cid])){
            $other = implode(', ', array_diff(@$data[$field->cid], $possbileStates));
          }else{
            $other = '';
          } 
          ?>
         @if(isset($field->field_options->include_other_option))
         @if($field->field_options->include_other_option)
         <div style="display:inline;">Other: <input type="text" value ="{{$other}}" class="form-control" name="{{$field->cid}}[]" style="display:inline;width:200px;height:25px;"/></div>
         @endif
         @endif
         @elseif($field->field_type == 'dropdown')	
         <select class="form-control" name="{{$field->cid}}" id="{{$field->cid}}">
           @if($field->field_options->include_blank_option){}
           <option></option>
           @endif
           @foreach($field->field_options->options as $option)
           <option value="{{$option->label}}" {{(@$data[@$field->cid] == $option->label)?'selected':''}}>{{$option->label}}</option>
           @endforeach
         </select>

         @elseif($field->field_type == 'paragraph')	
         <textarea class="form-control" rows="3" placeholder="{{trans('quote.request.enter')}}" style="resize: vertical;" name="{{$field->cid}}">{{@$data[@$field->cid]}}</textarea>

         @elseif($field->field_type == 'color')	
         <?php 
          if(isset($data[$field->cid])){
            $s_colours = array_filter(@$data[$field->cid]);
          }
          ?>
         @for($i=29,$j=0;$j < $i; $j++)
         <?php 
            if(isset($s_colours)){
              $color = array_pop($s_colours); 
              if(empty($color)){
                $color = '';
              }
            }else{
              $color = '';
            }
          ?>
         <div class="input-group my-colorpicker2 colorpicker-element" style="width:20px;display:inline-block;;padding-right:8px;">
          <input type="hidden" class="form-control" value="{{$color}}" name="{{$field->cid}}[]">
          <div class="input-group-addon" style="border:1px solid;padding:0px;margin:0px;">
            <i class="cp" style="background-color: rgb(255, 255, 255);" ></i>
          </div>
        </div>
        @endfor

        @endif
        <?php $s++; ?>
        @if(isset($field->field_options->description))
        <div style="display:block;padding-top:5px;padding-bottom:14px;color:#c2c2c2;"><small>{{$field->field_options->description}}</small></div>
        @endif
      </div>
      @endforeach

      <div class="row" style="margin:0px;border-top:1px solid #f4f4f4;padding-top:20px;">
        @if(Config::get('app.reCaptcha.enabled'))
          <div class="col-xs-6">
                <div class="form-group has-feedback" style="text-align:left;">
                  <div class="g-recaptcha" style="display: inline-block;" data-sitekey="{{Config::get('app.reCaptcha.sitekey')}}"></div>    
                </div> 
          </div>
          <div class="col-xs-6">
            <button type="button" class="btn btn-block btn-flat buttondata" style="height:75px !important;;"><i class="ion ion-paper-airplane">&nbsp;</i>&nbsp;{{trans('quote.request.submit')}}</button>
          </div><!-- /.col -->  
        @else
          <div class="col-xs-12">
            <button type="button" class="btn btn-block btn-flat buttondata" style="height:75px !important;;"><i class="ion ion-paper-airplane">&nbsp;</i>&nbsp;{{trans('quote.request.submit')}}</button>
          </div><!-- /.col -->  
        @endif     
      </div>

    </form>
  </div><!-- /.login-box-body --><br/>
  <p class="login-box-msg" style="color:gray;">Copyright © 2015 @if(Config::get('app.single'))
    <?php echo \App\Models\ktUser::getSystemLabel(); ?>
    @else
    <?php echo Config::get('app.name'); ?>
    @endif <br/>
    <?php echo Config::get('app.copyright.terms'); ?>
   </p>
  </div><!-- /.login-box -->

  <!-- jQuery 2.1.3 -->
  <script src="{{ asset('/assets/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
  <!-- Bootstrap 3.3.2 JS -->
  <script src="{{ asset('/assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
  <!-- Color Picker -->
  <script src="{{ asset('/assets/plugins/colorpicker/bootstrap-colorpicker.min.js') }}" type="text/javascript"></script>

  <script src="{{ asset('/assets/plugins/validator/jquery.validate.js') }}"></script>

  <script>
  $(function () {

    $(".my-colorpicker2").colorpicker();

    $(".buttondata").click(function(){
        if($("#form-request").valid()){
          $("#form-request").submit();
        }
    });
    $("#form-request").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {

            if (!validator.numberOfInvalids())
                return;

            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top - 50
            }, 100);

        },
      rules: {
        @foreach($fields as $field) 
          @if($field->required)
            @if(in_array($field->field_type, ['radio','checkboxes','color']))
              '{{$field->cid}}[]': {
                required: true,
              },
            @else
              '{{$field->cid}}': {
                required: true,
              },
            @endif
          @endif
        @endforeach
      },
      messages: {
          @foreach($fields as $field)
            @if($field->required)
              @if(in_array($field->field_type, ['radio','checkboxes','color']))
                '{{$field->cid}}[]': {
                  required: '( <i class="ion ion-alert-circled">&nbsp</i>&nbsp;{{trans('quote.request.validation.required_field')}})',
                },
              @else
                '{{$field->cid}}': {
                  required: '( <i class="ion ion-alert-circled">&nbsp</i>&nbsp;{{trans('quote.request.validation.required_field')}} )',
                },
              @endif
            @endif
        @endforeach
      }
    });
  });
  </script>
</body>
@endsection