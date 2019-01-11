<?php
//session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
$connection = getConnection();
$sid=$_POST['bb'];//开票类型
$sname=$_POST['sname'];//客户名称
$mark=$_POST['mark'];//订单摘要
$yu_number=$_POST['yu_number'];//运输单号
$c_name=$_POST['c_name'];//业务员
$yu_type=$_POST['yu_type'];//运输方式
$kuaidi=$_POST['kuaidi'];//快递公司
$bname=$_POST['bname'];//收货人
$l_man=$_POST['l_man'];//联系人
$time=time();
/*$h_number=$_POST['h_number'];//合同号*/
$s_time=$_POST['s_time'];//送货日期
$s_number=$_POST['s_number'];//收货方电话
$jk1[]=array();
$jk2[]=array();
$jk3[]=array();
$jk4[]=array();
$jk5[]=array();
$jk6[]=array();
$jk7[]=array();
$jk8[]=array();
$jk1=$_POST['jk1'];//订单号
$jk2=$_POST['jk2'];//货号
$jk3=$_POST['jk3'];//物料名称
$jk4=$_POST['jk4'];//物料id
$jk5=$_POST['jk5'];//数量
$jk6=$_POST['jk6'];//单价
$jk7=$_POST['jk7'];//总金额
$jk8=$_POST['jk8'];//备注
$jk21=$_POST['jk21'];//单位
$jk9=$_POST['jk9'];//总计
//print_r($jk2);
$sql5 = "INSERT INTO so_huo (sname,cname,bname,s_time,mark,yu_type,create_time,b_type,s_sun,s_number,kuaidi_n,kuaidi) VALUES ('".$sname."','".$c_name."','".$bname."','".$s_time."','".$mark."','".$yu_type."','".$time."',".$sid.",".$jk9.",'".$s_number."','".$yu_number."','".$kuaidi."')";
	 //echo $sql5;
	 $tyu=mysql_query($sql5, $connection);   
$sql1 = "SELECT id FROM `so_huo` where create_time='".$time."'";
$query1 = mysql_query($sql1, $connection);
while($row3 = mysql_fetch_assoc($query1)) {
	$id=$row3['id'];
}
for($i=0;$i<count($jk1);$i++){
	$bn=$jk5[$i]*$jk6[$i];
$sql5 = "INSERT INTO so_huo_de (eid,sid,item,sname,snumber,price,smark,s_sun,sname_id,unit) VALUES (".$id.",'".$jk1[$i]."','".$jk2[$i]."','".$jk3[$i]."','".$jk5[$i]."','".$jk6[$i]."','".$jk8[$i]."','".$bn."','".$jk4[$i]."','".$jk21[$i]."')";
	 //echo $sql5;
	 mysql_query($sql5, $connection);   
}

$sql45="update sheet1 set l_man='".$l_man."',l_number='".$s_number."' where name='".$sname."'";

 mysql_query($sql45, $connection);   


?>