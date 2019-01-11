<?php
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
$connection = getConnection();
$user=trim($_POST['username']);

$sql1="select * from sheet1 where name='".$user."'";
$query = mysql_query($sql1, $connection);
$sf="";
while($row=mysql_fetch_assoc($query)) {
	$sf=$row['name'];
	}
if($sf=="" || $sf==null){
	$sql2="insert into sheet1 (name) values ('".$user."')";
   mysql_query($sql2,$connection);
   echo $sql2;
echo "添加成功";
}else{
	echo "添加失败,该用户已存在";
}






?>