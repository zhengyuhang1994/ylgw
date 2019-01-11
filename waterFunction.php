<?php
function materialArr() {
	$array = array(
		array("temp" => "01", "material" => "夹克洗唛(复合) 45*79mm"),
		array("temp" => "02", "material" => "羽绒服洗唛(复合) 50*100mm"),
		array("temp" => "03", "material" => "裤子洗唛(缎带) 35*100mm"),
		array("temp" => "04", "material" => "棉服洗唛(复合) 45*79mm"),
		array("temp" => "05", "material" => "T恤衬衫洗唛(缎带) 35*100mm")
	);
	return $array;
}

function materialOption($id) {
	$arr = materialArr();
	$select = '<option value="">选择…</option>';
	foreach($arr as $key => $value) {
		if($value['temp'] == $id) {
			$select .= '<option value="'.$value['temp'].'" selected>'.$value['material'].'</option>';
		} else {
			$select .= '<option value="'.$value['temp'].'">'.$value['material'].'</option>';
		}
	}
	return $select;
}

function materialOptionNG($id) {
	$arr = materialArr();
	$select = '<option value=\"\">选择…</option>';
	foreach($arr as $key => $value) {
		if($value['temp'] == $id) {
			$select .= '<option value=\"'.$value['temp'].'\" selected>'.$value['material'].'</option>';
		} else {
			$select .= '<option value=\"'.$value['temp'].'\">'.$value['material'].'</option>';
		}
	}
	return $select;
}

function material($temp) {
	$array = materialArr();

	$res = "";
	foreach($array as $k => $v) {
		if($v["temp"] == $temp) {
			$res = $v["material"];
		}
	}

	return $res;
}

function getOrdersCount($status, $supplier_code) {
	global $connection,$waterConn;
	$sql = "SELECT COUNT(*) FROM `data_src` WHERE `status`='$status' AND `supplier_code`='$supplier_code';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_row($query);
	return $row[0];
}

function getSupplierCodeByName($name) {
	global $connection,$waterConn;

	$sql = "SELECT `supplier_code` FROM `supplier` WHERE `internal_short` LIKE '%".$name."%' OR `abbreviation` LIKE '%".$name."%' ORDER BY id;";
	$query = mysql_query($sql, $waterConn);
	if(mysql_num_rows($query)) {
		$row = mysql_fetch_assoc($query);
		return $row["supplier_code"];
	} else {
		return '';
	}
}

function getMarkItemCount($id, $item) {
	global $connection,$waterConn;
	return getWaterMarkItemCountNG($id, $item);
}

function getWaterMarkItemCountNGDW($id, $item) {
	global $connection,$waterConn,$db;

	if($item != '') {
		$sqlStr = " AND item='$item'";
	}

	$cot = 0;
	$sql = "SELECT DISTINCT(item) as str FROM ".$db.".".getDataNameDW($id)." WHERE LENGTH(item) >= 6 $sqlStr ORDER BY item DESC";
	file_put_contents("c:/sb.log", 'sss:'.$sql."\r\n", FILE_APPEND);
	$query = mysql_query($sql, $waterConn);
	while($row = mysql_fetch_assoc($query)) {
		$cot += getWaterMarkItemCountDW(getDataNameDW($id), $row['str']);
	}
	return $cot;
}

function getWaterMarkItemCountDW($data_name, $item) {
	global $connection,$waterConn,$db;
	$sql = "SELECT COUNT(*) FROM ".$db.".".$data_name." WHERE `item`='$item'";
	file_put_contents("c:/sb.log", 'sss:'.$sql."\r\n", FILE_APPEND);
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_row($query);
	return $row[0];
}

function getDataNameDW($id) {
	global $connection,$waterConn,$db;

	$sql = "SELECT `data_name` FROM ".$db.".`data_src` WHERE `id`='$id';";
	file_put_contents("c:/ss.log", 'sss:'.$sql."\r\n", FILE_APPEND);
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_assoc($query);
	return $row["data_name"];
}

function getWaterMarkItemCountNG($id, $item) {
	global $connection,$waterConn;

	if($item != '') {
		$sqlStr = " AND item='$item'";
	}

	$cot = 0;
	$sql = "SELECT DISTINCT(item) as str FROM ".getDataName($id)." WHERE LENGTH(item) >= 12 AND NOT FIND_IN_SET(item,'".areBillingById($id)."') $sqlStr ORDER BY item DESC;";
	//file_put_contents("c:/".date("Y-m-d H").".log", $sql."\r\n", FILE_APPEND);
	$query = mysql_query($sql, $waterConn);
	while($row = mysql_fetch_assoc($query)) {
		$para_value = getParameters('SELECT_'.$id.'_'.$row['str']);
		if($para_value == 'ELEMENT') {
			$pv1 = getParameters('ChangeElement'.$id.'_'.$row['str']);
			if($pv1 == 'Y') {
				$cot += getParameters('ChangeElementNumber'.$id.'_'.$row['str']) + getWaterMarkItemCount(getDataName($id), $row['str']);
			} else {
				$cot += getWaterMarkItemCount(getDataName($id), $row['str']);
			}
		} else if($para_value == 'NUMBER') {
			$pv3 = getParameters('ModifyNumber'.$id.'_'.$row['str']);
			$cot += $pv3 == '' ? getWaterMarkItemCount(getDataName($id), $row['str']) : $pv3;
		} else {
			$cot += getWaterMarkItemCount(getDataName($id), $row['str']);
		}
	}
	return $cot;
}

function areBillingById($id) {
	global $connection,$waterConn;

	$sql = "SELECT `t_item` FROM `are_billing` WHERE `status`=0 AND `t_res`='Y' AND `t_id`='$id';";
	$query = mysql_query($sql, $waterConn);
	while($row = mysql_fetch_row($query)) {
		$str .= $row[0].',';
	}
	return $str;
}

function areBillingStr($s, $t) {
	global $connection,$waterConn;
	$str = '';
	if($s == 'CERT') {
		$sql = "SELECT DISTINCT(`t_id`) FROM `are_billing` WHERE `status`='1' AND `t_res`='Y';";
	} else if($s == 'MARK') {
		if($t == 'ID') {
			$sql = "SELECT DISTINCT(`t_id`) FROM `are_billing` WHERE `status`='0' AND `t_res`='Y';";
		} else if($t == 'ITEM') {
			$sql = "SELECT DISTINCT(`t_item`) FROM `are_billing` WHERE `status`='0' AND `t_res`='Y';";
		}
	}
	$query = mysql_query($sql, $waterConn);
	while($row = mysql_fetch_row($query)) {
		$str .= $row[0].',';
	}
	return $str;
}

function getIdByExpress($status, $express_date1, $express_date2, $type) {
	global $connection,$waterConn;

	$data_id = "";
	if($type == "MIN") {
		$sql = "SELECT `data_id` FROM `express` WHERE `status`='$status' AND `express_date` >= '$express_date1'";
	} else if($type == "MAX") {
		$sql = "SELECT `data_id` FROM `express` WHERE `status`='$status' AND `express_date` <= '$express_date2'";
	} else if($type == "ALL") {
		$sql = "SELECT `data_id` FROM `express` WHERE `status`='$status' AND `express_date` >= '$express_date1' AND `express_date` <= '$express_date2'";
	}
	$query = mysql_query($sql, $waterConn);
	while($row = mysql_fetch_assoc($query)) {
		$data_id .= $row["data_id"].",";
	}
	return $data_id;
}

function getItemStr($item) {
	global $connection,$waterConn;

	$item_id = "";
	$sql = "SELECT t_id FROM `sept_cert_water` WHERE product_code_or_item LIKE '%".$item."%' AND status='W' AND flag=1";
	$query = mysql_query($sql, $waterConn);
	while($row = mysql_fetch_assoc($query)) {
		$item_id .= $row["t_id"].",";
	}
	return $item_id;
}

/*function getParameters($para_name) {
	global $connection,$waterConn;

	$sql = "SELECT `para_value` FROM `parameters` WHERE `para_name`='$para_name';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_assoc($query);
	return $row['para_value'];
}*/

function saveParameters($para_name, $para_value) {
	global $connection,$waterConn;

	$sql = "SELECT `para_value` FROM `parameters` WHERE `para_name`='$para_name';";
	$query = mysql_query($sql, $waterConn);
	if(mysql_num_rows($query)) {
		$sqlA = "UPDATE `parameters` SET `para_value`='$para_value' WHERE `para_name`='$para_name';";
	} else {
		$sqlA = "INSERT INTO `parameters` (`para_name`, `para_value`) VALUES ('$para_name', '$para_value');";
	}
	$queryA = mysql_query($sqlA, $waterConn);
	if(mysql_errno() == 0) {
		return true;
	} else {
		return false;
	}
}

function getCertTotalPrice($id) {
	global $connection,$waterConn;

	$tableName = getDataName($id);

//	$sqlC = "SHOW TABLE STATUS LIKE '$tableName'";
//	$queryC = mysql_query($sqlC, $waterConn);
//	$rowC = mysql_fetch_assoc($queryC);
//	$rows = $rowC["Rows"];

	$sql = "SELECT * FROM $tableName WHERE LENGTH(item) >= 12";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_num_rows($query);

	return $row * getUrl('cert_price', '0');
}

function getExpress($id, $status) {
	global $connection,$waterConn;

	$sql = "SELECT `express_date`,`pick` FROM `express` WHERE `data_id`='$id' AND `status`='$status';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_assoc($query);
	return $row;
}

function pick($pick) {
	if($pick == '0') {
		return '快递';
	} else if($pick == '1') {
		return '物流';
	}
}

function getSupplierContacts($supplier_code) {
	global $conn,$connection,$waterConn;

	$sql = "SELECT `contacts` FROM `supplier` WHERE `supplier_code`='$supplier_code';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_row($query);
	return $row[0];
}

function waterMarkItems($id) {
	global $conn,$connection,$waterConn;

	$cot = 0;
	$sql = "SELECT COUNT(DISTINCT(item)) FROM ".getDataName($id)." WHERE LENGTH(item) >= 12 AND NOT FIND_IN_SET(item,'".areBillingById($id)."') ORDER BY item DESC;";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_row($query);
	return $row[0];
}

function certSupplierOption($supplier) {
	global $conn,$connection,$waterConn;

	$code = '';
	$sql = "SELECT certCode FROM cert_assoc_customer ORDER BY id;";
	$query = mssql_query($sql);
	while($row = mssql_fetch_assoc($query)) {
		$code .= $row['certCode'].',';
	}

	$select = '<option value="">选择…</option>';
	$sql = "SELECT `internal_short`,`supplier_code` FROM `supplier` WHERE FIND_IN_SET('SeptCert', `owned_enterprises`) AND FIND_IN_SET(`supplier_code`, '".substr($code,0,-1)."');";
	file_put_contents("c:/SeptCert.log", $sql."\r\n", FILE_APPEND);
	$query = mysql_query($sql, $waterConn);
	while($row = mysql_fetch_assoc($query)) {
		if($row['supplier_code'] == $supplier) {
			$select .= '<option value="'.$row['supplier_code'].'" selected>'.$row['internal_short'].'</option>';
		} else {
			$select .= '<option value="'.$row['supplier_code'].'">'.$row['internal_short'].'</option>';
		}
	}
	return $select;
}

function supplierOption($supplier) {
	global $conn,$connection,$waterConn;

	$code = '';
	$sql = "SELECT waterCode FROM assoc_customer ORDER BY id;";
	$query = mssql_query($sql);
	while($row = mssql_fetch_assoc($query)) {
		$code .= $row['waterCode'].',';
	}

	$select = '<option value="">选择…</option>';
	$sql = "SELECT `internal_short`,`supplier_code` FROM `supplier` WHERE FIND_IN_SET('SeptMark', `owned_enterprises`) AND FIND_IN_SET(`supplier_code`, '".substr($code,0,-1)."');";
	file_put_contents("c:/SeptMark.log", $sql."\r\n", FILE_APPEND);
	$query = mysql_query($sql, $waterConn);
	while($row = mysql_fetch_assoc($query)) {
		if($row['supplier_code'] == $supplier) {
			$select .= '<option value="'.$row['supplier_code'].'" selected>'.$row['internal_short'].'</option>';
		} else {
			$select .= '<option value="'.$row['supplier_code'].'">'.$row['internal_short'].'</option>';
		}
	}
	return $select;
}

function getSwitchTemplate($id, $item, $tmp) {
	global $connection,$waterConn;

	$aaa = switchItemTitle($id, $item);
	if ($aaa != '') {
		return $aaa;
	} else if (getParameters('OPERATION_'.$id) == '') {
		return $tmp;
	} else {
		return getParameters('OPERATION_'.$id);
	}
}

function getTotalPrice($id) {
	global $connection,$waterConn;

	$sql = "SELECT `para_value` FROM `parameters` WHERE `para_name`='tax_supplier_code';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_assoc($query);
	$tax_supplier_code = $row['para_value'];

	$price = 0;
	$sql = "SELECT * FROM `data_src` WHERE FIND_IN_SET(`supplier_code`,'$tax_supplier_code') AND id='$id';";
	$query = mysql_query($sql, $waterConn);
	if(mysql_num_rows($query)) {//含税价格
		$data_name = getDataName($id);
		$arr = explode("_", $data_name);
		$sql = "SELECT DISTINCT(item) as str FROM $data_name WHERE LENGTH(item) >= 12 AND NOT FIND_IN_SET(item,'".areBillingById($id)."') ORDER BY item DESC;";
		$query = mysql_query($sql, $waterConn);
		while($row = mysql_fetch_assoc($query)) {
			$item = $row['str'];			

			$sqlA = "SELECT * FROM `water_mark_slit` WHERE `t_id`='$id' AND FIND_IN_SET('$item', `item_str`);";
			$queryA = mysql_query($sqlA, $waterConn);
			if(mysql_num_rows($queryA)) {
				$price += reserved2(getWaterMarkItemCountNG($id, $item) * getUrl("tax_temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_slit_price", "1"));
			} else {
				$price += reserved2(getWaterMarkItemCountNG($id, $item) * getUrl("tax_temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_no_slit_price", "1"));
			}
		}
	} else {//不含税价格
		$data_name = getDataName($id);
		$arr = explode("_", $data_name);
		$sql = "SELECT DISTINCT(item) as str FROM $data_name WHERE LENGTH(item) >= 12 AND NOT FIND_IN_SET(item,'".areBillingById($id)."') ORDER BY item DESC;";
		$query = mysql_query($sql, $waterConn);
		while($row = mysql_fetch_assoc($query)) {
			$item = $row['str'];

			$sqlA = "SELECT * FROM `water_mark_slit` WHERE `t_id`='$id' AND FIND_IN_SET('$item', `item_str`);";
			$queryA = mysql_query($sqlA, $waterConn);
			if(mysql_num_rows($queryA)) {
				$price += reserved2(getWaterMarkItemCountNG($id, $item) * getUrl("temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_slit_price", "1"));
			} else {
				$price += reserved2(getWaterMarkItemCountNG($id, $item) * getUrl("temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_no_slit_price", "1"));
			}
		}
	}
	return reserved2($price);
}

function getSupplierName($supplier_code) {
	global $connection,$waterConn;
	$sql = "SELECT `full_name_supplier` FROM `supplier` WHERE supplier_code='$supplier_code';";
	$query = mysql_query($sql, $waterConn);
	if(mysql_num_rows($query)) {
		$row = mysql_fetch_assoc($query);
		return $row["full_name_supplier"];
	} else {
		return $supplier_code;
	}
}

/*function getOrderName($order_status) {
	if($order_status == 0) {
		$order_name = "下单";
	} else if($order_status == 1) {
		$order_name = "印刷";
	} else if($order_status == 2) {
		$order_name = "打印";
	} else if($order_status == 3) {
		$order_name = "分切";
	} else if($order_status == 4) {
		$order_name = "发货";
	} else if($order_status == 5) {
		$order_name = "完成";
	} else if($order_status == 6) {
		$order_name = "送货";
	}
	return $order_name;
}
*/
function getTemplateData($i) {
	if($i == "01") {
		return "一";
	} else if($i == "02") {
		return "二";
	} else if($i == "03") {
		return "三";
	} else if($i == "04") {
		return "四";
	} else if($i == "05") {
		return "五";
	}
}

function switchItemTitle($id, $item) {
	global $connection,$waterConn;
	$para_name = 'OPERATION_'.$id.'_'.$item;
	$sql = "SELECT `para_value` FROM `parameters` WHERE `para_name`='$para_name';";
	$query = mysql_query($sql, $waterConn);
	if(mysql_num_rows($query)) {
		$row = mysql_fetch_assoc($query);
		return $row['para_value'];
	} else {
		$arr = explode("_", getDataName($id));
		return $arr[2];
	}
}

function getItemListShowAndHide($id, $data_name) {
	global $connection,$waterConn;

	$cot = 0;
	$str = "";
	$sql = "SELECT DISTINCT(item) as str FROM $data_name WHERE LENGTH(item) >= 12 ".$sql_str." ORDER BY item DESC;";
	$query = mysql_query($sql, $waterConn);
	while($row = mysql_fetch_assoc($query)) {
		$cot++;
		$str .= "<span class='spanItem'><a title='".getTemplateData(switchItemTitle($id, $row["str"]))."'>".$row["str"]."</a></span>&nbsp;&nbsp;<span class='spanCot'>".getWaterMarkItemCount($data_name, $row["str"])."</span>&nbsp;&nbsp;<span class='spanCot'>".getWaterMarkItemPrice($id, $row["str"])."</span>&nbsp;&nbsp;<span class='spanSlit'>".getItemPrice($id, $row["str"])."</span>&nbsp;&nbsp;<span class='spanPlate'>".getPrintIdByOrderInfo($id, $row["str"])."</span><br/>";
		if($cot <= 3) {
			$show .= "<span class='spanItem'><a title='".getTemplateData(switchItemTitle($id, $row["str"]))."'>".$row["str"]."</a></span>&nbsp;&nbsp;<span class='spanCot'>".getWaterMarkItemCount($data_name, $row["str"])."</span>&nbsp;&nbsp;<span class='spanCot'>".getWaterMarkItemPrice($id, $row["str"])."</span>&nbsp;&nbsp;<span class='spanSlit'>".getItemPrice($id, $row["str"])."</span>&nbsp;&nbsp;<span class='spanPlate'>".getPrintIdByOrderInfo($id, $row["str"])."</span><br/>";
		} else {
			$showAll .= "<span class='spanItem'><a title='".getTemplateData(switchItemTitle($id, $row["str"]))."'>".$row["str"]."</a></span>&nbsp;&nbsp;<span class='spanCot'>".getWaterMarkItemCount($data_name, $row["str"])."</span>&nbsp;&nbsp;<span class='spanCot'>".getWaterMarkItemPrice($id, $row["str"])."</span>&nbsp;&nbsp;<span class='spanSlit'>".getItemPrice($id, $row["str"])."</span>&nbsp;&nbsp;<span class='spanPlate'>".getPrintIdByOrderInfo($id, $row["str"])."</span><br/>";
		}
	}
	return array("cot" => $cot, "str" => $str, "show" => $show, "showAll" => $showAll);
}

function getWaterMarkItemCount($data_name, $item) {
	global $connection,$waterConn;
	$sql = "SELECT COUNT(*) FROM $data_name WHERE `item`='$item'";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_row($query);
	return $row[0];
}

function getPrintIdByOrderInfo($id, $item) {
	global $connection,$waterConn;
	$sql = "SELECT `print_id` FROM `water_mark_plate` WHERE `t_id`='$id' AND FIND_IN_SET('$item', `item_str`);";
	$query = mysql_query($sql, $waterConn);
	if(mysql_num_rows($query)) {
		$row = mysql_fetch_assoc($query);
		return getPrintName($row['print_id']);
	} else {
		return '&nbsp;';
	}
}

function getPrintName($id) {
	global $connection,$waterConn;
	$sql = "SELECT `print_name` FROM `print_supplier` WHERE `id`='$id';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_row($query);
	return $row[0];
}

function getItemPriceNG($id, $item) {
	global $connection,$waterConn;

	$sql = "SELECT `supplier_code` FROM `data_src` WHERE `status`=2 AND `id`='$id'";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_row($query);
	$supplier_code = $row[0];

	$taxArr = explode(',', getUrl('tax_supplier_code', '1'));

	$tax = '';
	if(in_array($supplier_code, $taxArr)) {
		$tax = 'tax_';
	}

	$arr = explode("_", getDataName($id));
	$sql = "SELECT * FROM `water_mark_slit` WHERE `t_id`='$id' AND FIND_IN_SET('$item', `item_str`);";
	$query = mysql_query($sql, $waterConn);
	if(mysql_num_rows($query)) {
		return getUrl($tax."temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_slit_price", "1");
	} else {
		return getUrl($tax."temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_no_slit_price", "1");
	}
}

function getWaterMarkItemPrice($id, $item) {
	global $connection,$waterConn;

	$sql = "SELECT `para_value` FROM `parameters` WHERE `para_name`='tax_supplier_code';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_assoc($query);
	$tax_supplier_code = $row['para_value'];

	$sql = "SELECT * FROM `data_src` WHERE FIND_IN_SET(`supplier_code`,'$tax_supplier_code') AND id='$id';";
	$query = mysql_query($sql, $waterConn);
	if(mysql_num_rows($query)) {
		$arr = explode("_", getDataName($id));
		$sql = "SELECT * FROM `water_mark_slit` WHERE `t_id`='$id' AND FIND_IN_SET('$item', `item_str`);";
		$query = mysql_query($sql, $waterConn);
		if(mysql_num_rows($query)) {
			return getUrl("tax_temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_slit_price", "1");
			return "<font color='red'>分切</font>:".reserved2(getProductCodeCount($id, $item) * getUrl("tax_temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_slit_price", "1"));
		} else {
			return getUrl("tax_temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_no_slit_price", "1");
			return "不分切:".reserved2(getProductCodeCount($id, $item) * getUrl("tax_temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_no_slit_price", "1"));
		}
	} else {
		$arr = explode("_", getDataName($id));
		$sql = "SELECT * FROM `water_mark_slit` WHERE `t_id`='$id' AND FIND_IN_SET('$item', `item_str`);";
		$query = mysql_query($sql, $waterConn);
		if(mysql_num_rows($query)) {
			return getUrl("temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_slit_price", "1");
			return "<font color='red'>分切</font>:".reserved2(getProductCodeCount($id, $item) * getUrl("temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_slit_price", "1"));
		} else {
			return getUrl("temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_no_slit_price", "1");
			return "不分切:".reserved2(getProductCodeCount($id, $item) * getUrl("temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_no_slit_price", "1"));
		}
	}
}

function getItemPriceDW($para) {
	global $connection,$waterConn,$db;
	$sql = "SELECT `para_value` FROM ".$db.".`parameters` WHERE `para_name`='$para'";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_assoc($query);
	return $row['para_value'];
}

function getItemPrice($id, $item) {
	global $connection,$waterConn;

	$sql = "SELECT `para_value` FROM `parameters` WHERE `para_name`='tax_supplier_code';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_assoc($query);
	$tax_supplier_code = $row['para_value'];

	$sql = "SELECT * FROM `data_src` WHERE FIND_IN_SET(`supplier_code`,'$tax_supplier_code') AND id='$id';";
	$query = mysql_query($sql, $waterConn);
	if(mysql_num_rows($query)) {
		$arr = explode("_", getDataName($id));
		$sql = "SELECT * FROM `water_mark_slit` WHERE `t_id`='$id' AND FIND_IN_SET('$item', `item_str`);";
		$query = mysql_query($sql, $waterConn);
		if(mysql_num_rows($query)) {
			return "<font color='red'>分切</font>:".reserved2(getWaterMarkItemCountNG($id, $item) * getUrl("tax_temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_slit_price", "1"));
		} else {
			return "不分切:".reserved2(getWaterMarkItemCountNG($id, $item) * getUrl("tax_temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_no_slit_price", "1"));
		}
	} else {
		$arr = explode("_", getDataName($id));
		$sql = "SELECT * FROM `water_mark_slit` WHERE `t_id`='$id' AND FIND_IN_SET('$item', `item_str`);";
		$query = mysql_query($sql, $waterConn);
		if(mysql_num_rows($query)) {
			return "<font color='red'>分切</font>:".reserved2(getWaterMarkItemCountNG($id, $item) * getUrl("temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_slit_price", "1"));
		} else {
			return "不分切:".reserved2(getWaterMarkItemCountNG($id, $item) * getUrl("temp_".intval(getSwitchTemplate($id, $item, $arr[2]))."_no_slit_price", "1"));
		}
	}
}

/*function getDataName($id) {
	global $connection,$waterConn;

	$sql = "SELECT `data_name` FROM `data_src` WHERE `id`='$id';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_assoc($query);
	return $row["data_name"];
}
*/
/*function getProductCodeCount($id, $item) {
	global $connection,$waterConn;

	$sql = "SELECT count(*) FROM ".getDataName($id)." WHERE `item`='$item';";
	$query = mysql_query($sql, $waterConn);
	if($row=mysql_fetch_row($query)) {
		return $row[0];
	}
}*/

/*function getUrl($para_name, $status) {
	global $connection,$waterConn;
	$sql = "SELECT `para_value` FROM `parameters` WHERE `para_name`='$para_name' AND `status`='$status';";
	$query = mysql_query($sql, $waterConn);
	$row = mysql_fetch_assoc($query);
	return $row["para_value"];
}*/
?>