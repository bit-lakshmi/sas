<?php

include ('codebase/gantt_connector.php');


$res=new PDO("mysql:dbname=gantt;host=localhost","root","A6i8");

$gantt = new JSONGanttConnector($res);
$gantt->render_links("gantt_links","id","source,target,type");
$gantt->render_table(
    "gantt_tasks",
    "id",
    "start_date,duration,text,progress,sortorder,parent,owner_id,priority"
);
/*

require("codebase/grid_connector.php");// connector file
$res=new PDO("mysql:dbname=gantt;host=localhost","root","A6i8");// db connection
// connector object; parameters: db connection and the type of the used db
$gridConn = new GridConnector($res,"MySQL"); 
$gridConn>render_table(
    "gantt_tasks",
    "id",
    "start_date,duration,text,progress,sortorder,parent"
);
*/
?>


