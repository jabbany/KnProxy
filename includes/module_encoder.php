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
}
?>
