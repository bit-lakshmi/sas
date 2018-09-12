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

$result = $conn->query("SELECT * FROM mrig WHERE pcode='{$PCODE}' AND type='1'");
$T_DATA_CH = array();
if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $T_DATA_CH[] = $row;
        }
}

$result = $conn->query("SELECT * FROM mrig WHERE pcode='{$PCODE}' AND type='2'");
$T_DATA_A = array();
if ($result->rowCount() > 0) {
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $T_DATA_A[] = $row;
        }
}


?>
<script type="text/javascript">
    projectData= {
                    pCode: "<?php echo $P_DATA[0]['pcode'] ?>",
                    pName: "<?php echo $P_DATA[0]['pname'] ?>",
                    pDiskName: "<?php echo $P_DATA[0]['pdisk_name'] ?>",
                    mtype: "rig"

    };
</script>
<div class="col s12 m12 l12">
	<div class="card invoices-card">
                                <div class="card-content">
                                <div class="card-options">
                                       
                                </div>
                                    <span class="card-title"><?php echo $P_DATA[0]['pname'] ?> |  <a href="#add_task" class="modal-trigger card-refresh"><i id="t_add_mrig" data-pname="<?php echo $P_DATA[0]['pname'] ?>" data-pcode="<?php echo $P_DATA[0]['pcode'] ?>" class="modal-trigger material-icons">playlist_add</i></a></span>
                                <table class="responsive-table bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Info</th>
                                            <th>Modeling</th>
                                            <th>Texturing</th>
                                            <th>Rigging</th>
                                            <th>OID</th>                                         
                                        </tr>
                                    </thead>
                                     <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Info</th>
                                            <th>Modeling</th>
                                            <th>Texturing</th>
                                            <th>Rigging</th>
                                            <th>OID</th>                                         
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
                                    foreach ($T_DATA_CH as $TASK => $value) {
                                    	echo '<tr style="background-color: #d7d7d7 !important;">';
                                    	printf("<td id='fpath' data-host='%s' data-pdiskn='%s' data-psname='%s' style='cursor: pointer;'>%s</td>", $_SESSION['user_data']['host'],$P_DATA[0]['pdisk_name'],$value['aname'], $value['aname']);
                                        echo '<td><input id="updateriginfo" class="validate" data-id="'.$value['id'].'" type="text" value="'.$value['info'].'"></td>';
                                        $sql_col = "mode";
                                        if($value[$sql_col] == 1){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="0" data-aname="%s"><a class="waves-effect waves-light btn green">Done</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        }else if ($value[$sql_col] == 2){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-isdone="1" data-colvalue="1" data-aname="%s"><a class="waves-effect waves-light btn blue">WIP</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        } else {
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="2" data-aname="%s"><a class="waves-effect waves-light btn grey">None</a></td>', $value['pcode'],$sql_col,$value['aname']);
                                        }

                                        $sql_col = "texturing";
                                        if($value[$sql_col] == 1){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="0" data-aname="%s"><a class="waves-effect waves-light btn green">Done</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        }else if ($value[$sql_col] == 2){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="1" data-aname="%s"><a class="waves-effect waves-light btn blue">WIP</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        } else {
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="2" data-aname="%s"><a class="waves-effect waves-light btn grey">None</a></td>', $value['pcode'],$sql_col,$value['aname']);
                                        }

                                        $sql_col = "rig";
                                        if($value[$sql_col] == 1){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="0" data-aname="%s"><a class="waves-effect waves-light btn green">Done</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        }else if ($value[$sql_col] == 2){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="1" data-aname="%s"><a class="waves-effect waves-light btn blue">WIP</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        } else {
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="2" data-aname="%s"><a class="waves-effect waves-light btn grey">None</a></td>', $value['pcode'],$sql_col,$value['aname']);
                                        }
                                        printf("<td id='edit_mrig' data-sid=".$value['aname']." data-pcode=".$value['pcode']."  style='cursor: pointer;'>%s</td>", $value['aoid']);
                                    	echo "</tr>";

                                    }

                                    foreach ($T_DATA_A as $TASK => $value) {
                                        echo '<tr style="background-color: #d9f2ee !important;">';
                                        printf("<td id='fpath' data-host='%s' data-pdiskn='%s' data-psname='%s' style='cursor: pointer;'>%s</td>", $_SESSION['user_data']['host'],$P_DATA[0]['pdisk_name'],$value['aname'], $value['aname']);
                                        echo '<td><input id="updateriginfo" class="validate" data-id="'.$value['id'].'" type="text" value="'.$value['info'].'"></td>';
                                        $sql_col = "mode";
                                        if($value[$sql_col] == 1){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="0" data-aname="%s"><a class="waves-effect waves-light btn green">Done</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        }else if ($value[$sql_col] == 2){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="1" data-aname="%s"><a class="waves-effect waves-light btn blue">WIP</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        } else {
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="2" data-aname="%s"><a class="waves-effect waves-light btn grey">None</a></td>', $value['pcode'],$sql_col,$value['aname']);
                                        }

                                        $sql_col = "texturing";
                                        if($value[$sql_col] == 1){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="0" data-aname="%s"><a class="waves-effect waves-light btn green">Done</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        }else if ($value[$sql_col] == 2){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="1" data-aname="%s"><a class="waves-effect waves-light btn blue">WIP</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        } else {
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="2" data-aname="%s"><a class="waves-effect waves-light btn grey">None</a></td>', $value['pcode'],$sql_col,$value['aname']);
                                        }

                                        $sql_col = "rig";
                                        if($value[$sql_col] == 1){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="0" data-aname="%s"><a class="waves-effect waves-light btn green">Done</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        }else if ($value[$sql_col] == 2){
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="1" data-aname="%s"><a class="waves-effect waves-light btn blue">WIP</a></td>',$value['pcode'],$sql_col,$value['aname']);
                                        } else {
                                            printf('<td id="proupdate" data-ugroup="2" data-pcode="%s" data-colname="%s" data-colvalue="2" data-aname="%s"><a class="waves-effect waves-light btn grey">None</a></td>', $value['pcode'],$sql_col,$value['aname']);
                                        }
                                        printf("<td id='edit_mrig' data-sid=".$value['aname']." data-pcode=".$value['pcode']."  style='cursor: pointer;'>%s</td>", $value['aoid']);
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