<?php


  require __DIR__.'/../../../bootstrap/autoload.php';
  $app = require_once __DIR__.'/../../../bootstrap/app.php';
  $kernel = $app->make('Illuminate\Contracts\Http\Kernel');
  $response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
  );

  $error = [];
  $file_premissions_issues = [];

  $path = realpath('../../../storage');

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
  $premissions_issue = false;
  if(isset($_POST['event']) && $_POST['event'] == 'install'){
        try {
            # MySQL with PDO_MYSQL
            if(strpos($_POST['dbhost'], ':') !== false){
                list($host, $port_number) = explode(':', trim($_POST['dbhost']),2);
                $port = ';port=' . $port_number;
            }else{
                $host = trim($_POST['dbhost']);
                $port = '';
            }
            $mainsql = "mysql:host=" . $host . ";dbname=" . $_POST['dbname'] . $port;
            $db = new PDO($mainsql, $_POST['dbuser'], $_POST['dbpassword']);

            $query = file_get_contents("db_structure.sql");
            $stmt = $db->prepare($query);

            if ($stmt->execute()) {

                sleep(20);
                $sql = 'INSERT INTO account (company_name) VALUES ("")';
                $db = new PDO($mainsql, $_POST['dbuser'], $_POST['dbpassword']);
                $accounts = $db->prepare($sql);

                $account = $accounts->execute();
                if ($account  === false) {
                    $emessage = $db->errorInfo();
                    $error[] = $emessage[2];
                }
                $account_id = $db->lastInsertId();

                $password = Hash::make($_POST['password']);
                $date = date("Y-m-d H:i:s");

                $sql = 'INSERT INTO users (name, email, password, created_at, updated_at) ' .
                    'VALUES ("","' . $_POST['email_address'] . '","' . $password . '", "' . $date . '", "' . $date . '")';
                $db = new PDO($mainsql, $_POST['dbuser'], $_POST['dbpassword']);
                $users = $db->prepare($sql);
                $user = $users->execute();
                if ($user === false) {
                    $emessage = $db->errorInfo();
                    $error[] = $emessage[2];
                }

                $user_id = $db->lastInsertId();

                $sql = 'INSERT INTO users_extended (user_id, account_id, first_name, last_name, user_level) ' .
                    'VALUES (' . $user_id . ',"' . $account_id . '","", "", "ADMIN")';
                $db = new PDO($mainsql, $_POST['dbuser'], $_POST['dbpassword']);
                $users_ext = $db->prepare($sql);

                $user_ext = $users_ext->execute();
                if ($user_ext  === false) {
                    $emessage = $db->errorInfo();
                    $error[] = $emessage[2];
                }

                if ($account  === false|| $user_ext  === false || $user  === false) {
                    $error[] = "Check with the administrator.";
                } else {
                    $dbconfig = "APP_ENV=production\nAPP_DEBUG=false\nDB_HOST="
                        . $host
                        . "\nDB_DATABASE="
                        . $_POST['dbname']
                        . "\nDB_USERNAME="
                        . $_POST['dbuser']
                        . "\nDB_PASSWORD="
                        . $_POST['dbpassword'];

                    if(!empty($port)){
                        $dbconfig = $dbconfig . "\nDB_PORT=" . $port_number;
                    }

                    try {
                        file_put_contents('../../../.env', $dbconfig);
                    } catch (Exception $e) {
                        $premissions_issue = nl2br($dbconfig);
                    }

                }
            } else {
                $error[] = 'Could not establish the connection with the database';
            }
        }catch(Exception $e) {
            $error[] = $e->getMessage();
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

  $installed = false;
  if(file_exists('../../../.env')){
    $installed = true;
  }

?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<title>AgileTeam Project Management Tool Setup</title>

  	<!-- Bootstrap 3.3.2 -->
  	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <link href="../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="../dist/css/app.css" rel="stylesheet" type="text/css" />
</head>
<body class="board">
<body class="login-page board" style="background-color:#eaeaea;">

  <div class="login-box">
    <div class="login-logo">
      	<b>Agile</b>Team Setup
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
                Before you can continue with the installation you need to change the priviledges for the listed directories below to 777. You can try to do that by clicking on the button below or manually: 
                <ul style="padding:15px;font-size:9px;">
                  <?php 
                      foreach($file_premissions_issues as $directory) {
                  ?>
                        <li><?php echo $directory['name']. ' ('. $directory['premissions'] .')' ;?></li>
                  <?php 
                      }
                  ?>
                </ul>
                In case if you are still not able to continue with the installation please double check if there are any issues on the server side and if the priviledges were changed, if not you will need to change them manually for each listed directory. Once you make these changes manually just refresh this page.
              </div>
            </div><!-- /.col -->
          </div>
           <button type="submit" class="btn btn-primary btn-block btn-flat">Try to change the priviledges</button>    
       </form>
    <?php } else if (!$installed ){ ?>

       <?php if(count($error) > 0 || $premissions_issue !== false){  ?>
        <div class="col-xs-12"> 
              <div class="alert alert-danger" style="font-size:11px;">
                Uppss something is not right, please try to fix the issues below:
                <ul style="padding:15px;font-size:9px;">
                    <?php
                      if($premissions_issue !== false){
                        echo "The Final step of the setup has failed due to a server side priviledges setup. In order to complete the installation please manually create a file called '.env' in the root of the AgileTeam Directory with this content:<br/>";
                        echo $premissions_issue;
                      }else{
                        foreach($error as $errorno) {
                    ?>
                          <li><?php echo $errorno; ?></li>
                    <?php 
                        }
                      }
                    ?>
                </ul>
              </div>
            </div><!-- /.col -->
          </div>
      <?php } ?>



    <form role="form" method="POST" action="index.php">
      <input type="hidden" id="event" name="event" value="install" />
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
		  <h4 class="page-header" style="width:100%;text-align:center;">Database</h4>      	
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="dbhost" value="" placeholder="Database Host : Port"/>
        </div>
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="dbname" value="" placeholder="Database Name"/>
        </div>        
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="dbuser" value="" placeholder="Database User"/>
        </div>
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="dbpassword" value="" placeholder="Database Password"/>
        </div>
		    <h4 class="page-header" style="width:100%;text-align:center;">Admin User</h4>
        <div class="form-group has-feedback">
          <input type="email" class="form-control" name="email_address" value="" placeholder="Admin Email"/>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="password" value="" placeholder="Admin Password"/>
        </div>

        <div class="row" style="margin:0px;">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Install</button>                  
          </div><!-- /.col -->
          <div class="col-xs-2">    
             &nbsp;
          </div><!-- /.col -->          
        </div>
        
      </form>

    <?php } else {?>
    <div class="alert alert-danger" style="font-size:11px;text-align:center;">
      <b>Please remove this directory as a security prevention</b><br/>
      <?php echo getcwd(); ?>
    </div>
    <div style="text-align:center;">
      You have successfully installed the AgileTeam. Enjoy using it and good luck with your business !<br/><br/>
      <a type="submit" href="/" class="btn btn-primary btn-block btn-flat">Login to AgileTeam</a>
    </div>
    <?php } ?>

    </div><!-- /.login-box-body -->
  </div><!-- /.login-box -->

</body>

</body>
</html>