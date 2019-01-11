<?php
//session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
$connection = getConnection();
$sid=$_POST['value1'];
//$sid="15秋冬合格证配套";
$sql1 = "SELECT * FROM `materials` where name='".$sid."'";
$query1 = mysql_query($sql1, $connection);
$row3 = mysql_fetch_assoc($query1);
//print_r($row3);
$ssid=$row3['number'];
$dan=$row3['unit'];
$price=$row3['unit_price'];
echo '{"sid":"'.$ssid.'", "price":"'.$price.'","dan":"'.$dan.'"}';

?>