<?php
/**
 * Class for basic encrption/encoding of the address
 *
 */
class KnEncoder {

  private $key = '';
  private $nonce = '';
  function __construct() {

  }

  function setKey($key) {
    $this->key = $key;
  }

  function getKey() {
    return $this->key;
  }

  function encode($string) {

  }

  function decode($string) {

  }

	protected $val2k = Array();
	protected $k2val = Array();
	protected $myLoop;
	var $key=0;
	var $serverKey='';
	function __construct(){
		$this->val2k = str_split('z0y1x2w3v4u5t6s7r8q9pAoBnCmDlEkFjGiHhIgJfKeLdMcNbOaPQRSTUVWXYZ');
		$this->myLoop = count($this->val2k);
		$this->reverse_me();
		$this->key=0;
	}
}
?>
