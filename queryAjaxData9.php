<?php
//session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
$connection = getConnection();
$sid=$_POST['value1'];
//$sid="15秋冬合格证配套";
$sql1 = "SELECT * FROM `materials` where type_id='WOLVES' order by id desc";
$query1 = mysql_query($sql1, $connection);
while ($row3 = mysql_fetch_assoc($query1)) {
	$ss[]=$row3['id'];
    $ss1[]=$row3['name'];
     $ss2[]=$row3['number'];
      $ss3[]=$row3['rid'];
}
$df="";
for($i=0;$i<count($ss);$i++){
	$gh=$ss[$i];
	$gh1=$ss1[$i];
	$gh2=$ss2[$i];
	$gh3=$ss3[$i];

if($gh3==0){
$df .='<div class=\"setdt\"><span style=\"display: inline-block;width: 8vw;text-align: right;\"><input type=\"checkbox\" name=\"bbs\"  value=\"'.$gh.'\"/></span><span style=\"display: inline-block;text-align: center;\">'.$gh1.'('.$gh2.')'.'</span></div>';
}else if($gh3==1){
$df .='<div class=\"setdt\"><span style=\"display: inline-block;width: 8vw;text-align: right;\"><input type=\"checkbox\" checked name=\"bbs\"  value=\"'.$gh.'\"/></span><span style=\"display: inline-block;text-align: center;\">'.$gh1.'('.$gh2.')'.'</span></div>';
}




}

//$dd1=json_encode($dd);
//print_r($ss);
//echo '{"sid":"'.$l_name.'", "name":"'.$s_name.'"}';
echo '{"wer":"'.$df.'"}';
?>