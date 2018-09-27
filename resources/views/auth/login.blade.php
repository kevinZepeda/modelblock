@extends('app')

@section('content')
<body class="login-page board" style="background-color:#eaeaea;">

  <div class="login-box">
    <div class="login-logo">
      @if(Config::get('app.single'))
        @if($systemLogo = \App\Models\ktUser::getSystemLogo())
          <img src="{{ url('/api') }}?event=get_system_logo&_token={{ csrf_token() }}&image={{$systemLogo}}" style="max-width:200px;max-height: 52px;"/>
        @else
          <?php echo \App\Models\ktUser::getSystemLabel(); ?>
        @endif
      @else
        <?php echo Config::get('app.name'); ?>
      @endif
      @if(Config::get('app.beta'))
        <div style="font-size:14px;">&nbsp;{{Config::get('app.betaname')}}</div>
      @endif
    </div><!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">{{trans('auth.login.login_welcome')}}</p>
      <form role="form" method="POST" action="{{ url('/auth/login') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
        <div class="form-group has-feedback">
          <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{trans('auth.login.input.email')}}"/>
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="password" placeholder="{{trans('auth.login.input.password')}}"/>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>           

        <div class="row" style="margin:0px;">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">{{trans('auth.login.button.sign_in')}}</button>
          </div><!-- /.col -->
          <div class="col-xs-2">    
             &nbsp;
          </div><!-- /.col -->          
        </div>
        <div class="row" style="margin-left:0px;text-align:center;">
          <div class="col-xs-12">
            @if(Config::get('app.single'))
              <a  style="padding-right:17px;" href="{{ url('/password/email') }}">{{trans('auth.login.button.forgot_password')}}</a>
            @else
              <a  style="float:left;" href="{{ url('/auth/register') }}">{{trans('auth.login.button.sign_up')}}</a>
              <a  style="float:right;padding-right:17px;" href="{{ url('/password/email') }}">{{trans('auth.login.button.forgot_password')}}</a>
            @endif
          </div><!-- /.col -->
        </div><br/>
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
</body>
@endsection