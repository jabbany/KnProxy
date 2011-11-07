<?php
function print_about_page($aboutType){
	if(defined('DISABLE_ABOUT_PAGES') && DISABLE_ABOUT_PAGES == 'true'){
		exit();
	}
	$a = substr($aboutType,6,strlen($aboutType));
	switch(strtolower($a)){
		case 'cookies':{
			echo '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Cookie Manager</title>
<script type="text/javascript">
function delCookie(cname){
	document.cookie=escape(cname) + "=deleted; Expires=Thu, 01-Jan-1970 00:00:01 GMT;";
	document.cookie=escape(cname) + "=deleted; Expires=Thu, 01-Jan-1970 00:00:01 GMT; path=/;";
}
function delAllCookies(){
	if(document.cookie=="")
		return;
	var arr=document.cookie.split(";");
	for(i=0;i<arr.length;i++){
		var tmp = arr[i].split("=");
		document.cookie=delCookie(tmp[0]);
	}
	alert("Cookies Deleted!");
}
function cookieList(){
	var arr=document.cookie.split(";");
	var output="<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><th>Cookie Name</th><th width=\"500\">Value</th><th width=\"100\">Delete?</th></tr>";
	for(i=0;i<arr.length;i++){
		var tmp = arr[i].split("=");
		if(tmp.length>1){
			output +="<tr><td>" + escape(tmp[0].replace(/^\\s/,\'\')) + "</td><td>" + escape(tmp[1]) + "</td><td><a href=\"javascript:delCookie(\'" + tmp[0] + "\');document.getElementById(\'cookies\').innerHTML = cookieList();\">Delete</a></tr>"; 
		}else{
			//invalid cookie
		}
	}
	if(arr.length==0){
		output +="<tr><td>(Cookie Jar Empty)</td><td>N/A</td><td></td></tr>";
	}
	output+="</table>";
	return output;
}
</script>
<style>
body {font-family: Arial,Verdana, "Lucida Grande","微软雅黑","Microsoft Yahei", Helvetica, sans-serif}
em {color:#0000FF; }
.a {color:#FF0000;}
table td,table th{border:1px solid #000;padding:3px;}
th{background:#ccccff;}
</style>
</head>
<body><h2>Cookie Manager</h2>
<p>Please use the cookie manager to manage your cookies on KnProxy. KnProxy does not always respect expiry times and will keep cookies as long as possible. All cookies are global in KnProxy. Deleting them frees up bandwidth and protects privacy.</p>
<p><a href="javascript:delAllCookies();" onclick="delAllCookies();">Delete All Cookies</a></p>
<h3>Current Cookies</h3><p id="cookies"></p>
<script type="text/javascript">document.getElementById("cookies").innerHTML = cookieList();</script>
<p>Please note that KnProxy uses a few cookies to mark status info: knproxy_ssl_warning, knLogin. Deleting these cookies may cause you to be logged out of a previous session!</p>
</body></html>';
		}break;
		case 'sysinternals':{
			echo'<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Internal Server Error - KnProxy</title>
<style>
body {font-family: Arial,Verdana, "Lucida Grande","微软雅黑","Microsoft Yahei", Helvetica, sans-serif}
h2 { color:#FF0000; }
em {color:#0000FF; }
.a {color:#FF0000;}
table td,table th{border:1px solid #000;padding:3px;}
th{background:#ccccff;}
</style>
</head>
<body><h2>About : SysInternals</h2><p>Here you can find default values for your installation of KnProxy. To disable this page, please set \'DISABLE_ABOUT_PAGES\' to true in the conf.php file!</p><table width="100%" cellpadding="0" cellspacing="0">';
		echo '<tr><th width="300px">Key</th><th>Value</th></tr>';
		echo '<tr><td>Version</td><td>KnProxy 4.40 - Beta</td></tr>';
		echo '<tr><td>GUI Language</td><td>' . KNPROXY_LANGUAGE . '</td></tr>';
		echo '<tr><td>Navbar Enabled</td><td>' . KNPROXY_NAVBAR . '</td></tr>';
		echo '<tr><td>GZIP Output</td><td>' . KNPROXY_USE_GZIP . '</td></tr>';
		echo '<tr><td>GZIP Input</td><td>' . KNPROXY_ACCEPT_GZIP . '</td></tr>';
		echo '<tr><td>Warn on HTTPS</td><td>' . KNPROXY_HTTPS_WARNING . '</td></tr>';
		echo '<tr><td>Referer mode</td><td>' . KNPROXY_REFERER . '</td></tr>';
		if(function_exists('curl_init')){
			echo '<tr><td>cURL enabled?</td><td style="color:#00a000;">true</td></tr>';
			$cv=curl_version();
			echo '<tr><td>cURL Version</td><td>' . $cv['version']. '</td></tr>';
		}else{
			echo '<tr><td>cURL enabled?</td><td style="color:#ff0000;">false</td></tr>';
		}
		echo '<tr><td>Stream Mode</td><td>' . KNPROXY_STREAMING_AVAILABLE . '</td></tr>';
		echo '<tr><td>PHP Version</td><td>' .phpversion() .'</td></tr>';
		if(function_exists('memory_get_usage'))
			echo '<tr><td>Memory Usage</td><td>' . memory_get_usage() .' bytes</td></tr>';
		echo '<tr><td>Memory Limits</td><td>' . @ini_get('memory_limit') . '</td></tr>';
		echo '</table>';
		echo '<a href="javascript:history.back();"><br>Go Back to Previous Page</a></p>
</body></html>';
		}
		case 'debugging':{
			echo '<h1>Debugging Interface</h1>';
		}break;
		case 'blank':
		default:{
			echo '';
		}
	}
}
?>