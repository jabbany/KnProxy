<?php
  require_once('conf.php');
  require_once('includes/module_http.php');
  require_once('includes/module_request.php');

  /* Extract all the parameters from the request object */
  if (isset($_GET['q'])) {
    $url = $_GET['q'];
  }
  if (TRANSIENT_STORAGE === 'none') {

  }
?>
