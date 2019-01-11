<?php
//session_start();
include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("../function/function.php");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma:no-cache");
header("Expires:0");
$connection = getConnection();
header("Content-Type: text/html; charset=utf-8");
$json = $GLOBALS["HTTP_RAW_POST_DATA"];
$array = json_decode($json);
$array = object_array($array);
$type_id = $array['type_id'];
$number = $array['number'];
$name = $array['name'];
$unit = $array['unit'];
$unit_price = $array['unit_price'];
/*$type_id = "wolf";
$number = "123";
$name = "我的";
$unit = "块";*/
$unit_price = 12.34;
$user = $array['user'];
$pass = $array['pass'];
/*$user = "root";
$pass ="1234";*/
if($user == 'root' && $pass == '1234') {
	//连接数据库
	//....
$create_time=date("Y-m-d H:i:s",time());
$sql5 = "INSERT INTO materials (type_id,number,name,unit,unit_price,create_time) VALUES ('".$type_id."','".$number."','".$name."','".$unit."',".$unit_price.",'".$create_time."')";
     //echo $sql5;
     $tyu=mysql_query($sql5, $connection); 
	echo $sql5;

//	echo '{"msg":"'.$str.'"}';
} else {
	echo '{"msg":"密码错误"}';
}
?>