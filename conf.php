<?php
  /**
   * Main settings:
   *   GUI_LANGUAGE: language used for interface
   *   SHOW_NAVBAR: whether to show the navigation bar or not
   *       Note: showing the nav bar injects extra code into the page so please make sure to
   *   SERVER_KEY: key for server encryption
   *   COOKIE_KEY: key used to encode cookies sent to client
   **/

  define( 'GUI_LANGUAGE' , 'en-US' );
  define( 'SHOW_NAVBAR'  , 'true' );
  define( 'SERVER_KEY'   , 'CorrectHorseBatteryStaple' );
  define( 'COOKIE_KEY'   , 'CookieMonsterYumYum' );

  /**
   * Server behavior settings:
   *   WARN_HTTPS: warn the user when trying to initiate HTTPS connections
   *     always: show a warning before each connection
   *     once: show a warning the first time a user uses HTTPS
   *     never: silently process HTTPS sites
   *   USE_AJAXFIX: inject a js script to catch all ajax requests and proxy them
   *     Note: enabling or disabling this feature causes different compatibility issues
   *   HTML_PARSER: how pages/scripts are parsed
   *     regex: use regular expressions (can cause bugs)
   *     domxpath: use domxpath extension (potentially bad if pages are large)
   *   CSS_PARSER: how css is parsed
   *     regex: use regular expressions (can cause bugs)
   *     none: don't parse css (can cause different bugs)
   *   AUTO_PARSERS: how other files (js, json etc.) are parsed
   *     none: don't parse other non-binary files
   *     conservative: only use a whitelisted patterns db to parse
   *       Note: can cause bugs but usually safe, same as none if no db
   *     zealous: use general patterns ('if it looks like a URL, rewrite it')
   *       Note: not recommended unless used as single-purpose proxy
   *   REWRITE_MODE: how urls are identified and rewritten
   *     encode: encode target url directly into rewritten url
   *       Note: may produce links that are "too long"
   *         links are persistent and can be shared
   *     reference: encode target urls as references in a server session/db
   *       Note: not allowed unless TRANSIENT_STORAGE is session or database
   *         links WILL EXPIRE and should not be shared
   *   POST_GET_FORMS: how to handle GET forms (trade-offs for each)
   *     if enabled, GET forms are replaced with POST forms for the client and turned back into GET forms on the server
   *     if disabled, GET forms remain GET forms, any extra URL parameters are passed to remote server
   **/
  define( 'WARN_HTTPS'     , 'never' );
  define( 'USE_AJAXFIX'    , 'true' );
  define( 'HTML_PARSER'    , 'domxpath' );
  define( 'CSS_PARSER'     , 'regex' );
  define( 'AUTO_PARSERS'   , 'conservative' );
  define( 'REWRITE_MODE'   , 'encode' );
  define( 'POST_GET_FORMS' , 'true' );

  /**
   * Request settings:
   *   HTTP_VERSION: the max http version supported
   *     accept_all: Accepts any http version (http/*) curl uses but parses responses as http/1.1
   *     1.1: Forces requests to be made in http/1.1
   *   HTTP_CODE: what to do with the response code
   *     auto: show some errors in native error dialog if html, otherwise forward
   *     forward: always forward the response code as-is
   *     aok: always return 200 OK and only show the errors via error dialog
   *   HTTP_ACCEPT_GZIP: whether to accept gzip responses
   *     Note: disable if server does not have zlib extension
   *   HTTP_FORWARD_GZIP: whether to forward gzip responses on binary objects as-is
   *     Note: only works if gzip is accepted, if mime is a binary, do not unpack
   *   HTTP_ETAG_MODE:
   *     forward: forwards the etag and if-none-match tags as-is
   *     suppress: strips etags from response
   *     weaken: make strong etags weak (due to rewrite)
   *   HTTP_REFERER:
   *     none: do not send a referer header
   *     pseudo: always make the referer the host address in the url
   *     client: forwards referer as-is from client
   *     auto: forwards client referer but if proxy detected decodes the URL
   *   HTTP_RANGES:
   *     forward: forward range request to the server as-is (also passes along accept-ranges)
   *     binary_only: only forward range headers if the requested resource is a binary type
   *     suppress: strips any range related headers
   *   HTTP_ALT_HEADERS:
   *     forward: forward unrecognized headers as-is
   *     suppress: strip away any unrecognized headers
   *
   **/
  define( 'HTTP_VERSION'      , 'auto' );
  define( 'HTTP_CODE'         , 'auto' );
  define( 'HTTP_ACCEPT_GZIP'  , 'true' );
  define( 'HTTP_FORWARD_GZIP' , 'true' );
  define( 'HTTP_ETAG_MODE'    , 'forward' );
  define( 'HTTP_REFERER'      , 'pseudo' );
  define( 'HTTP_RANGES'       , 'binary_only' );
  define( 'HTTP_ALT_HEADERS'  , 'suppress' );

  /***
  * Server-side storage settings:
  *   TRANSIENT_STORAGE: where to store transient data (cookies, settings)
  *     none: do not store transient data, forward to client
  *     session: use PHP sessions to store cookies and config
  *     database: use a database to store cookies and config (dangerous!)
  *   CACHE_STORAGE: whether to cache static resources
  *     none: do not cache static resources
  *       Note: used when proxy host does not care about outgoing traffic
  *     files: smart cache static resources in files (not recommended)
  ***/
  define( 'TRANSIENT_STORAGE' , 'none' );
  define( 'CACHE_STORAGE'     , 'none' );

  /***
   * Response settings:
   *   ENCRYPT_PAGES: encrypt html pages
   *     Note: we recommend using https instead of native page encryption whenever possible.
   *           only enable if you cannot run proxy over https.
   *           the encryption only scrambles responses to foil pattern matchers, it provides no security or privacy.
   *   OUTPUT_GZIP: output gzip
   *     Note: highly discouraged, please use a server plugin to handle gzip
   ***/
  define( 'ENCRYPT_PAGES' , 'false' );
  define( 'OUTPUT_GZIP'   , 'false' );

  /**
   * Debugging and safety settings:
   *   ALLOW_DEBUGGING: allow debugging mode via a url parameter
   *   PREVIEW_CUTOFF: cutoff in bytes for preview of body in debug screen
   **/
  define( 'ALLOW_DEBUGGING' , 'true' );
  define( 'PREVIEW_CUTOFF'  , 32 * 1024 );

  /** These values are for adjusting the PHP runtime **/
  @ini_set( 'pcre.backtrack_limit' , 10000000 );
  @ini_set( 'pcre.recursion_limit' , 10000000 );
  @ini_set( 'memory_limit' , '128M' );
  @set_time_limit( 180 );
  @error_reporting( 0 );
?>
