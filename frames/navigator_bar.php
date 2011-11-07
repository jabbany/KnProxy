<?php
include_once('knproxy_i18n.php');
include_once('../conf.php');
if(!defined('KNPROXY_NAVBAR') || KNPROXY_NAVBAR!='true'){
	header('HTTP/1.1 404 Not Found');
	exit('<html><title>404 Not Found</title><h1>404 Not Found</h1></html>');
}
$_LANG = KNPROXY_LANGUAGE;
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<script language="javascript" type="text/javascript" src="../js/denpa.js?<?php echo time();?>"></script>
<script language="javascript" type="text/javascript" src="../js/denjihou.js?<?php echo time()-56;?>"></script>
<script language="javascript" type="text/javascript">
//<!--
function checkAndEncode(){
	if(document.getElementById("check_enc").checked){
		var random = Math.floor(Math.random() * 256)+1;
		var url_value = document.getElementById("url_").value;
		document.getElementById("url").value = encryptText(document.getElementById("url_").value,random);
		document.getElementById("encrypt_key").value = random;
		return true;
	}else{
		document.getElementById("url").value = document.getElementById("url_").value;
		return true;
	}
}
//-->
</script>
<style>body{background: #000000;color: #ffffff;}a{color: #ffffff;}</style></head>
<body style="height:50px;position:fixed;top:0;left:0;width:100%;">

<form name="KN_BFORM" action="../index.php" method="GET" target="dynamic" onsubmit="return checkAndEncode();">
<div style="height: 45px;padding: 0px;">
<input type="submit" value="<?php echo knproxy_i18n('navigate',$_LANG);?>" style="font-size: 1em;">
<input type="hidden" id="url" name="url" value="" />
<input type="text" id="url_" size="65%" value="http://" ondblclick="this.value='';" style="font-family: Arial;font-weight: bold;font-size: 1em;">
<input type="checkbox" id="check_enc" value="1" CHECKED><?php echo knproxy_i18n('encode',$_LANG);?>&nbsp;
<input type="checkbox" name="debug" value="true"><?php echo knproxy_i18n('debug',$_LANG);?>
<div style="float:right;height:44px;padding-top:5px;padding-right:5px;" valign="center"><a href="javascript:;" onclick="top.location = top.dynamic.location;"><?php echo knproxy_i18n('hide_bar',$_LANG);?></a></div>
<input type="hidden" name="encrypt_key" id="encrypt_key" value="" />
</div>
</form>

</body>
</html> 