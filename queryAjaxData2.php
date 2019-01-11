<?php
session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma:no-cache");
header("Expires:0");
$connection = getConnection();
//usleep(100000);//0.3秒
$name=$_POST['value1'];
$sql = "select * from `sheet1` where name like '".$name."%'";
$query = mysql_query($sql, $connection);
$msg="";
while($row=mysql_fetch_assoc($query)) {
	$msg .='<div class="load">'.$row['name'].'</div>';
	
	}
	
//echo '{"sd":"'.$msg.'"}';
echo $msg;
?>