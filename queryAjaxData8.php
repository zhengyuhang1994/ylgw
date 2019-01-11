<?php
//session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
$connection = getConnection();
$sid=$_POST['value1'];
//$sid="15秋冬合格证配套";
$sql1 = "SELECT * FROM `sheet1` where name='".$sid."'";
$query1 = mysql_query($sql1, $connection);
$row3 = mysql_fetch_assoc($query1);
//print_r($row3);
$l_name=$row3['l_man'];
$l_number=$row3['l_number'];
echo '{"l_man":"'.$l_name.'", "l_number":"'.$l_number.'"}';

?>