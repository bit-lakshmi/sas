$servername = "solarts.db.9563506.hostedresource.com";
$username = "solarts";
$password = "LKe@gU!1H";
$dbname = "solarts";


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
<script type="text/javascript">
function pageload(spname){
  $("#loader").show();
  $('.button-collapse').sideNav('hide');
  pname = apiUrl + "?page=" + spname;
  $.get(pname, function(data, status){
    $("#page").html(data);
    $("#loader").hide();
  });
}

$('[id=pageload]').click(function() {
  $("#loader").show();
  $('.button-collapse').sideNav('hide');
  var pname = $(this).data('page');
  pname = apiUrl + "?page=" + pname;
  $.get(pname, function(data, status){
    $("#page").html(data);
    $("#loader").hide();
  });
});

 function notify(msg){
   Materialize.toast(msg, 4000)
 }

 var lourl = apiUrl + "?api=islogin";
 $.get(console.log();url, function(data, status){
   console.log(data);
   var loginstatus = JSON.parse(data);
   if (loginstatus.status) {
      pageload('home');
   }else {
      pageload('login');
   }
 });

</script>
