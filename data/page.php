<?php
include 'db_config.php';
if(!isset($_GET["pname"])) {
	echo "Error Code: 403";
	exit;
}
switch ($_GET["pname"]) {
	case 'dashboard':
                        if ($_SESSION['user_data']['id'] == '2' || $_SESSION['user_data']['id'] == '6' || $_SESSION['user_data']['id'] == '7') {
                        $result = $conn->query("SELECT * FROM `tasks` WHERE auser = '{$_SESSION['user_data']['id']}' OR cdoneby = '{$_SESSION['user_data']['id']}'");
                        if ($result->rowCount() > 0) {

                    ?>
                    <div class="row no-m-t no-m-b">
                        <div class="col s12 m12 l12">
                            <div class="card invoices-card">
                                <div class="card-content">
                                    <div class="card-options">
                                        <input type="text" class="expand-search" placeholder="Search" autocomplete="off">
                                    </div>
                                    <span class="card-title">Corrections</span>
                                <table class="responsive-table bordered">
                                    <thead>
                                        <tr>
                                            <th>Pcode</th>
                                            <th data-field="id">S.No</th>
                                            <th data-field="id">Task</th>
                                            <th data-field="number">Status</th>
                                            <th data-field="company">Artist</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                        printf('<tr class="current-task" data-psname="%s" >', $row['sname']);
                                                        echo "<td>{$row['pcode']}</td>";
                                                        printf("<td id='edit_task' data-sid=".$row['sname']." data-pcode=".$row['pcode']."  style='cursor: pointer;'>%s</td>", $row['sname']);
                                                        printf("<td id='edit_task' data-sid=".$row['sname']." data-pcode=".$row['pcode']."  style='cursor: pointer;'>%s</td>", $row['correction']);
                                                        if ($row['status'] == '5') {
                                                            printf('<td id="corupdate" data-pcode="%s" data-sname="%s"><a class="waves-effect waves-light btn %s">%s</a></td>',$row['pcode'],$row['sname'], $S_DATA[$row['c_status']][1], $S_DATA[$row['c_status']][0]);

                                                            foreach ($U_DATA as $key => $u_value) {
                                                                if($u_value['id'] ==  $row['cdoneby']) {
                                                                    echo "<td>" . $u_value['dname'] . "</td>";
                                                                }

                                                            }
                                                        } else{

                                                            foreach ($U_DATA as $key => $u_value) {
                                                                if ($row['cdoneby'] !== "") {
                                                                    if($u_value['id'] ==  $row['cdoneby']) {
                                                                        printf('<td id="corupdate" data-pcode="%s" data-sname="%s"><a class="waves-effect waves-light btn %s">%s</a></td>',$row['pcode'],$row['sname'], "for Review", $S_DATA[$row['c_status']][0]);
                                                                        echo "<td>" . $u_value['dname'] . "</td>";
                                                                    }

                                                                }else {
                                                                   if($u_value['id'] ==  $row['auser']) {
                                                                    printf("<td id='edit_task' data-sid=".$row['sname']." data-pcode=".$row['pcode']."  style='cursor: pointer;'><div class='chip ochip %s' style='color: white;'>%s</div></td>",$S_DATA[$row['status']][1], $S_DATA[$row['status']][0]);
                                                                        echo "<td>" . $u_value['dname'] . "</td>";
                                                                    }
                                                                }


                                                            }
                                                        }
                                                        echo "</tr>";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }}?>
                    <div class="col s9">
                        <div class="card invoices-card">
                                <div class="card-content">
                                    <span class="card-title">Todo List</span>
                                <table class="responsive-table bordered">
                                    <thead>
                                        <tr><?php
                                            switch ($_SESSION['user_data']['ugroup']) {
                                                case '1':
                                                        ?>
                                                        <th>Pcode</th>
                                                        <th data-field="id">S.No</th>
                                                        <th data-field="id">Task</th>
                                                        <th data-field="number">Status</th>
                                                        <th data-field="company">Artist</th>
                                                        <?php
                                                break;

                                                case '2':
                                                        ?>
                                                        <th>Pcode</th>
                                                        <th data-field="id">Ch/Assest</th>
                                                        <th>Modeling</th>
                                                        <th>Texturing</th>
                                                        <th>Rigging</th>
                                                        <th>OID</th>
                                                        <?php
                                                    break;


                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        switch ($_SESSION['user_data']['ugroup']) {
                                            case '1':
                                                $result = $conn->query("SELECT * FROM `tasks` WHERE status IN (2,3,4) OR c_status IN (2,3,4) ORDER BY pcode");

                                                if ($result->rowCount() > 0) {
                                                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                        if ($row['auser'] == $_SESSION['user_data']['id'] || $row['cdoneby'] == $_SESSION['user_data']['id']) {
                                                            printf('<tr class="current-task" data-psname="%s" >', $row['sname']);
                                                            echo "<td>{$row['pcode']}</td>";
                                                            printf("<td id='fpath' data-host='%s' data-pdiskn='%s' data-psname='%s' style='cursor: pointer;'>%s</td>", $_SESSION['user_data']['host'],getprojectdata('fpath', $row['pcode']),$row['sname'], $row['sname']);
                                                            printf('<td>%s</td>', $row['correction']);
                                                            if ($row['status'] == '5') {
                                                                printf("<td id='edit_task' data-sid=".$row['sname']." data-pcode=".$row['pcode']."  style='cursor: pointer;'><div class='chip ochip %s' style='color: white;'>%s</div></td>",$S_DATA[$row['c_status']][1], $S_DATA[$row['c_status']][0]);
                                                                foreach ($U_DATA as $key => $u_value) {
                                                                    if($u_value['id'] ==  $row['cdoneby']) {
                                                                        echo "<td>" . $u_value['dname'] . "</td>";
                                                                    }

                                                                }
                                                            }else{

                                                                foreach ($U_DATA as $key => $u_value) {
                                                                    if ($row['cdoneby'] !== "") {
                                                                        if($u_value['id'] ==  $row['cdoneby']) {
                                                                            printf("<td id='edit_task' data-sid=".$row['sname']." data-pcode=".$row['pcode']."  style='cursor: pointer;'><div class='chip ochip %s' style='color: white;'>%s</div></td>",$S_DATA[$row['c_status']][1], $S_DATA[$row['c_status']][0]);
                                                                            echo "<td>" . $u_value['dname'] . "</td>";
                                                                        }

                                                                    }else {
                                                                       if($u_value['id'] ==  $row['auser']) {
                                                                        printf("<td id='edit_task' data-sid=".$row['sname']." data-pcode=".$row['pcode']."  style='cursor: pointer;'><div class='chip ochip %s' style='color: white;'>%s</div></td>",$S_DATA[$row['status']][1], $S_DATA[$row['status']][0]);
                                                                            echo "<td>" . $u_value['dname'] . "</td>";
                                                                        }
                                                                    }


                                                                }
                                                            }
                                                            echo "</tr>";

                                                        }
                                                    }
                                                }
                                            break;

                                            case '2':
																								if (isdev()) {
																									$result = $conn->query("SELECT *  FROM `mrig` WHERE mode = '2' OR texturing = '2' OR rig = '2'");
																								}else {
																									$result = $conn->query("SELECT *  FROM `mrig` WHERE mode = '2' OR texturing = '2' OR rig = '2'");
																								}

                                                if ($result->rowCount() > 0) {
                                                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
																												//print_r($row);
                                                        printf('<tr class="current-task" data-psname="%s" >', $row['aname']);
                                                        echo "<td>{$row['pcode']}</td>";
                                                        printf("<td id='fpath' data-host='%s' data-pdiskn='%s' data-psname='%s' style='cursor: pointer;'>%s</td>", $_SESSION['user_data']['host'],getprojectdata('fpath', $row['pcode']),$row['aname'], $row['aname']);

                                                        $sql_col = "mode";
                                                        if($row[$sql_col] == 1){
                                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="0" data-aname="%s"><a class="waves-effect waves-light btn green">Done</a></td>',$row['pcode'],$sql_col,$row['aname']);
                                                        }else if ($row[$sql_col] == 2){
                                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="1" data-aname="%s"><a class="waves-effect waves-light btn blue">WIP</a></td>',$row['pcode'],$sql_col,$row['aname']);
                                                        } else {
                                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="2" data-aname="%s"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col,$row['aname']);
                                                        }

                                                        $sql_col = "texturing";
                                                        if($row[$sql_col] == 1){
                                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="0" data-aname="%s"><a class="waves-effect waves-light btn green">Done</a></td>',$row['pcode'],$sql_col,$row['aname']);
                                                        }else if ($row[$sql_col] == 2){
                                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="1" data-aname="%s"><a class="waves-effect waves-light btn blue">WIP</a></td>',$row['pcode'],$sql_col,$row['aname']);
                                                        } else {
                                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="2" data-aname="%s"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col,$row['aname']);
                                                        }

                                                        $sql_col = "rig";
                                                        if($row[$sql_col] == 1){
                                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="0" data-aname="%s"><a class="waves-effect waves-light btn green">Done</a></td>',$row['pcode'],$sql_col,$row['aname']);
                                                        }else if ($row[$sql_col] == 2){
                                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="1" data-aname="%s"><a class="waves-effect waves-light btn blue">WIP</a></td>',$row['pcode'],$sql_col,$row['aname']);
                                                        } else {
                                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="2" data-aname="%s"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col,$row['aname']);
                                                        }
                                                        echo "<td>{$row['aoid']}</td>";
                                                        echo "</tr>";
                                                    }
                                                }

                                            break;

                                        }
                                    ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                    </div>
                    <div  class="col s3 ScrollStyle">
                        <div id="dashlogs" class="card white darken-1">
                            <?php
                                $result = $conn->query("SELECT * FROM logs ORDER BY id DESC LIMIT 0,10");
                                if ($result->rowCount() > 0) {
                                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<div id='showhistory' data-sname=".$row['sname']." data-pcode=".$row['pcode']."  style='cursor: pointer;' class='card-content'>";
                                        printf('<span class="card-title">%s | %s</span>', $row['uid'], date('Y-m-d | h:i A', strtotime($row['ldatetime'])));
                                        echo "<p>" . nl2br($row['ltext']) . "<p>";
                                        echo "</div>";
                                    }
                                }
                            ?>
                        </div>
                         <input type="hidden" id="logloadmoreby" value="10">
                         <div align="center" style="padding: 20px;"><a id="dashlogsloadmore" class="waves-effect waves-light btn blue">Load more</a></div>
                    </div>

		<?php
		break;
        case 'sprojects': ?>
            <div class="col s12">
                        <div class="card invoices-card">
                                <div class="card-content">
                                    <span class="card-title"><?php echo ($_GET['spname'] ? $_GET['spname'] : "PUNCHTANTRA STORIES"); ?> - Projects List | <a href="#add_project" class="modal-trigger card-refresh"><i id="add_project" class="modal-trigger material-icons">playlist_add</i></a> | Fillter | 	<?php
																				$result = $conn->query("SELECT value FROM options WHERE name ='projects'");
																				if ($result->rowCount() > 0) {
																					while($row = $result->fetch(PDO::FETCH_ASSOC)) {
																							$projectname = explode(';', $row['value']);
																							foreach ($projectname as $key => $value) {
																								echo sprintf('<a href="#add_project" id="sprojectfilter" data-spname="%s" data-name="%s" class="modal-trigger card-refresh">%s</a> &#09;',$value, $key, $value);
																							}
																					 }
																				}
																			 ?></span>
                                <table class="responsive-table bordered">
                                    <thead>
                                        <tr>
                                            <th>Project Code</th>
                                            <th id="sproject_odby" data-name="orderby" style="cursor: pointer;">No.</th>
                                            <th id="sproject_odby" data-name="pubno" style="cursor: pointer;">Pub. No.</th>
                                            <th>Project Name</th>
                                            <th>SB</th>
                                            <th>BG</th>
                                            <th>M/T/R</th>
                                            <th>Anim.</th>
                                            <th>Sound</th>
                                            <th>YTB Data</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Project Code</th>
                                            <th>No.</th>
                                            <th>Pub. No.</th>
                                            <th>Project Name</th>
                                            <th>SB</th>
                                            <th>BG</th>
                                            <th>M/T/R</th>
                                            <th>Anim.</th>
                                            <th>Sound</th>
                                            <th>YTB Data</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                        $result = $conn->query("SELECT * FROM projects WHERE NOT mtr = 1 OR NOT sb = 1 OR NOT bg = 1 OR NOT anim = 1 OR NOT sound = 1 OR NOT info = 1 ORDER BY orderby ASC ");
                                        if(isset($_GET['odby'])){
                                            $result = $conn->query("SELECT * FROM projects ORDER BY {$_GET['odby']} DESC");
                                        }
																				if(isset($_COOKIE['sprojectodby'])){
                                            $result = $conn->query("SELECT * FROM projects ORDER BY {$_COOKIE['sprojectodby']} DESC");
                                        }
                                        if ($result->rowCount() > 0) {
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
																						$fillter = 0;
																						if (isset($_GET['fillter'])) {
																							$fillter = $_GET['fillter'];
																						}

																						if (isset($_COOKIE['sprojectfilter'])) {
																							 $fillter = $_COOKIE['sprojectfilter'];
																						}
																						if ($row['pstype'] != $fillter) {
																							continue;
																						}
                                            echo "<tr>";

                                            printf('<td>%s</td>', $row['pcode']);
                                            printf('<td>%s</td>', $row['orderby']);
                                            printf('<td>%s</td>', $row['pubno']);
                                            printf('<td>%s</td>', $row['pname']);

                                            $sql_col = "sb";
                                            if($row[$sql_col] == 3){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="0"><a class="waves-effect waves-light btn green">Done</a></td>', $row['pcode'],$sql_col);
                                            }else if ($row[$sql_col] == 0){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="1"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col);
																						}else if ($row[$sql_col] == 1){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="15"><a class="waves-effect waves-light btn blue">WIP</a></td>', $row['pcode'],$sql_col);
                                            }else if ($row[$sql_col] == 15){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="2"><a class="waves-effect waves-light btn lime">Ready</a></td>', $row['pcode'],$sql_col);
                                            }else if  ($row[$sql_col] == 2){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="3"><a class="waves-effect waves-light btn amber">Check</a></td>', $row['pcode'],$sql_col);
                                            }


                                            $sql_col = "bg";
                                            if($row[$sql_col] == 1){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="2"><a class="waves-effect waves-light btn green">Done</a></td>', $row['pcode'],$sql_col);
                                            }else if ($row[$sql_col] == 2){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="0"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col);
                                            } else {
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="1"><a class="waves-effect waves-light btn blue">WIP</a></td>', $row['pcode'],$sql_col);
                                            }

                                            $sql_col = "mtr";
                                            if($row[$sql_col] == 1){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="2"><a class="waves-effect waves-light btn green">Done</a></td>', $row['pcode'],$sql_col);
                                            }else if ($row[$sql_col] == 2){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="0"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col);
                                            } else {
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="1"><a class="waves-effect waves-light btn blue">WIP</a></td>', $row['pcode'],$sql_col);
                                            }

                                            $sql_col = "anim";
                                            if($row[$sql_col] == 1){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="2"><a class="waves-effect waves-light btn green">Done</a></td>', $row['pcode'],$sql_col);
                                            }else if ($row[$sql_col] == 2){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="0"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col);
                                            } else {
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="1"><a class="waves-effect waves-light btn blue">WIP</a></td>', $row['pcode'],$sql_col);
                                            }

                                            $sql_col = "sound";
                                            if($row[$sql_col] == 1){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="2"><a class="waves-effect waves-light btn green">Done</a></td>', $row['pcode'],$sql_col);
                                            }else if ($row[$sql_col] == 2){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="0"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col);
                                            }else if ($row[$sql_col] == 3){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="1"><a class="waves-effect waves-light btn blue">M/SFX</a></td>', $row['pcode'],$sql_col);
                                            } else {
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="3"><a class="waves-effect waves-light btn blue">WIP</a></td>', $row['pcode'],$sql_col);
                                            }
                                            $sql_col = "info";
                                            if($row[$sql_col]){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="0"><a class="waves-effect waves-light btn green">Done</a></td>', $row['pcode'],$sql_col);
                                            }else{
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="1"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col);
                                            }
                                            $sql_col = "status";
                                            if($row[$sql_col] == 1){
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="2"><a class="waves-effect waves-light btn blue">WIP</a></td>', $row['pcode'],$sql_col);
                                            } elseif ($row[$sql_col] == 2) {
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="3"><a class="waves-effect waves-light btn grey">None</a></td>', $row['pcode'],$sql_col);
                                            }else{
                                                printf('<td id="proupdate" data-ugroup="1" data-pcode="%s" data-colname="%s" data-colvalue="1"><a class="waves-effect waves-light btn green">Done</a></td>', $row['pcode'],$sql_col);
                                            }
                                            echo "</tr>";

                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                    </div>

            <?php
            break;
}
