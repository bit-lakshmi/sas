<?php
include 'db_config.php';
if(!isset($_GET["fname"])) {
	echo "Error Code: 403";
	exit;
}
function update_client($conn){
	$uid = $_SESSION['user_data']['id'];
	$result = $conn->query("UPDATE sessions SET isupdate = '1' WHERE NOT uid = '{$uid}'");
}

switch ($_GET["fname"]) {
	case 'add_mrig':
		$aname = $_POST['aname'];
		$oid = $_POST['oid'];
		$type = $_POST['type'];
		$pcode = $_POST['pcode'];
		$result = $conn->query("INSERT INTO mrig (pcode, type, aname, aoid) VALUES ('{$pcode}', '{$type}', '{$aname}', '{$oid}')");
		if($result){
			update_client($conn);
			echo "1";
		}
		break;

	case 'mrigedit':
					$sname = $_POST['sname'];
					$pcode = $_POST['pcode'];
					$sgroup = $_SESSION['user_data']['ugroup'];
					$sql = "SELECT * FROM mrig WHERE aname='{$sname}' AND pcode= '{$pcode}'";
					$result = $conn->query($sql);
					$post_data =  array();
					if ($result->rowCount() > 0) {
				    // output data of each row
				    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				        $post_data[] =  $row;
				    }}
					?>
					<form id="tasks_edit" class="col s12">
					<div class="card">
	                    <div class="card-content">
	                        <span id="add_new_task_pname" class="card-title">Edit Ch/Assest - <?php echo $sname ?></span><br>
	                        <div class="row">
	                                <div class="row">
	                                    <div class="input-field col s6">
	                                        <input id="mrig_sname" class="validate" value="<?php echo $post_data[0]['aname']  ?>" type="text">
	                                    </div>
	                                    <div class="input-field col s6">
	                                        <input id="mrig_oid" class="validate" value="<?php echo $post_data[0]['aoid']  ?>" type="text">
	                                    </div>
	                                </div>
	                                <div class="row">
	                                    <div class="col s6">
	                                    	<a id="mrig_update" class="waves-effect waves-light btn green m-b-xs">Update</a>
                                    	</div>
                                    	<div class="col s6">
	                                    	<a id="btn_delete" data-pcode="<?php echo $post_data[0]['pcode'] ?>" data-tabel="mrig" data-id="<?php echo $post_data[0]['id'] ?>" class="waves-effect waves-light btn red m-b-xs">Delete</a>
                                    	</div>
	                               </div>
	                        </div>
	                    </div>
                </div>
	             <input id="mrig_uid" type="hidden" name="id" value="<?php echo $post_data[0]['id'] ?>">
	             <input id="mrig_pcode" type="hidden" name="pcode" value="<?php echo $post_data[0]['pcode'] ?>">
                </form>

		<?php

		break;

		case 'mrigupdate':
			$result = $conn->query("UPDATE mrig SET aname = '{$_POST['sname']}', aoid = '{$_POST['soid']}' WHERE id = '{$_POST['id']}'");
			if($result){
				if (isdev()) {
					$log_text .= "\naName : " . $_POST['sname'] . "\naOid : ". $_POST['soid'];
					add_log($log_text, $conn, $_POST['pcode'],$_POST['sname']);
				}
				update_client($conn);
				echo "1";
			}
		break;

		case 'mrigupdateinfo':
			$result = $conn->query("UPDATE mrig SET info = '{$_POST['info']}' WHERE id = '{$_POST['id']}'");
			if($result){
				if (isdev()) {
					$log_text .= "\naInfo : " . $_POST['info'] . "\nId : ". $_POST['id'];
					add_log($log_text, $conn, $_POST['pcode'],$_POST['sname']);
				}
				//add_log($log_text, $conn, $_POST['pcode'],$_POST['sname']);
				update_client($conn);
				echo "1";
			}
		break;

}

?>
