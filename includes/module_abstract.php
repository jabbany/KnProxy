<?php
class stdParseEngine{
	private $type;
	private $url;
	private $source;
	function parse($type,$url,$source){
		//CANNOT BE CALLED STATICALLY
		return $source;//NO MODIFY
	}
	public function changeType($type){
		$this->type = $type;
	}
	public function setSource($source){
		$this->source = $source;
	}
}
?>