<?php
header("Content-Type: text/html; charset=utf-8");
require_once ('PHPExcel/Writer/Excel2007.php');
require_once ('PHPExcel/Writer/Excel5.php');
require_once ('PHPExcel.class.php');
require_once ('PHPExcel/IOFactory.php');
include_once("function.php");
include_once("waterFunction.php");
$conn = getConnection();
$date1 = $_POST["date1"];
$date2 = $_POST["date2"];
$page = $_POST["pageNum"];
$status = $_POST["status"];
$pAccount = $_POST["account"];

/*$date1 = "";
$date2 =  "";
$page =  "";
$status = "";
$pAccount = "景盛";*/
$where_str = "a.id>0";
if($status == "DayOrders") {
	$date1 = date("Y-m-d", time())." 00:00:00";
	$date2 = date("Y-m-d", time())." 23:59:59";
}
if($date1 != "") {
	$date1=strtotime($date1);
	$where_str .= " and a.create_time >= '$date1'";
}
if($date2 != "") {
	$date2=strtotime($date2);
	$where_str .= " and a.create_time <= '$date2'";
}

if($pAccount != "") {
	$where_str .= " and a.sname = '$pAccount'";
}
header("Expires: 0");
header("Content-type:application/vnd.ms-excel.numberformat:@;charset=UTF-8");
 header("Content-Disposition:attachment;filename=export_data.xls"); 

  $objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1','送货单号')
    ->setCellValue('B1','货号')
    ->setCellValue('C1','送货日期')
    ->setCellValue('D1','货品名称')
    ->setCellValue('E1','收货方')
    ->setCellValue('F1','单位')
    ->setCellValue('G1','数量')
     ->setCellValue('H1','开单数量')
    ->setCellValue('I1','单价')
    ->setCellValue('J1','金额(元)')
     ->setCellValue('K1','备注')
      ->setCellValue('L1','订单类型');

$rt="个";

$sql = "SELECT a.id,a.b_type,a.cname,a.sname, a.order_num,a.s_time,b.sname,b.item,a.bname,b.snumber,b.r_number,b.price,b.s_sun,b.smark FROM so_huo a INNER JOIN so_huo_de b ON a.id=b.eid WHERE $where_str ORDER BY a.order_num;";

//echo $sql;
$query = mysql_query($sql);
//$arr = mysql_fetch_assoc($query);
//print_r($arr);
$i=2;
$t1=0;
$t2=0;
while($arr = mysql_fetch_assoc($query)) {
$t3+=$arr['snumber'];
$t1+=$arr['r_number'];
$t2+=$arr['s_sun'];

if($arr['b_type']==1){
$rf="正常订单";
}

    else if($arr['b_type']==2){
$rf="正常补单";
    }
    else if($arr['b_type']==3){
$rf="多裁补单";
    }
    else if($arr['b_type']==4){
$rf="错单补单";
    }

 $objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue('A'.$i,$arr['order_num'])
     ->setCellValue('B'.$i,$arr['item'])
      ->setCellValue('C'.$i,$arr['s_time'])
     ->setCellValue('D'.$i,$arr['sname'])
	  ->setCellValue('E'.$i,$arr['bname'])
     ->setCellValue('F'.$i,$rt)
     ->setCellValue('G'.$i,$arr['snumber'])
      ->setCellValue('H'.$i,$arr['r_number'])
	  ->setCellValue('I'.$i,$arr['price'])
     ->setCellValue('J'.$i,$arr['s_sun'])
       ->setCellValue('K'.$i,$arr['smark'])
         ->setCellValue('L'.$i,$rf);
 $i++;
	
}
$i=$i+1;
$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue('A'.$i,'')
     ->setCellValue('B'.$i,'')
      ->setCellValue('C'.$i,'')
     ->setCellValue('D'.$i,'')
	  ->setCellValue('E'.$i,'')
     ->setCellValue('F'.$i,"总计:")
     ->setCellValue('G'.$i,$t3)
      ->setCellValue('H'.$i,$t1)
	  ->setCellValue('I'.$i,'')
     ->setCellValue('J'.$i,$t2)
       ->setCellValue('K'.$i,'')
        ->setCellValue('L'.$i,'');

$objPHPExcel->getActiveSheet()->setTitle('二维码表');
$objPHPExcel->setActiveSheetIndex(0);
if($data1!="" || $data2!=""){
$filename=urlencode(strtotime($data1).'到'.strtotime($data2).'与狼共舞送货单');
}else if($data1!="" || $data2!="" || $pAccount!=""){
$filename=urlencode($strtotime($data1).'到'.strtotime($data2).$pAccount.'与狼共舞送货单');
}
else if($data1=="" && $data2=="" && $pAccount=="") {
$filename=urlencode('与狼共舞送货单');
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>