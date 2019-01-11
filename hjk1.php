<?php
//session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
$connection = getConnection();
$sid=$_POST['bb'];//开票类型
$sname=$_POST['sname'];//客户名称
$mark=$_POST['mark'];//订单摘要
//$yu_number=$_POST['yu_number'];//运输单号
$c_name=$_POST['c_name'];//业务员
//$yu_type=$_POST['yu_type'];//运输方式
//$kuaidi=$_POST['kuaidi'];//快递公司
//$bname=$_POST['bname'];//收货人
//$l_man=$_POST['lman'];//联系人
$time=time();
/*$h_number=$_POST['h_number'];//合同号*/
$s_time=$_POST['s_time'];//送货日期
//$s_number=$_POST['s_number'];//收货方电话*/
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
$jk09=$_POST['jk09'];//数量
$jk211=$_POST['jk211'];//客户
$jk6=$_POST['jk6'];//单价
$jk7=$_POST['jk7'];//总金额
$jk8=$_POST['jk8'];//备注
$jk18=$_POST['jk18'];//备注
$jk21=$_POST['jk21'];//单位
$jk9=$_POST['jk9'];//总计
$oeid=$_POST['oeid'];//总计
//print_r($jk2);
$sql511 = "select * from so_huo where id=".$oeid;
//echo $sql511;
     //echo $sql5;
     $ty121=mysql_query($sql511, $connection);   
 $df121=mysql_fetch_assoc($ty121);
$hnum=$df121['order_num'];
$yp=0;
for($r=0;$r<count($jk09);$r++){
$yp=$yp+$jk09[$r];
}
$sql15 = "update  so_huo set sname='".$sname."',cname='".$c_name."',s_time='".$s_time."',mark='".$mark."',create_time='".$time."',b_type=".$sid.",s_sun='".$jk18."',order_num='".$hnum."',s_ss=".$yp." where id=".$oeid;
//echo $sql15;
     $tyu=mysql_query($sql15, $connection);   
$ssql="delete from so_huo_de where eid=".$oeid;
mysql_query($ssql, $connection); 
for($i=0;$i<count($jk1);$i++){
    $bn=$jk09[$i]*$jk6[$i];
$sql5 = "INSERT INTO so_huo_de (eid,sid,item,sname,snumber,r_number,price,smark,s_sun,sname_id,unit,jgc) VALUES (".$oeid.",'".$jk1[$i]."','".$jk2[$i]."','".$jk3[$i]."','".$jk5[$i]."','".$jk09[$i]."','".$jk6[$i]."','".$jk8[$i]."','".$bn."','".$jk4[$i]."','".$jk21[$i]."','".$jk215[$i]."')";
     //echo $sql5;
     //echo $sql5;
     mysql_query($sql5, $connection);   
}
//推送数据到oa
$sql31 = "SELECT * FROM so_huo WHERE id=".$oeid;
$query31 = mysql_query($sql31);
$row31 = mysql_fetch_assoc($query31);
$order_num=$row['order_num'];//送货单编号
//$sname="与狼共舞";//客户名称
$sdm="SYL002";//商家代码
$cname=$c_name;//业务员
$s_time=$s_time;//送货日期
$eeid=$row31['id'];
$mark=$mark;//订单摘要
$create_time=$time;//创建时间
//$create_time=$row['b_type'];//订单类型
$s_sun=$jk9;//总金额
$sql = "SELECT * FROM so_huo_de WHERE eid=".$eeid." order by id desc;";
$query = mysql_query($sql);
$price= array();
$snumber= array();
 $sb_sun= array();
 $sname_id= array();
 $item= array();
while($row4 = mysql_fetch_assoc($query)) {
    $price[]=$row4['price'];
    $snumber[]=$row4['r_number'];
    $sb_sun[]=$row4['s_sun'];
    $sname_id[]=$row4['sname_id'];
    $item[]=$row4['item'];
      $rrmark[]=$row4['smark'];
       $jgc1[]=$row4['$jgc'];
}
$sitem=implode("s",$item);
$sprice=implode("s",$price);
$ssnumber=implode("s",$snumber);
$ss_sun=implode("s",$sb_sun);
$ssname_id=implode("s",$sname_id);
$srrmark=implode("s",$rrmark);
$jgc3=implode("s",$jgc1);
$url = "http://59.57.37.217:8686/general/finance/wolf1.php";
$json = '{"order_num":"'.$hnum.'","shang_dai":"'.$sdm.'","eid":"'.$eeid.'","create_time":"'.$create_time.'", "sname":"'.$sname.'","cname":"'.$cname.'","s_time":"'.$s_time.'","mark":"'.$mark.'","s_sun":"'.$s_sun.'","sitem":"'.$sitem.'","price":"'.$sprice.'","snumber":"'.$ssnumber.'","ss_sun":"'.$ss_sun.'","sname_id":"'.$ssname_id.'","jgc":"'.$jgc3.'","rrmark":"'.$srrmark.'","user":"root", "pass":"1234"}';
$res = http_post_data($url, $json);
//echo $res;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>与狼共舞_送货单</title>
        <link href="css/404.css" rel="stylesheet" type="text/css" />
        <script src="js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript">
            $(function() {
                var h = $(window).height();
                $('body').height(h);
                $('.mianBox').height(h);
                centerWindow(".tipInfo");
            });

            //2.将盒子方法放入这个方，方便法统一调用
            function centerWindow(a) {
                center(a);
                //自适应窗口
                $(window).bind('scroll resize',
                        function() {
                            center(a);
                        });
            }

            //1.居中方法，传入需要剧中的标签
            function center(a) {
                var wWidth = $(window).width();
                var wHeight = $(window).height();
                var boxWidth = $(a).width();
                var boxHeight = $(a).height();
                var scrollTop = $(window).scrollTop();
                var scrollLeft = $(window).scrollLeft();
                var top = scrollTop + (wHeight - boxHeight) / 2;
                var left = scrollLeft + (wWidth - boxWidth) / 2;
                $(a).css({
                    "top": top,
                    "left": left
                });
            }
        </script>
    </head>
    <body>
        <div class="mianBox">
            <img src="img/yun0.png" alt="" class="yun yun0" />
            <img src="img/yun1.png" alt="" class="yun yun1" />
            <img src="img/yun2.png" alt="" class="yun yun2" />
            <img src="img/bird.png" alt="" class="bird" />
            <img src="img/san.png" alt="" class="san" />
            <div class="tipInfo">
                <div class="in">
                    <div class="textThis">
                        <h2>送货单修改成功</h2>
                        <p><span>页面自动<a id="href" href="http://192.168.0.86:99/ylgw/so_huo.php">跳转</a></span><span>等待<b id="wait">3</b>秒</span></p>
             <script type="text/javascript">                            (function() {
                             var wait = document.getElementById('wait'), href = document.getElementById('href').href;
                             var interval = setInterval(function() {
                                 var time = --wait.innerHTML;
                                 if (time <= 0) {
                                     location.href = href;
                                     clearInterval(interval);
                                 }
                                 ;
                             }, 1000);
                         })();
                     </script>  
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>