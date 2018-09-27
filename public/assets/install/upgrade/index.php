<?php

  require __DIR__.'/../../../../bootstrap/autoload.php';
  $app = require_once __DIR__.'/../../../../bootstrap/app.php';
  $kernel = $app->make('Illuminate\Contracts\Http\Kernel');
  $response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
  );

  $errors = [];
  $file_premissions_issues = [];

  $path = realpath('../../../../storage');

  if(isset($_POST['event']) && $_POST['event'] == 'priv'){

    $premissions = decoct(fileperms($path) & 0777);
    if((fileperms($path) & 0777) !== 0777) {
      chmod($path, 0777);
    }

    $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach($iter as $file) {
        if ($file->getFilename() == '.') {
            chmod($file->getPath(), 0777);
        }
    }

  }
  $installed = false;
  $error = false;
  if(isset($_POST['event']) && $_POST['event'] == 'upgrade'){

        try {
            $port = Config::get('database.connections.mysql.port');
            if($port == 'NULL'){
                $port = '';
            }

            $mainsql = "mysql:host=" . Config::get('database.connections.mysql.host') . ";dbname=" . Config::get('database.connections.mysql.database') . $port;
            $db = new PDO($mainsql, Config::get('database.connections.mysql.username'), Config::get('database.connections.mysql.password'));
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = file_get_contents('db_update.sql');
            $stmt = $db->prepare($query);

            if ($stmt->execute()) {
                $installed = true;
            } else {
                $error = true;
                $errors[] = 'Database update failure';
            }
        }catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
  }

  $premissions = decoct(fileperms($path) & 0777);
  if((fileperms($path) & 0777) !== 0777) {
    $file_premissions_issues[] = [
      'name' => $path,
      'premissions' => $premissions
    ];
  }

  $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
  foreach($iter as $file) {
      if ($file->getFilename() == '.') {
          $premissions = decoct(fileperms($file->getPath())& 0777);
          if((fileperms($file->getPath()) & 0777) !== 0777) {
              $file_premissions_issues[] = [
                'name' => $file->getPath(),
                'premissions' => $premissions
              ];
          }
      }
  }

?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<title>AgileTeam Project Management Tool Setup</title>

  	<!-- Bootstrap 3.3.2 -->
  	<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/css/app.css" rel="stylesheet" type="text/css" />
</head>
<body class="board">
<body class="login-page board" style="background-color:#eaeaea;">

  <div class="login-box">
    <div class="login-logo">
      	<b>Agile</b>Team Upgrade
		<div style="font-size:14px;">Just a few fields and you are ready to go!</div>
    </div><!-- /.login-logo -->
    <div class="login-box-body">


<div class="row" style="margin:0px;">

    <?php if(count($file_premissions_issues) > 0){  ?>
      <form role="form" method="POST" action="index.php">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="event" name="event" value="priv" />
            <div class="col-xs-12">
              <div class="alert alert-success" style="font-size:11px;">
                Before you can continue with the upgrade you need to change the priviledges for the listed directories below to 777. You can try to do that by clicking on the button below or manually:
                <ul style="padding:15px;font-size:9px;">
                  <?php
                      foreach($file_premissions_issues as $directory) {
                  ?>
                        <li><?php echo $directory['name']. ' ('. $directory['premissions'] .')' ;?></li>
                  <?php
                      }
                  ?>
                </ul>
                In case if you are still not able to continue with the upgrade please double check if there are any issues on the server side and if the priviledges were changed, if not you will need to change them manually for each listed directory. Once you make these changes manually just refresh this page.
              </div>
            </div><!-- /.col -->
          </div>
           <button type="submit" class="btn btn-primary btn-block btn-flat">Try to change the priviledges</button>
       </form>
    <?php } else if(count($errors) > 0){  ?>
        <form role="form" method="POST" action="index.php">
            <div class="col-xs-12">
                <div class="alert alert-warning" style="font-size:11px;">
                    Updating the database failed because:
                    <ul style="padding:15px;font-size:9px;">
                        <?php
                        foreach($errors as $err) {
                            ?>
                            <li><?php echo $err; ?></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div><!-- /.col -->
            </div>
      </form>
      <?php } else if($installed == true){ ?>

          <div class="alert alert-danger" style="font-size:11px;text-align:center;">
              <b>Please remove this directory as a security prevention</b><br/>
              <?php echo getcwd(); ?>
          </div>
          <div style="text-align:center;">
              You have successfully upgraded the AgileTeam Database !<br/><br/>
              <a type="submit" href="/" class="btn btn-primary btn-block btn-flat">Login to AgileTeam</a>
          </div>

    <?php } else { ?>

    <form role="form" method="POST" action="index.php">
      <input type="hidden" id="event" name="event" value="upgrade" />
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row" style="margin:0px;">
            <div style="text-align:center;">In this step you will execute a database upgrade. Please make sure to create a manual backup of your current database before
        proceeding with the upgrade.
            </div>
        </div><br/>
        <div class="row" style="margin:0px;">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Proceed with Upgrade</button>
          </div><!-- /.col -->
        </div>
        
      </form>

    <?php } ?>

    </div><!-- /.login-box-body -->
  </div><!-- /.login-box -->

</body>

</body>
</html>