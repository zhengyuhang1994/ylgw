
<?php
//session_start();
//include_once("../function/auth.php");
header("Content-Type: text/html; charset=utf-8");
include_once("function.php");
$connection = getConnection();
$sql = "SELECT * FROM `materials` where type_id='WOLVES' and rid=1";
$query = mysql_query($sql, $connection);
while($row = mysql_fetch_assoc($query)) {
	$ss[]=$row['name'];
}
$sid=$_GET['a'];
$sql12 = "SELECT * FROM `so_huo` where id=".$sid;
$query12 = mysql_query($sql12, $connection);
$row12 = mysql_fetch_assoc($query12);
//print_r($query121);
$sql121 = "SELECT * FROM `so_huo_de` where eid=".$sid;
$query121 = mysql_query($sql121, $connection);
//$row121 = mysql_fetch_assoc($query121);

$sss=0;
while($row121 = mysql_fetch_assoc($query121)) {
$sss++;
$sb20[]=$sss;
	$sb1[]=$row121['sid'];
	$sb2[]=$row121['item'];
	$sb3[]=$row121['sname'];
	$sb4[]=$row121['sname_id'];
	$sb5[]=$row121['snumber'];
	$sb6[]=$row121['r_number'];
	$sb7[]=$row121['price'];
	$sb8[]=$row121['s_sun'];
	$sb9[]=$row121['unit'];
	$sb10[]=$row121['smark'];
	$sb19[]=$row121['jgc'];
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="css/index.css"/>
		<title></title>
		<script src="laydate/laydate.js"></script>
		<style type="text/css">
.der{
width: 15vw;
}

		</style>
	</head>
	<body>
		<form action="hjk1.php" method="post">
		<input  type="hidden" value="<?php echo $row12['id']; ?>" name="oeid" />
		<div class="top">
			修改_与狼共舞送货单
		</div>
		<a href="http://192.168.0.86:99/ylgw/so_huo.php" style="display: inline-block;
    text-decoration: none;
    margin-left: 61px;
    background: #00c4ff;
    border-radius: 5px;
    margin-top: 13px;
    width: 100px;
    height: 25px;
    text-align: center;
    color: white;" target="_blank">全部送货单</a>
		<div class="choosebox" style="overflow: hidden;">
				<div style="display: block;float: left;width: 30%;">
					<select name="bb" style="padding: 2px 7px;padding-right: 28px;display: block;float: left;" >
					<?php 

					if($row12['b_type']==1){
						echo '<option value="1" selected>正式订单</option>';
					}
					else{
						echo '<option value="1">正式订单</option>';
					}
if($row12['b_type']==2){
						echo '<option value="2" selected>正常补单</option>';
					}
					else{
						echo '<option value="2">正常补单</option>';
					}
					if($row12['b_type']==3){
						echo '<option value="3" selected>多裁补单</option>';
					}
					else{
						echo '<option value="3">多裁补单</option>';
					}
					if($row12['b_type']==4){
						echo '<option value="4" selected>错单补单</option>';
					}
					else{
						echo '<option value="4">错单补单</option>';
					}

/*
						<option value="1">正式订单</option>
						<option value="2">正常补单</option>
						<option value="3">多裁补单</option>
						<option value="4">错单补单</option>*/
					

?>

					</select>
					<div class="setbtn" style="float: left;margin-left: 10px;font-size: 14px;background: #4098fc;padding: 3px 15px;color: #fff;cursor: pointer;">
						物料筛选
					</div>
				</div>
			
			<div style="float: left;width: 40%;text-align: center;">		
					<span style="margin-right: 5px;">送货日期:</span><input readonly type="text" name="s_time" id="tdate" value="<?php echo $row12['s_time']; ?>" style="color: #000;font-size: 16px;width:8vw"  />
			</div>
			<div style="float: left;width: 30%;text-align: right;">
				<span style="display: inline-block;">
					业务员:
				</span>
				<select name="c_name" style="padding: 2px 7px;padding-right: 28px;" >

<?php   
if($row12['cname']==13){

echo '<option value="13" selected>黄春兰</option>';

}else{
	echo '<option value="13">黄春兰</option>';
}
if($row12['cname']==147){

echo '<option value="147" selected>李林</option>';

}else{
	echo '<option value="147">李林</option>';
}
if($row12['cname']==144){

echo '<option value="144" selected>邱丽玲</option>';

}else{
	echo '<option value="144">邱丽玲</option>';
}



					/*<option value="13">黄春兰</option>
					<option value="147">李林</option>
					<option value="144" selected>邱丽玲</option>*/


?>
				</select>
			</div>
		</div>

		<div class="ttl">
				基本信息
			</div>	
		<div class="tab" style="overflow: visible;">
			<div class="box">
				<div class="textbox isempty">
					<span class="nav">客户名称</span>
					<span class="inp"><input class="loadbtn" type="text" value="<?php echo $row12['sname']; ?>" name="sname" id="inputString" onkeyup="lookup(this.value);" onblur="fill();" /></span>
					<div class="searchbtn" style="border-radius:.5vw;font-size: 14px; overflow: hidden;margin: .3vw 1vw;">
						搜索
					</div>
					<div class="loadbox" id="loadbox" style="left: 7vw;">	

					</div>

				</div>
					<div class="textbox">
					<span class="nav">订单备注</span>
					<span class="inp"><input type="text" name="mark" id="" value="<?php echo $row12['mark']; ?>" /></span>
					
				</div>
				<!-- <div class="textbox2">
					<span class="nav" style="width: 6vw;">收货方电话</span>
					<span class="inp"><input style="width: 21vw;"  type="text" name="s_number" id="" value="" /></span>
				</div>	 -->
			</div>
				<!-- <div class="textbox isempty">
					<span class="nav">业务员</span>
					<span class="inp">
				
				<select name="c_name" style="width: 78px;
				    height: 22px;
				    border: none;">
				<option value="黄春兰">黄春兰</option>
				<option value="李林">李林</option>
				<option value="陈如男">陈如男</option>
				</select>
					<input type="text" name="c_name" id="" value="" /></span>
				</div>
				<div class="textbox">
					<span class="nav">联系人</span>
					<span class="inp"><input type="text" name="lman" id="l_man" value="" /></span>
				</div> -->
				<!-- <div class="textbox">
					<span class="nav">订单摘要</span>
					<span class="inp"><input type="text" name="mark" id="" value="" /></span>
				</div> -->
				<!-- <div class="textbox">
					<span class="nav">运输单号</span>
					<span class="inp"><input type="text" name="yu_number" id="" value="" /></span>
				</div> -->
			</div>
			<!-- <div class="box">
				
				<div class="textbox isempty">
					<span class="nav">运输方式</span>
					<span class="inp">
			
			<select name="yu_type" style="width: 78px;
				    height: 22px;
				    border: none;">
				<option value="快递物流">快递物流</option>
				<option value="自送">自送</option>
			
				</select>
			
						<input class="selbtn" type="text"  id="" value="" />
					</span>
					
				</div>
				<div class="textbox isempty">
					<span class="nav">快递公司</span>
					<span class="inp">
				
				
					
				<select name="kuaidi" style="width: 78px;
				    height: 22px;
				    border: none;">
				<option value="顺丰速运">顺丰速运</option>
				<option value="百世快递">百世快递</option>
				<option value="快捷快递">快捷快递</option>
				</select>
				
				
				
				
					</span>
				</div> 
			
				<div class="textbox">
					<span class="nav">运输单号</span>
					<span class="inp"><input type="text" name="yu_number" id="" value="" /></span>
				</div>
			
			
			
			</div>
			<div class="box">
				<div class="textbox isempty">
					<span class="nav">收货方</span>
					<span class="inp"><input type="text" name="bname" id="sh" readonly value="" /></span>
				</div>
				<div class="textbox">
					<span class="nav">联系人</span>
					<span class="inp"><input type="text" name="l_man" id="" value="" /></span>
				</div>
				<div class="textbox  last">
					<span class="nav">合同号</span>
					<span class="inp"><input type="text" name="h_number" id="" value="" /></span>
				</div>
				 <div class="textbox2">
					<span class="nav" style="width: 6vw;">收货方电话</span>
					<span class="inp"><input style="width: 21vw;"  type="text" name="s_number" id="l_number" value="" /></span>
				</div>
				<div class="textbox2 isempty">
					<span class="nav">送货日期</span>
					<span class="inp"><input type="text" name="s_time" id="" value="" readonly onClick="laydate()"/></span>
				</div>
				
			</div> -->
			<div class="box2">
			<!-- 	<div class="textbox">
				<span class="nav">订单备注</span>
				<span class="inp"><input type="text" name="mark" id="" value="" /></span>
			</div>
			<div class="textbox2">
				<span class="nav" style="width: 6vw;">收货方电话</span>
				<span class="inp"><input style="width: 21vw;"  type="text" name="s_number" id="" value="" /></span>
			</div>
						</div> -->
		</div>
		<div style="height: 100px;"></div>
		<!-- <div class="searchbox">
			<span class="sernav">工厂：</span>
			<span class="fabox">
				<input class="srear" type="text" readonly  name="sid" id="sid" value="" />
				<div class="searchbtn">
					搜索
				</div>
			</span>
		</div> -->
	<style>
		.fivw{
			width: 5vw;
		}
		</style>
		<div class="order1">
			<div class="title2">
				开单信息
			</div>	
			<div class="thclsbox">
				<div class="thcls fivw">
					序号
				</div>
				<div class="thcls tenvw">
					订单ID
				</div>
				<div class="thcls tenvw">
					货号
				</div>
				<div class="thcls  tenvw">
					物料名称
				</div>
				<div class="thcls tenvw">
					物料编号
				</div>
				
				<div class="thcls fivw">
					数量
				</div>

<div class="thcls fivw">
					开单数量
				</div>

				<div class="thcls fivw">
					单价(元)
				</div>
				<div class="thcls fivw">
					总额(元)
				</div>
				<div class="thcls tenvw">
					客户名称
				</div>
				<div class="thcls tenvw">
					备注
				</div>

				<div class="thcls fivw">
					操作
				</div>
			</div>

<!--   条数-->
<?php 
for($i=0;$i<count($sb1);$i++){

echo '<div class="tdclsbox">
			<div class="tdcls fivw"><input type="text" name="" id="" readonly="" value="'.$sb20[$i].'"></div>
			<div class="tdcls tenvw">
			<input type="text" name="jk1[]" id="" readonly="" value="'.$sb1[$i].'"></div>

			<div class="tdcls tenvw"><input type="text" name="jk2[]" id="" readonly="" value="'.$sb2[$i].'"></div>

			<div class="tdcls  tenvw">
			<select onchange="shange(this)" class="selt" title="" name="jk3[]" style="width: 115px;height: 22px;border: none;">';
$sa=$sb3[$i];
for($j=0;$j<count($ss);$j++){
echo '<option value="'.$ss[$j].'">'.$ss[$j].'</option>';
}
			echo '</select>
			</div>



			<div class="tdcls tenvw"><input type="text" name="jk4[]" id="" readonly="" value="'.$sb4[$i].'"></div>
			<div class="tdcls fivw"><input type="text" name="jk5[]" id="" readonly="" value="'.$sb5[$i].'"></div>
			<div class="tdcls fivw"><input type="number" onchange="ichan(this)" name="jk09[]" id="" value="'.$sb6[$i].'"></div>
			<div class="tdcls fivw"><input type="text" name="jk6[]" id="" readonly="" value="'.$sb7[$i].'"></div><div class="tdcls fivw"><input type="text" name="jk7[]" id="" readonly="" value="'.$sb8[$i].'"></div>
			<div class="tdcls tenvw"><input type="text" name="jk21[]" id="" readonly="" value="'.$sb19[$i].'"></div>
			<div class="tdcls tenvw"><input type="text" name="jk8[]" id="" value="'.$sb10[$i].'"></div><div class="tdcls fivw"><span class="icon del delete"></span></div></div>';


}
?>
			
<script type="text/javascript">
var sel=new Array();
<?php
for($i=0;$i<count($sb3);$i++){
  echo 'sel['.$i.']="'.$sb3[$i].'";';
}
?>
</script>

<!--   总计-->
			<div class="tdclsbox zongji " style="color:red;"><div class="tdcls tenvw"></div><div class="tdcls tenvw"></div><div class="tdcls tenvw"></div><div class="tdcls tenvw"></div><div class="tdcls fivw"></div><div class="tdcls fivw"></div><div class="tdcls fivw">总计</div><div class="tdcls fivw"><input class="allcount" style="color:red;" readonly="" type="text" name="jk18" id="" value="<?php echo  $row12['s_sun'];?>"></div><div class="tdcls fivw"></div></div>
			
		</div>
			<div class="submit"/>提交</div>
		</form>
		<div id="main" class="order2" style="position: absolute;left: 150px;top: 100px;width: 46vw">
		<div class="title" id="title">
       						订单信息<span class="icon del ordhide"></span>
       					</div>
       		<div class="mbox" style=" overflow-x: hidden;
    overflow-y: scroll;height: 300px;"></div>			
       		
			</div>
		</div>
				<style type="text/css">
			.setbox{
				display: none;
				position: absolute;
				top: 0;
				left: 0;
				width: 100vw;
				height: 100vh;
				background: rgba(0,0,0,0.6);
			}
			.settitle{
				position: fixed;
				width: 35.8vw;
				background: #f1f8ff;
				text-align: center;
				color: #4098fc;
				font-size: 16px;
				padding: 5px 0;
				text-align: center;
			}
			.set{
				background: #fff;
				width: 37vw;
				height: 25vw;
				padding-bottom: 10px;
				overflow: auto;
				position: relative;
				
				left: 35vw;
				top: 15vh;
			}
			.setdt{
				
				font-size: 14px;
				padding: 8px 0 ;

			}
			.setjbtn{
				position: fixed;
left: 63vw;

				width: 5vw;
				margin: 1vw auto; 
				font-size: 14px;
				background: #4098fc;
				padding: 3px 15px;
				color: #fff;
				cursor: pointer;
				text-align: center;
				border-radius:.2vw ;
				bottom: 16vw;
			}
			.sethide{
				    position: relative;
				    top: .5vw;
				    float: right;
				    margin-right: 2vw;
				    left: 1.1vw;
			}
		</style>
		<div class="setbox">
			<div class="set ">
				<div class="settitle">
					物料筛选<span class="icon del sethide"></span>
				</div>
				<div style="height: 32px;"></div>
				
<div id="wer">
				<!-- <div class="setdt">
					<span style="display: inline-block;width: 8vw;text-align: right;">
						<input type="checkbox" name="" id="" value="" /></span>
						<span style="display: inline-block;text-align: center;">与狼共舞与狼共舞121</span>
				</div>
				
				<div class="setdt">
					<span style="display: inline-block;width: 8vw;text-align: right;">
						<input type="checkbox" name="" id="" value="" /></span>
						<span style="display: inline-block;text-align: center;">与狼共舞与狼</span>
				</div>
				
				<div class="setdt">
					<span style="display: inline-block;width: 8vw;text-align: right;">
						<input type="checkbox" name="" id="" value="" /></span>
						与狼共舞与狼共舞
				</div> -->
				
</div>


				<div class="setjbtn" >修改</div>
			</div>
		</div>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript">
			var slen=sel.length;

			for(var k=0;k<slen;k++){
					var leng=$(".selt").eq(k).find("option").length;

					for(var j=0;j<leng;j++){						
						if(sel[k]==$(".selt").find("option").eq(j).val()){
							$(".selt").eq(k).find("option").eq(j).attr('selected','selected');
							console.log(j);
							break;
						}
					}

			}
			$(".sethide").click(function(){
				$(".setbox").hide();
			})
			$(".setbtn").click(function(){


$.ajax({
					type:"post",
					url:"queryAjaxData9.php",
					data:{"value1":"123"},
					success:function(data){
							var ddd=JSON.parse(data);
						//console.log(ddd.wer);
						  //console.log(data);
              $('#wer').html(ddd.wer);
			  $(".setbox").show();
					},
					error:function(data){
						console.log("123");
					}	
				})






				
				
				})
			$(".setjbtn").click(function(){

 var checkID = [];//定义一个空数组
         $("input[name='bbs']:checked").each(function(i){//把所有被选中的复选框的值存入数组
             checkID[i] =$(this).val();
         });

var b = checkID.join('ss');

//console.log(b);
$.ajax({
					type:"post",
					url:"queryAjaxData10.php",
					data:{"value1":b},
					success:function(data){
						$(".setbox").hide();

				        tip("设置成功")	 
					},
					error:function(data){
						console.log("123");
					}	
				})	
			})


		function getNowFormatDate() {
			    var date = new Date();
			    var seperator1 = "-";
			    var seperator2 = ":";
			    var month = date.getMonth() + 1;
			    var strDate = date.getDate();
			    if (month >= 1 && month <= 9) {
			        month = "0" + month;
			    }
			    if (strDate >= 0 && strDate <= 9) {
			        strDate = "0" + strDate;
			    }
			    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate;        
			    return currentdate;
			}
			laydate({
			  elem: '#tdate'
			});
			var isshow=0;
			//$("#tdate").val(getNowFormatDate());
		 function lookup(inputString) {
        if(inputString.length == 0) {
          $('#loadbox').hide();
        } else {
        	
				$.ajax({
					type:"post",
					url:"queryAjaxData2.php",
					data:{"value1":inputString},
					success:function(data){
						 //console.log(data);
						 
              $('#loadbox').html(data);
			    $("#loadbox").show();
			  $("#loadbtn").slideDown();
					},
					error:function(data){
						//console.log("123");
					}	
				})
        }
      } // lookup
      function fill(thisValue) {
        $('#inputString').val(thisValue);
        setTimeout("$('#loadbtn').hide();", 200);
      }
		
			$("body").click(function(){
				$(".loadbox").hide()
				$(".selbox").hide()
			})
			
			/*function load(){
				var ty=$(".loadbtn").val();
				$.ajax({
					type:"post",
					url:"queryAjaxData2.php",
					data:ty,
					success:function(d){
						//console.log(d);
						 $("#loadbtn").html(d);
					}
					
				})
				 
          }*/
		  
				
			
			function tiphide(){
				$(".tip").fadeOut();
				$(".tip").remove();
			}
			function tip(s){
				if($(".tip").length>=1){
					return;
				}
				var cont ="<div class='tip'>"+s+"</div>"
				$("body").append(cont);
				setTimeout("tiphide()",1500);
			}
			//获取客户名称
			/*$(".load").click(function(){*/
			$(document).on("click",'.load',function(){

                  var gh=$(this).text();



$.ajax({
					type:"post",
					url:"queryAjaxData8.php",
					data:{"value1":gh},
					success:function(data){
						 // console.log(data);
						  	var ddd=JSON.parse(data);
						  	//console.log(ddd);
						 	$("#l_man").val(ddd.l_man);
						$("#l_number").val(ddd.l_number);
						
       
					},
					error:function(data){
						//console.log("123");
					}
					
				})

				$(".srear").val($(this).text());
				$("#sh").val($(this).text());
				$(".loadbtn").val($(this).text());
				$(".loadbox").hide()
			//	console.log(00);

				event.stopPropagation();
 				event.preventDefault();
			})
			
			$(".selbtn").click(function(){
				$(".loadbox").hide()
				$(".selbox").slideDown();
				event.stopPropagation();
 				event.preventDefault();
			})
			$(".sel").click(function(){
				$(".selbtn").val($(this).text());
				$(".selbox").hide()
			})
			$(document).on("click",'.yes',function(){
				$(this).addClass("no");
				$(this).removeClass("yes")
			});
			$(document).on("click",'.no',function(){
				var tdlen=$(".order1").find(".tdclsbox").length;
				if(tdlen==0){
					tdlen=1;
				}
				var aval=0;
				var zj=$(".allcount").val();
				var zongji='<div class="tdclsbox zongji " style="color:red;">';
				zongji+='<div class="tdcls fivw" ></div><div class="tdcls tenvw"></div><div class="tdcls tenvw"></div><div class="tdcls tenvw"></div><div class="tdcls tenvw" ></div><div class="tdcls fivw"></div><div class="tdcls fivw" ></div><div class="tdcls fivw" >总计</div>'

				$(this).addClass("yes");
				$(this).removeClass("no");
				var addnew='<div class="tdclsbox">';
					addnew+='<div class="tdcls fivw"><input type="text" name="" id="" readonly value="'+tdlen+'" /></div>';				
					addnew+='<div class="tdcls tenvw"><input type="text" name="jk1[]" id="" readonly value="'+$(this).parent().parent().find(".tdcls").eq(0).text()+'" /></div>';//订单号
					addnew+='<div class="tdcls tenvw"><input type="text" name="jk2[]" id="" readonly value="'+$(this).parent().parent().find(".tdcls").eq(1).text()+'" /></div>';//货号



					addnew+='<div class="tdcls  tenvw"><select onchange="shange(this)" class="selt" title="" name="jk3[]" style="width: 115px;height: 22px;border: none;">';
<?php
for($i=0;$i<count($ss);$i++){
?>
addnew+='<option value="<?php echo $ss[$i]?>"><?php echo $ss[$i]?></option>';
<?php
}
?>
addnew+='</select></div>';
//物料名称
					addnew+='<div class="tdcls tenvw"><input type="text" name="jk4[]" id="" readonly value="" /></div>';//物料id
					addnew+='<div class="tdcls fivw"><input type="text" name="jk5[]" id="" readonly value="'+$(this).parent().parent().find(".tdcls").eq(2).text()+'" /></div>';//数量

addnew+='<div class="tdcls fivw"><input type="number"    onchange="ichan(this)"   name="jk09[]" id=""  value="'+$(this).parent().parent().find(".tdcls").eq(2).text()+'" /></div>';//数量


					addnew+='<div class="tdcls fivw"><input type="text" name="jk6[]" id="" readonly value="" /></div>';//单价
					addnew+='<div class="tdcls fivw"><input type="text" name="jk7[]" id="" readonly value="0" /></div>';//总额
// addnew+='<div class="tdcls tenvw"><input type="text" name="jk21[]" id="" readonly value="" /></div>';//单位
addnew+='<div class="tdcls tenvw"><input type="hidden" class="danwei" name="jk21[]" id="" readonly value="" /><input class="coster" type="text" name="jk211[]" id="" readonly value="'+$(this).parent().parent().find(".tdcls").eq(3).text()+'" /></div>';
					addnew+='<div class="tdcls tenvw"><input type="text" name="jk8[]" id="" value="" /></div>';//备注



					 addnew+='<div class="tdcls fivw"><span class="icon del delete"></span></div></div>'
				 $(".order1").append(addnew);
				 if(zj==undefined){		 	 
				 	zongji+='<div class="tdcls fivw"><input class="allcount" style="color:red;" type="text" name="jk18" id="" value="0" /></div><div class="tdcls fivw"></div></div>'
				 }
				 else{

				 	aval=Number(zj).toFixed(2);
				 	zongji+='<div class="tdcls fivw"><input class="allcount" style="color:red;" readonly type="text" name="jk18" id="" value="'+aval+'" /></div><div class="tdcls fivw"></div></div>'
				 $(".zongji").remove();
				 }			 
				  $(".order1").append(zongji);
			});
			
			function shange(ta){
				var aa =$(ta).val();
				console.log($(ta).parent().find('select').attr("title"))
				$(ta).parent().find('select').attr("title",aa);

$.ajax({
					type:"post",
					url:"queryAjaxData7.php",
					data:{"value1":aa},
					success:function(data){
						 // console.log(data);
						  	var ddd=JSON.parse(data);
						 	
						$(ta).parent().next().find("input").val(ddd.sid);
						$(ta).parent().next().next().next().next().find("input").val(ddd.price);
						$(ta).parent().parent().find(".danwei").val(ddd.dan);
						var $num=$(ta).parent().next().next().next().find("input").val();
						var all=Number($num)*Number(ddd.price);						
						$(ta).parent().next().next().next().next().next().find("input").val(all.toFixed(2));
						
						var len=$(".order1").find(".tdclsbox").length;
						
						if(len<=2){
							console.log(2);
							$(".allcount").val(all.toFixed(2));
						}
						else{
							var zoj=0;
							//console.log(1);
							for(var a=0;a<len-1;a++){
								//console.log($(".order1").find(".tdclsbox").eq(a).find(".tdcls").eq(6).find("input").val())
								zoj+=Number($(".order1").find(".tdclsbox").eq(a).find(".tdcls").eq(8).find("input").val())
							}
							
							//console.log(zoj.toFixed(2));
							$(".allcount").val(zoj.toFixed(2));
							zoj=0;
						}
						
       
					},
					error:function(data){
						//console.log("123");
					}
					
				})
			}



			$(document).on("click",'.delete',function(){
				var dzongj;				
				var decont=Number($(this).parent().parent().find(".tdcls").eq(7).find("input").val());
				console.log(decont)
				
				if($(".order1").find('.tdclsbox').length==2){
					$(this).parent().parent().remove();
					$(".zongji").remove();
				}
				else{
					var aval=Number($(".allcount").val())-decont;
					var eval=aval.toFixed(2);
					console.log(eval);
					dzongj='<div class="tdclsbox zongji " style="color:red;">';
					dzongj+='<div class="tdcls tenvw"></div><div class="tdcls tenvw"></div><div class="tdcls tenvw"></div><div class="tdcls tenvw" ></div><div class="tdcls fivw" ></div><div class="tdcls fivw" ></div><div class="tdcls fivw" >总计</div>'

				 	dzongj+='<div class="tdcls fivw"><input class="allcount" style="color:red;" readonly type="text" name="jk18" id="" value="'+eval+'" /></div><div class="tdcls fivw"></div></div>'
					$(this).parent().parent().remove();
					$(".zongji").remove();
					$(".order1").append(dzongj);
				}	 
			});
			$(document).on("click",'.delete',function(){
				$(this).parent().parent().remove();
			});
			$(".searchbtn").click(function(){
			
				if($(".loadbtn").val()==""){
					tip("搜素内容不能为空");
				}
				else{
			var dd=$(this).parent().find("input").val();

			$.ajax({
					type:"post",
					url:"queryAjaxData3.php",
					data:{"value1":dd},
					success:function(data){
						  //console.log(data);
						   $('.mbox').html(data);
						   $(".order2").show();
						/* if(data==0){
						 	tip("该订单不存在或还未完成,请认真核对");
						 }else{

 $('.mbox').html(data);
						$(".order2").show();

						 }*/
						
              //$('#loadbox').html(data);
			    //$("#loadbox").show();
			  //$("#loadbtn").slideDown();
					},
					error:function(data){
						//console.log("123");
					}
					
				})
					
				}	
						
			})
			$(".ordhide").click(function(){
				$(".order2").hide();
				
				
			})
			$(".submit").click(function(){
				var orlen=$(".order1").find(".tdclsbox").length;
				var tips="";
				var len=$(".isempty").length;
				for(var i=0;i<len;i++){
					if($(".isempty").find("input").eq(i).val()==""){
						tips+="'"+$(".isempty").eq(i).find(".nav").text()+"'";
					}
				}
				if($(".order1").find(".tdclsbox").length==0){
					tips+="开单信息不能为空";
				}else{
					for(var i=0;i<orlen;i++){
						//console.log($(".order1").find(".tdclsbox").eq(i).find(".tdcls").eq(8).find("input").val());
						if ($(".order1").find(".tdclsbox").eq(i).find(".tdcls").eq(8).find("input").val()=="0") {
							tips+="'序号为"+$(".order1").find(".tdclsbox").eq(i).find("input").eq(0).val()+"的订单未选物料'<br />";
						}
					}
				}

				if(tips==""){
					$("form").submit();
				}else{
					
					tip(tips);
				}
			})
			function ichan(nowval){
				var price=$(nowval).parent().next().find("input").val();
				var acot=Number(price)*Number($(nowval).val())
				$(nowval).parent().next().next().find("input").val(acot.toFixed(2));
				var len=$(".order1").find(".tdclsbox").length;
				var zonj=0;
				for(var a=0;a<len-1;a++){
								//console.log($(".order1").find(".tdclsbox").eq(a).find(".tdcls").eq(6).find("input").val())
								zonj+=Number($(".order1").find(".tdclsbox").eq(a).find(".tdcls").eq(7).find("input").val())
							}
				$(".allcount").val(zonj.toFixed(2));

			}
			
		</script>
		<script type="text/javascript">
		function Mover(title) {
        this.obj = title;
        this.startx = 0;
        this.starty;
        this.startLeft;
        this.startTop;
        this.mainDiv = title.parentNode;
        var that = this;
        this.isDown = false;
        this.movedown = function (e) {
            e = e ? e : window.event;
            if (!window.captureEvents) {
                this.setCapture();
            }
            that.isDown = true;
            that.startx = e.clientX;
            that.starty = e.clientY;
            that.startLeft = parseInt(that.mainDiv.style.left);
            that.startTop = parseInt(that.mainDiv.style.top);
        }
        this.move = function (e) {
            e = e ? e : window.event;
            if (that.isDown) {
                that.mainDiv.style.left = e.clientX - (that.startx - that.startLeft) + "px";
                that.mainDiv.style.top = e.clientY - (that.starty - that.startTop) + "px";
            }
        }
        this.moveup = function () {
            that.isDown = false;
            if (!window.captureEvents) {
                this.releaseCapture();
            }
        }
        this.obj.onmousedown = this.movedown;
        this.obj.onmousemove = this.move;
        this.obj.onmouseup = this.moveup;
        document.addEventListener("mousemove", this.move, true);
    }
    var mover = new Mover(document.getElementById("title"));
		</script>
	</body>
</html>
