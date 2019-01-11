<?php
session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma:no-cache");
header("Expires:0");
$connection = getConnection();
$date1 = $_POST["date1"];
$date2 = $_POST["date2"];
$page = $_POST["pageNum"];
$status = $_POST["status"];
$pAccount = $_POST["account"];
$where_str = "";
if($status == "DayOrders") {
	$date1 = date("Y-m-d", time())." 00:00:00";
	$date2 = date("Y-m-d", time())." 23:59:59";
}
if($date1 != "") {
	$date1=strtotime($date1);
	$where_str .= " and create_time >= '$date1'";
}
if($date2 != "") {
	$date2=strtotime($date2);
	$where_str .= " and create_time <= '$date2'";
}

if($pAccount != "") {
	$where_str .= " and sname = '$pAccount'";
}
$sql = "select count(*) from `so_huo` order by id desc";
$query = mysql_query($sql, $connection);
if($row=mysql_fetch_row($query)) {
	$total = $row[0];
}
//$allTotalData = allTotalData(3, $where_str);
$pageSize = 10;
$startPage = $page * $pageSize;
$resTotal = $total;
$resPageSize = $pageSize;
$resTotalPage = ceil($total/$pageSize);
//$cert_price = getUrl("cert_price", "4");
$totalPrice = 0;
$totalNum = 0;
$msg = '';
if($where_str==""){
$sql = "select * from `so_huo`   $where_str order by id desc limit $startPage, $pageSize";

}else{
$sql = "select * from `so_huo` where id>0   $where_str order by id desc limit $startPage, $pageSize";
}
//echo $sql;
$query = mysql_query($sql, $connection);
while($row=mysql_fetch_assoc($query)) {
if($row['b_type']==1){
$bt="正式订单";
}
else if($row['b_type']==2){
$bt="正常补单";
}
	else if($row['b_type']==3){
$bt="多裁补单";
	}
		else if($row['b_type']==4){
$bt="错单补单";
		}
$yj=$row['cname'];
$sql65 = "select * from `suser` where rid='".$yj."';";
$query65 = mysql_query($sql65, $connection);
$row65=mysql_fetch_assoc($query65);
$cde=$row65['name'];
	$dtime=date("Y-m-d H:i:s",$row['create_time']);
   $msg .= '<tr  align=\"center\" class=\"TableControl\"><td nowrap  align=\"center\">'.$row['id'].'</td><td nowrap  align=\"center\">'.$row['order_num'].'</td><td nowrap  align=\"center\">与狼共舞</td><td nowrap  align=\"center\">'.$cde.'</td><td nowrap  align=\"center\">'.$bt.'</td><td nowrap  align=\"center\">'.$row['s_ss'].'</td><td nowrap  align=\"center\">'.$row['mark'].'</td><td nowrap  align=\"center\">'.$row['s_time'].'</td><td nowrap  align=\"center\">'.$dtime.'</td><td nowrap  align=\"center\"><input type=\"button\" name=\"b8\" id=\"NEXT_'.$id.'\" class=\"SmallButton\" title=\"打印\" value=\"打印\" onClick=\"printingPreview(\'Y\', '.$row['id'].');\" />&nbsp;|&nbsp;<input type=\"button\" name=\"b8\" id=\"NEXT_'.$id.'\" class=\"SmallButton\" title=\"预览\" value=\"预览\" onClick=\"printingPreview(\'N\', '.$row['id'].');\" />';

/*$msg .='&nbsp;|&nbsp;<a  id=\"NEXT_'.$id.'\" class=\"SmallButton alcs\" style=\"padding: 4px 14px;color: #36434E; \" href=\"so_xiugai.php?a='.$row['id'].'\">修改</a>';*/

$msg .='&nbsp;|&nbsp;<input type=\"button\" name=\"b8\" id=\"NEXT_'.$id.'\" class=\"SmallButton\" title=\"修改\" onClick=\"ssoa('.$row['id'].')\" value=\"修改\"/>';


$msg .='&nbsp;|&nbsp;<input type=\"button\" name=\"b8\" id=\"NEXT_'.$id.'\" class=\"SmallButton\" title=\"删除\" onClick=\"javascript:drop_confirm(\'确定要删除该送货单吗？\',\'sc.php?a='.$row['id'].'\')\" value=\"删除\"/>';



$msg .='</td></tr>';

}
if($msg == '') {
	$msg = '<tr class=\"TableControl\" align=\"center\"><td colspan=\"12\"><font color=\"red\">无数据</font></td></tr>';
} else {
	
}

echo '{"data":"<table width=\"100%\" class=\"TableBlock\" align=\"center\"><tr class=\"TableHeader\"><td nowrap align=\"center\">序号</td><td nowrap align=\"center\">订单号</td><td nowrap align=\"center\">客户名称</td><td nowrap align=\"center\">业务员</td><td nowrap align=\"center\">送货单类型</td><td nowrap align=\"center\">数量</td><td nowrap align=\"center\">订单摘要</td><td nowrap align=\"center\">送货时间</td><td nowrap align=\"center\">创建时间</td><td nowrap align=\"center\">操作</td></tr>'.$msg.'</table>", 
"total":"'.$resTotal.'", "pageSize":"'.$resPageSize.'", "cc":"'.$pAccount.'","totalPage":"'.$resTotalPage.'"}';
?>