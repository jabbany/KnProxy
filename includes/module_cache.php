<?php
class knCache{
	$cache_location = "";
	$address_key = "";
	function is_cached($url=false){
		//Generate the URL's key
		if($url !== false)
			$this->address_key = md5($url);
		if(!file_exists(dirname(__FILE__) . '/cache/' . $this->address_key))
			return false;
		return true;
	}
	function is_expired($url,$expires=false){
		if(!$this->is_cached($url))
			return true;
		$fp = fopen(dirname(__FILE__) . '/cache/' . $this->address_key)
	}
}
?>