<?php
//printingPreview.php
header("Content-Type: text/html; charset=utf-8");
//include_once('inc/conn.php');
include_once("function.php");
include_once("waterFunction.php");
/*function http_post_data($url, $data) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charset=utf-8',
		'Content-Length: ' . strlen($data))
	);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}*/
$conn = getConnection();
$id = $_POST["id"];
$time = $_POST["time"];
if($time == '') {
	echo '{"res":"非法操作"}';
	exit;
}
$sql = "SELECT * FROM so_huo WHERE id='$id';";
$query = mysql_query($sql);
if(!mysql_num_rows($query)) {
	echo '{"res":"非法操作，该订单不存在"}';
	exit;
}
$row = mysql_fetch_assoc($query);
$order_num=$row['order_num'];//送货单编号
$sname=$row['sname'];//客户名称
$cname=$row['cname'];//业务员
$s_time=$row['s_time'];//送货日期
$eeid=$row['id'];
$mark=$row['mark'];//订单摘要
$create_time=$row['create_time'];//创建时间
//$create_time=$row['b_type'];//订单类型
$s_sun=$row['s_sun'];//总金额
$sql = "SELECT * FROM so_huo_de WHERE eid='$id' order by id;";
$query = mysql_query($sql);
while($row4 = mysql_fetch_assoc($query1)) {
	$price[]=$row4['price'];
	$snumber[]=$row4['snumber'];
	$sb_sun[]=$row4['s_sun'];
	$sname_id[]=$row4['sname_id'];
	$item[]=$row4['item'];
}
$sitem=implode("s",$item);
$sprice=implode("s",$price);
$ssnumber=implode("s",$snumber);
$ss_sun=implode("s",$sb_sun);
$ssname_id=implode("s",$sname_id);
$url = "http://59.57.37.217:8686/general/finance/wolf.php";
$json = '{"order_num":"'.$order_num.'","eid":"'.$eeid.'","create_time":"'.$create_time.'", "sname":"'.$sname.'","cname":"'.$cname.'","s_time":"'.$s_time.'","mark":"'.$mark.'","s_sun":"'.$s_sun.'","sitem":"'.$sitem.'","price":"'.$sprice.'","snumber":"'.$ssnumber.'","ss_sun":"'.$ss_sun.'","sname_id":"'.$ssname_id.'","user":"root", "pass":"1234"}';
$res = http_post_data($url, $json);
echo $res;
?>