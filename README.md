# KnProxy

KnProxy is a lightweight, batteries-included, PHP-based rewriting web proxy that
uses either cURL or PHP sockets to relay HTTP/HTTPS connections through a remote
machine. It was created to achieve a relatively high compatibility in its class
of proxies with considerations for ease of deployment and sane defaults.

To deploy, just upload the files from this repository onto a server with PHP
enabled, and it should just work.

## What is a Rewriting Proxy?

A _rewriting_ web proxy is a _primitive_ type of web proxy implementation
designed to allow websites to be relayed through it without any client (browser)
configuration. It has the following distinctions from other types of proxies:

- **Transparent Web Proxy**: A transparent web proxy forwards content between
    remote server and client _without modification_. Because the nominal
    location of the resource differs from the actual target of the request,
    these proxies usually must be configured before hand to coerce a client to
    communicate with the proxy server rather than directly with the specified
    resource. This could be in the form of a direct configuration via the
    client (browser setting), or done through a system group policy (usually
    by IT departments). In contrast, a _rewriting_ proxy cannot assume the
    client will request foreign resources from it on its own, so to ensure that
    future requests go through the proxy, the proxy must _modify_ content sent
    through it, replacing references to foreign resources with references to
    the proxy itself.
- **SOCKS Proxy**: A SOCKS proxy, or similar TCP based proxy, connects a single
    incoming connection to it onto a single outgoing connection to another
    server. These proxies are _protocol agnostic_, meaning they do not need to
    understand the data being transmitted through it. A _rewriting_ proxy,
    however, works on a higher level. It must interpret what it is sending so as
    to replace any links inside to different targets. KnProxy specifically
    provides rewriting for HTML, CSS and some limited other text data types.
- **VPN**: A VPN simulates an extension of a physical network. It allows
    machines not on the same network to behave as if they were. Just like a TCP
    connection based proxy, a VPN goes a level lower and does not even need to
    know details about the sources and targets of the connections being made
    through it.

## Should I use KnProxy?

In modern times, the answer is almost always `no`.

Rewriting proxies like KnProxy make significant trade-offs in compatibility,
privacy and security for the very limited benefit of requiring absolutely no
change to the client.

Do NOT use a rewriting proxy if:
- You are concerned about privacy:
    Rewriting proxies are not private. As a consequence of their mode of
    operation, they have access to not only the metadata of the connection
    (source and target), but every bit of information transferred in the clear.

- You are concerned about security:
    Rewriting proxies must have access to read and change the cleartext content.
    This means they act as a "man-in-the-middle" and strip away all protections
    offered by modern encryption technologies like HTTPS.

- You are concerned about integrity and faithfulness:
    Rewriting proxies make only a best-effort edit of the content to facilitate
    redirection. This means they may easily introduce errors into the content
    inadvertently. It is almost guaranteed that something will go wrong.

Maybe consider a rewriting proxy if:
- You cannot control any configuration:
    If you cannot install tunneling software on an operating system level and
    are also prevented from changing your browser proxy settings, a rewriting
    proxy may be your only choice.

- You need to bootstrap your proxy game:
    If software or knowledge of how to configure better proxy mechanisms is
    unavailable to you, KnProxy can give you the opportunity to download and
    learn to configure a better proxy solution.

- You want to be discreet:
    Access patterns to a rewriting proxy look almost identical to that of
    accessing a standard website. Unlike a SOCKS proxy or VPN, they require no
    special handshake and expose nothing beyond what looks like a web server.

# KnProxy 某个诡异的网页代理

KnProxy是一个轻量级的，基于PHP的网页代理。它可以通过 cURL 或者 PHP Sockets 来将
HTTP/HTTPS 的内容进行重新传导。KnProxy的设计目标是在同类基于改写的网页代理中，提供相对高的
还原度并保持简洁的部署。KnProxy 的最初目标是用于临时快速架设一个简单粗暴的穿越 G-F-W 的
网络代理。

KnProxy一般不需要任何更改就可使用，不过你可以在设置中对其进行微调。

# License
KnProxy is licensed under the [MIT License](http://opensource.org/licenses/MIT)
