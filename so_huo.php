<?php
//include_once("../function/auth.php");
include_once("function.php");
//include_once("../function/returnCode.php");
$connection = getConnection();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>送货单_与狼共舞</title>
<link rel="stylesheet" type="text/css" href="/style/css/ui.css" />
<link rel="stylesheet" type="text/css" href="/style/css/style.css" />
<link rel="stylesheet" type="text/css" href="/style/css/dialog.css">
<link rel="stylesheet" type="text/css" href="/style/css/jquery.autocomplete.css" />
<script type="text/javascript" src="/style/js/ccorrect_btn.js"></script>
<script type="text/javascript" src="/style/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/style/js/attach.js"></script>
<script type="text/javascript" src="/style/js/public.js"></script>
<script type="text/javascript" src="/style/js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/style/js/json2.js"></script>
<script type="text/javascript" src="/style/js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/style/js/wolves.js"></script>
<script type="text/javascript" src="/style/js/dialogDiv.js"></script>
<script type="text/javascript" src="/style/js/LodopFuncs.js"></script>
<script type="text/javascript">
var LODOP; //声明为全局变量
function drop_confirm(message,pathurl)
{
if(confirm(message))
{
window.location.href=pathurl;
}else
{
return false;
}
}
function printingPreview(t, id){
$.ajax({
					type:"post",
					url:"printingPreview.php",
					data: {
			//uid: uid,
			id: id,
			time: new Date().getTime()
		},
		beforeSend: function(XMLHttpRequest) {
		//ShowDialog('comment');

		},
					success:function(data){
						 console.log(data);
						 var ddd=JSON.parse(data)
						 
              createPrintPage(ddd.order_num, ddd.res, ddd.user, ddd.sign);
			//LODOP.SET_PRINTER_INDEX('58 Printer');
			if(t == 'Y') {
				if(window.confirm('确定直接打印吗？')) {
					var default_printer = document.getElementById('default_printer').value;
					if(default_printer != -1) {
						LODOP.SET_PRINTER_INDEX(default_printer);
						LODOP.PRINT();
					} else {
						alert('未设置打印机或打印机不存在');
					}
				}
			} else {
				LODOP.PREVIEW();
			}
					},
					complete: function(XMLHttpRequest) {
			//HideDialog('comment');
		},
					error:function(data){
						alert('慢点操作1');
					}	
				})
}

function newExportData() {
	document.form1.action = "newExportData.php";
	document.form1.submit();	
}

function createPrintPage(order_num, res, user, sign) {
	LODOP = getLodop();
	LODOP.PRINT_INIT("合格证送货单打印");
	LODOP.SET_PRINT_PAGESIZE(1, '215mm', '250mm', "");//1:竖版,2:横版
	LODOP. ADD_PRINT_IMAGE('12mm', '0mm', '215mm', '145mm', '<img width="70" src="img/yiop.png" style="background:white;" />');
	LODOP.SET_PRINT_STYLE("FontSize", 14);
	LODOP.ADD_PRINT_TEXT('18mm', '166mm', '215mm', '145mm', "【送 货 单 据】");
	LODOP.ADD_PRINT_TEXT('25mm', '166mm', '215mm', '145mm', order_num);
	LODOP.ADD_PRINT_HTM('35mm', '-25mm', '255mm', '210mm', res);
	LODOP.SET_PRINT_STYLE("FontSize", 12);
	LODOP.ADD_PRINT_TEXT('220mm', '30mm', '215mm', '145mm', "白色联：存根");
	LODOP.ADD_PRINT_TEXT('220mm', '65mm', '215mm', '145mm', "红色联：结算联");
	LODOP.ADD_PRINT_TEXT('220mm', '100mm', '215mm', '145mm', "蓝色联：顾客联");
	LODOP.ADD_PRINT_TEXT('220mm', '140mm', '215mm', '145mm', "黄色联：顾客存根");
	LODOP.ADD_PRINT_TEXT('230mm', '30mm', '215mm', '145mm', user);
	LODOP.ADD_PRINT_TEXT('230mm', '120mm', '215mm', '145mm', sign);
}
var curPage = 1; //当前页码
var total, pageSize, totalPage;
function QueryData() {
	getData(1);
}

$(document).ready(function() {
	getData(1);	
});
function SearchForm() {
	getData(1);
}
function Update(id) {
	window.location.href = 'new.php?id='+id;
}

function ShowDetail(order_num, where_str) {
	URL = "../show.php?order_num="+order_num+"&where_str="+where_str;
    var diag = new Dialog();
	diag.Title = "查看明细";
	diag.Width = 900;
	diag.Height = 500;
	diag.URL = URL;
	diag.CancelEvent=function() {
		getData(curPage);
		diag.close();
	};
	diag.show();
}

function getData(page) {
	curPage = page;

	var create_time1 = document.getElementById("create_time1").value;
	var create_time2 = document.getElementById("create_time2").value;
	var order_num = document.getElementById("order_num").value;
	var customer_id = document.getElementById("customer_id").value;
	var uid = document.getElementById('uid').value;
	var COPY_TO_ID = document.getElementById("COPY_TO_ID").value;
	var delivery_date1 = document.getElementById("delivery_date1").value;
	var delivery_date2 = document.getElementById("delivery_date2").value;

	jQuery.ajax({
		type: "POST",
		cache: false,
		url: "queryData.php",
		data: {
			create_time1:create_time1,
			create_time2:create_time2,
			order_num:order_num,
			customer_id:customer_id,
			COPY_TO_ID:COPY_TO_ID,
			delivery_date1:delivery_date1,
			delivery_date2:delivery_date2,
			uid:uid,
			pageNum: page-1,
			time: new Date().getTime()
		},
		dataType: "json",
		beforeSend: function(XMLHttpRequest) {
			ShowDialog('comment');
		},
		success: function(data) {
			total = data.total;
			pageSize = data.pageSize;
			totalPage = data.totalPage;

			document.getElementById("resText").innerHTML = data.data;
		},
		complete: function(XMLHttpRequest) {
			getPageBar();
			HideDialog('comment');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert('慢点操作');
		}
	});
}

function ChangeData(CustomerID) {
	var Customer = trim(CustomerID);
	if(Customer != "") {
		if(Customer.length >= 2) {
			jQuery.ajax({
				type: "POST",
				cache: false,
				url: "/general/finance/product/out/customer.php",
				data: {
					customer:Customer,
					time:new Date().getTime()
				},
				dataType: "json",
				success: function(data) {
					if(data.msg == "N") {
						$('#CustomerID').val('');
						$('#customer_id').val('');
					} else {
						$('#customer_id').val(data.customer_id);
					}
				}
			});
		}
	}
}

function ExportData() {
	document.form1.action = "exportData.php";
	document.form1.submit();
}

function newExportData() {
	document.form1.action = "newExportData.php";
	document.form1.submit();	
}
</script>

<script type="text/javascript">

function CheckForm() {
	var date1 = trim(document.getElementById("date1").value);
	var date2 = trim(document.getElementById("date2").value);
	if(date1 == "" && date2 == "") {
		alert("至少有一个条件不能为空");
		return false;
	}
	return true;
}

var curPage = 1; //当前页码
var total, pageSize, totalPage, status = "", field = "", asc_desc = "";
function QueryDayOrders() {
	status = "DayOrders";
	getData(1);
}

function QueryData() {
	getData(1);
}

function OrderBy(field1, asc_desc1) {
	field = field1;
	asc_desc = asc_desc1;
	getData(1);
}

function getData(page) {
	curPage = page;//当前页
	var date1 = trim($("#date1").val());
	var date2 = trim($("#date2").val());
	var account = $("#account").val();
$.ajax({
					type:"post",
					url:"queryAjaxData5.php",
					data:{"pageNum": page-1,
		"date1": date1,
		"date2": date2,
		"status":status,
		"account":account,
		"time":new Date().getTime()
	},
					success:function(data){
						console.log(data.cc);
console.log(data);
						var ddd=JSON.parse(data)
						
						total = ddd.total;//总记录数
						pageSize = ddd.pageSize;//每页显示条数
						totalPage = ddd.totalPage;//总页数
						$("#resText").html(ddd.data);
						getPageBar();

					},
					error:function(data){
							alert(XMLHttpRequest.status);
			alert(XMLHttpRequest.readyState);
			alert(textStatus);
					}	
				})

}

function getPageBar() {
	//console.log(123);
	if(curPage > totalPage) {
		curPage = totalPage;
	}

	if(curPage < 1) {
		curPage = 1;
	}
	var pageStr = "共" + total + "条&nbsp;&nbsp;每页" + pageSize + "条&nbsp;&nbsp;" + curPage + "/" + totalPage + "&nbsp;&nbsp;";

	if(curPage == 1) {
		pageStr += "<input type='button' value='首页' class='SmallButton' onClick='AlertNG(1);' />&nbsp;<input type='button' value='上一页' class='SmallButton' onClick='AlertNG(1);' />&nbsp;";
	} else {
		pageStr += "<input type='button' value='首页' class='SmallButton' onClick='getData(1);' />&nbsp;<input type='button' value='上一页' class='SmallButton' onClick='getData("+(curPage-1)+");' />&nbsp;";
	}

	if(curPage >= totalPage) {
		pageStr += "<input type='button' value='下一页' class='SmallButton' onClick='AlertNG(2);' />&nbsp;<input type='button' value='尾页' class='SmallButton' onClick='AlertNG(2);' />&nbsp;";
	} else {
		pageStr += "<input type='button' value='下一页' class='SmallButton' onClick='getData("+(parseInt(curPage)+1)+");' />&nbsp;<input type='button' value='尾页' class='SmallButton' onClick='getData("+totalPage+");' />&nbsp;";
	}

	pageStr += "<input type='text' name='gotoPage' id='gotoPage' value='' class='SmallInput' size='3' />&nbsp;<input type='button' value='GO' class='SmallButton' onClick='GoToPage();' id='bt1' />&nbsp;<span id='pageRes'></span>";
	$("#pageBar").html(pageStr);
}

function GoToPage() {
	var gotoPage = $("#gotoPage").val();
	if(gotoPage == "") {
		alert("页码不能为空");
		document.getElementById("gotoPage").focus();
		return;
	}
	for(var i = 0; i < gotoPage.length; i++) {
		if(gotoPage.charAt(i) < '0' || gotoPage.charAt(i) > '9') {
			alert("页码请填入数字");
			document.getElementById("gotoPage").focus();
			return;
		}
	}
	if(gotoPage.substring(0, 1) == "0") {
		alert("第一位不能为0");
		document.getElementById("gotoPage").focus();
		return;
	}
	if(parseInt(gotoPage) > parseInt(totalPage) || parseInt(gotoPage) < 1) {
		alert("页码必须为1至"+totalPage);
		return false;
	}
	getData(gotoPage);
}

function AlertNG(msg) {
	if(msg == "1") {
		document.getElementById("pageRes").innerHTML = "<font color='red'>已到首页</font>";
	} else if(msg == "2") {
		document.getElementById("pageRes").innerHTML = "<font color='red'>已到尾页</font>";
	}
}

$(document).ready(function() {
	getData(1);
});
//推送数据到oa
function ssoa(id){
window.location.href="so_xiugai.php?a="+id;
}

</script>
</head>
<body class="bodycolor">
<form name="form1" method="post">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
	<td class="Small">
		开始时间:<input type="text" name="date1" id="date1" size="15" class="BigStatic" readonly value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
		结束时间:<input type="text" name="date2" id="date2" size="15" class="BigStatic" readonly value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
		客户:<select name="account" id="account" <?=$disabled?> class="BigSelect"><option value="">所有用户</option>
		<?php
		$sql = "select * from sheet1";
		$query = mysql_query($sql, $connection);
		while($row=mysql_fetch_assoc($query)) {
		?>
		<option value="<?=$row["name"]?>"><?=$row["name"]?></option><?php }?></select>
		<input type="button" id="bt2" value="查询送货单" class="SmallButton" onClick="QueryData();" />
		<input type="button" id="bt5" value="当天送货单" class="SmallButton" onClick="QueryDayOrders();" />
		<input type="button" id="bt3" value="刷新" class="SmallButton" onClick="window.location.reload();" />
		<input type="button" class="SmallButton" value="导出送货单" onClick="newExportData();" />&nbsp;&nbsp;
		<a href="note.php" class="SmallButton" target="_blank" style="display: inline-block;
    width: 95px;
    height: 22px;
    background: #4e95ff8a;
    border-radius: 5px;
    text-align: center;
    text-decoration: none;
    color: black;">新建送货单</a>
		<input type="hidden" name="push_fail" id="push_fail" value="" />
	</td>
</tr>
</table>
<div id="pageBar"></div>
<div id="resText"></div>
</form>
</body>
</html>