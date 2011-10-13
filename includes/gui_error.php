<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Internal Server Error - KnProxy</title>
<style>
body {font-family: Arial,Verdana, "Lucida Grande","微软雅黑","Microsoft Yahei", Helvetica, sans-serif}
h2 { color:#FF0000; }
em {color:#0000FF; }
.a {color:#FF0000;}
</style>
</head>
<body>
<h2><span style="color:#000000;"><?php if($eobj['status']<1000){ echo 'Error';}else{ echo 'Proxy';}?>: </span><i><?php echo fetch_error((int)$eobj['status']);?></i></h2>
<p>The server met an error or was interrupted while trying to fulfill your request for <span style="font-family:monospace;"><?php echo preg_replace('~>~','&gt;',preg_replace('~<~','&lt;',$url));?></span>
<br>Please check that this is a valid URL and that the remote server is not down.<br>
The following information is the technical data of the request:
<div style="font-family:monospace;background-color:#FFFFCC;margin-top:0px;padding:10px;word-wrap:break-all;word-break:break-all;overflow:hidden"><?php if($eobj['status']<1000) { ?>cURL returned : Request for <em class="a"><?php echo $knHTTP->url;?></em> was processed returning the following header : <br><em><?php echo $knHTTP->headers;?></em> (<?php echo strlen($knHTTP->headers);?> bytes)<br>
The whole of content returned was <?php echo strlen($knHTTP->content);?> bytes of data. The Content-Type determined was <em><?php if($knHTTP->doctype){echo $knHTTP->doctype;}else{echo '(Could not determine)';}?></em>
<?php } else {
	$header = $knHTTP->parseHeader();
	echo 'Dumping knHTTP object: <br>';
	echo '<em class="a">knHTTP request:</em> ' . preg_replace('~>~','&gt;',preg_replace('~<~','&lt;',$knHTTP->url)) . '<em>(' . strlen($knHTTP->url) . ' bytes)</em><br>';
	if(isset($header['location']))
		echo '<em class="a">knHTTP redirect:</em> ' . $header['location'] . '<br>';
	echo '<em class="a">knHTTP is HTTPS mode:</em> ';
	if($knHTTP->is_secure)
		echo '<em>True</em>';
	else
		echo '<em>False</em>';
	echo '<br>';
	echo '<em class="a">knHTTP form post(urlencoded):</em> ' . $knHTTP->getPost() . '<em>(' . strlen($knHTTP->getPost()) . ' bytes)</em><br>';
	echo '<em class="a">knHTTP cookies:</em> ' . $knHTTP->getCookies() . '<em>(' . strlen($knHTTP->getCookies()) . ' bytes)</em><br>';
	echo '<em class="a">knHTTP headers:</em> <br>&nbsp;&nbsp;' . preg_replace('~\n\r*~','<br>&nbsp;&nbsp;',$knHTTP->headers) . '<em>(' . strlen($knHTTP->headers) . ' bytes)</em><br>';
	echo '<em class="a">knHTTP return body length:</em> <em>' . strlen($knHTTP->content) . ' bytes</em><br>';
	echo '<em class="a">knHTTP return body(html encoded):</em> <br>' . preg_replace('~\t~','&nbsp;&nbsp;&nbsp;&nbsp;',preg_replace('~\n\r*~','<br>',preg_replace('~<~','&lt;',$knHTTP->content))) . '<br>';
};?>
</div><a href="javascript:history.back();"><br>Go Back to Previous Page</a></p>
</body></html>