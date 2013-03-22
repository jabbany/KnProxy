<?php
define('KNPROXY_SECRET','joWVexlW4fHeH/2GGNLefy8bV7JFaaTTF92AWp1k0jDsMqC8tqeAvdLo/gg');
// Set the language for KnProxy (currently supports en-US and ja-JP)
define('KNPROXY_LANGUAGE','zh-CN');
// Should the server use GZIP when outputting text data?
define('KNPROXY_USE_GZIP','true');
// Should the server accept remote gzipped files ?
define('KNPROXY_ACCEPT_GZIP','true');
// Enable the navigation bar?
define('KNPROXY_NAVBAR','true');
/***
* Client Side Cache
* Values:
*   forward - Forwards ETag if it exists in response, otherwise ignores
*   generate - Generates an ETag (just the MD5sum of file) if ETag doesn't exist else forward
*   suppress - Ignores all ETags and force the client to do fresh requests
*   cache - Uses a cache mechanism on server or forward if exists
***/
define('KNPROXY_ETAG','forward');

/***
* Cache Mode
* Values:
*   none - No server-side cache
*   smart - Caches resources without ETag (client cache) and by access frequency
*   userdef - Only caches content in user-defined paths
***/
define('KNPROXY_CACHE_MODE','none');

//Show a warning when using HTTPS for the first time?
define('KNPROXY_HTTPS_WARNING','off');//on, off
// Referer mode
define('KNPROXY_REFERER','pseudo');//pseudo, disable, auto
define('KNPROXY_ENCRYPT_PAGE','false');//是否加密页面，一般来说GFW不强烈时没必要启用

//Only set to false if errors occur on JS-Rich pages 
define('OPTIMIZE_JAVASCRIPT','false');//优化JS（去除注释，降低下载量）
define('ENABLE_JS_PARSING','true');
define('ENABLE_INJECTED_AJAXFIX','false');

/** These values are for debugging **/
define('KNPROXY_BINARY_CUTOFF',32*1024);//Cutoff of 32kb for binary data

/** These values are for adjusting the PHP runtime **/
@ini_set('pcre.backtrack_limit', 10000000);
@ini_set('pcre.recursion_limit', 10000000);
@ini_set('memory_limit','128M');
@set_time_limit(180);
@error_reporting(0);
?>
