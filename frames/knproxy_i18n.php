<?php
function knproxy_i18n($string,$lang)
{
	if(!file_exists(dirname(__FILE__).'/../includes/i18n/' . $lang . '.ktr')){
		return;//FAIL
	}
	$f=@file_get_contents(dirname(__FILE__).'/../includes/i18n/' . $lang . '.ktr');
	$f_arr=explode("\n",$f);
	foreach($f_arr as $f_)
	{
		$f_ = explode('|',$f_);
		if($f_[0]==strtolower($string))
		{
			return $f_[1];
		}
	}
	return false;
}
function knproxy_translate($text,$lang,$replacement)
{
	$a=knproxy_i18n($text,$lang);
	if($a)
		return $a;
	else
		return $replacement;
}
?>