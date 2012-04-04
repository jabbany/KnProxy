<?php
include_once(dirname(__FILE__). '/../frames/knproxy_i18n.php');
function print_about_page($aboutType){
	if(defined('DISABLE_ABOUT_PAGES') && DISABLE_ABOUT_PAGES == 'true'){
		exit();
	}
	header('Content-Type: text/html');
	$a = substr($aboutType,6,strlen($aboutType));
	switch(strtolower($a)){
		case 'cookies':{
			echo base64_decode(knproxy_i18n('cookie_manager',KNPROXY_LANGUAGE));
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
		echo '<tr><td>Version</td><td>KnProxy 4.5</td></tr>';
		echo '<tr><td>GUI Language</td><td>' . KNPROXY_LANGUAGE . '</td></tr>';
		echo '<tr><td>Navbar Enabled</td><td>' . KNPROXY_NAVBAR . '</td></tr>';
		echo '<tr><td>GZIP Output</td><td>' . KNPROXY_USE_GZIP . '</td></tr>';
		echo '<tr><td>GZIP Input</td><td>' . KNPROXY_ACCEPT_GZIP . '</td></tr>';
		echo '<tr><td>Warn on HTTPS</td><td>' . KNPROXY_HTTPS_WARNING . '</td></tr>';
		echo '<tr><td>Cache ETag</td><td>' . KNPROXY_ETAG . '</td></tr>';
		echo '<tr><td>Cache Mode</td><td>' . KNPROXY_CACHE_MODE . '</td></tr>';
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
		}break;
		case 'debugging':{
			echo '<html>
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
<body><h2>Debugging Interface</h2><p>Debugging interface is where developers can use direct connections to fetch data. You may set up your own HTTP request headers and fetch raw return data.</p></body></html>';
		}break;
		case 'stream_cache':{
			echo '<html>
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
<body><h2>Stream Mode Cache</h2><p>Due to limitations in PHP, stream mode requires a cache to function. Files that have been streamed should be cleared from the cache, but interruptions may cause this to malfunction. Here is a list of the uncleaned cache files: </p><ul>';
			$count=0;
			$size=0;
			if($handle = opendir(dirname(__FILE__) .'/temp/'))
				while (false !== ($file = readdir($handle))) 
				{
					if ($file != "." && $file != '..' && $file != 'index.php') {
						echo '<li>' . $file . ' ('. filesize(dirname(__FILE__) .'/temp/'.$file) .' bytes)</li>';
					$size +=filesize(dirname(__FILE__) .'/temp/'.$file);
					$count++;
					}
				}
			echo '</ul><p>Total: ' . $count . ' file(s), taking up ' . $size . ' bytes.</p>';
			echo '<p><form action="" method="post"><input type="button" name="do" value="Clean Up Now"/></form></p>';
			echo '</body></html>';
		}break;
		case 'blank':
		default:{
			echo '';
		}
	}
}
?>