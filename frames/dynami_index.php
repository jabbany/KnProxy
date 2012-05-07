<?php
include_once('knproxy_i18n.php');
include_once('../conf.php');
$_LANG = KNPROXY_LANGUAGE;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<style>
.copyright{font: 0.8em Verdana, "Lucida Grande", Arial, Helvetica, sans-serif;}
.options{font-size:0.95em; font-family:"Microsoft Yahei",Simsun,Simhei,Verdana,"Lucida Grande", Arial, Helvetica, sans-serif; }
.urlbox{background-color:#FFFFCC;color: #FF0000;font-family: Arial;font-weight: bold;font-size: 1em;}
<?php echo knproxy_translate('css',$_LANG,'.mymsg{font-family:Simhei,Verdana;}');?>
</style>
</head>
<body bgcolor="FFFFFF">
<br>
<p align="center"><img src="../logo_new.png" width="320" height="150"></p>
<h1 align="center" class="mymsg"><?php echo knproxy_i18n('message',$_LANG);?></h1>
<div align="center">
	<table border="0" width="50%" id="table1" class="copyright">
		<tr>
			<td valign="top" align="center">Copyright <?php echo date('Y'); ?> - Knh Internet Services Limited</td>
		</tr>
	</table>
</div>
</body>