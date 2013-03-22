var oldOpen= XMLHttpRequest.prototype.open;
XMLHttpRequest.prototype.open = function(method, url, async, username, password){
	console.log(url);
	oldOpen.call(this, method, url, async, username, password);
}
