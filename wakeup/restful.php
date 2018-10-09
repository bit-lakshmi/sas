<?php

$dev = 0;


error_reporting($dev);
$version = "2.2.8";




header("Access-Control-Allow-Origin: *");
session_start();



$servername = "solarts.db.9563506.hostedresource.com";
$username = "solarts";
$password = "LKe@gU!1H";
$dbname = "solarts";
if($dev){
  $servername = "192.168.23.92";
  $username = "admin";
  $password = "2648";
  $dbname = "wakeup";
}
//include ("lib.php");
/*if ($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']){
  die(' Access Denied, Your IP: ' . $_SERVER['REMOTE_ADDR'] );
  exit; //just for good measure
}*/


/*
CREATE TABLE  `attendance` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT,
`uid`  VARCHAR(50) NOT NULL ,
`intime` TIME NOT NULL ,
`outtime` TIME NOT NULL ,
`tdate` DATE NOT NULL ,
`comment` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM


CREATE TABLE `wakeup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sip` varchar(20) NOT NULL,
  `pname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `access` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (  `id` )
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

*/


$conn = new mysqli($servername, $username, $password, $dbname);
global $conn;
if ($conn->connect_error) {
    echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Connection failed: '. $conn->connect_error));
    exit();
}


date_default_timezone_set('Asia/Kolkata');
$tdate = date("Y-m-d");
$ctime = date("H:i:s");



function missingDate()
{
  $msDate = date('Y-m-01');
  $mtDate = date('Y-m-d');
  $timeFrom = strtotime($msDate);
  $timeTo = strtotime($mtDate);
  global $conn;
  $sql = "SELECT tdate FROM attendance WHERE uid = '{$_SESSION['userData']['username']}' AND tdate BETWEEN '{$msDate}' AND '{$mtDate}' ";
  $arrMyDates = array();
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        array_push($arrMyDates, $row['tdate']);
      }
  }
  $arrDateSpan = array();
  for ($n = $timeFrom; $n <= $timeTo; $n += 86400)
  {
  $strDate = date("Y-m-d", $n);
  array_push($arrDateSpan, $strDate);
  }
  $arrMissingDates = array_diff($arrDateSpan, $arrMyDates);
  return $arrMissingDates;
}

if(isset($_GET['api'])){
  $api = $_GET['api'];
  switch ($api) {
    case 'ping':
                $sql = "SELECT sip FROM wakeup WHERE status = 1";
                $result = $conn->query($sql);
                $data = "";
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                      $data .= $row['sip']." ";
                    }
                }
                $result->status = "1";
                if($data == ""){
                  $result->status = "0";
                }
                $result->ip = $data;
                echo json_encode($result);
                $conn->close();
                exit();
      break;

    case 'pingupdate':
              $parts = preg_split('/\s+/', $_GET['sip']);
              $sql = "UPDATE wakeup SET status='0' WHERE sip='";
              foreach ($parts as $key => $value) {
                    if($key == 0){
                      $sql .= $value."'";
                    }else {
                      $sql .= " OR sip='".$value."'";
                    }
              }
              if ($conn->query($sql) === TRUE) {
                  echo json_encode(array('status' => 1, 'error'=> 0, 'text'=> 'Config Update'));
              } else {
                  echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> "Error: " . $sql . "<br>" . $conn->error));
              }
              $conn->close();
              exit();
      break;



    case 'islogin':
          if(isset($_COOKIE['autologin'])) {
                $autologin = base64_decode($_COOKIE['autologin']);
                $autologin = explode("|",$autologin);
                $sql = "SELECT * FROM wakeup WHERE username = '".$autologin[0]."'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        if($row['pass'] === $autologin[1]){
                          $_SESSION['login']= "1";
                          $_SESSION['admin']= "0";
                          $autologin = base64_encode($row['username']."|".$row['pass']);
                          setcookie("autologin", $autologin, time() + 80000, '/');
                          $row['pass'] = "";
                          $_SESSION['userData']= $row;
                          echo json_encode(array('status' => 1));
                        }
                        else {
                          echo json_encode(array('status' => 0));
                        }
                      if($row['access'] == "2"){
                        $_SESSION['admin']= "1";
                      }else if ($row['access'] == "0") {
                        echo json_encode(array('status' => 0));
                      }
                    }
                }
          }else {
            echo json_encode(array('status' => 0));
          }

    break;


    case 'doLogin':
              $sql = "SELECT * FROM wakeup WHERE username = '".$_POST['uname']."'";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                      if($row['pass'] === $_POST['pass']){
                        $_SESSION['login']= "1";
                        $_SESSION['admin']= "0";
                        $autologin = base64_encode($row['username']."|".$row['pass']);
                        setcookie("autologin", $autologin, time() + 80000, '/');
                        $row['pass'] = "";
                        $_SESSION['userData']= $row;
                        echo json_encode(array('status' => 1));
                      }
                      else {
                        echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Incorrect password. please try again'));
                      }
                    if($row['access'] == "2"){
                      $_SESSION['admin']= "1";
                    }else if ($row['access'] == "0") {
                      echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Login disabled'));
                    }
                  }
              }
     break;

     case 'doUpdatePass':
                   $pdata = $_POST;
                   if($pdata['npass'] === $pdata["rnpass"]){
                     $sql = "SELECT pass FROM wakeup WHERE username = '".$_SESSION['userData']['username']."'";
                     $result = $conn->query($sql);
                     $pass = "";
                     if ($result->num_rows > 0) {
                       while($row = $result->fetch_assoc()) {
                         $pass = $row['pass'];
                       }
                     }
                     if($pass === $pdata['opass']){
                       $sql = "UPDATE wakeup SET pass='".$pdata['npass']."' WHERE username='".$pdata['uname']."'";

                       if ($conn->query($sql) === TRUE) {
                           echo json_encode(array('status' => 1, 'error'=> 0, 'text'=> 'Password changed successfully.'));
                       } else {
                           echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> "Error: " . $sql . "<br>" . $conn->error));
                       }
                     }else {
                       echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Wrong password. try again'));
                     }
                   }else {
                     echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Password not matched.'));
                   }
                   $conn->close();
                   exit();
      break;


     case 'pcStart':
             $sql = "UPDATE wakeup SET status='1' WHERE sip='".$_SESSION['userData']['sip']."'";
             if ($conn->query($sql) === TRUE) {
                 echo json_encode(array('status' => 1, 'error'=> 0, 'text'=> 'System start with in 10 seconds.'));
             } else {
                  $error = "Error: " . $sql . "<br>" . $conn->error;
                  echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> $error));
             }
             $conn->close();
            exit();
    break;

    case 'isStart':
            $sql = "SELECT status FROM wakeup WHERE sip = '".$_SESSION['userData']['sip']."'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo json_encode(array('status' => $row['status']));
                }
            }
            $conn->close();
            exit();
    break;



    case 'addUser':
              if(!$_SESSION['admin']){
                  echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Access not allowed.'));
                  exit();
              }
              $pdata = $_POST;
              $sql = "SELECT id FROM wakeup WHERE username = '".$pdata['uname']."'";
              $result = $conn->query($sql);
              $pass = "";
              if ($result->num_rows == 0) {
                  $sql = "INSERT INTO wakeup (sip, pname, username, pass)
                  VALUES ('".$pdata['sip']."', '".$pdata['sname']."', '".$pdata['uname']."', '".$pdata['pass']."')";
                  $result = $conn->query($sql);
                  if($result){
                    echo json_encode(array('status' => 1, 'error'=> 0, 'text'=> 'User added successfully.'));
                  }else {
                    echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> "Error: " . $result . "<br>" . $conn->error));
                  }
              } else {
                    echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> "User already exists."));
              }
              $conn->close();
            exit();
      break;


      case 'todayim':
                  $sql = "SELECT * FROM  attendance WHERE  uid = '".$_SESSION['userData']['username']."' AND  tdate =  '".$tdate."'";
                  $isin = TRUE;
                  $result = $conn->query($sql);
                  if (!$result->num_rows > 0) {
                    $sql = "INSERT INTO attendance (uid, intime, tdate) VALUES ('".$_SESSION['userData']['username']."', '".$ctime."', '".$tdate."')";
                  }else {
                    $sql = "UPDATE attendance SET outtime='". $ctime ."' WHERE uid = '".$_SESSION['userData']['username']."' AND tdate = '{$tdate }'";
                    $isin = FALSE;
                  }
                  $result = $conn->query($sql);
                  if($result){
                    if ($isin) {
                      echo json_encode(array('status' => 1, 'error'=> 0, 'text'=> "Hi " . $_SESSION['userData']['username'] . ", Have a good day."));
                    }else {
                      echo json_encode(array('status' => 1, 'error'=> 0, 'text'=> "Hi " . $_SESSION['userData']['username'] . ", See you tomorrow."));
                    }

                  }else {
                    echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> "Error: " . $result . "<br>" . $conn->error));
                  }
                  $conn->close();
                  exit();
        break;


        case 'test':
          //print "<pre>";
          $today      = new DateTime('now');
          $tomorrow   = new DateTime('tomorrow');
          $difference = $today->diff($tomorrow);

          echo $difference->format('%h hours %i minutes %s seconds until tomorrow');


        break;



        case 'doTimeUpdate':
                if(!$_SESSION['login']){
                    echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Access not allowed.'));
                    exit();
                }
                $sql = "UPDATE attendance SET ".$_POST['type']."='".$_POST['time'] ."' WHERE uid = '".$_SESSION['userData']['username']."' AND tdate = '{$tdate }'";
                $result = $conn->query($sql);
                if($result){
                    echo json_encode(array('status' => 1, 'error'=> 0, 'text'=> "Time update successfully."));
                  }else {
                    echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> "Error: " . $result . "<br>" . $conn->error));
                }
                $conn->close();
                exit();
        break;


    default:
      // code...
      break;
  }
}










if(isset($_GET['page'])){
  $page = $_GET['page'];
  switch ($page) {
    case 'login':
        ?>
                <form>
                    <div class="row">
                     <div class="input-field col s12">
                       <input placeholder="Username" id="uname" name="uname" type="text" class="validate">
                       <label>User Name</label>
                     </div>
                   </div>
                    <div class="row">
                     <div class="input-field col s12">
                       <input placeholder="Passoword" id="pass" name="pass" type="text" class="validate">
                       <label>Passoword</label>
                     </div>
                   </div>
                   <div class="row">
                    <div class="input-field col s12">
                       <div align="center">
                      <button id="doLogin" type="button" name="login" class="waves-effect waves-light btn-large btn green ">Login</button>
                    </div>
                    </div>
                  </div>
              </form>
      <?php

      break;

      case 'dologout':
          session_unset();
          session_destroy();
          setcookie("autologin", "", 0);
          unset($_COOKIE['autologin']);
          echo "logout";
          exit();
      break;


      case 'updatepass':
      if(!$_SESSION['login']){
            echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Access not allowed.'));
        exit();
      }

      ?> <div class="row">
                 <div class="input-field col s12">
                   <input placeholder="Old Passoword" id="opass" type="text" class="validate">
                   <label>Old Passoword</label>
                 </div>
               </div>
               <div class="row">
                <div class="input-field col s12">
                  <input placeholder="New Passoword" id="npass" type="text" class="validate">
                  <label>New Passoword</label>
                </div>
              </div>
              <div class="row">
               <div class="input-field col s12">
                 <input placeholder="Re-New Passoword" id="rnpass" type="text" class="validate">
                 <label>Re-New Passoword</label>
               </div>
             </div>
             <div class="row">
              <div class="input-field col s12">
                 <div align="center">
                <a id="updatepass" class="waves-effect waves-light btn-large btn green">Update</a>
              </div>
              </div>
            </div>
      <?php
        break;


        case 'home':
        if(!$_SESSION['login']){
              echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Access not allowed.'));
          exit();
        }
        ?>
        <div class="center-align">
          <div class="card-panel z-depth-4">
                <span class="blue-text text-darken-2"><h3>Hi, <?php echo ucfirst($_SESSION['userData']['username']) ?></h3></span>
          </div>
          <span class="blue-text text-darken-2"><h5><?php echo date('l, F jS, Y') ?><h5></span>
        </div>








        <?php
        break;






    case 'systemboot':
    if(!$_SESSION['login']){
          echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Access not allowed.'));
      exit();
    }
                  ?><div align="center" style="margin: 5em;">
                        <style>
                        .btn-floating.btn-large{
                          width: 150px !important;
                          height: 150px !important;
                        }
                        .btn-floating.btn-large i {
                          line-height: 150px !important;
                        }
                        .btn-large i {
                          font-size: 4rem !important;
                        }
                        #prostart {
                        display:none;
                        }
                        </style>
                        <a id="pstart" data-sip="<?php echo $_SESSION['userData']['sip'] ?>" class="btn-floating btn-large pulse waves-effect waves-light green scale-transition"><i class="mdi mdi-power-settings"></i></a>
                      </div>
                  <?php
      break;



      case 'sideMenu':
      if(!$_SESSION['login']){
            echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Access not allowed.'));
        exit();
      }
          ?>
          <li><div class="user-view">
            <div class="background">
              <img src="./imgs/office.jpg">
            </div>
            <a ><img class="circle" src="./imgs/user.png"></a>
            <a ><span class="white-text name"><?php echo $_SESSION['userData']['username'] ?></span></a>
            <a ><span class="white-text email"><?php echo $_SESSION['userData']['sip'] . " | " . $tdate . " " . $ctime; ?></span></a>
          </div></li>
          <li><a id="pageload" data-page="home" class="waves-effect"><i class="mdi mdi-home"></i>Home</a></li>
          <li><a id="pageload" data-page="attend" class="waves-effect"><i class="mdi mdi-blur-radial"></i>Attendance</a></li>
          <li><a id="pageload" data-page="systemboot" class="waves-effect"><i class="mdi mdi-power-settings"></i>System Start</a></li>
          <li><div class="divider"></div></li>
          <li><a id="pageload" data-page="updatepass" class="waves-effect"><i class="mdi mdi-account-key"></i>Change password</a></li>
          <li><a id="pageload" data-page="dologout" class="waves-effect"><i class="mdi mdi-exit-to-app"></i>Logout</a></li>
          <?php if($_SESSION['admin'] == "1") { ?>
          <li><div class="divider"></div></li>
          <li><a class="subheader">Admin</a></li>
          <li><a id="pageload" data-page="addNew" class="waves-effect"><i class="mdi mdi-account-plus"></i>Add New</a></li>
          <li><a id="pageload" data-page="allui" class="waves-effect"><i class="mdi mdi-format-list-checkbox"></i>All User</a></li>
          <?php } ?>
          <li><div class="divider"></div></li>
          <li><a class="subheader">About</a></li>
          <li><a class="subheader">Version <?php echo $version ?></a></li>
          <li><a class="subheader">Last Update: <?php echo date("Y-m-d H:i:s", filemtime(__FILE__)) ?></a></li>
          <li><a href="https://amitmaurya.tk/?ref=saswkeup" class="subheader">by Amit Maurya</a></li>
          <?php
        break;



        case 'addNew':
        if(!$_SESSION['login']){
              echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Access not allowed.'));
          exit();
        }

        ?>
               <div class="row">
                <div class="input-field col s12">
                  <input placeholder="User Name" id="uname" type="text" class="validate">
                  <label>User Name</label>
                </div>
              </div>
                <div class="row">
                 <div class="input-field col s12">
                   <input placeholder="System IP" id="sip" type="text" class="validate">
                   <label>System IP</label>
                 </div>
               </div>
               <div class="row">
                <div class="input-field col s12">
                  <input placeholder="System Name" id="sname" type="text" class="validate">
                  <label>System Name</label>
                </div>
              </div>
              <div class="row">
               <div class="input-field col s12">
                 <input placeholder="Passoword" id="pass" type="text" class="validate">
                 <label>Passoword</label>
               </div>
             </div>
             <div class="row">
              <div class="input-field col s12">
                 <div align="center">
                <a id="savedata" class="waves-effect waves-light btn-large btn green">Save</a>
              </div>
              </div>
            </div>

                <?php
                exit();
          break;

      case 'attend':
      if(!$_SESSION['login']){
            echo json_encode(array('status' => 0, 'error'=> 1, 'text'=> 'Access not allowed.'));
        exit();
      }
      ?>      <div align="center" style="margin: 5em;">
                <style>
                .btn-floating.btn-large{
                  width: 150px !important;
                  height: 150px !important;
                }
                .btn-floating.btn-large i {
                  line-height: 150px !important;
                }
                .btn-large i {
                  font-size: 4rem !important;
                }
            #prostart {
            display:none;
            }
                </style>
                <?php
                $sql = "SELECT outtime, intime FROM  attendance WHERE  uid = '".$_SESSION['userData']['username']."' AND  tdate =  '".$tdate."'";
                $isin = "green";
                $result = $conn->query($sql);
                $isout = "";
                $adata = "";
                if ($result->num_rows > 0) {
                  $isin = "lime darken-3";
                  while($row = $result->fetch_assoc()) {
                    $adata = $row;
                    if ($row['outtime'] != '00:00:00') {
                      $isout = "disabled";
                    }
                  }
                }
                ?>
                <a id="isiam" class="btn-floating btn-large pulse waves-effect waves-light <?php echo $isin. " " . $isout ?> scale-transition" ><i class="mdi mdi-blur-radial"></i></a>
              </div>
            <div class="collection">
              <a href="#!" id="pageload" data-page="missDays" data-action='absent' class="collection-item"><span class="badge red"><?php echo count(missingDate()) ?></span>Absent</a>
              <a href="#!" data-action='hday' class="collection-item"><span class="badge yellow accent-4">4</span>Half Days</a>
              <a href="#!" id="modelLoad" data-action='intime' class="collection-item"><span class="badge grey darken-3"><?php echo $adata['intime'] ?></span>Today In</a>
              <a href="#!" id="modelLoad" data-action='outtime' class="collection-item"><span class="badge grey darken-3"><?php echo $adata['outtime'] ?></span>Today Out</a>
              <a href="#!" data-action='calender' class="collection-item">Calendar view</a>
            </div>
            <?php
        break;


        case 'timeUpdate':
        echo json_encode(array('status' => 1, 'error'=> 0, 'text'=> '
          <h5>Time</h5>
          <input id="timepicker" type="text">
          '));
        break;


        case 'missDays':
              $msDate = date('Y-m-01');
              $mtDate = date('Y-m-d');
              $timeFrom = strtotime($msDate);
              $timeTo = strtotime($mtDate);
              global $conn;
              $sql = "SELECT intime, outtime, tdate, comment FROM attendance WHERE uid = '{$_SESSION['userData']['username']}' AND tdate BETWEEN '{$msDate}' AND '{$mtDate}' ";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                echo '<div class="row"><div class="col s12 m6">';
                  while($row = $result->fetch_assoc()) {
                    echo '<div class="card grey lighten-5">
                              <div class="card-content black-text">
                                  <span class="card-title">'.$row["tdate"].'</span>
                                  <p>'.$row["intime"].' | '.$row["outtime"].'</p>
                                  <p>'.$row["comment"].'</p>
                                </div>
                          </div>';
                  }
                  echo '</div></div>';
              }
        break;



    default:
      // code...
      break;
  }

}


 ?>
