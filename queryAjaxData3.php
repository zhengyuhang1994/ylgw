<?php
//session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
function array_unset_tt($arr,$key){     
        //建立一个目标数组  
        $res = array();        
        foreach ($arr as $value) {           
           //查看有没有重复项  
             
           if(isset($res[$value[$key]])){  
                 //有：销毁  
                  
                 unset($value[$key]);  
                   
           }  
           else{   
                $res[$value[$key]] = $value; 
           }    
        }  
        return $res;  
    }  
$connection = getConnection();
$sid=$_POST['value1'];
//$sid="丰宇";

$sql1 = "SELECT count(*) as cor FROM `data_src` where FILE_NAME like '%".$sid."%' and order_status>=3 and id>27000";//624
$query1 = mysql_query($sql1, $connection);
while($row3 = mysql_fetch_assoc($query1)) {
	$scout=$row3['cor'];
}
$sql = "SELECT * FROM `data_src` where FILE_NAME like '%".$sid."%' and order_status>=3 and id>27000";
//echo $sql;
$query = mysql_query($sql, $connection);
if($scout>=1){
while($row = mysql_fetch_assoc($query)) {
	$tablename[]=$row['data_name'];
	$rid[]=$row['id'];
}
for($i=0;$i<count($tablename);$i++){
$sql = "select * from `".$tablename[$i]."`;";
$query = mysql_query($sql, $connection);
    $fg1=array();
	$fg2=array();
	$fg3=array();
	$fg4=array();
     $data=array();
	while($row4 = mysql_fetch_assoc($query)) {
	$fg1[]=$row4['item'];
	$fg2[]=$row4['price'];
	$fg3[]=$row4['name'];
	$fg4[]=$row4['id'];
	}
for($j=0;$j<count($fg1);$j++){

if($fg1[$j]=='1111111'){

}else{
	    $data[$j]['id']=$rid[$i];
		$data[$j]['item']=$fg1[$j];
		$data[$j]['price']=$fg2[$j];
        $data[$j]['ssf']=$fg1[$j].$rid[$i];
        $data[$j]['name']=$fg3[$j];
	}
}
//print_r($data);
$newArr = array_unset_tt($data,'ssf');
  $yu=array_values($newArr);
//print_r($yu);
$gh1[]=$tablename[$i];
$gh[]=$yu;
}
//print_r($gh1);

for($i=0;$i<count($gh);$i++){
	$yus=$gh[$i];
	$table=$gh1[$i];
for($k=0;$k<count($yus);$k++){
$item=$yus[$k]['item'];
$sql="select count(*) as cot from ".$table." where item='".$item."';";
$query = mysql_query($sql, $connection);
while($row = mysql_fetch_assoc($query)) {
$cc[$k]['ss']=$row['cot'];
}
}

//print_r($cc);
for($l=0;$l<count($yus);$l++){
$yus[$l]["count"]=$cc[$l]['ss'];
}



//print_r($yu);
$hj[]=$yus;
}
//print_r($hj);
$msg="";
$msg .='<div class="thclsbox">
				<div class="thcls tenvw">订单ID</div>
				<div class="thcls tenvw">货号</div>
			
				<div class="thcls sixvw">数量</div>
			    <div class="thcls sixvw">客户</div>
				<div class="thcls sixvw">状态</div>
				<div class="thcls sixvw">操作</div>
			</div>';
$sql = "select * from `parameters` where `para_name`='cert_price' and `status`=4";
$query = mysql_query($sql, $connection);
$row = mysql_fetch_assoc($query);
$cert_price = $row["para_value"];
for($k=0;$k<count($hj);$k++){
	$yui=$hj[$k];
	$cot=0;
for($i=0;$i<count($yui);$i++){
	 $id=$yui[$i]["id"];
          $item=$yui[$i]["item"];
        $count=$yui[$i]["count"];
        $price=$yui[$i]["price"];
        $name=$yui[$i]["name"];
$sql="select count(*) as cot from so_huo_de where sid='".$id."' and item='".$item."';";
$query = mysql_query($sql, $connection);
while($row = mysql_fetch_assoc($query)) {
	$fg=$row['cot'];
}
$cot++;
if($fg==0){
$msg .='<div class="tdclsbox">
  
		        <div class="tdcls tenvw">'.$id.'</div>
				<div class="tdcls tenvw">'.$item.'</div>
						<div class="tdcls sixvw">'.$count.'</div>
				<div class="tdcls sixvw">'.$sid.'</div>
			
				<div class="tdcls sixvw">未开单</div>
				<div class="tdcls sixvw">
					<span class="icon no"></span>
				</div>
			</div>
			';


}else{

/*$msg .='<div class="tdclsbox">
  
		        <div class="tdcls tenvw">'.$id.'</div>
				<div class="tdcls tenvw">'.$item.'</div>
				
				<div class="tdcls sixvw">'.$count.'</div>
			
				
				<div class="tdcls sixvw">已开单</div>
				<div class="tdcls sixvw">
					<span class="icon">无</span>
				</div>
			</div>
			';
*/
}


}
}
 echo $msg;//619
/*}else{

echo 0;*/


/*for($i=0;$i<count($yu);$i++){
$item=$yu[$i]['item'];
$sql="select count(*) as cot from ".$tablename." where item='".$item."';";
$query = mysql_query($sql, $connection);
while($row = mysql_fetch_assoc($query)) {
$cc[]=$row['cot'];
}
}
for($i=0;$i<count($yu);$i++){
$yu[$i]["count"]=$cc[$i];
}
$msg="";
$msg .='<div class="thclsbox">
				<div class="thcls tenvw">订单ID</div>
				<div class="thcls tenvw">货号</div>
				<div class="thcls tenvw">品名</div>
				<div class="thcls sixvw">数量</div>
				<div class="thcls sixvw">单价(元)</div>
				<div class="thcls sixvw">总额(元)</div>
				<div class="thcls sixvw">状态</div>
				<div class="thcls sixvw">操作</div>
			</div>';

$sql = "select * from `parameters` where `para_name`='cert_price' and `status`=4";
$query = mysql_query($sql, $connection);
$row = mysql_fetch_assoc($query);
$cert_price = $row["para_value"];
			for($i=0;$i<count($yu);$i++){
        $item=$yu[$i]["item"];
        $count=$yu[$i]["count"];
        $price=$yu[$i]["price"];
        $name=$yu[$i]["name"];
$sql="select count(*) as cot from so_huo_de where sid='".$sid."' and item='".$item."';";
$query = mysql_query($sql, $connection);
while($row = mysql_fetch_assoc($query)) {
	$fg=$row['cot'];
}

if($fg==0){
$msg .='<div class="tdclsbox">
		        <div class="tdcls tenvw">'.$sid.'</div>
				<div class="tdcls tenvw">'.$item.'</div>
					<div class="tdcls tenvw">'.$name.'</div>
				<div class="tdcls sixvw">'.$count.'</div>
				<div class="tdcls sixvw">'.$cert_price.'</div>
			<div class="tdcls sixvw">'.$count*$cert_price.'</div>
				<div class="tdcls sixvw">未开单</div>
				<div class="tdcls sixvw">
					<span class="icon no"></span>
				</div>
			</div>
			';


}else{

$msg .='<div class="tdclsbox">
		        <div class="tdcls tenvw">'.$sid.'</div>
				<div class="tdcls tenvw">'.$item.'</div>
				<div class="tdcls tenvw">'.$name.'</div>
				<div class="tdcls sixvw">'.$count.'</div>
				<div class="tdcls sixvw">'.$cert_price.'</div>
			<div class="tdcls sixvw">'.$count*$cert_price.'</div>
				
				<div class="tdcls sixvw">已开单</div>
				<div class="tdcls sixvw">
					<span class="icon">无</span>
				</div>
			</div>
			';

}



		}
  echo $msg;
}else{

echo 0;

}*/


}
?>