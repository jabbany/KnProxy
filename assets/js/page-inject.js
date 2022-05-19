'use strict';

/**
 * Script injected into the page to handle XHR rewrites and the such
 * = Must immediately follow knproxy-inject at the beginning of the document =
 * 
 * @license MIT
 * @author Jim Chen
 **/

(function (exports) {

  function UrlEncoder(prefix, key) {
    this._prefix = prefix;
    this._key = key;
  }

  UrlEncoder.prototype._encrypt = function (str) {
    
    
  }

  UrlEncoder.prototype.encode = function (url) {
    // First make sure the url is encoded
    const encodedUrl = encodeUri(url);
    return this._prefix + this._encrypt(encodedUrl);
  }

  UrlEncoder.prototype.decode = function (encoded) {
  
  }

  UrlEncoder.prototype.checkUrl = function (url) {
    /* 
      Checks to see if the URL has already been encoded.
     */
    return url.indexOf(this._prefix) === 0;
  }

  exports.__UrlEncoder__ = UrlEncoder;
})(window);
