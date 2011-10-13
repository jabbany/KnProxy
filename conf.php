<?php
define('KNEN_SECRET','joWVexlW4fHeH/2GGNLefy8bV7JFaaTTF92AWp1k0jDsMqC8tqeAvdLo/gg');
define('KNPROXY_LANGUAGE','zh-CN');//Set the language for Knproxy!
define('USE_GZIP','true');
define('ALLOW_YOUTUBE','true');
define('ENABLE_JS_PARSING','true');
define('ACCEPT_ENCODING_GZIP','true');
define('ALLOW_NAVBAR','true');
define('NO_REFERER','true');
define('OPTIMIZE_JAVASCRIPT','false');//优化JS（去除注释，降低下载量）

/**********SET DEFAULT RUNTIME THINGS*************/
@ini_set('pcre.backtrack_limit', 10000000);//Prevent REGEXs from exceeding limit
@ini_set('pcre.recursion_limit', 10000000);
@ini_set('memory_limit','64M');//INCREASE MEMORY LIMIT
@set_time_limit(180);//Run for 3 minutes at Most
/**
* 如果你访问的一些网站页面内嵌JS或CSS代码超过 100k 字符，有些服务器不允许执行正则表达式来处理它们。
* 这些情况下，服务器可能会返回完全空白的页面
* 如果你的服务器反复出现完全空白页面，请取消下面一行的注释，这将取消JAVASCRIPT解析
**/
//define('DISABLE_JAVASCRIPT_REGEX','true');

?>