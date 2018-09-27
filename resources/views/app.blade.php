<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    @if(isset($questionarie))
      <title>{{$questionarie->name}}</title>
    @else
      <title><?php echo strip_tags(\App\Models\ktUser::getSystemLabel()); ?> {{trans('application.title_ext')}}</title>
    @endif
      <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
      <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
      <meta http-equiv="Pragma" content="no-cache" />
      <meta http-equiv="Expires" content="0" />
      @if(Config::get('app.reCaptcha.enabled'))
      <script src='https://www.google.com/recaptcha/api.js'></script>
      @endif
      <!-- Bootstrap 3.3.2 -->
      <link href="{{ asset('/assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
      <!-- Font Awesome Icons -->
      <link href="{{ asset('/assets/dist/css/font-awsome.min.css') }}" rel="stylesheet" type="text/css" />
      <!-- Ionicons -->
      <link href="{{ asset('/assets/dist/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />

      <link href="{{ asset('/assets/plugins/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet" type="text/css" />

      <link rel="stylesheet" type="text/css" href="{{ asset('/assets/dist/css/jquery.gridster.css') }}">

      <link href="{{ asset('/assets/plugins/colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet">

      <link rel="stylesheet" type="text/css"  href="{{ asset('/assets/dist/css/select2.css') }}" />

      <link href="{{ asset('/assets/dist/css/skins/_all-skins.min.css') }}" rel="stylesheet" type="text/css" />

      <link rel="stylesheet" type="text/css"  href="{{ asset('/assets/dist/css/select2.css') }}" />

      <link rel="stylesheet" type="text/css" href="{{ asset('/assets/dist/css/jquery.qtip.css') }}"  />

      <link rel="stylesheet" type="text/css" href="{{ asset('/assets/dist/jquery.ui.css') }}"  />

      <link rel="stylesheet" type="text/css" href="{{ asset('/assets/dist/css/animate.css') }}"  />

      <link rel="stylesheet" type="text/css" href="{{ asset('/assets/plugins/summernote/summernote.css') }}"  />

      <link href="{{ asset('/assets/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

      <link href="{{ asset('/assets/dist/css/elegant-icons/style.css') }}" rel="stylesheet" type="text/css" />

      <link rhref="{{ asset('/assets/plugins/validator/screen.css') }}" rel="stylesheet">

      <!-- Theme style -->
      <link href="{{ asset('/assets/dist/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset('/assets/dist/css/app.css') }}" rel="stylesheet" type="text/css" />      
      <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->

      <link rel="stylesheet" href="{{ asset('/assets/plugins/formbuilder/vendor.css') }}" />
      <link rel="stylesheet" href="{{ asset('/assets/plugins/formbuilder/formbuilder.css') }}" />

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="{{ asset('/assets/dist/js/html5shiv.js') }}"></script>
      <script src="{{ asset('/assets/dist/js/respond.js') }}"></script>
      <![endif]-->

      <script src="{{ asset('/assets/dist/js/jquery.1.5.2.min.js') }}"></script>
      <script src="{{ asset('/assets/dist/js/modernizr.js') }}"></script>

      <script src="{{ asset('/assets/plugins/dropzone/dropzone.js') }}"></script>
      <link rel="stylesheet" type="text/css" href="{{ asset('/assets/plugins/dropzone/dropzone.css') }}"  />

      <script>
        //paste this code under head tag or in a seperate js file.
        // Wait for window load
        $(window).load(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");
        });

        function ktNotification(title, message, delay, type){
            if(type) {
                var icon_url = '{{asset('/assets/ok.png')}}';
            }else{
                var icon_url = '{{asset('/assets/error.png')}}';
            }

            if($.isArray(message)) {
                var count = 1;
                var final_message = '';
                $.each(message, function (index, message) {
                    final_message = final_message + (count++) + '.' + message + '<br/>';
                });
            }else{
                var final_message = message;
            }

            $.notify({
                icon: icon_url,
                title: title,
                message: final_message
            },{
                placement: {
                    from: "bottom",
                    align: "right"
                },
                type: 'minimalist',
                delay: delay,
                newest_on_top: true,
                allow_dismiss: true,
                z_index: 99999999999,
                animate: {
                    enter: 'animated fadeInUp',
                    exit: 'animated fadeOutUp'
                },
                icon_type: 'image',
                template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<img data-notify="icon" class="img-circle pull-left">' +
                '<span data-notify="title">{1}</span>' +
                '<span data-notify="message">{2}</span>' +
                '</div>'
            });
        }

        Dropzone.options.myAwesomeDropzone = {
            maxFile: 1,
            init: function() {
                this.on("complete", function() {
                    // If all files have been uploaded
                    if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                        var _this = this;
                        // Remove all files
                        _this.removeAllFiles();
                    }
                });
            },
            success: function(file, response){
                if(response.status == 'ok'){
                    $(".profile-image").attr('src','{{ url('/api') }}?event=get_avatar&_token={{ csrf_token() }}&image=' + response.image)
                }else{
                    ktNotification('{{trans('application.error')}}',response.message,2000, false);
                }
            }
        };

      </script>

      <style>
          .color-palette {
              height: 50px;
              line-height: 50px;
              text-align: center;

          }
          .color-palette-set {
              margin-bottom: 15px;
          }
          .color-palette span {
              display: block;
          }
          .color-palette:hover span {
              display: block;
          }

          /* This only works with JavaScript, if it's not present, don't show loader */
          .no-js #loader { display: none;  }
          .js #loader { display: block; position: absolute; left: 100px; top: 0; }
          .se-pre-con {
              position: fixed;
              left: 0px;
              top: 0px;
              width: 100%;
              height: 100%;
              z-index: 9999;
              background: url({{ asset('/assets/loading.gif') }}) center no-repeat #fff;
          }

      </style>

      <?php
        $layoutColors = false;
        if(is_object(\Auth::user())){
            $layoutColors = \Auth::user()->getSystemColors();
        }
      ?>

      @if($layoutColors !== false)
      <style>
          .mmenu .nav > li > a:hover {
            background-color: {{\App\adjustBrightness($layoutColors['layout_color'], -10)}} !important;
          }
          .mmenu .nav > li > a:focus,
          .mmenu .nav > li > a:active {
              background-color: {{\App\adjustBrightness($layoutColors['layout_color'], -20)}} !important;
          }
          .thank-you-message {
            background-color: {{\App\adjustBrightness($layoutColors['layout_color'], -10)}} !important;
          }


          .ribbon {
              position: absolute;
              right: -5px; top: -8px;
              z-index: 1;
              overflow: hidden;
              width: 75px; height: 75px;
              text-align: right;
          }
          .ribbon span {
              font-size: 10px;
              font-weight: bold;
              color: {{$layoutColors['text_color']}};
              text-transform: uppercase;
              text-align: center;
              line-height: 20px;
              transform: rotate(45deg);
              -webkit-transform: rotate(45deg);
              width: 100px;
              background: {{$layoutColors['layout_color']}};
              /*background: linear-gradient(#9BC90D 0%, #79A70A 100%);*/
              position: absolute;
              top: 19px; right: -21px;
          }
          .ribbon span::before {
              content: "";
              position: absolute; left: 0px; top: 100%;
              z-index: -1;
              border-left: 3px solid {{\App\adjustBrightness($layoutColors['layout_color'], -10)}};
              border-right: 3px solid transparent;
              border-bottom: 3px solid transparent;
              border-top: 3px solid {{\App\adjustBrightness($layoutColors['layout_color'], -10)}};
          }
          .ribbon span::after {
              content: "";
              position: absolute; right: 0px; top: 100%;
              z-index: -1;
              border-left: 3px solid transparent;
              border-right: 3px solid #79A70A;
              border-bottom: 3px solid transparent;
              border-top: 3px solid #79A70A;
          }
          .previous > span {
              font-size: 10px;
              font-weight: bold;
              color: #FFF;
              text-transform: uppercase;
              text-align: center;
              line-height: 20px;
              transform: rotate(45deg);
              -webkit-transform: rotate(45deg);
              width: 100px;
              background: red !important;
              /*background: linear-gradient(#9BC90D 0%, #79A70A 100%);*/
              position: absolute;
              top: 19px; right: -21px;
          }
          .previous span::before {
              content: "";
              position: absolute; left: 0px; top: 100%;
              z-index: -1;
              border-left: 3px solid red;
              border-right: 3px solid transparent;
              border-bottom: 3px solid transparent;
              border-top: 3px solid red;
          }
          .previous span::after {
              content: "";
              position: absolute; right: 0px; top: 100%;
              z-index: -1;
              border-left: 3px solid transparent;
              border-right: 3px solid red;
              border-bottom: 3px solid transparent;
              border-top: 3px solid red;
          }

          .board-map{
              position: fixed;
              color: {{$layoutColors['text_color']}} !important;
              bottom: 0px;
              right: 50%;
              color: white;
              z-index:1600;
          }

          .board-zoom{
              position: fixed;
              color: {{$layoutColors['text_color']}} !important;
              bottom: 0px;
              right: 35%;
              color: white;
              z-index:1600;
              display: none;
          }


          .b-map {
              height: 100%;
              width: 100%;
              position:absolute;
              top:0px;
              left:0px;
              background-color: #ecf0f5;
              z-index:1500;
              display: none;
          }

          #kougiland{
              position:relative;
              width:110px;
              height:18px;
              text-align:center;
              font-size: 12px;
              vertical-align: middle;
              padding-top: 2px;
              background-color:{{\App\adjustBrightness($layoutColors['layout_color'], 5)}};
              box-shadow: 0 4px 8px #ccc, 10px 5px 8px -4px #ccc, -9px 5px 8px -4px black;
              padding-bottom:20px;
          }

          .clear {  clear: both;  }

          #kougiland:before,#kougiland:after{
              content:'';
              position:absolute;
              top:5.5px;
              height: 30px;
              width: 30px;
              background:red;
              -webkit-transform: rotateZ(45deg);
              background-color:{{\App\adjustBrightness($layoutColors['layout_color'], 5)}};
          }
          #kougiland:before{
              left:-15px;
              box-shadow: 0px 17px 11px -4px black;
          }
          #kougiland:after{
              right:-15px;
              box-shadow: 17px 0px 11px -4px black;
          }

          #wiki-content {
              div {}
              p {}
              ul {}
              li {}
              span {}
              a {}
          }
      </style>
      @endif

      @if(Config::get('app.ga_tracking_code') !== NULL)
      <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', '{{Config::get('app.ga_tracking_code')}}', 'auto');
          ga('send', 'pageview');

      </script>
      @endif

  </head>

  @yield('content')

</html>