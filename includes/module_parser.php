<?php
/****************************
* Parser to parse a webpage
****************************/
class knParser{
	var $url_prefix = '';
	var $url='';
	var $source='';
	var $type='';
	var $output='';
	var $charset='';
	var $extraParseEngine=false;
	var $stdEncoder=false;
	var $values=Array();
	function __construct($page_url,$page_data,$url_prefix=""){
		$this->url = $page_url;
		$this->source = $page_data;
		$this->stdEncoder = false;
		$this->url_prefix = $url_prefix;
	}
	function setPluginEngine($engine){
		$engine->url = $this->url;
		$engine->url_prefix = $this->url_prefix;
		$engine->stdEncoder = $this->stdEncoder;
		$this->extraParseEngine=$engine;
	}
	function setMimeType($mime_type){
		$mime_type=preg_replace('~;.*$~','',$mime_type);
		$this->type=preg_replace('~\s~','',$mime_type);
		if($this->type == ""){
			/** Try to determine mime type from extension **/
			if($this->url != null){
				if(preg_match("~\.(.+)$~",$this->url->base["FILE"],$m)){
					switch($m[1]){
						case "css":$this->type = "text/css";break;
						case "js":$this->type = "text/javascript";break;
						case "htm":
						case "html":$this->type = "text/html";break;
						case "txt":$this->type = "text/txt";break;
						default:$this->type = "";
					}
				}
			}
		}
	}
	function setCharset($mime_raw,$page_raw){
		if(preg_match('~^.*;\s*charset\s*=\s*([a-zA-Z0-9\-]*)\s*[;]*$~',$mime_raw,$matches)){
			$this->charset = $matches[1];
		}
		if($this->charset==""){
			preg_match('~<meta.*charset=(.+)["\'].*\>~iUs',$page_raw,$pmatch);
			if(count($pmatch)>0)
				$this->charset = $pmatch[1];
			
		}
		return $this->charset;
	}
	function setEncoder($encoder){
		$this->stdEncoder = $encoder;
	}
	function convertCharset($to='UTF-8'){
		if(function_exists('iconv')){
			$this->output = iconv($this->get_value('charset_iconv','GBK'),$to . '//IGNORE//TRANSLIT',$this->output);
		}elseif(function_exists('mb_convert_encoding')){
			$this->output = mb_convert_encoding($this->output,$to,$this->charset);
		}else{
			//UNMODIFY
			return false;
		}
		return true;
	}
	function set_value($name,$val){
		$this->values[$name]=$val;
	}
	function get_value($name,$def){
		if(isset($this->values[$name]))
			return $this->values[$name];
		return $def;
	}
	function parse(){
		if($this->extraParseEngine!=false)
			$this->source = $this->extraParseEngine->pre($this->source,$this->type);
		switch($this->type){
			case 'knproxy/noparse':$this->output = $this->source;break;
			case 'text/css':$this->parseCss();break;
			case 'text/javascript':
			case 'application/javascript':
			case 'application/x-javascript':$this->parseJS();break;
			case 'video/mp4':
			case 'image/gif':
			case 'image/png':
			case 'image/jpeg':$this->output = $this->source;break;
			case 'text/html':$this->parseHTML();break;
			default:{
				if(substr($this->type,0,6)=='video/' || substr($this->type,0,6)=='audio/' || substr($this->type,0,12)=='application/')
					$this->output = $this->source;
				else
					$this->parseHTML();
			}break;
		}
		if($this->extraParseEngine!=false){
			$this->output = $this->extraParseEngine->post($this->output,$this->type);
		}
		if($this->get_value('use_page_encryption',false)){
			if($this->stdEncoder->can('page_encrypt') && ($this->type=="text/html" || $this->type=="")){
				$key = $this->stdEncoder->getKey();
				if($this->charset=="gb2312")
					$this->convertCharset();
				$this->output = $this->stdEncoder->encrypt_page($this->output,$key);
				$this->set_value('key',$key);
			}
		}
	}
	/** Below are the primary parse modules **/
	protected function toAbsoluteUrl($urlField){
		if($urlField == '')
			return '';
		if(strtolower(substr($urlField,0,5)) == 'data:' || strtolower(substr($urlField,0,1)) == '#'){
			return $urlField;
		}elseif(strtolower(substr($urlField,0,11)) == 'javascript:'){
			return 'javascript:' . $this->jsParse(substr($urlField,10,strlen($urlField)));
		}
		$urlBase = $this->url->getAbsolute($urlField);
		if($this->stdEncoder != false)
			return $this->url_prefix . $this->stdEncoder->encode($urlBase);
		return $urlBase;
	}
	/** Parser for non-html **/
	protected function jsParse($js){
		if(defined("ENABLE_JS_PARSING") && ENABLE_JS_PARSING == "false")
			return $js;
		//Remove the comments
		$replace = Array();
		$ptr = 0;
		$len = strlen($js);
		$in = false;$temp = 0;$regex = false;$comment = false;$slcmt = false;
		$lastStringIterator = '';
		while($ptr < $len){
			if(!$comment && $js[$ptr] =="\\"){$ptr+=2;continue;}
			if(($js[$ptr] == "'" || $js[$ptr] == '"') && !$in && !$comment && !$regex && !$slcmt){
				$li = $js[$ptr];$temp = $ptr;$in = true;
				$ptr++;continue;
			}
			if($js[$ptr] == '/' && $js[$ptr+1] == '*' && !$in && !$regex && !$comment && !$slcmt) {$comment = true;$ptr++;continue;}
			if($js[$ptr] == '/' && $ptr > 0 && $js[$ptr-1] == '*' && $comment) {$comment = false;$ptr++;continue;}
			
			if($js[$ptr] == '/' && $js[$ptr+1] == '/' && !$in && !$regex && !$comment && !$slcmt) {$slcmt = true;$ptr++;continue;}
			if($slcmt && $js[$ptr] == "\n") {$slcmt = false;$ptr++;continue;}
			
			if($js[$ptr] == '/' && !$in && !$comment && !$slcmt){
				//Might be A division sign!
				if(!$regex){
					$lookAhead = substr($js,$ptr + 1,256);
					$lookBehind = substr($js,$ptr - 10,10);
					if(preg_match('~[a-zA-Z0-9)]\s*$~iUs',$lookBehind) || !preg_match('~/~',$lookAhead)){
						$ptr++;continue;
					}
				}
				$regex = !$regex;
				$ptr++;
				continue;
			}
			if(isset($li) && $js[$ptr] == $li && $in ){
				$replace[] = Array($temp + 1,$ptr,$this->__cb_jsStr(substr($js,$temp+1,$ptr - $temp - 1)));
				$temp = 0;
				$in = false;
			}
			$ptr++;
		}
		$offset = 0;
		foreach($replace as $r){
			$before = substr($js,0,$r[0] + $offset);
			$after = substr($js,$r[1] + $offset,strlen($js) + $offset);
			$diff = strlen($r[2]) - $r[1] + $r[0];
			$js = $before . $r[2] . $after;
			$offset += $diff;
		}
		return $js;
	}
	protected function cssParse($css){
		$css = preg_replace_callback('~(url|src)(\()\s*([^\s].*)\s*\)~iUs',array('self','__cb_std'),$css);
		$css = preg_replace_callback('~(@import\s*)(["\'\(])\s*([^\s].*)\s*["\'\)]~iUs',array('self','__cb_std'),$css);
		return $css;
	}
	/** Below are the REGEX callbacks **/
	protected function __cb_std($m){
		/** Standard URL parse callback **/
		$url = $m[3];
		$delimiter = $m[2];
		$method = $m[1];
		$wrapper = '';
		//Find Wrappers for the URL
		if(preg_match('~^([\'"])(.+)\1$~iUs',$url,$wrp)){
			$url = $wrp[2];
			$wrapper = $wrp[1];
		}
		$url = $wrapper . $this->toAbsoluteUrl($url) . $wrapper;
		if($delimiter == '(')
			return $method . '(' . $url . ')';
		return $method . $delimiter . $url . $delimiter;
	}
	protected function __cb_jsStr($jsStr){
		//Unescape this
		if(preg_match('~^http://(www\.)*w3\.org~',$jsStr))
			return $jsStr;//This is for initing namespaces probably.
		$unesc = preg_replace('~\\\\/~','/',$jsStr);
		if(preg_match('~^https*://~',$unesc,$m) || preg_match('~^//~',$unesc,$m)){
			if($unesc == $jsStr)
				return $this->toAbsoluteUrl($unesc) . '&x=';
			else
				return preg_replace('~/~',"\\/",$this->toAbsoluteUrl($unesc)) . '&x=';
		}
		if(preg_match('~^/~',$unesc) && (preg_match('~\..{0,5}$~',$unesc) || preg_match('~/[a-zA-Z0-9\-_=]$~iUs',$unesc))){
			if($unesc == $jsStr)
				return $this->toAbsoluteUrl($unesc) . '&x=';
			else
				return preg_replace('~/~',"\\/",$this->toAbsoluteUrl($unesc)) . '&x=';
		}
		if($unesc != $jsStr)
			$esc = true;
		$unesc = preg_replace_callback('~(href|src|codebase|url|action)\s*=\s*([\'\"])(?(2) (.*?)\\2 | ([^\s\>]+))~isx',array('self','__cb_url'),$unesc);
		if(isset($esc) && $esc)
			$unesc = preg_replace('~/~',"\\/",$unesc);
		return $unesc;
	}
	protected function __cb_htmlTag($match){
		if($match[1][0] == '/')
			return '<' . $match[1] . '>';
		//echo $match[1] . "\n";
		$is_pform=false;
		if(preg_match('~^\s*form~iUs',$match[1])){
			if(preg_match('~method\s*=~',$match[1])){
				if(preg_match('~method\s*=\s*([\'\"]?)get~isx',$match[1])){
					$match[1] = preg_replace('~(method)\s*=\s*([\'\"])?(?(2) (.*?)\\2 | ([^\s\>]+))~isx','$1="POST"',$match[1]);
					$is_pform = true;
				}else{
					$is_pform = false;
				}
			}else{
				$match[1].= ' method="POST"';
				$is_pform=true;
			}
		}
		$code = preg_replace_callback('~(href|src|codebase|url|action)\s*=\s*([\'\"])?(?(2) (.*?)\\2 | ([^\s\>]+))~isx',array('self','__cb_url'),$match[1]);
		$code = preg_replace_callback('~(style\s*=\s*)([\'\"])(.*)\2~iUs',Array('self','__cb_cssEmbed'),$code);
		if($is_pform)
			return '<' . $code . '><input type="hidden" name="knproxy_gettopost" value="true">';
		return '<' . $code . '>';
	}
	
	protected function __cb_url($matches){
		$method = $matches[1];
		$delim = $matches[2];
		if($delim=='')
			$url = $matches[4];
		else
			$url = $matches[3];
		return $method . '=' . $delim . $this->toAbsoluteUrl($url) . $delim . ' ';
	}
	
	protected function __cb_cssEmbed($m){
		return $m[1] . $m[2] . $this->cssParse($m[3]) . $m[2];
	}
	protected function __cb_cssTag($m){
		return $m[1] . $this->cssParse($m[2]) . '</style>';
	}
	
	protected function __cb_escapeJSLT($matches){
		$tagInner = preg_replace('~<~iUs','#KNPROXY_SCRIPT_LT#',$matches[2]);
		return '<script' . $matches[1] . '>' . $tagInner . '</script>';
	}
	protected function __cbJSParser($matches){
		$tagInner = preg_replace('~#knproxy_script_lt#~iUs','<',$matches[2]);
		$tagInner = $this->jsParse($tagInner);
		return '<script' . $matches[1] . '>' . $tagInner . '</script>';
	}
	/** End **/
	function parseCss(){
		$this->output = $this->cssParse($this->source);
	}
	function parseJS(){	
		$this->output = $this->jsParse($this->source);
	}
	function parseHTML(){
		$noJS = false;
		$code = preg_replace_callback('~<script([^>]*)>(.*)</script>~iUs',Array('self','__cb_escapeJSLT'),$this->source);
		//Prevents lt signs messing up the parser 
		if(preg_last_error() != PREG_NO_ERROR){
			$noJS = true;
			$code = $this->source;
		}
		$code = preg_replace_callback('~<([^!].*)>~iUs',Array('self','__cb_htmlTag'),$code);
		if(defined('KNPROXY_NAVBAR') && KNPROXY_NAVBAR=="true")
			$code = preg_replace('~<\s*/\s*head\s*>~iUs','<script type="text/javascript" language="javascript">if(parent !=  null && parent.fixed != null){parent.fixed.document.getElementById(\'urlx\').value=parent.fixed.knEncode.unBase64("' . base64_encode($this->url->output($this->url->base)) . '");}</script></head>',$code);
		
		if(defined("ENABLE_INJECTED_AJAXFIX") && ENABLE_INJECTED_AJAXFIX == "true"){
			$code = preg_replace("~<\s*head\s*>~iUs",'<head><script type="text/javascript" language="javascript" src="js/ajaxfix.js"></script>',$code);
		}
		$code = preg_replace_callback('~(<\s*style[^>]*>)(.*)<\s*/style\s*>~iUs',Array('self','__cb_cssTag'),$code);
		if(!$noJS){
			$code = preg_replace_callback('~<script([^>]*)>(.*)<\s*/\s*script>~iUs',Array('self','__cbJSParser'),$code);
		}
		$this->output = $code;
	}
}

?>
