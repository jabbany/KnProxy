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
	<script language="javascript" type="text/javascript" src="../js/denpa.js?3"></script>
	<script language="javascript" type="text/javascript" src="../js/denjihou.js?3"></script>
	<script language="javascript" type="text/javascript">
	//<!--
	function checkAndEncode(){
		if($ == null){
			console.log("Error: Inclusion of js file failed");
			alert("Resource Load Error");
			return false;
		}
		if($("urlx").value == "")
			return false;

		if($("check_enc").checked){
			var random = Math.floor(Math.random() * 256)+1;
			var url_value = $("urlx").value;
			$("url").value = encryptText(document.getElementById("urlx").value,random);
			$("encrypt_key").value = random;
			return true;
		}else{
			$("url").value = $("urlx").value;
			return true;
		}
	}
	//-->
	</script>
	<style>
	body{background: #000000;color: #ffffff;}
	a{color: #ffffff;}
	#urlx{position:relative; font-family: Arial;font-weight: bold;font-size: 1em;width:60%;}
	.mobilenoshow{float:right;padding-top:4px; padding-right:10px;}
	@media only screen and (max-width: 1200px) {
		#urlx{width:55%;}
	}
	@media only screen and (max-width: 1000px) {
		#urlx{width:45%;}
	}
	@media only screen and (max-width: 800px) {
		#urlx{position:absolute;left:90px;right:5px;width:auto;}
		.mobilenoshow{display:none;}
	}
	</style>
</head>
<body style="height:50px;position:fixed;top:0;left:0;right:0;">
	<form name="KN_BFORM" action="../index.php" method="GET" target="dynamic" onsubmit="return checkAndEncode();">
	<div style="height: 45px;padding: 0px; position:relative;">
		<input type="submit" value="<?php echo knproxy_i18n('navigate',$_LANG);?>" style="font-size: 1em;">
		<input type="hidden" id="url" name="url" value="" />
		<input type="text" id="urlx" value="http://" ondblclick="this.value='';">
		<div class="mobilenoshow">
			<input type="checkbox" id="check_enc" value="1" CHECKED><?php echo knproxy_i18n('encode',$_LANG);?>&nbsp;
			<input type="checkbox" name="debug" value="true"><?php echo knproxy_i18n('debug',$_LANG);?>
			<a href="javascript:;" onclick="top.location = top.dynamic.location;"><?php echo knproxy_i18n('hide_bar',$_LANG);?></a>
		</div>
		<input type="hidden" name="encrypt_key" id="encrypt_key" value="" />
	</div>
	</form>
</body>
</html> 