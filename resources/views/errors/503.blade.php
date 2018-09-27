<html>
<head>
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <title><?php echo strip_tags(\App\Models\ktUser::getSystemLabel()); ?> {{trans('application.title_ext')}}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 60px;
            margin-bottom: 20px;
        }

        #my-timer {
            font-size: 14px;
        }

        b {
            font-weight: 100;
            font-family: 'Lato';
        }

        a {
            text-decoration: none;
            font-weight: 300;
            font-family: 'Lato';
            color: white;
            background-color: lightgrey;
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>
    <script type="text/javascript" src="js/jquery-1.4.2.js"></script>
    <script type="text/javascript">
        var settimmer = 0;
        $(function () {
            window.setInterval(function () {
                var timeCounter = $("b[id=show-time]").html();
                var updateTime = eval(timeCounter) - eval(1);
                $("b[id=show-time]").html(updateTime);

                if (updateTime == 0) {
                    window.location = ("/");
                }
            }, 1000);

        });
    </script>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">503 Service Unavailable</div>
        <div></div>
    </div>
    <div id="my-timer">
        you will be redirect with in <b id="show-time">15</b> seconds
    </div>
    <br/>
    <a href="/">GO BACK NOW</a>
</div>
</div>
</body>
</html>
