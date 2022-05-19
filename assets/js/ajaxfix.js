console.log('[KNPROXY] Loaded AjaxFix! Applying bulletproof wrapper for XHR.open.');

(function () {
    function encryptText(text, shift){
	    text = text.split("").reverse().join("");
	    var input = text;
	    if(shift==null)
		    shift = 0;
	    var chr1,chr2,a;
	    var output = "";
	    var keys = "z0y1x2w3v4u5t6s7r8q9pAoBnCmDlEkFjGiHhIgJfKeLdMcNbOaPQRSTUVWXYZ";
	    var key = keys.split("");
	    var length_input = input.length;
	    if(!length_input > 0)
		    return "";
	    for(a = 0;a<length_input;a++){
		    chr1 = (input.charCodeAt(a)+ shift) % key.length;
		    chr2 = (input.charCodeAt(a)+ shift - chr1) / key.length;
		    output += key[chr2] + key[chr1];
	    }
	    return output;
    }

    var key = Math.round(Math.random() * 100);

    var oldOpen= XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function(method, url) {
	    var args = [].slice.call(arguments, 0);
	    if (url.startsWith('index.php?url=')) {
	        console.log('[KNPROXY] Ajaxfix ignores "' + url + '"');
	        return oldOpen.apply(this, args);
	    } else {
	        console.log('[KNPROXY] Ajaxfix for "' + url + '"');
	    }
	    args[1] = 'index.php?url=' + encryptText(url, key) + '&encrypt_key=' + key;
	    return oldOpen.apply(this, args);
    }
})();
