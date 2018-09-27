@extends('app')

@section('content')
<body class="login-page board" style="background-color:#eaeaea;">

  <div class="login-box">
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
      <div style="font-size:14px;">
        {{trans('quote.thankyou.title')}}
      </div>
    </div><!-- /.login-logo -->
    <div class="login-box-body" style="text-align:center;">
      {{trans('quote.thankyou.message')}}
      @if(Auth::user()->isClient())
        <br/><br/>
        <button onclick="window.location='{{url('/')}}';" type="button" class="btn btn-block btn-flat"><i class="ion ion-arrow-left-c">&nbsp;</i>&nbsp;Back to Account</button>
      @endif
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

  <script>
  $(function () {
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    $(".my-colorpicker2").colorpicker();
  });
  </script>
</body>
@endsection