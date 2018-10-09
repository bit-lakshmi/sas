<?php
//error_reporting(0);

$servername = "solarts.db.9563506.hostedresource.com";
$username = "solarts";
$password = "LKe@gU!1H";
$dbname = "solarts";

/*
CREATE TABLE  `solarts`.`attendance` (
`id` INT( 11 ) NOT NULL ,
`uid` INT( 11 ) NOT NULL ,
`intime` TIME NOT NULL ,
`outtime` TIME NOT NULL ,
`date` DATE NOT NULL ,
`comment` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM
*/


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    ?>
    <div class="row">
        <div class="col s12 m6">
          <div class="card blue-grey darken-1">
            <div class="card-content white-text">
              <span class="card-title">Server Error</span>
              <p>Connection failed: <?php echo  $conn->connect_error ; ?></p>
            </div>
          </div>
        </div>
      </div>
      <?php
}

if(isset($_GET['ping'])){
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
}
if(isset($_GET['wping'])){
  $sql = "SELECT status FROM wakeup WHERE sip = '".$_GET['wping']."'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo $row['status'];
      }
  }
  $conn->close();
  exit();
}

if(isset($_GET['pingupdate'])){
  $parts = preg_split('/\s+/', $_GET['pingupdate']);
  $sql = "UPDATE wakeup SET status='0' WHERE sip='";
  foreach ($parts as $key => $value) {
        if($key == 0){
          $sql .= $value."'";
        }else {
          $sql .= " OR sip='".$value."'";
        }
  }
  if ($conn->query($sql) === TRUE) {
      echo "Config Update";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $conn->close();
  exit();
}


$admin = false;
$login = false;
$userdata = "";
if(isset($_POST['login'])){
  $sql = "SELECT * FROM wakeup WHERE username = '".$_POST['uname']."'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          if($row['pass'] === $_POST['pass']){
            $login = TRUE;
            $userdata = $row;
          }
          else {
          ?>
          <div class="row">
              <div class="col s12 m6">
                <div class="card blue-grey darken-1">
                  <div class="card-content white-text">
                    <p>Incorrect password. please try again</p>
                  </div>
                </div>
              </div>
            </div>

        <?php }
        if($row['access'] == "2"){
          $admin = TRUE;
        }else if ($row['access'] == "0") {
          $login = false;
          ?>
          <div class="row">
              <div class="col s12 m6">
                <div class="card blue-grey darken-1">
                  <div class="card-content white-text">
                    <p>Login disabled</p>
                  </div>
                </div>
              </div>
            </div>
          <?php
        }
      }
  }
}


if(isset($_GET['page'])){
$page = $_GET['page'];
switch ($page) {
  case 'login':
    ?>
    <form method="post">
    <div class="row">
     <div class="input-field col s12">
       <input placeholder="Username" name="uname" type="text" class="validate">
       <label>User Name</label>
     </div>
   </div>
  <div class="row">
   <div class="input-field col s12">
     <input placeholder="Passoword" name="pass" type="text" class="validate">
     <label>Passoword</label>
   </div>
 </div>
 <div class="row">
  <div class="input-field col s12">
     <div align="center">
    <button type="submit" name="login" class="waves-effect waves-light btn-large btn green">Login</button>
  </div>
  </div>
</div>
</form>

    <?php
    $conn->close();
    exit();
    break;

    case 'home': ?>
  <div id="prostart" class="progress">
        <div class="indeterminate"></div>
    </div>
    <div align="center" style="margin: 5em;">
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

    </div>
          <?php
          exit();


          case 'attend': ?>
        <div id="prostart" class="progress">
              <div class="indeterminate"></div>
          </div>
                  <div align="center" style="margin: 5em;">
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
                    <a id="isiam" class="btn-floating btn-large waves-effect waves-light green"><i class="mdi mdi-blur-radial"></i></a>
                  </div>
                <div class="collection">
                  <a href="#!" class="collection-item"><span class="badge red">1</span>Absent</a>
                  <a href="#!" class="collection-item"><span class="badge yellow accent-4">4</span>Half Days</a>
                  <a href="#!" class="collection-item"><span class="badge grey darken-3">11:15</span>Max In</a>
                  <a href="#!" class="collection-item"><span class="badge grey darken-3">8:40</span>Max Out</a>
                </div>

                <?php
                break;
                exit();

                case 'todayim':
                $pdata = $_POST;
                $tdate = date("Y-m-d");
                $ctime = date("H:i:s", strtotime('+30 minutes -23 seconds'));
                $sql = "SELECT * FROM  attendance WHERE  uid = '".$pdata['username']."' AND  tdate =  '".$tdate."'";
                $isin = TRUE;
                $result = $conn->query($sql);
                if (!$result->num_rows > 0) {
                  $sql = "INSERT INTO attendance (uid, intime, tdate) VALUES ('".$pdata['username']."', '".$ctime."', '".$tdate."')";
                }else {
                  $sql = "UPDATE attendance SET outtime='". $ctime ."' WHERE uid = '".$pdata['username']."'";
                  $isin = FALSE;
                }
                $result = $conn->query($sql);
                if($result){
                  if ($isin) {
                    echo "Hi " . $pdata['username'] . ", Have a good day.";
                  }else {
                    echo "Hi " . $pdata['username'] . ", See you tomorrow.";
                  }

                }else {
                  echo "Error: " . $result . "<br>" . $conn->error;
                }
                $conn->close();
                exit();

  case 'systemboot': ?>
<div id="prostart" class="progress">
      <div class="indeterminate"></div>
  </div>
  <div align="center" style="margin: 5em;">
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

    <a id="pstart" class="btn-floating btn-large waves-effect waves-light green"><i class="mdi mdi-power-settings"></i></a>
  </div>
        <?php
        exit();
        break;

        case 'pstart':
        $pdata = $_POST;
        $sql = "UPDATE wakeup SET status='".$pdata['status']."' WHERE sip='".$pdata['sip']."'";
        if ($conn->query($sql) === TRUE) {
            echo "System start with in 10 seconds.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
          exit();
        break;

        case 'allui':
        $sql = "SELECT * FROM wakeup";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
        ?>
          <ul class="collection">
              <div class="row">
                <div class="col s4"><span class="title"><?php echo $row['username'] ?></span>
                <p><?php echo $row['pname'] ?> | <?php echo $row['sip'] ?></p></div>
                <div class="col s4"><a id="ustart" data-ip="<?php echo $row['sip'] ?>" class="waves-effect waves-light btn">Start</a></div>
                <div class="col s4"><a id="ureset" data-ip="<?php echo $row['sip'] ?>" class="waves-effect waves-light btn">Reset</a></div>
              </div>
          </ul>
          <?php
        }
    }
          $conn->close();
          exit();
          break;

        case 'add':
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
            $conn->close();
            exit();
          break;

          case 'savedata':
              $pdata = $_POST;
              $sql = "INSERT INTO wakeup (sip, pname, username, pass)
              VALUES ('".$pdata['sip']."', '".$pdata['sname']."', '".$pdata['uname']."', '".$pdata['pass']."')";
              $result = $conn->query($sql);
              if($result){
                echo "User added successfully.";
              }else {
                echo "Error: " . $result . "<br>" . $conn->error;
              }
              $conn->close();
            exit();
            break;

  case 'password':
      ?>
      <div class="row">
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
      $conn->close();
      exit();
    break;

    case 'updatepass':
          $pdata = $_POST;
          if($pdata['npass'] === $pdata["rnpass"]){
            $sql = "SELECT pass FROM wakeup WHERE username = '".$pdata['uname']."'";
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
                  echo "Password changed successfully.";
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
            }else {
              echo "Wrong password. try again";
            }
          }else {
            echo "Password not matched.";
          }
          $conn->close();
        exit();
      break;


}
//switch

}
$conn->close();
?>
<!DOCTYPE html>
 <html>
   <head>
     <title>Wakeup</title>
     <!--Import Google Icon Font-->
     <link href="http://cdn.materialdesignicons.com/2.8.94/css/materialdesignicons.min.css" rel="stylesheet">
     <!--Import materialize.css-->
     <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css"  media="screen,projection"/>
     <meta name="theme-color" content="#0288d1">
     <link rel="manifest" href="/manifest.json">
     <!--Let browser know website is optimized for mobile-->
     <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
     <style>
     .spinner-red, .spinner-red-only {
        border-color: #ffffff !important;
      }
     #loader{
       display: none;
       position: absolute;
       right: 10%;
       z-index: 999;
       top: 0%
     }
     </style>
   </head>

   <body>
     <nav class="light-blue darken-2">
       <div class="nav-wrapper">
         <a href="../wake" class="brand-logo center">Wakeup</a>
         <?php if($login) { ?>
         <a href="#" data-activates="slide-out" class="button-collapse show-on-large"><i class="mdi mdi-menu"></i></a>
         <ul id="slide-out" class="side-nav">
            <li><div class="user-view">
              <div class="background">
                <img src="http://materializecss.com/images/office.jpg">
              </div>
              <a ><img class="circle" src="https://www.shareicon.net/data/256x256/2016/09/15/829473_man_512x512.png"></a>
              <a ><span class="white-text name"><?php echo $userdata['username']; ?></span></a>
              <a ><span class="white-text email"><?php echo $userdata['sip']; ?> | <?php echo date("Y-m-d H:i:s",, strtotime('0 hours 0 minutes 0 seconds')) ?></span></a>
            </div></li>
            <li><a id="pageload" data-page="home" class="waves-effect"><i class="mdi mdi-home"></i>Home</a></li>
            <li><a id="pageload" data-page="attend" class="waves-effect"><i class="mdi mdi-blur-radial"></i>Attendance</a></li>
            <li><a id="pageload" data-page="systemboot" class="waves-effect"><i class="mdi mdi-power-settings"></i>System Start</a></li>
            <li><a id="pageload" data-page="password" class="waves-effect"><i class="mdi mdi-account-key"></i>Change password</a></li>
            <?php if($admin) { ?>
            <li><div class="divider"></div></li>
            <li><a class="subheader">Admin</a></li>
            <li><a id="pageload" data-page="add" class="waves-effect"><i class="mdi mdi-account-plus"></i>Add New</a></li>
            <li><a id="pageload" data-page="allui" class="waves-effect"><i class="mdi mdi-format-list-checkbox"></i>All User</a></li>
            <?php } ?>
            <li><div class="divider"></div></li>
            <li><a class="subheader">About</a></li>
            <li><a class="subheader">Version 2.0.0</a></li>
            <li><a href="https://amitmaurya.tk/?ref=saswkeup" class="subheader">by Amit Maurya</a></li>
        </ul> <?php } ?>
       </div>
     </nav>
     <div align="center" id="loader" style="padding: 10px;">
       <div class="preloader-wrapper small active">
        <div class="spinner-layer spinner-red-only">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div><div class="gap-patch">
            <div class="circle"></div>
          </div><div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
     </div>
     <div id="page">


     </div>


     <!--Import jQuery before materialize.js-->
     <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
     <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
     <script type="text/javascript">
     $( document ).ready(function(){
        $(".button-collapse").sideNav();
        $("#prostart").hide();
        $("#loader").show();
        <?php if($login) { ?>
        $.get("?page=home", function(data, status){
          $("#page").html(data);
          $("#loader").hide();
        });

        $('[id=pageload]').click(function() {
          $("#loader").show();
          $('.button-collapse').sideNav('hide');
      		var pname = $(this).data('page');
          pname = "?page="+pname
          $.get(pname, function(data, status){
            $("#page").html(data);
            $("#loader").hide();
          });
      	});
       function notify(msg){
         Materialize.toast(msg, 4000)
       }

       $(document.body).on('click', '#pstart' ,function(){
         $("#loader").show();
         $.post("?page=pstart",
         {
             sip: "<?php echo $userdata['sip']; ?>",
             status: "1",
         },
         function(data, status){
             notify(data);
             $("#loader").hide();
$("#prostart").show();
             var refreshId = setInterval(function() {
               $.get("?wping=<?php echo $userdata['sip']; ?>", function(data, status){
                 if(data == "0"){
                   notify("System successfully started.");
                   navigator.vibrate([500, 300, 500]);
$("#prostart").hide();
                    clearInterval(refreshId);
                 }
               });

              }, 4000);
         });
       });

       $(document.body).on('click', '#updatepass' ,function(){
         $("#loader").show();
         $.post("?page=updatepass",
         {
              uname: "<?php echo $userdata['username']; ?>",
             opass: $("#opass").val(),
             npass: $("#npass").val(),
             rnpass: $("#rnpass").val()
         },
         function(data, status){
             notify(data);
             $("#loader").hide();
             if(data == "Password changed successfully."){
               $.get("?page=home", function(data, status){
                 $("#page").html(data);
                 $("#loader").hide();
               });
             }
         });
       });

       $(document.body).on('click', '#isiam' ,function(){
         $("#loader").show();
         $.post("?page=todayim",
         {
             username: "<?php echo $userdata['username']; ?>"
         },
         function(data, status){
             notify(data);
             $("#loader").hide();
         });
       });

        <?php if($admin) { ?>

       $(document.body).on('click', '#savedata' ,function(){
         $("#loader").show();
         $.post("?page=savedata",
         {
             sip: $("#sip").val(),
             sname: $("#sname").val(),
             pass: $("#pass").val(),
             uname: $("#uname").val()
         },
         function(data, status){
             notify(data);
             $("#loader").hide();
         });
       });

       $(document.body).on('click', '[id=ustart]' ,function(){
         $("#loader").show();
         var pname = $(this).data('page');
         $.post("?page=savedata",
         {
             sip: $("#sip").val(),
             sname: $("#sname").val(),
             pass: $("#pass").val()
         },
         function(data, status){
             notify(data);
             $("#loader").hide();
         });
       });

       <?php } }else { ?>
         $.get("?page=login", function(data, status){
           $("#page").html(data);
           $("#loader").hide();
         });

      <?php } ?>

     });
     </script>


   </body>
 </html>
