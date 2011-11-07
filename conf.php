<?php
define('KNPROXY_SECRET','joWVexlW4fHeH/2GGNLefy8bV7JFaaTTF92AWp1k0jDsMqC8tqeAvdLo/gg');
define('KNPROXY_LANGUAGE','zh-CN');//Set the language for Knproxy!
define('KNPROXY_USE_GZIP','true');
define('KNPROXY_ACCEPT_GZIP','true');
define('KNPROXY_NAVBAR','true');
define('KNPROXY_HTTPS_WARNING','on');//on, off
define('KNPROXY_REFERER','pseudo');//Pseudo, Disable or auto
define('KNPROXY_ENCRYPT_PAGE','false');//是否加密页面，一般来说GFW不强烈时没必要启用

define('OPTIMIZE_JAVASCRIPT','false');//优化JS（去除注释，降低下载量）
define('ENABLE_JS_PARSING','true');

/** These values are for debugging **/
define('KNPROXY_BINARY_CUTOFF',32*1024);//Cutoff of 32kb for binary data

/** These values are for adjusting the PHP runtime **/
@ini_set('pcre.backtrack_limit', 10000000);
@ini_set('pcre.recursion_limit', 10000000);
@ini_set('memory_limit','128M');
@set_time_limit(180);
@error_reporting(0);
?>