<?php
//printingPreview.php
header("Content-Type: text/html; charset=utf-8");
//include_once('inc/conn.php');
include_once("function.php");
include_once("waterFunction.php");
$conn = getConnection();
function encodeConvertNG($content) {
	$fromCode = "gbk";
	$toCode = "utf-8";
	return encodeConvert($content, $fromCode, $toCode);
}
function getMaterialsInfos($id) {
	global $conn;
	if(is_numeric($id)) {
		$sql = "SELECT * FROM materials WHERE id='$id';";
		$query = mysql_query($sql);
		$row = mysql_fetch_assoc($query);
		return encodeConvertNG($row);
	} else {
		return '';
	}
}

function numToRenminbi($num) {
	$c1 = "零壹贰叁肆伍陆柒捌玖";
	$c2 = "分角元拾佰仟万拾佰仟亿";
	//精确到分后面就不要了，所以只留两个小数位
	$num = round($num, 2); 
	//将数字转化为整数
	$num = $num * 100;
	if (strlen($num) > 10) {
		return "金额太大，请检查";
	} 
	$i = 0;
	$c = "";
	while (1) {
		if ($i == 0) {
			//获取最后一位数字
			$n = substr($num, strlen($num)-1, 1);
		} else {
			$n = $num % 10;
		}
		//每次将最后一位数字转化为中文
		$p1 = substr($c1, 3 * $n, 3);
		$p2 = substr($c2, 3 * $i, 3);
		if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
			$c = $p1 . $p2 . $c;
		} else {
			$c = $p1 . $c;
		}
		$i = $i + 1;
		//去掉数字最后一位了
		$num = $num / 10;
		$num = (int)$num;
		//结束循环
		if ($num == 0) {
			break;
		} 
	}
	$j = 0;
	$slen = strlen($c);
	while ($j < $slen) {
		//utf8一个汉字相当3个字符
		$m = substr($c, $j, 6);
		//处理数字中很多0的情况,每次循环去掉一个汉字“零”
		if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
				$left = substr($c, 0, $j);
				$right = substr($c, $j + 3);
				$c = $left . $right;
				$j = $j-3;
				$slen = $slen-3;
		} 
		$j = $j + 3;
	} 
	//这个是为了去掉类似23.0中最后一个“零”字
	if (substr($c, strlen($c)-3, 3) == '零') {
		$c = substr($c, 0, strlen($c)-3);
	}
	//将处理的汉字加上“整”
	if (empty($c)) {
		return "零元整";
	} else {
		return $c . "整";
	}
}

function encodeConvert($str, $fromCode, $toCode) {
	if(strtoupper($toCode) == strtoupper($fromCode))
		return $str;
	if(is_string($str)) {
		if(function_exists("mb_convert_encoding")) {
			return mb_convert_encoding($str, $toCode, $fromCode);
		} else {
			return iconv($fromCode, $toCode, $str);
		}
	} else if(is_array($str)) {
		foreach($str as $k=>$v) {
			$str[$k] = encodeConvert($v, $fromCode, $toCode);
		}
		return $str;
	}
	return $str;
}

//$uid = $_POST['uid'];
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


if($row['b_type']==1){
$rf="正常订单";
}

	else if($row['b_type']==2){
$rf="正常补单";
	}
	else if($row['b_type']==3){
$rf="多裁补单";
	}
	else if($row['b_type']==4){
$rf="错单补单";
	}
//$row = encodeConvertNG($row);
$res = '<style>.table_class{border:1px solid black;border-collapse:collapse;font-size:11pt}.table_class tr,.table_class tr td{border:1px solid black;}</style>';
$res .= '<table width=\"80%\" align=\"center\"><tr><td width=\"50%\" align=\"left\">客户名称：与狼共舞</td></tr></table>';
//<td width=\"50%\" align=\"right\">运输方式：'.$row['yu_type'].'</td>

$res .= '<table width=\"80%\" align=\"center\"><tr><td width=\"50%\" align=\"left\">订单摘要：('.$rf.')</td><td width=\"50%\" align=\"right\">送货日期：'.$row['s_time'].'</td></tr></table>';
$res .= '<table width=\"80%\" align=\"center\" class=\"table_class\"><tr align=\"center\" class=\"TableControl\"><td>序号</td><td>货号</td><td>品名</td><td>收货方</td><td>数量</td><td>单价(元)</td><td>金额(元)</td><td>备注</td></tr>';
$sql56 = "select * from `parameters` where `para_name`='cert_price' and `status`=4";
$query56 = mysql_query($sql56, $conn);
$row56 = mysql_fetch_assoc($query56);
$cert_price = $row56["para_value"];
$priceCount = 0;
$cot = 0;
$sqlB = "SELECT * FROM so_huo_de WHERE   eid=".$id;
$queryB = mysql_query($sqlB);
$fghh=0;
while($rowB = mysql_fetch_assoc($queryB)) {
//$rowB = encodeConvertNG($rowB);
	$cot++;
	$array = getMaterialsInfos($rowB['m_id']);
	$res .= '<tr align=\"center\">';
	$dfg=date("Ymd",time());
	$res .= '<td>'.$cot.'</td>';
	$res .= '<td>'.$rowB['item'].'</td>';
	$res .= '<td>'.$rowB['sname'].'</td>';
	$res .= '<td>'.$rowB['jgc'].'</td>';
	//$res .= '<td>'.$row['unit'].'</td>';
	$res .= '<td>'.$rowB['r_number'].'</td>';
	$res .= '<td>'.$rowB['price'].'</td>';
		$res .= '<td>'.$rowB['s_sun'].'</td>';
	//$res .= '<td>'.reserved2($rowB['quantity'] * $rowB['price']).'</td>';
	$res .= '<td>'.$rowB['smark'].'</td>';
	$res .= '</tr>';
$fghh=$fghh+$rowB['r_number'];
	//$priceCount += $rowB['quantity'] * $rowB['price'];
}
if((9-$cot) > 0) {
	for($i = 0; $i < (9-$cot); $i++) {
		$res .= '<tr align=\"center\">';
		for($j = 0; $j < 8; $j++) {
			$res .= '<td>&nbsp;</td>';
		}
		$res .= '</tr>';
	}
}
$res .= '<tr class=\"TableControl\"><td align=\"left\" colspan=\"4\">合计人民币(大写)：'.numToRenminbi(sprintf("%.2f", $row['s_sun'])).'</td><td align=\"left\" colspan=\"2\">'.$fghh.'</td><td align=\"center\" colspan=\"2\">'.sprintf("%.2f", $row['s_sun']).'</td></tr>';
$res .= '</table>';
//$res .= '<div align=\"center\">&nbsp;&nbsp;&nbsp;&nbsp;白色联：存根&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;红色联：结算联&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;蓝色联：顾客联&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;黄色联：顾客存根</div>';

//$res .= '<table width=\"96%\"><tr><td width=\"50%\" align=\"left\">&nbsp;送货单位及经手人（盖章）：'.getUserName($row['create_user']).'</td><td align=\"right\" width=\"50%\"><span style=\"float:left;\">收货单位及经手人（盖章）：</span></td></tr></table>';
$yj=$row['cname'];
$sql65 = "select * from `suser` where rid='".$yj."';";
$query65 = mysql_query($sql65, $conn);
$row65=mysql_fetch_assoc($query65);
$cde=$row65['name'];
echo '{"order_num":"NO:'.$row['order_num'].'", "res":"'.$res.'", "user":"送货单位及经手人（盖章）:'.$cde.'", "sign":"收货单位及经手人（盖章）："}';
?>