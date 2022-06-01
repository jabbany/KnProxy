<?php

class KnRewriter {
  private $responseCode = 200;
  private $headers = Array();
  private $cookies = Array();
  private $body = '';
  private $response;

  function __construct($response) {
    $this->response = $response;
  }

  public function respond() {
    // Note: This method must be called as the only thing in the index

    // Set all the headers

    // Set cookies if needed

    // Save stuff in the session

    // Output content

  }
}

?>
