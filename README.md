KnProxy
============

KnProxy is a lightweight, PHP-based web proxy that uses either cURL or PHP Sockets to proxy HTTP/HTTPS connections on a remote machine. 
It was created to achieve a high compatibility and ease of deployment and serves as a means to bypass the China GFW. 

It should work out of the box without modifications, though you can fine tune it to fit your specific needs.
Aside from the connecting ability module (which requires at least one of cURL, PHP Sockets or remote file reading to be available), KnProxy
does not rely on any other additional PHP extensions (Although optional page compression requires the compression module for PHP to be enabled). 
KnProxy includes a document parser, url parser and session management module all self contained.

KnProxy 某个诡异的网页代理
=============
KnProxy是一个轻量级的，基于PHP的网页代理。采用cURL或者PHP Sockets来将HTTP/HTTPS流量重新导流。KnProxy的设计意在实现最高的兼容性和最简单
的架设。KnProxy可用于临时快速架设一个穿越G - F - W的安全的网络代理。

KnProxy一般不需要任何更改就可使用，不过你可以在设置中对其进行微调。
