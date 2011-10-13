<?php
function print_about_page($aboutType){
	if(defined('DISABLE_ABOUT_PAGES') && DISABLE_ABOUT_PAGES == 'true'){
		exit();
	}
	$a = substr($aboutType,6,strlen($aboutType));
	switch(strtolower($a)){
		case 'cookies':{
			echo '<html><body><h2>Cookie Manager</h2></body></html>';
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
<body><h2>About:SysInternals</h2><p>Here you can find default values for your installation of KnProxy. To disable this page, please set \'DISABLE_ABOUT_PAGES\' to true in the conf.php file!</p><table width="100%" cellpadding="0" cellspacing="0">';
		echo '<tr><th width="300px">Key</th><th>Value</th></tr>';
		echo '<tr><td>Version</td><td>KnProxy 4.34</td></tr>';
		echo '<tr><td>GUI Language</td><td>' . KNPROXY_LANGUAGE . '</td></tr>';
		echo '<tr><td>Url Bar Enabled?</td><td>' . ALLOW_NAVBAR . '</td></tr>';
		echo '<tr><td>Hide \'REFERER\'?</td><td>' . NO_REFERER . '</td></tr>';
		echo '<tr><td>GZIP output?</td><td>' . USE_GZIP . '</td></tr>';
		if(function_exists('curl_init')){
			echo '<tr><td>cURL enabled?</td><td style="color:#00a000;">true</td></tr>';
			$cv=curl_version();
			echo '<tr><td>cURL Version</td><td>' . $cv['version']. '</td></tr>';
		}else{
			echo '<tr><td>cURL enabled?</td><td style="color:#ff0000;">false</td></tr>';
		}
		echo '<tr><td>PHP Version</td><td>' .phpversion() .'</td></tr>';
		echo '</table>';
		echo '<a href="javascript:history.back();"><br>Go Back to Previous Page</a></p>
</body></html>';
		}
		case 'blank':
		default:{
			echo '';
		}
	}
}
?>