'use strict';

/**
 * Generalized Ajax Injection Fix built for KnProxy
 *
 * @license MIT
 * @author Jim Chen
 **/

(function () {
  /** Create namespace for injector variables **/
  var __injectorNamespace__ = {
    'rewrite': function (url) {
        console.log('[Debug] Rewriter got: ' + url);
        return url;
    }
  };

  var _proxyFetch = function (windowInstance) {
    const oldFetch = windowInstance.fetch;
    const requestClass = windowInstance.Request;

    return function fetch() {
      if (arguments.length > 0 && arguments[0] instanceof requestClass) {
        // Fake the request
        const oldRequest = arguments[0];
        const newRequest = new Request(
          __injectorNamespace__.rewrite(oldRequest.url),
          {
            'method': oldRequest.method,
            'headers': oldRequest.headers,
            'body': oldRequest.body,
            'mode': oldRequest.mode,
            'credentials': oldRequest.credentials,
            'cache': oldRequest.cache,
            'redirect': oldRequest.redirect,
            'referrer': oldRequest.referrer,
            'integrity': oldRequest.integrity
          });
        const newArguments = [];
        for (var i = 0; i < arguments.length; i++) {
          newArguments.push(i > 0 ?
            arguments[i] : newRequest);
        }
        return oldFetch.apply(windowInstance, newArguments);
      } else if (arguments.length > 0) {
        /* Must be dealing with a URL */
        const newArguments = [];
        for (var i = 0; i < arguments.length; i++) {
          newArguments.push(i > 0 ?
            arguments[i] : __injectorNamespace__.rewrite(arguments[i]));
        }
        return oldFetch.apply(windowInstance, newArguments);
      }
    };
  }

  var _proxyXHR = function (windowInstance) {
    const oldXHROpen = windowInstance.XMLHttpRequest.prototype.open;
    windowInstance.XMLHttpRequest.prototype.open = function () {
      const newArguments = [];
      for (var i = 0; i < arguments.length; i++) {
        newArguments.push(i === 1 ?
          __injectorNamespace__.rewrite(arguments[i]): arguments[i])
      }
      return oldXHROpen.apply(this, newArguments);
    }
    /** XHR Proto has been patched **/
    return windowInstance.XMLHttpRequest;
  }

  var setInjectorParam = function (key, value) {
    __injectorNamespace__[key] = value;
  }

  function injectAjaxFix(windowInstance, proxyFetch, proxyXHR) {
    windowInstance.__hasInjected__ = true;
    windowInstance.fetch = proxyFetch(windowInstance);
    windowInstance.XMLHttpRequest = proxyXHR(windowInstance);

    /**
     * We also make a sneaky edit to inject us into any "pristine" windows made
     *   via iframes.
     **/
    if ('document' in windowInstance) {
      const documentProto = Object.getPrototypeOf(windowInstance.document);
      const _savedFn = documentProto.createElement;
      documentProto.createElement = function () {
        const result = _savedFn.apply(this, arguments);
        if (arguments.length > 0 &&
          ('' + arguments[0]).toLowerCase() === 'iframe') {

          /** We made an iframe, inject into its contentwindow **/
          Object.defineProperty(result, 'contentWindow', {
            'get': function () {
              const proto = Object.getPrototypeOf(result);
              const superGetter = Object.getOwnPropertyDescriptor(proto,
                'contentWindow');
              const origContentWindow = superGetter.get.call(result);
              if (!origContentWindow) {
                /* If the super window is falsy, return it as-is */
                return origContentWindow;
              }
              /* Only do this if it has not been done before */
              if (!origContentWindow.__hasInjected__) {
                injectAjaxFix(origContentWindow, proxyFetch, proxyXHR);
              }
              return origContentWindow;
            },
            'set': function (contentWindow) {

            }
          });
        }
        return result;
      }
    }
  }

  injectAjaxFix(window, _proxyFetch, _proxyXHR);
})();
