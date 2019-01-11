
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>与狼共舞_增加客户</title>
        <link href="css/404.css" rel="stylesheet" type="text/css" />
        <script src="js/jquery-1.7.2.min.js"></script>
       <script>
	
	function ss(){
		var wait = $("#user").val();
		if(wait==""){
			alert("客户不能为空");
			return false;
		}else{
			return true;
		}
		
	}
	</script>
    </head>
    <body>
       <form action="p_add_do.php" method="post">
	   
	   <div>
	   客户名称:<input name="username" id="user"/>
	   <input type="submit" onclick="return ss()" value="提交"/>
	   </div>
	   
	   </form>
    </body>
	
</html>