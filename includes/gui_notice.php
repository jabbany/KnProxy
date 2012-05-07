<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Secure Connection - KnProxy</title>
<style>
body {font-family: Arial,Verdana, "Lucida Grande","Microsoft Yahei", Helvetica, sans-serif}
h2 { color:#FF0000; }
em {color:#0000FF; }
.a {color:#FF0000;}
</style>
</head>
<body>
<h2><span style="color:#000000;">Warning: </span><i>HTTPS Secure Connection Ahead</i></h2>
<p>You are about to enter a secure connection with the url : <span style="font-family:monospace;background-color:#00A000;color:#FFFFFF;"><?php echo $url;?></span> through knProxy.
<br><span class="a">Please take note that if you choose to continue, your security may be compromised! </span></p>
<p>Any data ( account data, cookies, passwords, transactions ) transmitted through this connection will be transmitted <strong>in cleartext</strong>. Hijacks of the connection by a third-party could, potentially,
endanger your security. This server cannot and will not record any data transferred, but your connection to the secure site will no longer be digitally protected by encryption.</p>
<p>The SSL certificate of the site will not be checked for validity so please take caution when inputting sensitive data.<br>
<strong>We strongly advise against visiting highly security-conscious sites (such as e-banks, stock exchange, payment sites etc.) through knProxy.</strong></p> 
<p>Are you sure you wish to proceed?
<div style="background-color:#FFFFCC;margin-top:0px;padding:10px;word-wrap:break-all;word-break:break-all;overflow:hidden">
<form name="ssl_Notice_form" action="" method="POST">
<?php 
foreach($_POST as $key=>$val){
	echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
}?>
<input type="submit" name="yes" value="Yes, I am aware of the risks.">&nbsp;&nbsp;&nbsp;<input type="button" name="no" onclick="history.back();" value="No, return to the previous page">&nbsp;&nbsp;&nbsp;<input type="submit" name="force_http" value="No, force http connections">
<br>Note: You will no longer be warned of secure connections if you choose "Yes".
</form>
</div></p>
</body>