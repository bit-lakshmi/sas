<?php
include 'db_config.php';
if(!isset($_GET["fname"])) {
	echo "Error Code: 403";
	exit;
}
function add_log($ltext, $conn, $pcode, $pname){
		$uid = $_SESSION['user_data']['uname'];
		$new_time = date("Y-m-d H:i:s", strtotime('+3 hours +29 minutes -23 seconds'));
		$result = $conn->query("INSERT INTO logs (uid, ltext, pcode, sname, ldatetime) VALUES ('{$uid}', '{$ltext}', '{$pcode}', '{$pname}', '{$new_time}')");

		
}

function update_client($conn){
	$uid = $_SESSION['user_data']['id'];
	$result = $conn->query("UPDATE sessions SET isupdate = '1' WHERE NOT uid = '{$uid}'");
}

switch ($_GET["fname"]) {
	case "login":
		$uid = $_POST['uid'];
		$sql = "SELECT * FROM user WHERE uname='{$uid}'";
		$result = $conn->query($sql);
		if ($result->rowCount() > 0) {
	    // output data of each row
			$isuser = false;
		    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		        if($row['password'] == $_POST['password']){
		        	$_SESSION['login']= "1";
		        	$_SESSION['user_data']= $row;
		        	$autologin = base64_encode($row['uname']."|".$row['password']);
		        	setcookie("autologin", $autologin, time() + 80000);
		        	setcookie("host", $row['host'], 0,'/');
		        	$isuser = true;
		        	echo "1";
		        	header('Location: ../');
		        }
		        else{
		        	echo "0";
		        	header('Location: ../');
		        }
		    }
		    if($isuser){
	        	$uname = $_SESSION['user_data']['id'];
	        	$result = $conn->query("DELETE FROM sessions WHERE uid = '{$uname}'");
	        	$result = $conn->query("INSERT INTO sessions (uid) VALUES ('{$uname}')");
		    }

		}
		break;

	case "logout":
		session_unset();
		session_destroy();
		setcookie("autologin", "", 0,'/');
		unset($_COOKIE['autologin']); 
		header('Location: ../dashboard');
		break;

	case "upass":
		$uid = $_SESSION['user_data']['uname'];
		$result = $conn->query("SELECT id, password FROM user WHERE uname='{$uid}'");
		$p_match = false;
		if ($result->rowCount() > 0) {
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		        if($row['password'] == $_POST['o_pass']){
		        	$p_match = true;
		        }
		        else{
		        	echo "0";
		        }
		    }


		}else{
			echo "0";
		}
		if($p_match){
			$result = $conn->query("UPDATE user SET password = '{$_POST['n_pass']}' WHERE uname = '{$uid}'");
			echo "1";
		}
		break;


	case 'deletedata':
		$sql = "DELETE FROM {$_POST['tabel']} WHERE id = {$_POST['id']}";
		$result = $conn->query($sql);
		if($result){
			update_client($conn);
			echo "1";
		}
	break;


	case "add_task":
		$pcode = $_POST['pcode'];
		$auser = $_POST['auser'];
		$sname = $_POST['sname'];
		$sframe = $_POST['sframe'];
		$scorrec = $_POST['correc'];
		$ugroup = $_SESSION['user_data']['ugroup'];
		$result = $conn->query("INSERT INTO tasks (pcode, sname, sframe, auser, correction, group_id) VALUES ('{$pcode}', '{$sname}', '{$sframe}', '{$auser}','{$scorrec}', '{$ugroup}')");
		if($result){
			update_client($conn);
			echo "1";
		}

	break;


	case 'taskedit': 
					$sname = $_POST['sname'];
					$pcode = $_POST['pcode'];
					$sgroup = $_SESSION['user_data']['ugroup'];
					$sql = "SELECT * FROM tasks WHERE sname='{$sname}' AND pcode= '{$pcode}'";
					$result = $conn->query($sql);
					$post_data =  array();
					if ($result->rowCount() > 0) {
				    // output data of each row
				    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				        $post_data[] =  $row;
				    }}
					?>
					<form id="tasks_edit" class="col s12">
						<div class="col s12 m6 l6">
	                        <div class="row">
	                        	<div class="col s12">
	                        		<div class="card">
	                        			<div class="card-content">
	                        				Shot No. <?php echo $_POST['sname'] ?> | <?php echo $pcode ?>	
	                        			</div>
	                        			
	                        		</div>
	                        		
	                        	</div>
	                            <div class="col s12">
	                                <ul class="tabs tab-demo z-depth-1" style="width: 100%;">
	                                    <li class="tab col s3"><a id="tab_btn" data-tab="info" >Info</a></li>
	                                    <li class="tab col s3"><a id="tab_btn" data-tab="correction">Correction</a></li>
	                                    <li class="tab col s3"><a id="tab_btn" data-tab="bgsb">BG / Storyboard</a></li>
	                                    <li class="tab col s3"><a id="tab_btn" data-tab="animc">Anim. Correction</a></li>
	                                </ul>
	                            </div>
	                            <div id="tab_info" class="col s12">
		                            <div class="card">
					                    <div class="card-content">
					                        <span id="add_new_task_pname" class="card-title">Task Edit</span><br>
					                        <div class="row">
					                                <div class="row">
					                                    <div class="input-field col s6">
					                                        <input id="task_sname" class="validate" value="<?php echo $post_data[0]['sname']  ?>" type="text">
					                                    </div>
					                                    <div class="input-field col s6">
					                                        <input id="task_sframe" class="validate" value="<?php echo $post_data[0]['sframe']  ?>" type="text">
					                                        <label for="add_new_task_sframe"></label>
					                                    </div>
					                                </div>
					                                <div class="row">
					                                    <div class="col s6">
					                                    	<label>Assigned to</label>
					                                        <select class="browser-default" id="task_auser" >
					                                            <option  value="">Choose your option</option>
					                                            <?php                                            
					                                            foreach ($U_DATA as $key => $u_value) {
					                                            	if ($u_value['ugroup'] == $sgroup) {
					                                            	
					                                            		if($post_data[0]['auser'] == $u_value['id']){	                                         
					                                            			printf("<option value='%s' selected='selected'>%s</option>", $u_value['id'], $u_value['dname']);
					                                            		} else {
					                                            			printf("<option value='%s'>%s</option>", $u_value['id'], $u_value['dname']);
					                                            		}
					                                            	}
					                                                    
					                                            }  

					                                            ?>
					                                        </select>                                       
					                                    </div>
					                                    <div class="col s6">
					                                    	<label>Status</label>
					                                        <select  class="browser-default" id="task_status">
					                                            <option  value="">Choose your option</option>
					                                            <?php
					                                            print_r($S_DATA);
					                                            foreach ($S_DATA as $key => $s_value) {
					                                                   if($post_data[0]['status'] == $key){
						                                                 printf("<option value='%s' selected='selected'>%s</option>", $key, $s_value[0]);
						                                        		} else {
						                                        			printf("<option value='%s'>%s</option>", $key, $s_value[0]);
						                                        		}
					                                            }  

					                                            ?>
					                                        </select>	                                        
				                                    </div>
					                               </div>
					                        </div>
					                    </div>
				                	</div>
	                            </div>
	                            <div id="tab_correction" class="col s12" style="display: none;">
	                            	<div class="card">
					                    <div class="card-content">
					                        <span class="card-title">Task Correction</span><br>
					                        <div class="row">
				                				<div class="input-field col s12">
				                                    <textarea id="task_correction" class="materialize-textarea" length="120"><?php echo $post_data[0]['correction']  ?></textarea>
				                                	<span class="character-counter" style="float: right; font-size: 12px; height: 1px;"></span>
				                                </div>
					                        </div>
					                         <div class="row">
				                                <div class="col s6">
				                                	<label>Assigned to</label>
				                                    <select class="browser-default" id="task_cdoneby" >
				                                        <option  value="">Choose your option</option>
				                                        <?php
				                                        foreach ($U_DATA as $key => $u_value) {
				                                               if($post_data[0]['cdoneby'] == $u_value['id']){
				                                        			printf("<option value='%s' selected='selected'>%s</option>", $u_value['id'], $u_value['dname']);
				                                        		} else {
				                                        			printf("<option value='%s'>%s</option>", $u_value['id'], $u_value['dname']);
				                                        		}
				                                        }  

				                                        ?>
				                                    </select>                                       
					                            </div>
				                                <div class="col s6">
				                                	<label>Status</label>
				                                    <select class="browser-default" id="task_c_status" >
				                                        <option  value="">Choose your option</option>
				                                        <?php
				                                        foreach ($S_DATA as $key => $s_value) {
				                                        		if($post_data[0]['c_status'] == $key){
				                                                 printf("<option value='%s' selected='selected'>%s</option>", $key, $s_value[0]);
				                                        		} else {
				                                        			printf("<option value='%s'>%s</option>", $key, $s_value[0]);
				                                        		}
				                                        }  

				                                        ?>
				                                    </select>                                       
					                            </div>
				                           </div>
					                    </div>
					             	</div>
	                            </div>
	                            <div id="tab_bgsb" class="col s12" style="display: none;">
	                            	<div class="card">
					                    <div class="card-content">
					                    	<div class="row">
				                                <div class="col s6">
				                                	<div class="material-placeholder"><img data-src="http://sas.com/cgi/img.py?pcode=<?php echo $pcode ?>&sno=<?php echo $sname ?>&ext={}.jpg&type=sb" class="materialboxed responsive-img initialized loadlater" src="" alt=""></div>
				                                </div>
				                                <div class="col s6">
				                                	<div class="material-placeholder"><img  data-src="http://sas.com/cgi/img.py?pcode=<?php echo $pcode ?>&sno=<?php echo $sname ?>&ext=BG_({}).png&type=bg" class="materialboxed responsive-img initialized" src="" alt=""></div>
				                                </div>
			                               	</div>
					                    </div>
					                </div>
	                            </div>
	                            <div id="tab_animc" class="col s12" style="display: none;"><p class="p-v-sm">Test 4</p></div>
	                        </div>
	                    </div>
	                    <input id="task_uid" type="hidden" name="id" value="<?php echo $post_data[0]['id'] ?>">
			            <input id="task_pcode" type="hidden" name="pcode" value="<?php echo $post_data[0]['pcode'] ?>">
			            <a id="task_btn_update" class="waves-effect waves-light btn green m-b-xs" style="float: right;">Update</a>
			            <a id="task_btn_log" data-pcode="<?php echo $pcode ?>" data-sname="<?php echo $_POST['sname'] ?>" class="waves-effect waves-light btn blue m-b-xs">History</a>
                    </form>
                    
		<?php
		break;
		case 'taskupdate':
			$result = $conn->query("UPDATE tasks SET sname = '{$_POST['sname']}', sframe = '{$_POST['sframe']}', status = '{$_POST['status']}', auser = '{$_POST['auser']}', correction = '{$_POST['correction']}', c_status = '{$_POST['c_status']}', cdoneby = '{$_POST['cdoneby']}' WHERE id = '{$_POST['id']}'");
			if($result){
				$log_text = "Update a Shot No. " . $_POST['sname'] . " from Story code : " . $_POST['pcode'] . "\nStatus : " . $S_DATA[$_POST['status']][0] . "\nAssigned to : ";
				foreach ($U_DATA as $key => $u_value) {
					if($u_value['id'] ==  $_POST['auser']) {
                        $log_text .= $u_value['dname'];
					}

				}
				$log_text .= "\nCorrection : " . $_POST['correction'] . "\nCorrection Status : ". $S_DATA[$_POST['c_status']][0] . "\nDone by : ";
				foreach ($U_DATA as $key => $c_value) {
					if($c_value['id'] == $_POST['cdoneby']) {
						 $log_text .= $c_value['dname'];
					}
				}
				add_log($log_text, $conn, $_POST['pcode'],$_POST['sname']);
				update_client($conn);
				echo "1";
			}
		break;

		case 'updatebshot': 
			$pcode = $_POST['pcode'];
			$bshots = 0;
	        $result = $conn->query("SELECT bshots FROM projects WHERE pcode='{$pcode}'");
	            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	                $bshots = intval($row['bshots']);
	             
	        }

		?>
				<div class="card">
	                    <div class="card-content">
	                        <span id="add_new_task_pname" class="card-title">bShots</span><br>
	                        <div class="row">
	                                <div class="row">
	                                    <div class="input-field col s6">
	                                        <input id="ibshots" class="validate" value="<?php echo $bshots  ?>" type="text">
	                                        <label for="sname" class="active">No. of Shots</label>
	                                    </div>
	                                    <div class="input-field col s6">
	                                        <a id="ibshots_btn_update" class="waves-effect waves-light btn green m-b-xs">Update</a>
	                                    </div>
	                                </div>
	                        </div>
	                    </div>
                </div>
			
			

			<?php
			break;

		case 'ubshot':
			$result = $conn->query("UPDATE tasks SET sname = '{$_POST['sname']}', sframe = '{$_POST['sframe']}', status = '{$_POST['status']}', auser = '{$_POST['auser']}', correction = '{$_POST['correction']}', c_status = '{$_POST['c_status']}', cdoneby = '{$_POST['cdoneby']}' WHERE id = '{$_POST['id']}'");
			if($result){
				$log_text = "Shot no. " . $_POST['sname']. " update.";
				add_log($log_text, $conn);
				update_client($conn);
				echo "1";
			}
			break;

		case 'isupdate':
			$uid = $_SESSION['user_data']['id'];
			$result = $conn->query("SELECT * FROM sessions WHERE uid ='{$uid}'");
			$isclientupdate = false;
			if ($result->rowCount() > 0) {
				while($row = $result->fetch(PDO::FETCH_ASSOC)) {				
					if (isdev()) {
						echo "<pre>";
						print_r($row);
					}					
			        if($row['isupdate'] == '1'){
			        	$isclientupdate = true;			        	
			        }
			    }
			}
			if ($isclientupdate) {
				$result = array("code"=> "1", "CURTASK" => $_SESSION['CURTASK']);
			    echo json_encode($result);
				$conn->query("UPDATE sessions SET isupdate = '0' WHERE uid = '{$uid}'");
			}else{
				$result = array("code"=> "0");
			    echo json_encode($result);
			}


			break;

		case 'proupdate':
			$sql = "";
			switch ($_POST['ugroup']) {
				case '1':
					$sql = sprintf("UPDATE projects SET %s = '%s' WHERE pcode = '%s'", $_POST['colname'], $_POST['colvalue'], $_POST['pcode']);
					break;
				case '2':
					$sql = sprintf("UPDATE mrig SET %s = '%s' WHERE pcode = '%s' AND aname = '%s'", $_POST['colname'], $_POST['colvalue'], $_POST['pcode'], $_POST['aname']);
					break;
			}
			$result = $conn->query($sql);
			if($result){
				update_client($conn);
				echo "1";
			}
		break;


		case 'corupdate':
			$sql = sprintf("UPDATE tasks SET status = '2', c_status = '6', cdoneby ='' WHERE pcode = '%s' AND sname = '%s'", $_POST['pcode'], $_POST['sname']);
			$result = $conn->query($sql);
			if($result){
				$log_text = "Update a Shot No. " . $_POST['sname'] . " from Story code : " . $_POST['pcode'] . "\n Correction Done";
				add_log($log_text, $conn, $_POST['pcode'],$_POST['sname']);
				update_client($conn);
				echo "1";
			}
		break;




		case 'addprojectui':
							?>
					<form id="tasks_edit" class="col s12">
					<div class="card">
	                    <div class="card-content">
	                        <span id="add_new_task_pname" class="card-title">Add new Story</span><br>
	                        <div class="row">
	                                <div class="row">
	                                	<div class="col s6">
		                                	<label>Story type</label>
		                                    <select class="browser-default" id="story_type">
		                                        <option value="">Choose your option</option>
		                                        <option value="0">Punchtantra stories</option>
		                                        <option value="1">Occasional Films</option>
		                                    </select>
		                                </div>
	                                    <div class="input-field col s6">
	                                        <input id="story_name" class="validate" type="text">
	                                        <label for="story_name" class="active">Story Name</label>
	                                    </div>
	                                </div>
	                        </div>
	                         <div class="row">
	                                <div class="row">
	                                    <div class="input-field col s6">
	                                        <input id="story_no" class="validate" type="text">
	                                        <label for="story_no" class="active">Story No.</label>
	                                    </div>
	                                    <div class="input-field col s6">
	                                        <input id="story_code" class="validate" type="text">
	                                        <label for="story_code">Story Code</label>
	                                    </div>
	                                </div>
	                        </div>
	                    </div>
                </div>
	             <a id="story_add" class="waves-effect waves-light btn green m-b-xs">Add Story</a>
                </form>
		
		<?php
		break;

		case 'addproject':
			$pname = $_POST['pname'];
			$pcode = $_POST['pcode'];
			$pno = $_POST['pno'];
			$ptype = $_POST['ptype'];
			$fpath = $pno . " " . $pname;
			$result = $conn->query("INSERT INTO projects (pstype, pname, pcode, pdisk_name, orderby) VALUES ('{$ptype}', '{$pname}', '{$pcode}', '{$fpath}', '{$pno}')");
			if($result){
				update_client($conn);
				echo "1";
			}
		break;


		case 'getlog':
			$sname = $_GET['sname'];
			$pcode = $_GET['pcode'];
			$result = $conn->query("SELECT uid, ltext, ldatetime FROM logs WHERE pcode ='{$pcode}' AND sname = '{$sname}'");
			echo '<div class="card white darken-1">';                           
            if ($result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='card card-content'>";
                    printf('<span class="card-title">%s | %s</span>', $row['uid'], date('Y-m-d | h:i A', strtotime($row['ldatetime'])));
                    echo "<p>" . nl2br($row['ltext']) . "<p>";
                    echo "</div>";
                }
            }
            echo '</div>';
			break;

		case 'dashlogsloadmore':
			$no = $_GET['from'];
			$result = $conn->query("SELECT uid, ltext, ldatetime FROM logs ORDER BY id DESC LIMIT $no,10");                           
            if ($result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='card-content'>";
                    printf('<span class="card-title">%s | %s</span>', $row['uid'], date('Y-m-d | h:i A', strtotime($row['ldatetime'])));
                    echo "<p>" . nl2br($row['ltext']) . "<p>";
                    echo "</div>";
                }
            }
		break;

}
