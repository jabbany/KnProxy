<?php
/*************
 * HTTP REQUEST MODULE FOR KNPROXY THETA
 * AUTHOR: CQZ
 **************/
@include_once('class_stream.php');
class knHttp{
	var $url='';
	var $is_https=false;
	var $user_agent='';
	var $cookies = Array();
	var $httpauth = "";
	var $http_post = Array();
	var $http_get = '';
	var $ranges = false;
	var $request_headers = Array();
	protected $referer = '';
	protected $streaming = false;
	protected $mode = 'curl';
	/* Return Values */
	var $content;
	var $headers;
	var $doctype;
	function __construct($url,$streaming = false){
		$this->url = $url;
		$this->streaming = $streaming;
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->set_referer(KNPROXY_REFERER);
		if(strtolower(substr($this->url,0,6))=='https:')
			$this->is_https=true;
		else
			$this->is_https=false;
		if(!function_exists('curl_init'))
			$this->mode = 'filesockets';
	}
	function set_request_headers($header = array()){
		if(!is_array($header) || count($header)<2)
			return;
		$this->request_headers[$header[0]] = $header[1];
		return;
	}
	function force_mode($mode = 'filesockets'){
		$this->mode = $mode;
	}
	function set_referer($referer = 'none'){
		switch($referer){
			case 'pseudo': $this->referer = $this->url;break;
			case 'none': $this->referer = '';break;
			case 'auto': $this->referer = '';break;
			default:return;
		}
	}
	function set_url($url){
		$this->__construct($url);
	}
	function set_cookies($cookies){
		$this->cookies = $cookies;
	}
	function set_post($post){
		$this->http_post=$post;
	}
	function set_get($getArray){
		$get=Array();
		foreach($getArray as $key=>$value){
			$get[]=urlencode($key) . '=' . urlencode($value);
		}
		$this->http_get = implode('&',$get); 
	}
	function set_http_creds($unam,$pass){
		if($unam!=false){
			$this->httpauth=$unam . ':' . $pass;
		}else{
			$this->httpauth='';
		}
	}
	function getPost(){
		$post = $this->http_post;
		if(!is_array($post) ||  count($post)<1){
			return '';
		}else{
			$ret="";
			$curr=0;
			foreach($post as $name=>$value){
				if($curr!=0){
					$ret.='&';
				}
				$ret.=$name . '=' . $value;
				$curr++;
			}
			return $ret;
		}
	}
	function getCookies(){
		$cookies = $this->cookies;
		if(!is_array($cookies) ||  count($cookies)<1){
			return '';
		}else{
			$ret="";
			$curr=0;
			foreach($cookies as $name=>$value){
				if($curr!=0){
					$ret.=';';
				}
				$ret.=$name . '=' . $value;
				$curr++;
			}
			return $ret;
		}
	}
	function head(){
		/** Head Calls check for availability **/
		/** We can only support SAFE states in HEAD **/
		if(count($this->http_post)>0)
			return false; //POST calls not avaliable in HEAD request
		$ch = curl_init();
		$url = $this->url;
		if($this->http_get!='')
			if(substr_count('?',$this->url)>0){
				$url = $this->url . '&'.$this->http_get;
			}else{
				$url = $this->url . '?'.$this->http_get;
			}
		curl_setopt($ch, CURLOPT_URL, $url);
		if($this->is_https){
			@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		if(count($this->cookies)>0){
			@curl_setopt($ch, CURLOPT_COOKIE, $this->getCookies());
		}
		if($this->httpauth!=''){
			@curl_setopt($ch, CURLOPT_USERPWD, $this->httpauth);
		}
		@curl_setopt($ch, CURLOPT_REFERER,$this->referer);
		@curl_setopt($ch,CURLOPT_AUTOREFERER,true);
		if(count($this->request_headers)>0){
			foreach($this->request_headers as $key=>$val){
				$hdr[] = $key .': ' . $val;
			}
			@curl_setopt($ch, CURLOPT_HTTPHEADER,$hdr);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		$head = curl_exec($ch);
		$this->headers = $head;
		curl_close($ch);
		return true;//Head Request Successful
	}
	function start_stream($ending = true){
		/** Note: Streams are direct output and unbuffered in KnProxy! **/
		/** No parsing takes effect in stream mode **/
		if(!defined('KNPROXY_STREAMING_AVAILABLE') || !KNPROXY_STREAMING_AVAILABLE)
			return false;//Unavailable
		$tempName = dirname(__FILE__) . "/temp/" . 'temp_' . time() . '_' . mt_rand(0,9) ;
		$fp = fopen($tempName, "wb");
		$ch = curl_init();
		if($this->http_get!=''){
			if(substr_count('?',$this->url)>0){
				$this->url.='&'.$this->http_get;
			}else{
				$this->url.='?'.$this->http_get;
			}
		}
		curl_setopt($ch, CURLOPT_URL, $this->url);
		if($this->is_https){
			@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		if(count($this->http_post)>0){
			curl_setopt($ch, CURLOPT_POST,count($this->http_post));
			curl_setopt($ch, CURLOPT_POSTFIELDS,$this->getPost());
		}
		if(count($this->cookies)>0){
			@curl_setopt($ch, CURLOPT_COOKIE, $this->getCookies());
		}
		if($this->httpauth!=''){
			@curl_setopt($ch, CURLOPT_USERPWD, $this->httpauth);
		}
		if(!defined('KNPROXY_ACCEPT_GZIP') || KNPROXY_ACCEPT_GZIP!="true"){
			@curl_setopt($curl,CURLOPT_ENCODING,''); 
		}
		@curl_setopt($ch, CURLOPT_REFERER,$this->referer);
		@curl_setopt($ch,CURLOPT_AUTOREFERER,true);
		if(count($this->request_headers)>0){
			foreach($this->request_headers as $key=>$val){
				$hdr[] = $key .': ' . $val;
			}
			@curl_setopt($ch, CURLOPT_HTTPHEADER,$hdr);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_BUFFERSIZE, 256);//BUF_SIZ
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_exec($ch);
		$this->doctype = @curl_getinfo($ch,CURLINFO_CONTENT_TYPE);
		curl_close($ch);
		fclose($fp);
		header('Content-Type: ' . $this->doctype);
		if(is_resource($fp))
			fclose($fp);
		$fpN = fopen($tempName,'rb');
		if(!$fpN)
			return;//UnHandle Error
		while(!feof($fpN)){
			echo fread($fpN,2048);
		}
		fclose($fpN);
		if(is_resource($fpN))
			fclose($fpN);
		@unlink($tempName);//Do a trash collection?
		//Should we call the end of script?
		if($ending)
			exit();//Stop The Script to Prevent Corruption
		return true;
	}
	protected function do_chunk_combine($chunked){
		if(preg_match('~transfer-encoding:\s*chunked~iUs',$this->headers)){
			//chunk iterate
			$return = '';
			$a = preg_split('~\r*\n~',$chunked,2);
			$chunksize = hexdec($a[0]);
			while($chunksize>0){
				$return.=substr($a[1],0,$chunksize);
				$chunked=preg_replace('~^\r*\n~','',substr($a[1],$chunksize,strlen($a[1])));
				$a = preg_split('~\r*\n~',$chunked,2);
				$chunksize = hexdec($a[0]);
			}
			return $return;
		}
		return $chunked;
	}
	function fsockets_send(){
		/** Allows limited running in FileSockets mode, Buggy and not tested **/
		if($this->http_get!=''){
			if(substr_count('?',$this->url)>0){
				$this->url.='&'.$this->http_get;
			}else{
				$this->url.='?'.$this->http_get;
			}
		}
		$urlObj = new knURL();
		$urlObj->setBaseurl($this->url);
		if($this->is_https)
			return;//Https Not Supported
		$fp = fsockopen($urlObj->base['HOST'],80,$errno,$errstr);
		if(!$fp){
			//Action Failed
			return;
		}
		//Create The HTTP Request
		define('LB',"\r\n");
		if(count($this->http_post) > 0){
			$request = "POST";
			$req = 'POST ' .  $urlObj->get_path($urlObj->base) . ' HTTP/1.1' . LB;
		}else{
			$request = "GET";
			$req = 'GET ' .  $urlObj->get_path($urlObj->base) . ' HTTP/1.1' . LB;
		}
		
		$req .= 'Host: ' . $urlObj->base['HOST'] . LB;
		$req .= 'User-Agent: ' . $this->user_agent . LB;
		if($request == "POST")
			$req .= "Content-Length: " . strlen($this->getPost()) . LB;
		$req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . LB;
		if(count($this->cookies) > 0)
			$req .= 'Cookie: ' . $this->getCookies() . LB;
		$req .= 'Connection: Close'.LB;
		$req .= LB;
		if($request == "POST")
			$req .= $this->getPost();
		fputs($fp, $req);
		$ret='';
		while ($line = fgets($fp)) $ret .= $line; 
		fclose($fp);
		$spl = preg_split('~\r*\n\r*\n~',$ret,2);
		$this->headers = $spl[0];
		if(preg_match('~^http/1.\d \d+~iUs',$spl[1])){
			//second split is also a header, may be because of HTTP/1.1 100 Continue
			$splExt = preg_split('~\r*\n\r*\n~',$spl[1],2);
			$this->headers .= "\n\r" . $splExt[0];
			$spl[1] = $splExt[1];
		}
		
		$this->content = $this->do_chunk_combine($spl[1]);
	}
	
	function send(){
		/** Added Support For Streaming Connections **/
		if($this->streaming)
			return;
		if($this->mode!='curl')
			return $this->fsockets_send();
		$ch = curl_init();
		if($this->http_get!=''){
			if(substr_count('?',$this->url)>0){
				$this->url.='&'.$this->http_get;
			}else{
				$this->url.='?'.$this->http_get;
			}
		}
		@curl_setopt($ch, CURLOPT_URL, $this->url);
		@curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($this->is_https){
			@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		if(count($this->http_post) > 0){
			curl_setopt($ch, CURLOPT_POST,count($this->http_post));
			curl_setopt($ch, CURLOPT_POSTFIELDS,$this->getPost());
		}
		if(count($this->cookies)>0){
			@curl_setopt($ch, CURLOPT_COOKIE, $this->getCookies());
		}
		if($this->httpauth!=''){
			@curl_setopt($ch, CURLOPT_USERPWD, $this->httpauth);
		}
		if(!defined('KNPROXY_ACCEPT_GZIP') || KNPROXY_ACCEPT_GZIP!="true"){
			@curl_setopt($curl,CURLOPT_ENCODING,''); 
		}
		@curl_setopt($ch, CURLOPT_REFERER,$this->referer);
		@curl_setopt($ch,CURLOPT_AUTOREFERER,true);
		if(count($this->request_headers)>0){
			foreach($this->request_headers as $key=>$val){
				$hdr[] = $key .': ' . $val;
			}
			@curl_setopt($ch, CURLOPT_HTTPHEADER,$hdr);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		$raw = curl_exec($ch);
		$this->doctype = @curl_getinfo($ch,CURLINFO_CONTENT_TYPE);
		curl_close($ch);
		$spl = preg_split('~\r*\n\r*\n~',$raw,2);
		$this->headers = $spl[0];
		if(preg_match('~^http/1.\d \d+~iUs',$spl[1])){
			//second split is also a header, may be because of HTTP/1.1 100 Continue
			$splExt = preg_split('~\r*\n\r*\n~',$spl[1],2);
			$this->headers .= "\n\r" . $splExt[0];
			$spl[1] = $splExt[1];
		}
		if((preg_match('~\ncontent-encoding\s*:\s*gzip~iUs',$this->headers) || preg_match('~\ncontent-encoding\s*:\s*deflate~iUs',$this->headers)) && isset($spl[1]) && function_exists('gzinflate'))
			$spl[1] = gzinflate($spl[1]);
		if(isset($spl[1]))
			$this->content = $spl[1];
	}
	function refined_headers(){
		$headers = explode("\n",preg_replace('~\r~','',$this->headers));
		$head = array();
		if(is_array($headers) && count($headers)>0)
		foreach($headers as $line){
			if(preg_match('~^http/\d+.\d+\s(\d+)\s~iUs',$line,$matches)){
				$head['HTTP_RESPONSE'] = (int)$matches[1];
				continue;
			}else{
				$pair = preg_split('~:~',$line,2);
				switch(preg_replace('~\s~','',strtoupper($pair[0]))){
					case 'LOCATION':{
						$head['HTTP_LOCATION'] = preg_replace('~^\s*~','',$pair[1]);						
					}break;
					case 'SET-COOKIE':{
						$cookie = explode(';',$pair[1]);
						if(is_array($cookie) && count($cookie)>1)
							$cookie[1] = preg_replace('~expires\s*=\s*~iUs','',$cookie[1]);
						else
							$cookie[1] = '';
						$cookie_ = preg_split('~=~iUs',preg_replace('~^\s*~','',$cookie[0]),2);
						$head['HTTP_COOKIES'][] = Array($cookie_[0],$cookie_[1],$cookie[1]);
					}break;
					case 'WWW-AUTHENTICATE-MODE':{
						$head['WWW_AUTHENTICATE_MODE'] = $pair[1];
					}break;
					case 'WWW-AUTHENTICATE':{
						preg_match('~realm=([\'"])(.*)\\1~is',$pair[1],$m);
						$head['WWW_AUTHENTICATE_REALM'] = $m[2];
					}break;
					case 'CONTENT-DISPOSITION':{
						$head['CONTENT_DISPOSITION'] = $pair[1];
					}break;
					case 'REFRESH':{
						$m = explode(';',$pair[1]);
						$head['HTTP_REFRESH'] = Array((int)$m[0],preg_replace('~^\s*url\s*=(.*)$~iUs','$1',$m[1]));
					}break;
					case 'CONTENT-TYPE':{
						$this->doctype = $pair[1];
						$head["CONTENT_TYPE"] = $pair[1];
					}break;
					case 'DATE':{
						$head["DATE"] = $pair[1];
					}break;
					case 'ACCEPT-RANGES':{
						$head['ACCEPT_RANGES'] = preg_replace('~\s*~','',$pair[1]);
					}break;
					case 'CONTENT-RANGE':{
						$head['CONTENT_RANGE'] = preg_replace('~\s*~','',$pair[1]);;
					}break;
					case 'CACHE-CONTROL':{
						$head['CACHE_CONTROL'] = preg_replace('~^\s*~','',$pair[1]);
					}break;
					case 'EXPIRES':{
						$head['EXPIRES'] = preg_replace('~^\s*~','',$pair[1]);
					}break;
					case 'ETAG':{
						$head['ETAG'] = preg_replace('~^\s*~','',$pair[1]);
					}break;
					case 'LAST-MODIFIED':{
						$head['LAST_MODIFIED'] = preg_replace('~^\s*~','',$pair[1]);
					}break;
					case 'X-KNPROXY-LOCATION':{
						//Allows for internal redirection protocol
						$head['KNPROXY_LOCATION'] = @base64_decode(preg_replace('~^\s*~','',$pair[1]));
					}
					default:{
						if(isset($pair[0]) && !empty($pair[0])){
							$head['UNKNOWN'][] = Array($pair[0],$pair[1]);
						}
					}break;
				}
			}
		}
		return $head;
	}
}
?>