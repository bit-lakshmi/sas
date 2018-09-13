<?php
include 'db_config.php';
$PCODE = $_GET['pcode'];
$_SESSION['CURTASK']= $PCODE;
$result = $conn->query("SELECT * FROM projects WHERE pcode='{$PCODE}'");
$P_DATA = array();
if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $P_DATA[] = $row;
        }
}

$result = $conn->query("SELECT * FROM tasks WHERE pcode='{$PCODE}' ORDER BY order_id ASC");
$T_DATA = array();
if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $T_DATA[] = $row;
        }
}


?>
<script type="text/javascript">
    projectData= {
                    pCode: "<?php echo $P_DATA[0]['pcode'] ?>",
                    pName: "<?php echo $P_DATA[0]['pname'] ?>",
                    pDiskName: "<?php echo $P_DATA[0]['pdisk_name'] ?>",
                    mtype: "task",
                    sname: ""

    };
</script>
<div class="col s12 m12 l12">
	<div class="card invoices-card">
                                <div class="card-content">
                                <div class="card-options">
                                <?php
                                    $result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '3' GROUP BY status");
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                               printf('<div id="table_search" data-search="working" class="chip light-blue accent-3 tooltipped" style="color: white;cursor: pointer;">%s</div>', $row['COUNT(status)']);
                                    }
                                	$result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '4' GROUP BY status");
										while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										       printf('<div id="table_search" data-search="correction" class="chip red tooltipped" style="color: white;cursor: pointer;">%s</div>', $row['COUNT(status)']);
									}
                                    $result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '2' GROUP BY status");
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                               printf('<div id="table_search" data-search="pending" class="chip blue lighten-4" style="color: white;cursor: pointer;">%s</div>', $row['COUNT(status)']);
                                    }
									$result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '6' GROUP BY status");
										while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										       printf('<div id="table_search" data-search="done" class="chip green accent-4" style="color: white;cursor: pointer;">%s</div>', $row['COUNT(status)']);
									}
									$result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '7' GROUP BY status");
										while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										       printf('<div id="table_search" data-search="rendering" class="chip deep-purple darken-3" style="color: white;cursor: pointer;">%s</div>', $row['COUNT(status)']);
									}
                                    $result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '8' GROUP BY status");
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                               printf('<div id="table_search" data-search="r-done" class="chip light-blue darken-4" style="color: white;cursor: pointer;">%s</div>', $row['COUNT(status)']);
                                    }
									$result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '5' GROUP BY status");
										while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										       printf('<div id="table_search" data-search="review" class="chip amber accent-4" style="color: white;cursor: pointer;">%s</div>', $row['COUNT(status)']);
									}


                                    //shots in animation
                                    $bshots = 0;
                                    $result = $conn->query("SELECT bshots FROM projects WHERE pcode='{$PCODE}'");
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            $bshots = intval($row['bshots']);

                                    }

                                    $subshots = 0;
                                    $result = $conn->query("SELECT subshots FROM projects WHERE pcode='{$PCODE}'");
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            $subshots = intval($row['subshots']);

                                    }


                                    $ani_done = 0;
                                    $result = $conn->query("SELECT COUNT(*) FROM `tasks` WHERE pcode = '{$PCODE}' AND NOT status = 0 GROUP BY status ");
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $ani_done = $ani_done + intval($row['COUNT(*)']);
                                    }
                                    printf('<div class="chip blue-grey lighten-2" style="color: white;cursor: default;">%s</div>', abs($ani_done-$bshots));

                                    $tshots = 0;
                                    $result = $conn->query("SELECT total_shots FROM projects WHERE pcode='{$PCODE}'");
                                        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            $tshots = intval($row['total_shots']);

                                    }
                                    $tshots = $tshots - $subshots;
                                     printf('<div id="bshotsupdate" data-pcode="%s" class="chip  grey" style="color: white;cursor: pointer;">%s</div>',$PCODE, (abs(($tshots - $ani_done) + $bshots)));


                                    ?>
                                		<a href="#task_reorder" class="card-refresh"><i id="t_task_reorder" class="material-icons">reorder</i></a>
                                        <a href="#add_task" class="modal-trigger card-refresh"><i id="t_add_task" data-pname="<?php echo $P_DATA[0]['pname'] ?>" data-pcode="<?php echo $P_DATA[0]['pcode'] ?>" class="modal-trigger material-icons">playlist_add</i></a>

                                </div>
                                    <span class="card-title"><?php echo $P_DATA[0]['pname'] ?></span>
                                <table id="taskstable" class="display responsive-table datatable-example">
                                    <thead>
                                        <tr>
                                            <th>Shots</th>
                                            <th>Frames</th>
                                            <th>Assigned to</th>
                                            <th>Status</th>
                                            <th>Correction</th>
                                            <th>Status</th>
                                            <th>Done by</th>
                                        </tr>
                                    </thead>
                                     <tfoot>
                                        <tr>
                                            <th>Shots</th>
                                            <th>Frames</th>
                                            <th>Assigned to</th>
                                            <th>Status</th>
                                            <th>Correction</th>
                                            <th>Status</th>
                                            <th>Done by</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    foreach ($T_DATA as $TASK => $value)
                                    {
                                    	//echo '<tr class="current-task" data->';
                                      printf('<tr class="current-task" data-psname="%s" >', $value['sname']);
                                    	printf("<td id='fpath' data-host='%s' data-pdiskn='%s' data-psname='%s' style='cursor: pointer;'>%s</td>", $_SESSION['user_data']['host'],$P_DATA[0]['pdisk_name'],$value['sname'], $value['sname']);
                                    	printf("<td>%s</td>", $value['sframe']);
                                        echo "<td id='edit_task' data-sid=".$value['sname']." data-pcode=".$value['pcode']."  style='cursor: pointer;'>";
                                    	foreach ($U_DATA as $key => $u_value) {
                  											if($u_value['id'] ==  $value['auser']) {
                                                                  echo $u_value['dname'];
                  											}

                  										}
                                      echo "</td>";
                                    	printf("<td id='edit_task' data-sid=".$value['sname']." data-pcode=".$value['pcode']."  style='cursor: pointer;'><div class='chip ochip %s' style='color: white;'>%s</div></td>",$S_DATA[$value['status']][1], $S_DATA[$value['status']][0]);
                                    	printf("<td>%s</td>", $value['correction']);
                                    	printf("<td><div class='chip ochip %s' style='color: white;'>%s</div></td>",$S_DATA[$value['c_status']][1], $S_DATA[$value['c_status']][0]);
                                      echo "<td>";
                                    	foreach ($U_DATA as $key => $c_value) {
                  											if($c_value['id'] == $value['cdoneby']) {
                  												echo $c_value['dname'];
                  											}
                  										}
                                      echo "</td>";
                                    	echo "</tr>";

                                    }
                                    ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>

</div>
<script type="text/javascript">
	 $(document).ready(function() {
	 	e.preventDefault();
        var url = "/data/task?pcode=" + $(this).data("pcode");
        console.log(url);
        $.get(url, function(data){
            $("#page").html(data);
        })
	 }

</script>
