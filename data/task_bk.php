<?php
include 'db_config.php';
$PCODE = $_GET['pcode'];
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
<div class="col s12 m12 l12">
	<div class="card invoices-card">
                                <div class="card-content">
                                <div class="card-options">
                                <?php 
                                	$result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '4' GROUP BY status");
										while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										       printf('<div class="chip red" style="color: white;">%s</div>', $row['COUNT(status)']);
									}
									$result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '6' GROUP BY status");
										while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										       printf('<div class="chip green accent-4" style="color: white;">%s</div>', $row['COUNT(status)']);
									}/*
									$result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '6' GROUP BY status");
										while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										       printf('<div class="chip grey">%s</div>', $row['COUNT(status)']);
									}*/
									$result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '7' GROUP BY status");
										while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										       printf('<div class="chip deep-purple darken-3" style="color: white;">%s</div>', $row['COUNT(status)']);
									}
									$result = $conn->query("SELECT COUNT(status) FROM tasks WHERE pcode='{$PCODE}' AND status = '5' GROUP BY status");
										while($row = $result->fetch(PDO::FETCH_ASSOC)) {
										       printf('<div class="chip amber accent-4" style="color: white;">%s</div>', $row['COUNT(status)']);
									}

                                ?>
                                		<a href="#task_reorder" class="card-refresh"><i id="t_task_reorder" class="material-icons">reorder</i></a>
                                        <a href="#add_task" class="modal-trigger card-refresh"><i id="t_add_task" data-pname="<?php echo $P_DATA[0]['pname'] ?>" data-pcode="<?php echo $P_DATA[0]['pcode'] ?>" class="modal-trigger material-icons">playlist_add</i></a>
                                </div>
                                    <span class="card-title"><?php echo $P_DATA[0]['pname'] ?></span>
                                <table class="responsive-table bordered striped">
                                    <thead>
                                        <tr>
                                            <th data-field="id">Shots</th>
                                            <th data-field="number">Frames</th>
                                            <th data-field="company">Assigned to</th>
                                            <th data-field="date">Status</th>
                                            <th data-field="progress">Correction</th>
                                            <th data-field="total">Status</th>
                                            <th data-field="total">Done by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($T_DATA as $TASK => $value) {
                                    	echo "<tr id='edit_task' data-sid=".$value['sname']."  style='cursor: pointer;'>";
                                    	printf("<td>%s</td>", $value['sname']);
                                    	printf("<td>%s</td>", $value['sframe']);
                                        echo "<td>";
                                    	foreach ($U_DATA as $key => $u_value) {
											if($u_value['id'] ==  $value['auser']) {
                                                echo $u_value['dname'];
											}

										}
                                        echo "</td>";                                 	
                                    	printf("<td><div class='chip %s' style='color: white;'>%s</div></td>",$S_DATA[$value['status']][1], $S_DATA[$value['status']][0]);
                                    	printf("<td>%s</td>", $value['correction']);
                                    	printf("<td><div class='chip %s' style='color: white;'>%s</div></td>",$S_DATA[$value['c_status']][1], $S_DATA[$value['c_status']][0]);
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