<?php
error_reporting(0);
$APJP_KEY = 'knpv4v5compat';
$td = null;
$td = mcrypt_module_open(MCRYPT_ARCFOUR, '', MCRYPT_MODE_STREAM, '');
if(!$td){
	header('HTTP/1.0 500 Internal Server Error');
	die();
}
// INPUT
$handle1 = null;
$handle1 = fopen('php://input', 'r');
if(!$handle1)
{
	header('HTTP/1.0 500 Internal Server Error');
	
	die();
}
mcrypt_generic_init($td, $APJP_KEY, '');
$buffer1 = null;
$buffer2 = null;
$buffer3 = null;
$i = 0;
while(!feof($handle1))
{
	$buffer1 = fread($handle1, 1);
	
	if($buffer1 != null)
	{
		$buffer2 = $buffer2 . mdecrypt_generic($td, $buffer1);
		
		$i = $i + 1;
		
		if($i >= 4)
		{
			if
			(
				$buffer2[$i - 4] == "\r" && 
				$buffer2[$i - 3] == "\n" && 
				$buffer2[$i - 2] == "\r" && 
				$buffer2[$i - 1] == "\n"
			)
			{
				break;
			}
		}
	}
}
$handle2 = null;
if(preg_match('/\r\nHost: ([0-9a-z\.\-]+)(?:\:([0-9]+))?\r\n/i', $buffer2, $buffer3) != 0)
{
	if(count($buffer3) == 2)
	{
		$handle2 = fsockopen($buffer3[1], 80);
	}
	else
	{
		$handle2 = fsockopen($buffer3[1], $buffer3[2]);
	}
}
if(!$handle2)
{
	header('HTTP/1.0 500 Internal Server Error');
	
	die();
}
$buffer2 = preg_replace('/^([A-Z]+) https?:\/\/[^\/]+\//i', '$1 /', $buffer2);
$buffer2 = preg_replace('/HTTP\/1\.1\r\n/i', 'HTTP/1.0'."\r\n", $buffer2);
$buffer2 = preg_replace('/\r\nConnection: [^\r\n]+\r\n/i', "\r\n", $buffer2);
$buffer2 = preg_replace('/\r\nProxy-Connection: [^\r\n]+\r\n/i', "\r\n", $buffer2);
$buffer2 = preg_replace('/\r\nProxy-Authorization: [^\r\n]+\r\n/i', "\r\n", $buffer2);
$buffer2 = preg_replace('/\r\nTE: [^\r\n]+\r\n/i', "\r\n", $buffer2);
$buffer2 = preg_replace('/\r\nKeep-Alive: [^\r\n]+\r\n/i', "\r\n", $buffer2);
$buffer2 = preg_replace('/\r\n\r\n/i', "\r\n".'Connection: close'."\r\n\r\n", $buffer2);
fwrite($handle2, $buffer2);
$buffer1 = null;
$buffer2 = null;
$buffer3 = null;
while(!feof($handle1))
{
	$buffer1 = fread($handle1, 5120);
	
	if($buffer1 != null)
	{
		fwrite($handle2, mdecrypt_generic($td, $buffer1));
	}
}
mcrypt_generic_deinit($td);
fclose($handle1);
// OUTPUT
$handle3 = null;
$handle3 = fopen('php://output', 'w');
if(!$handle3)
{
	header('HTTP/1.0 500 Internal Server Error');
	
	die();
}
mcrypt_generic_init($td, $APJP_KEY, '');
$buffer1 = null;
$buffer2 = null;
$buffer3 = null;
while(!feof($handle2))
{
	$buffer1 = fread($handle2, 5120);
	
	if($buffer1 != null)
	{
		fwrite($handle3, mcrypt_generic($td, $buffer1));
	}
}
fclose($handle2);
mcrypt_generic_deinit($td);
fclose($handle3);
mcrypt_module_close($td);
?>