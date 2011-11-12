<?php
//This is put here to prevent virtual directory listings
header('HTTP/1.1 404 Not found');
//Fake a 404
echo '<html><head><title>404 Not Found</title><body><h2>404 Not Found</h2></body></html>';
exit();
?>