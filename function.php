<?php
function getConnection() {
	$connection = mysql_connect("127.0.0.1", "root", "yimatong,._cy");
	mysql_select_db("wolf", $connection);
	mysql_query("set names utf8");
	return $connection;
}
function getwConnection() {
	$connection = mysql_connect("127.0.0.1", "root", "yimatong,._cy");
	mysql_select_db("ajaxdb", $connection);
	mysql_query("set names utf8");
	return $connection;
}
function iconvU2G($content) {
	$fromCode = "utf-8";
	$toCode = "gbk";
	return iconv($fromCode, $toCode, $content);
}
function reserved2($num) {
	return sprintf("%.2f", $num);
}
function getBaoFei($id) {
	global $connection;
	$sql = "SELECT * FROM `data_order` WHERE `data_id`='$id' AND `status`=2;";
	$query = mysql_query($sql, $connection);
	if(mysql_num_rows($query)) {
		return '&nbsp;<span id=\"s'.$id.'\" style=\"color:#FF0000; cursor:pointer\" onmouseover=\"OnmouseOver('.$id.');\" onmouseout=\"OnmouseOut('.$id.');\" onclick=\"alert(\'开发中\');\">已经报废</span>';
	} else {
		return '';
	}
}

function orderNotPush($id) {
	global $connection;
	$sql = "SELECT * FROM `data_order` WHERE `data_id`='$id' AND `status`=3";
	$query = mysql_query($sql, $connection);
	if(mysql_num_rows($query)) {
		return '&nbsp;<span id=\"s'.$id.'\" style=\"color:#FF0000; cursor:pointer\" onmouseover=\"OnmouseOver1('.$id.', \'要推送\');\" onmouseout=\"OnmouseOut1('.$id.', \'不推送\');\" onclick=\"alert(\'开发中\');\">不推送</span>';
	} else {
		return '';
	}
}

function getDataName($id) {
	global $connection;
	$sql = "SELECT `data_name` FROM `data_src` WHERE `status`=3 AND `id`='$id';";
	$query = mysql_query($sql, $connection);
	$row = mysql_fetch_assoc($query);
	return $row['data_name'];
}

function getDataDetail($data_name, $productCode, $num) {
	global $connection;
	if($num == 9) {
		//$sql = "SELECT product_code,plus_password,serial_number FROM $data_name WHERE status=0 AND gb_unique_code != '' AND FIND_IN_SET('$productCode', left(product_code, 9)) ORDER BY id;";
		$sql = "SELECT product_code,plus_password,serial_number FROM $data_name WHERE `product_code` != '' AND FIND_IN_SET('$productCode', left(product_code, 9)) ORDER BY id;";
	} else if($num == 12) {
		//$sql = "SELECT product_code,plus_password,serial_number FROM $data_name WHERE status=0 AND gb_unique_code != '' AND FIND_IN_SET('$productCode', left(product_code, 12)) ORDER BY id;";
		$sql = "SELECT product_code,plus_password,serial_number FROM $data_name WHERE `product_code` != '' AND FIND_IN_SET('$productCode', left(product_code, 12)) ORDER BY id;";
	} else if($num == 15) {
		//$sql = "SELECT product_code,plus_password,serial_number FROM $data_name WHERE status=0 AND gb_unique_code != '' AND FIND_IN_SET('$productCode', product_code) ORDER BY id;";
		$sql = "SELECT product_code,plus_password,serial_number FROM $data_name WHERE `product_code` != '' AND FIND_IN_SET('$productCode', product_code) ORDER BY id;";
	}
	$total = 0;
	$query = mysql_query($sql, $connection);
	while($row = mysql_fetch_assoc($query)) {
		$total++;
		$tmp["ProductCodeNumber"] = $row["product_code"].$row["serial_number"];
		$array = explode(".com/g?v=", $row["plus_password"]);
		$tmp["CheckCode"] = $array[1];
		$arr[] = $tmp;
	}

	return array('total' => $total, 'arr' => $arr);
}

function getDataCount($data_name, $productCode, $num) {
	global $connection;
	if($num == 9) {
		$sql = "SELECT count(*) FROM $data_name WHERE `product_code` != '' AND FIND_IN_SET('$productCode', left(product_code, 9)) ORDER BY `id` ASC;";
	} else if($num == 12) {
		$sql = "SELECT count(*) FROM $data_name WHERE `product_code` != '' AND FIND_IN_SET('$productCode', left(product_code, 12)) ORDER BY `id` ASC;";
	} else if($num == 15) {
		$sql = "SELECT count(*) FROM $data_name WHERE `product_code` != '' AND FIND_IN_SET('$productCode', product_code) ORDER BY `id` ASC;";
	}
	$query = mysql_query($sql, $connection);
	$row = mysql_fetch_row($query);
	return $row[0];
}

function allTotalData($status, $where_str) {
	global $connection;

	$totalPrice = 0;
	$totalNum = 0;
	$cert_price = getUrl("cert_price", "4");

	$allOrderNum = 0;
	$sql = "select * from `data_src` where status='$status' ".$where_str;
	$query = mysql_query($sql, $connection);
	while($row=mysql_fetch_assoc($query)) {
		foreach($row as $k=>$v) {
			$$k = $v;
		}
		$allOrderNum++;

		$sql1 = "SHOW TABLE STATUS LIKE '$data_name'";
		$query1 = mysql_query($sql1, $connection);
		if($row1=mysql_fetch_assoc($query1)) {
			$rows = $row1["Rows"];
		}

		$totalNum += $rows;
		$totalPrice += ($cert_price * $rows);
	}
	//file_put_contents("c:/mylog.log", "totalNum:".$totalNum.",totalPrice:".$totalPrice."\r\n", FILE_APPEND);
	//return array("allTotalNum" => $totalNum, "allTotalPrice" => $totalPrice);
	return array("allTotalNum" => $totalNum, "allTotalPrice" => $totalPrice, "allOrderNum" => $allOrderNum);
}

function getShowAccount($account) {
	global $connection;
	$sql = "select show_account from `account` where account='$account'";
	$query = mysql_query($sql, $connection);
	$row = mysql_fetch_assoc($query);
	return $account.",".$row["show_account"];
}

function getRows($table, $mark) {
	global $connection;
	$sql = "select count(*) from $table where mark='$mark'";
	$query = mysql_query($sql, $connection);
	if($row=mysql_fetch_row($query)) {
		return $row[0];
	}
}

function getRows2($table, $mark) {
	global $connection;
	$sql = "select count(*) from $table where brand != '' AND mark='$mark'";
	$query = mysql_query($sql, $connection);
	if($row=mysql_fetch_row($query)) {
		return $row[0];
	}
}

function getDataId($data_name) {
	global $connection;
	$sql = "select * from `data_src` where data_name='$data_name'";
	$query = mysql_query($sql, $connection);
	$row = mysql_fetch_assoc($query);

	$len = 4;
	$prefix = "";
	if(strlen($row["id"]) == $len) {
		$prefix = $row["id"];
	} else if(strlen($row["id"]) < $len) {
		$prefix = $row["id"].randomKeys($len - strlen($row["id"]));
	}

	return $prefix;
}

function getParametersUrl($para_name, $status) {
	global $connection;
	$sql = "select para_value from parameters where para_name='$para_name' and status='$status'";
	$query = mysql_query($sql, $connection);
	if($row=mysql_fetch_row($query)) {
		return $row[0];
	}
}

function find_id($STRING, $ID) {
	$STRING = ltrim($STRING, ",");
	if($ID == "" || $ID == ",")
		return false;
	if(substr($STRING, -1) != ",")
		$STRING .= ",";
	if(strpos($STRING, ",".$ID.",") > 0)
		return true;
	if(strpos($STRING, $ID.",") === 0)
		return true;
	if(!strstr($ID, ",") && $STRING == $ID)
		return true;
	return false;
}

function getPushInfosByMark($id, $mark) {
	global $connection;
	$sql = "select * from `account_wolves`";
	$query = mysql_query($sql, $connection);
	$account = mysql_fetch_assoc($query);
	$sql = "select data_name from `data_src` where id='$id'";
	$query = mysql_query($sql, $connection);
	$row = mysql_fetch_assoc($query);
	$data_name = $row["data_name"];

	$qty = 0;
	//$sql = "select product_code,plus_password,serial_number from $data_name where status=0 and mark='$mark' and gb_unique_code != '' order by id";
	$sql = "select product_code,plus_password,serial_number from $data_name where `product_code` != '' and mark='$mark' order by id";
	$query = mysql_query($sql, $connection);
	while($row=mysql_fetch_assoc($query)) {
		$qty++;
		$tmp["ProductCode"] = $row["product_code"].$row["serial_number"];
		$array = explode(".com/g?v=", $row["plus_password"]);
		$tmp["CheckCode"] = $array[1];
		$arr[] = $tmp;
	}

	$temp["Qty"] = $qty;
	$temp["DataDetails"] = $arr;

	$infos["Account"] = $account["account"];
	$infos["AccessToken"] = $account["password"];
	$infos["DataContent"] = $temp;

	return json_encode($infos);
}

function getPushInfos($id) {
	global $connection;

	$sql = "select * from `account_wolves`";
	$query = mysql_query($sql, $connection);
	$account = mysql_fetch_assoc($query);

	$sql = "select data_name from `data_src` where id='$id'";
	$query = mysql_query($sql, $connection);
	$row = mysql_fetch_assoc($query);
	$data_name = $row["data_name"];

	$qty = 0;
	//$sql = "select product_code,plus_password,serial_number from $data_name where status=0 and gb_unique_code != '' order by id";
	$sql = "select product_code,plus_password,serial_number from $data_name where `product_code` != '' order by id";
	$query = mysql_query($sql, $connection);
	while($row=mysql_fetch_assoc($query)) {
		$qty++;
		$tmp["ProductCode"] = $row["product_code"].$row["serial_number"];
		$array = explode(".com/g?v=", $row["plus_password"]);//goods?code=
		$tmp["CheckCode"] = $array[1];
		$arr[] = $tmp;
	}

	$temp["Qty"] = $qty;
	$temp["DataDetails"] = $arr;

	$infos["Account"] = $account["account"];
	$infos["AccessToken"] = $account["password"];
	$infos["DataContent"] = $temp;

	return json_encode($infos);
}

function getOrderName($order_status) {
	if($order_status == 0) {
		$order_name = "下单";
	} else if($order_status == 1) {
		$order_name = "打印";
	} else if($order_status == 2) {
		$order_name = "裁切";
	} else if($order_status == 3) {
		$order_name = "分拣";
	} else if($order_status == 4) {
		$order_name = "发货";
	} else if($order_status == 5) {
		$order_name = "完成";
	}
	return $order_name;
}

function getIdByProductCode($product_code) {
	global $connection;
	$res = "";
	$sql = "SELECT `t_id` FROM `wolves`.`wolves_cert` WHERE `product_code` LIKE '%".$product_code."%';";
	$query = mysql_query($sql, $connection);
	while($row = mysql_fetch_assoc($query)) {
		$res .= $row["t_id"].",";
	}
	return $res;
}

function getScanResult($id, $mark) {
	global $connection;
	$sql = "select count(*) from `data_wolves_scan` where data_id='$id' and mark='$mark' and status=0";
	$query = mysql_query($sql, $connection);
	$row1 = mysql_fetch_row($query);
	$sql = "select count(*) from `data_wolves_scan` where data_id='$id' and mark='$mark' and status=1";
	$query = mysql_query($sql, $connection);
	$row2 = mysql_fetch_row($query);
	$sql = "select count(*) from `data_wolves_scan` where data_id='$id' and mark='$mark' and status=2";
	$query = mysql_query($sql, $connection);
	$row3 = mysql_fetch_row($query);
	$res = "【成功：".$row1[0]."个，重复扫描：".$row2[0]."个，失败：".$row3[0]."个】";
	return $res;
}

function codeEncrypt($data, $key) {//加密
	$key = md5($key);
	$x  = 0;
	$len = strlen($data);
	$l = strlen($key);
	$char = "";
    for ($i = 0; $i < $len; $i++) {
		if ($x == $l)  {
			$x = 0;
		}
		$char .= $key{$x};
		$x++;
    }
	$str = "";
	for ($i = 0; $i < $len; $i++) {
		$str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
	}
	return base64_encode($str);
}

function codeDecrypt($data, $key) {//破解
	$key = md5($key);
	$x = 0;
	$data = base64_decode($data);
	$len = strlen($data);
	$l = strlen($key);
	$char = "";
    for ($i = 0; $i < $len; $i++) {
		if ($x == $l)  {
			$x = 0;
		}
		$char .= substr($key, $x, 1);
		$x++;
    }
	$str = "";
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
			$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
			$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
		}
	}
	return $str;
}

function getUrl($para_name, $status) {
	global $connection;
	$sql = "select para_value from `parameters` where para_name='$para_name' and status='$status'";
	$query = mysql_query($sql, $connection);
	if($row=mysql_fetch_assoc($query)) {
		return $row["para_value"];
	}
}

function saveSysLog($table_name, $supplier_name, $type_id, $quantity) {
	global $connection;
	//type_id	1:登录	2:退出	3:导入合格证	4:批量录入	5:导入供应商	6:帐号密码修改	7:参数修改	8:导出合格证	9:获取数据	10:导出供应商
	$sql = "INSERT INTO `sys_log` (`table_name`, `supplier_name`, `type_id`, `quantity`, `opera_time`, `status`, `ip`) VALUES ('$table_name', '$supplier_name', '$type_id', '$quantity', '".date("Y-m-d H:i:s", time())."', '2', '".getClientIP()."')";
	mysql_query($sql, $connection);
}

//得到当前ip
function getClientIP() {
	if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
		$ip = getenv("HTTP_CLIENT_IP");
	} else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
		$ip = getenv("REMOTE_ADDR");
	} else if (isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) {
		$ip = $_SERVER["REMOTE_ADDR"];
	}
	return preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : "unknown";
}

function https_request($url, $data = null) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	if (!empty($data)) {
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}

function getIngredients($i) {
	if($i == 0) {
		return "一";
	} else if($i == 1) {
		return "二";
	} else if($i == 2) {
		return "三";
	} else if($i == 3) {
		return "四";
	} else if($i == 4) {
		return "五";
	} else if($i == 5) {
		return "六";
	} else if($i == 6) {
		return "七";
	} else if($i == 7) {
		return "八";
	} else if($i == 8) {
		return "九";
	} else if($i == 9) {
		return "十";
	}
}

function getBatchName($id) {
	global $connection;
	$sql = "select * from `parameters` where id='$id' and status='3'";
	$query = mysql_query($sql, $connection);
	if($row=mysql_fetch_assoc($query)) {
		return $row["para_value"];
	}
}

function getSerialNumber($i) {
	if(strlen($i) == 1) {
		$xlh = "0000".$i;
	} else if(strlen($i) == 2) {
		$xlh = "000".$i;
	} else if(strlen($i) == 3) {
		$xlh = "00".$i;
	} else if(strlen($i) == 4) {
		$xlh = "0".$i;
	} else {
		$xlh = $i;
	}
	return $xlh;
}

function getFullFields($tableName) {
	global $connection;
	$sql = "SHOW FULL FIELDS FROM $tableName where not find_in_set(Field, 'id,batch_id,url,supplier_code,check_code')";
	$query = mysql_query($sql, $connection);
	while($row=mysql_fetch_assoc($query)) {
		return "";
	}
}

function checkInteger($varnum) {
	$string_var = "0123456789";
	$len_string = strlen($varnum);
	if(substr($varnum, 0, 1) == "0") {
		return false;
		//die();
	} else {
		for($i = 0; $i < $len_string; $i++) {
			$checkint = strpos($string_var, substr($varnum, $i, 1));
			if($checkint === false) {
				return false;
				//die();
			}
		}
		return true;
	}
}

function td_trim($STR, $charlist=" ,\t\n\r\0\x0B") {
	return trim($STR, $charlist);
}

function randomKeysNG($length) {
	$key = "";
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	for($i = 0; $i < $length; $i++) {
		$key .= $pattern{mt_rand(0, 62)};	//生成php随机数
		//$key .= $pattern{mt_rand(0, 9)};	//生成php随机数
	}
	return $key;
}

function randomKeys($length) {
	$key = "";
	//$pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
	$pattern = '1234567890';
	for($i = 0; $i < $length; $i++) {
		//$key .= $pattern{mt_rand(0, 35)};	//生成php随机数
		$key .= $pattern{mt_rand(0, 9)};	//生成php随机数
	}
	return $key;
}

function serialNumberMinMax($data_name, $product_code) {
	global $connection;
	$sql = "select min(serial_number) as min, max(serial_number) as max from $data_name where product_code='$product_code' order by id";
	$query = mysql_query($sql, $connection);
	if($row=mysql_fetch_row($query)) {
		$arr = $row;
	}
	return $arr;
}

function getProductCodeCount($id, $product_code) {
	global $connection;
	$sql = "select data_name from `data_src` where id='$id'";
	$query = mysql_query($sql, $connection);
	if($row=mysql_fetch_assoc($query)) {
		$data_name = $row["data_name"];
	}

	$sql = "select count(*) from ".$data_name." where `product_code`='$product_code'";
	$query = mysql_query($sql, $connection);
	if($row=mysql_fetch_row($query)) {
		return $row[0];
	}
}

function getParameters($para_name, $status) {
	global $connection;
	$sql = "select para_value from `parameters` where para_name='$para_name' and status='$status'";
	$query = mysql_query($sql, $connection);
	if($row=mysql_fetch_assoc($query)) {
		return $row["para_value"];
	}
}

function tableExists($tableName, $database = "") {
	global $connection;
	$tableName = trim($tableName);
	if($tableName == "") {
		return false;
	}
	if($database == "") {
		$sql = "SHOW TABLES LIKE '$tableName'";
	} else {
		$sql = "SHOW TABLES FROM $database LIKE '$tableName'";
	}
	$query = mysql_query($sql, $connection);
	if(mysql_num_rows($query) > 0) {
		return true;
	} else {
		return false;
	}
}

function getTableName($sid, $FILE_NAME, $userid) {
	global $connection;
	$tableName = "data_wolves_".$sid;
	$sql = "select * from `data_src` where data_name='$tableName'";
	file_put_contents("c:/mylog.log", $sql."\r\n", FILE_APPEND);
	$query = mysql_query($sql, $connection);
	if(mysql_num_rows($query)) {
		return $tableName;
	} else {
		$arr = explode("-", $FILE_NAME);//海盟-通用4.21水洗产品_27_1
		file_put_contents("c:/mylog.log", $arr."\r\n", FILE_APPEND);
		$res = createTable($arr[0], $tableName, $userid);
		return $res;
	}
}

function createTable($FILE_NAME, $tableName, $userid) {
	global $connection;
	$sql = "CREATE TABLE ".$tableName." (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `mark` char(5) NOT NULL COMMENT '标记',
  `gb_code` varchar(20) NOT NULL COMMENT '国标码',
  `brand` char(10) NOT NULL COMMENT '品牌',
  `item` varchar(11) NOT NULL COMMENT '货号',
  `name` char(10) NOT NULL COMMENT '品名',
  `grade` char(6) NOT NULL COMMENT '等级',
  `certificate_of_conformity` char(3) NOT NULL COMMENT '合格证号',
  `safety_category` char(20) NOT NULL COMMENT '安全类别',
  `color` char(10) NOT NULL COMMENT '颜色',
  `color_number` char(3) NOT NULL COMMENT '色号',
  `color_name` char(6) NOT NULL COMMENT '色名',
  `size` char(20) NOT NULL COMMENT '尺码',
  `no_type` char(20) NOT NULL COMMENT '号型',
  `standard` char(20) NOT NULL COMMENT '执行标准',
  `ingredients_a` varchar(50) NOT NULL COMMENT '成份一',
  `ingredients_two` varchar(50) NOT NULL COMMENT '成份二',
  `ingredients_three` varchar(50) NOT NULL COMMENT '成份三',
  `ingredients_four` varchar(50) NOT NULL COMMENT '成份四',
  `ingredients_five` varchar(50) NOT NULL COMMENT '成份五',
  `ingredients_six` varchar(50) NOT NULL COMMENT '成份六',
  `ingredients_seven` varchar(50) NOT NULL COMMENT '成份七',
  `ingredients_eight` varchar(50) NOT NULL COMMENT '成份八',
  `price` int(4) NOT NULL COMMENT '价格',
  `price_adjustment` int(4) NOT NULL COMMENT '调价',
  `price_adjustment_no` int(3) NOT NULL COMMENT '调价合格证号',
  `product_code` char(17) NOT NULL COMMENT '产品码',
  `gb_unique_code` char(12) NOT NULL COMMENT '国标唯一码',
  `plus_password` varchar(254) NOT NULL COMMENT '加密码',
  `verification_code` char(6) NOT NULL COMMENT '验证码',
  `quantity` int(1) NOT NULL COMMENT '数量',
  `serial_number` char(5) NOT NULL COMMENT '序列号',
  `sequence_number` int(10) NOT NULL COMMENT '顺序号',
  `size_1` char(6) NOT NULL COMMENT '尺码1',
  `size_2` char(6) NOT NULL COMMENT '尺码2',
  `size_3` char(6) NOT NULL COMMENT '尺码3',
  `size_4` varchar(10) NOT NULL COMMENT '尺码4',
  `size_5` char(11) NOT NULL COMMENT '尺码5',
  `size_6` char(6) NOT NULL COMMENT '尺码6',
  `custom_1` varchar(20) NOT NULL COMMENT '自定义1',
  `custom_2` varchar(20) NOT NULL COMMENT '自定义2',
  `custom_3` varchar(20) NOT NULL COMMENT '自定义3',
  `custom_4` varchar(40) NOT NULL COMMENT '自定义4',
  `custom_5` varchar(20) NOT NULL COMMENT '自定义5',
  `custom_6` varchar(23) NOT NULL COMMENT '自定义6',
  `custom_7` varchar(20) NOT NULL COMMENT '自定义7',
  `custom_8` varchar(20) NOT NULL COMMENT '自定义8',
  `custom_9` varchar(20) NOT NULL COMMENT '自定义9',
  `custom_10` varchar(20) NOT NULL COMMENT '自定义10',
  `discount_type` varchar(15) NOT NULL COMMENT '折扣类型',
  `category` char(10) NOT NULL COMMENT '类别',
  `check_code` char(6) NOT NULL COMMENT '校检码',
  `status` char(1) NOT NULL DEFAULT '0' COMMENT '状态:0未推送,1-已推送',
  PRIMARY KEY (`id`),
  KEY `product_code` (`product_code`),
  KEY `check_code` (`check_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='$FILE_NAME' AUTO_INCREMENT=1 ;";
	mysql_query($sql, $connection);
file_put_contents("c:/mylog.log", $sql."\r\n", FILE_APPEND);
	if(mysql_errno() == 0) {
		$sql = "INSERT INTO `data_src` (`data_name`, `FILE_NAME`, `status`, `account`, `create_time`) VALUES ('$tableName', '$FILE_NAME', '3', '$userid', '".date("Y-m-d H:i:s",time())."');";
		mysql_query($sql, $connection);
		return $tableName;
	} else {
		return "";
	}
}

function Message($title, $content) {
	if($title == "提示") {
		$status = 'center info';
	} else if($title == "警告") {
		$status = 'center warning';
	} else {
		$status = 'center info';
	}
	$message = '<table class="MessageBox" align="center" width="500">
				<tr class="head">
					<td class="left"></td>
					<td class="center">
						<div class="title">'.$title.'</div>
					</td>
					<td class="right"></td>
				</tr>
				<tr class="msg">
					<td class="left"></td>
					<td class="'.$status.'">
						<div class="msg-content">'.$content.'</div>
					</td>
					<td class="right"></td>
				</tr>
				<tr class="foot">
					<td class="left"></td>
					<td class="center"></td>
					<td class="right"></td>
				</tr>
			</table>';
	return $message;
}

function checkStr($str) {
	$output = '';
	$a = ereg('['.chr(0xa1).'-'.chr(0xff).']', $str);
	$b = ereg('[0-9]', $str);
	$c = ereg('[a-zA-Z]', $str);
	if($a && $b && $c) {
		$output = '汉字数字英文的混合字符串';
	} else if($a && $b && !$c) {
		$output = '汉字数字的混合字符串';
	} else if($a && !$b && $c){
		$output = '汉字英文的混合字符串';
	} else if(!$a && $b && $c){
		$output = '数字英文的混合字符串';
	} else if($a && !$b && !$c) {
		$output = '纯汉字';
	} else if(!$a && $b && !$c) {
		$output = '纯数字';
	} else if(!$a && !$b && $c) {
		$output = '纯英文';
	}
	return $output;
}

function http_post_data($url, $data) {
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
}

function object_array($array) {
	if(is_object($array)) {
		$array = (array)$array;
	}
	if(is_array($array)) {
		foreach($array as $key=>$value) {
			$array[$key] = object_array($value);
		}
	}
	return $array;
}

function getfirstchar($s0) {
	$fchar = ord($s0{0});
	if($fchar >= ord("A") and $fchar <= ord("z"))
		return strtoupper($s0{0});
	$s1 = iconv("UTF-8", "gb2312", $s0);
	$s2 = iconv("gb2312", "UTF-8", $s1);
	if($s2 == $s0) {
		$s = $s1;
	} else {
		$s = $s0;
	}
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	if($asc >= -20319 and $asc <= -20284)
		return "A";
	if($asc >= -20283 and $asc <= -19776)
		return "B";
	if($asc >= -19775 and $asc <= -19219)
		return "C";
	if($asc >= -19218 and $asc <= -18711)
		return "D";
	if($asc >= -18710 and $asc <= -18527)
		return "E";
	if($asc >= -18526 and $asc <= -18240)
		return "F";
	if($asc >= -18239 and $asc <= -17923)
		return "G";
	if($asc >= -17922 and $asc <= -17418)
		return "H";
	if($asc >= -17417 and $asc <= -16475)
		return "J";
	if($asc >= -16474 and $asc <= -16213)
		return "K";
	if($asc >= -16212 and $asc <= -15641)
		return "L";
	if($asc >= -15640 and $asc <= -15166)
		return "M";
	if($asc >= -15165 and $asc <= -14923)
		return "N";
	if($asc >= -14922 and $asc <= -14915)
		return "O";
	if($asc >= -14914 and $asc <= -14631)
		return "P";
	if($asc >= -14630 and $asc <= -14150)
		return "Q";
	if($asc >= -14149 and $asc <= -14091)
		return "R";
	if($asc >= -14090 and $asc <= -13319)
		return "S";
	if($asc >= -13318 and $asc <= -12839)
		return "T";
	if($asc >= -12838 and $asc <= -12557)
		return "W";
	if($asc >= -12556 and $asc <= -11848)
		return "X";
	if($asc >= -11847 and $asc <= -11056)
		return "Y";
	if($asc >= -11055 and $asc <= -10247)
		return "Z";
	return null;
}

//返回首字母
function pinyin1($zh) {
	$ret = "";
	$s1 = iconv("UTF-8", "gb2312", $zh);
	$s2 = iconv("gb2312", "UTF-8", $s1);
	if($s2 == $zh) {
		$zh = $s1;
	}

	for($i = 0; $i < strlen($zh); $i++) {
		$s1 = substr($zh,$i,1);
		$p = ord($s1);
		if($p > 160) {
			$s2 = substr($zh, $i++, 2);
			$ret .= getfirstchar($s2);
		} else {
			$ret .= $s1;
		}
	}
	return $ret;
}

function getISP($ip) {
	if($ip == "") {
		return "";
	}
	if($ip == "127.0.0.1") {
		return "本机访问";
	}
	if(isInternalIP($ip) == 1) {
		return "局域网访问";
	}
	$url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=".$ip;
	$res = https_request($url, "");
	$arr = json_decode($res, true);
	return $arr["country"]."-".$arr["province"]."-".$arr["city"]."-".$arr["isp"];
}

function isInternalIP($ip) {
	$ip = ip2long($ip);
	$net_a = ip2long('10.255.255.255') >> 24; //A类网预留ip的网络地址
	$net_b = ip2long('172.31.255.255') >> 20; //B类网预留ip的网络地址
	$net_c = ip2long('192.168.255.255') >> 16; //C类网预留ip的网络地址
	return $ip >> 24 === $net_a || $ip >> 20 === $net_b || $ip >> 16 === $net_c;
}

function page_bar($current_start_item, $total_items, $page_size=10, $var_name="start",$script_href=null,$direct_print=false)
{
   if($current_start_item<0 || $current_start_item>$total_items)
      $current_start_item=0;

   if($script_href == null)
      $script_href = $_SERVER['PHP_SELF'];
   if($_SERVER['QUERY_STRING'] != "")
      $script_href .= "?".$_SERVER['QUERY_STRING'];
   $script_href = preg_replace("/^(.+)(\?|&)TOTAL_ITEMS=[^&]+&?(.*)$/i", "\$1\$2\$3", $script_href);
   $script_href = preg_replace("/^(.+)(\?|&)PAGE_SIZE=[^&]+&?(.*)$/i", "\$1\$2\$3", $script_href);
   $script_href = preg_replace("/^(.+)(\?|&)".$var_name."=[^&]+&?(.*)$/i", "\$1\$2\$3", $script_href);
   if(substr($script_href,-1) == "&" || substr($script_href,-1) == "?")
      $script_href = substr($script_href,0,-1);
   $hyphen = (strstr($script_href, "?")===FALSE)? "?" : "&";

   $num_pages = ceil($total_items/$page_size);
   $cur_page=ceil($current_start_item/$page_size)+1;

   $result_str.="<script>function goto_page(){var page_no=parseInt(document.getElementById('page_no').value);if(isNaN(page_no)||page_no<1||page_no>".$num_pages."){alert(\"".sprintf(_p("页数必须为1-%s"), $num_pages)."\");return;}window.location=\"".$script_href.$hyphen.$var_name."=\"+(page_no-1)*".$page_size."+\"&TOTAL_ITEMS=".$total_items."&PAGE_SIZE=".$page_size."\";} function input_page_no(){if(event.keyCode==13) goto_page();if(event.keyCode<47||event.keyCode>57) event.returnValue=false;}</script>";

   $result_str.="<div id=\"pageArea\" class=\"pageArea\">\n".sprintf(_p("第%s页"), "<span id=\"pageNumber\" class=\"pageNumber\">".$cur_page."/".$num_pages."</span>");
   if($cur_page<=1)
      $result_str.="<a href=\"javascript:;\" id=\"pageFirst\" class=\"pageFirstDisable\" title=\""._p("首页")."\"></a>
  <a href=\"javascript:;\" id=\"pagePrevious\" class=\"pagePreviousDisable\" title=\""._p("上一页")."\"></a>";
   else
      $result_str.="<a href=\"".$script_href.$hyphen.$var_name."=0&TOTAL_ITEMS=".$total_items."&PAGE_SIZE=".$page_size."\" id=\"pageFirst\" class=\"pageFirst\" title=\""._p("首页")."\"></a>
  <a href=\"".$script_href.$hyphen.$var_name."=".($current_start_item-$page_size)."&TOTAL_ITEMS=".$total_items."&PAGE_SIZE=".$page_size."\" id=\"pagePrevious\" class=\"pagePrevious\" title=\""._p("上一页")."\"></a>";

   if($cur_page>=$num_pages)
      $result_str.="<a href=\"javascript:;\" id=\"pageNext\" class=\"pageNextDisable\" title=\""._p("下一页")."\"></a>
  <a href=\"javascript:;\" id=\"pageLast\" class=\"pageLastDisable\" title=\""._p("末页")."\"></a>";
   else
      $result_str.="<a href=\"".$script_href.$hyphen.$var_name."=".($current_start_item+$page_size)."&TOTAL_ITEMS=".$total_items."&PAGE_SIZE=".$page_size."\" id=\"pageNext\" class=\"pageNext\" title=\""._p("下一页")."\"></a>
  <a href=\"".$script_href.$hyphen.$var_name."=".(($total_items%$page_size>0)?($total_items-$total_items%$page_size):$total_items-$page_size)."&TOTAL_ITEMS=".$total_items."&PAGE_SIZE=".$page_size."\" id=\"pageLast\" class=\"pageLast\" title=\""._p("末页")."\"></a>";

   $result_str.=sprintf(_p("转到 第 %s 页 "), "<input type=\"text\" size=\"3\" class=\"SmallInput\" name=\"page_no\" id=\"page_no\" onkeypress=\"input_page_no()\" style='text-align:center;'>")."<a href=\"javascript:goto_page();\" id=\"pageGoto\" class=\"pageGoto\" title=\""._p("转到")."\"></a>";
   $result_str.="</div>";
   
   if($direct_print)
      echo $result_str;
   else
      return $result_str;
}

function _p($str) {
	return $str;
}
?>