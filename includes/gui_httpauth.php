<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Authorization Required - KnProxy</title>
<style>
body {font-family: Arial,Verdana, "Lucida Grande","Microsoft Yahei", Helvetica, sans-serif}
h2 { color:#FF0000; }
em {color:#0000FF; }
.a {color:#FF0000;}
</style>
</head>
<body>
<h2><span style="color:#000000;">Notice: </span><i>401 Authorization Required</i></h2>
<div style="text-align:center;width:100%;background-color:#CCCC99;margin-top:0px;padding:0px;">
<div style="width:100%;background-color:#CCCCFF;"><strong>The address you are visiting, <span style="font-family:monospace;background-color:#00A000;color:#FFFFFF;"><?php echo $url;?></span> requires you to provide a user name and/or password!</strong><br>
Please take note that this is not a secure connection on either side. You are as secure as you would be without using KnProxy.
</div>
<br>
<form name="login" action="" method="POST">
<strong>Login for <?php if(isset($realm)&& $realm!='' ){echo $realm;}else{echo $url;}?></strong><br>
Username: <input type="text" value="" name="knUSER"><br>
Password: <input type="password" value="" name="knPASS"><br>
<input type="submit" value="Login" name="subbtn"><br>
&nbsp;
</form>
</div>
<a href="javascript:history.back();">Return to previous page</a>
</body>