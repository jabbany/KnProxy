<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Internal Server Error - KnProxy</title>
<style>
body {font-family: Arial,Verdana, "Lucida Grande","Microsoft Yahei", Helvetica, sans-serif}
h2 { color:#FF0000; }
em {color:#0000FF; }
.a {color:#FF0000;}
</style>
<script type="text/javascript">
//<!--
var k={tb:function(){var a = document.getElementById('ret_body');if(!a){alert();return;}if(a.style.display==""){a.style.display="none";}else{a.style.display="";}}};
//-->
</script>
</head>
<body>
<h2><span style="color:#000000;"><?php if($eobj['status']<1000){ echo 'Error';}else{ echo 'Proxy';}?>: </span><i><?php echo fetch_error((int)$eobj['status']);?></i></h2>
<p>The server met an error or was interrupted while trying to fulfill your request for <span style="font-family:monospace;"><?php echo preg_replace('~>~','&gt;',preg_replace('~<~','&lt;',$url));?></span>
<br>Please check that this is a valid URL and that the remote server is not down.<br>
The following information is the technical data of the request:
<div style="font-family:monospace;background-color:#FFFFCC;margin-top:0px;padding:10px;word-wrap:break-all;word-break:break-all;overflow:hidden"><?php if(false && $eobj['status']<1000) { ?>cURL returned : Request for <em class="a"><?php echo $knHTTP->url;?></em> was processed returning the following header : <br><em><?php echo $knHTTP->headers;?></em> (<?php echo strlen($knHTTP->headers);?> bytes)<br>
The whole of content returned was <?php echo strlen($knHTTP->content);?> bytes of data. The Content-Type determined was <em><?php if($knHTTP->doctype){echo $knHTTP->doctype;}else{echo '(Could not determine)';}?></em>
<?php } else {
	$header = $knHTTP->refined_headers();
	echo 'Dumping knHTTP object: <br>';
	echo '<em class="a">knHTTP request type:</em> ' . (count($knHTTP->http_post) == 0 ? "GET" : "POST") . '<br>';
	echo '<em class="a">knHTTP request:</em> ' . preg_replace('~>~','&gt;',preg_replace('~<~','&lt;',$knHTTP->url)) . '<em>(' . strlen($knHTTP->url) . ' bytes)</em><br>';
	if(isset($header['HTTP_LOCATION']))
		echo '<em class="a">knHTTP redirect:</em> ' . $header['HTTP_LOCATION'] . '<br>';
	echo '<em class="a">knHTTP content type:</em> ' . $knHTTP->doctype . '<br>';
	echo '<em class="a">knHTTP is HTTPS mode:</em> ';
	if($knHTTP->is_https)
		echo '<em>True</em>';
	else
		echo '<em>False</em>';
	echo '<br>';
	echo '<em class="a">knHTTP form post(urlencoded):</em> ' . $knHTTP->getPost() . '<em>(' . strlen($knHTTP->getPost()) . ' bytes)</em><br>';
	echo '<em class="a">knHTTP cookies:</em> ' . $knHTTP->getCookies() . '<em>(' . strlen($knHTTP->getCookies()) . ' bytes)</em><br>';
	echo '<em class="a">knHTTP headers:</em> <br>&nbsp;&nbsp;' . preg_replace('~\n\r*~','<br>&nbsp;&nbsp;',$knHTTP->headers) . '<em>(' . strlen($knHTTP->headers) . ' bytes)</em><br>';
	echo '<em class="a">knHTTP parsed headers:</em>';
	foreach($knHTTP->refined_headers() as $key=>$value){
		if(!is_array($value))
			echo '<br>&nbsp;&nbsp;' . $key . ': ' . $value;
		else
			echo '<br>&nbsp;&nbsp;' . $key . ': (Truncated)';
	}
	echo '</br>';
	echo '<em class="a">knHTTP return body length:</em> <em>' . strlen($knHTTP->content) . ' bytes</em><br>';
	if(substr($knHTTP->doctype,0,6) =='audio/' || substr($knHTTP->doctype,0,6) =='video/' || strlen($knHTTP->content) > 40 * 1024 * 1024){
		echo '<em class="a">knHTTP return body(partial):</em> <br>' . substr($knHTTP->content,0,(int)KNPROXY_BINARY_CUTOFF) . '<br>';
	}else
		echo '<em class="a">knHTTP return body(html encoded):</em> <a href="javascript:void(0);" onclick="k.tb();">Show/Hide</a><div id="ret_body" style="display:none;">' . preg_replace('~\t~','&nbsp;&nbsp;&nbsp;&nbsp;',preg_replace('~\n\r*~','<br>',preg_replace('~<~','&lt;',$knHTTP->content))) . '</div>';
};?>
</div><a href="javascript:history.back();"><br>Go Back to Previous Page</a></p>
</body></html>