<?php
class KnUrl {
  public $scheme = 'http';
  public $userinfo = '';
  public $host = '';
  public $port = '';
  public $path = '';
  public $query = '';
  public $fragment = '';

  function __construct($url, $autoScheme = true) {
    // If autoscheme is true, infer the scheme from the port if not provided

  }

  private function _decomposeUrl($url) {

  }

  public function makeAbsolute($otherUrl) {

  }

  public function isWebProtocol($assumeWeb = true) {
    return $this->scheme === 'http' || $this->scheme === 'https' ||
      ($assumeWeb && $this->scheme === '');
  }

  public function isRelative() {
    // Figure out if the current uri is relative
    return $this->getAuthority() === '';
  }

  public function getAuthority() {
    if ($this->userinfo === '' && $this->host === '' && $this->port === '') {
      return ''; // There is no authority component
    }
    $authority = '//';
    if ($this->userinfo !== '') {
      $authority .= $this->userinfo . '@';
    }
    if ($this->host !== '') {
      $authority .= $this->host;
    }
    if ($this->port !== '') {
      $authority .= ':' . $this->port;
    }
    return $authority;
  }

  public function getQueryString() {
    return $this->query === '' ? '' : ('?' . $this->query);
  }

  public function getFragment() {
    return $this->fragment === '' ? '' : ('#' . $this->fragment);
  }

  public function toString() {
    // Build the url string
    return $this->scheme . ':' . $this->getAuthority() . $this->path .
      $this->getQueryString() . $this->getFragment();
  }

}
?>
