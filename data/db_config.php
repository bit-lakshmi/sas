<?php
session_start();
$servername = "192.168.23.92";
$username = "admin";
$password = "2648";
$dbname = "sas";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    global $conn;
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

define("SITE_NAME","SAS");

if(isset($_COOKIE['autologin'])) {
    $autologin = base64_decode($_COOKIE['autologin']);
    $autologin = explode("|",$autologin);
    $sql = "SELECT * FROM user WHERE uname='{$autologin[0]}'";
    $result = $conn->query($sql);
    if ($result->rowCount() > 0) {
    // output data of each row
        $isuser = false;
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if($row['password'] == $autologin[1]){
                $_SESSION['login']= "1";
                $_SESSION['user_data']= $row;
                $autologin = base64_encode($row['uname']."|".$row['password']);
                setcookie("autologin", $autologin, time() + 80000, '/');
                setcookie("host", $row['host'], 0,'/');
                $isuser = true;
            }
        }
        if($isuser){
            $uname = $_SESSION['user_data']['id'];
            //$result = $conn->query("DELETE FROM sessions WHERE uid = '{$uname}'");
            //$result = $conn->query("INSERT INTO sessions (uid) VALUES ('{$uname}')");
        }

    }

}

$result = $conn->query("SELECT * FROM options WHERE name='status'");
$S_DATA = array();
if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $S_DATA[] = $row['value'];
        }
}
$S_DATA =  json_decode($S_DATA[0], true);

$result = $conn->query("SELECT id, dname, color, ugroup FROM user");
$U_DATA = array();
if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $U_DATA[] = $row;
        }
}

if (isset($_GET["dev"])) {
    if ($_GET["dev"] == '1') {
        $_SESSION['dev']= "1";
        setcookie("dev", 1, 0,'/');
    }else{
        $_SESSION['dev']= "0";
        setcookie("dev", 0, 0,'/');
    }

}

function isdev(){
    if (isset($_SESSION['dev'])) {
        if ($_SESSION['dev']) {
            return true;
        }else{
            return false;
        }
    }
}

function getprojectdata($type, $pcode){
    $sql = "";
    switch ($type) {
        case 'pname':
            $sql = "SELECT pname FROM projects WHERE pcode='{$pcode}'";
            break;

        case 'fpath':
            $sql = "SELECT pdisk_name FROM projects WHERE pcode='{$pcode}'";
            break;

    }
    global $conn;
    $result = $conn->query($sql);
    $P_DATA = array();
    if ($result->rowCount() > 0) {
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $P_DATA[] = $row;
            }
    }
    switch ($type) {
        case 'pname':
            return $P_DATA[0]['pname'];
            break;

        case 'fpath':
            return $P_DATA[0]['pdisk_name'];
            break;
    }

}

function add_log($ltext, $conn, $pcode, $pname){
    if (!isdev()) {
  		$uid = $_SESSION['user_data']['uname'];
  		$new_time = date("Y-m-d H:i:s", strtotime('+3 hours +29 minutes -23 seconds'));
  		$result = $conn->query("INSERT INTO logs (uid, ltext, pcode, sname, ldatetime) VALUES ('{$uid}', '{$ltext}', '{$pcode}', '{$pname}', '{$new_time}')");
    }
    if (isdev()) {
      return "<script>console.log( 'Add Log: " . $ltext . "\n" .$pcode. "\n" .$pname. "' );</script>";
    }

}
if (!isdev()) {
  error_reporting(0);
}
//$S_DATA =  json_decode($S_DATA[0], true);
