<?php
//session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
$connection = getConnection();
$sid=$_POST['value1'];
$df=explode("ss", $sid);
$sql11 = "update `materials` set rid=0 where id>0 and type_id='WOLVES'";
$query11 = mysql_query($sql11, $connection);
for($i=0;$i<count($df);$i++){
$sql1 = "update `materials` set rid=1 where id=".$df[$i];
$query1 = mysql_query($sql1, $connection);
}
echo '{"wer":"123"}';
?>