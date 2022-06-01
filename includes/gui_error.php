<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
    <title>Internal Server Error - KnProxy</title>
    <style>
      body {
        font-family: Arial,Verdana, "Lucida Grande","Microsoft Yahei", Helvetica, sans-serif
      }
      em { color:#0000FF; }
      h2, .a { color:#FF0000; }
      .mono, .debug { font-family: monospace; }
      .debug {
        background-color: #FFFFCC;
        margin-top: 0px;
        padding: 10px;
        word-wrap: break-all;
        word-break: break-all;
        overflow: hidden;
      }
    </style>
    <script type="text/javascript">
      //<!--
      function togglePreview() {

      }
      //-->
    </script>
  </head>
  <body>
    <h2>
      <span style="color:#000000;"><?php
        if ($eobj['status'] < 1000) {
          echo 'Error';
        } else {
          echo 'Notice';
        }
      ?>: </span>
      <i><?php
        echo $errorMessages[(int) $eobj['status']];
      ?></i>
    </h2>

    <p>
      The server encountered an error or was interrupted while trying to fulfill
      your request for <span class="mono"><?php
        echo htmlspecialchars($eobj['request']['url']->toString());
      ?></span>.
    </p>
    <p>
      Please check that this is a valid URL.<br/>
      The following information is the technical data of the request:
    </p>
    <div class="debug"><?php
      if ($eobj['status'] < 1000) {
        echo ''
      } else {
        echo 'Dumping request and response data: <br/>';
        echo '<h3>Request:</h3>';
        echo '<em class="a">Method: </em> ' . $eobj['request']['method'] . '<br/>';
        echo '<em class="a">Scheme: </em> ' . $eobj['request']['url']->scheme . '<br/>';
        echo '<em class="a">Authority: </em> ' . $eobj['request']['url']->getAuthority() . '<br/>';
        echo '<em class="a">Path: </em> ' . $eobj['request']['url']->path . '<br/>';
        echo '<em class="a">Headers: </em><br/>';
        for ($eobj['request']['headers'] as $key=>$value) {
          echo '<em class="a">  ' . $key . '</em>: ' . $value . '<br/>';
        }
        echo '<h3>Response:</h3>';

      }
    ?></div>
    <p>Please refer to the FAQ for debugging tips.</p>
    <p>
      <a href="javascript:history.back();"><br>Go Back to Previous Page</a>
    </p>
  </body>
</html>
