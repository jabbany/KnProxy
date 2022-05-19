function $(e){
	return document.getElementById(e);
}

function utf8Decode(utftext) {
	var string = "";
	var i = 0;
	var c = c1 = c2 = 0;
	while ( i < utftext.length ) {
		c = utftext.charCodeAt(i);
		if (c < 128) {
			string += String.fromCharCode(c);
			i++;
		} else if((c > 191) && (c < 224)) {
			c2 = utftext.charCodeAt(i+1);
			string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
			i += 2;
		} else {
			c2 = utftext.charCodeAt(i+1);
			c3 = utftext.charCodeAt(i+2);
			string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
			i += 3;
		}
	}
	return string;
}

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