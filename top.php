<?php
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
$connection = getConnection();
$sid=26709;
$sql = "select data_name from data_src where id=".$sid;
$query = mysql_query($sql, $connection);
$row65=mysql_fetch_assoc($query);
$table=$row65['data_name'];

$sql = "select * from ".$table;
/*$query = mysql_query($sql, $connection);
while($row = mysql_fetch_assoc($query)) {

}*/
$sql1 = "select plus_password from ssq";
$query1 = mysql_query($sql1, $connection);
while($yus = mysql_fetch_assoc($query1)) {
$sql2="delete from ".$table." where plus_password='".$yus['plus_password']."';";
//$query = mysql_query($sql2, $connection);
echo $sql2;
}


?>