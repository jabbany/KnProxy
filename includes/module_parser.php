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
	}
	function setCharset($mime_raw,$page_raw){
		if(preg_match('~^.*;\s*charset\s*=\s*([a-zA-Z0-9\-]*)\s*[;]*$~',$mime_raw,$matches)){
			$this->charset = $matches[1];
		}
		if($this->charset==""){
			preg_match('~<meta.*charset=(.+)["\'].*\>~iUs',$page_raw,$pmatch);
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
			case 'image/gif':
			case 'image/png':
			case 'image/jpeg':$this->output = $this->source;break;
			case 'text/html':
			default:$this->parseHTML();break;
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
	function parseRawCSS($css){
		$css = preg_replace_callback('~(url|src)(\()\s*([^\s].*)\s*\)~iUs',array('self','parseSimpleURL'),$css);
		$css = preg_replace_callback('~(@import\s*)(["\'\(])\s*([^\s].*)\s*["\'\)]~iUs',array('self','parseSimpleURL'),$css);
		return $css;
	}
	function parseStyle($callback){
		return $callback[1] . $callback[2]. $this->parseRawCSS($callback[3]) . $callback[2];
	}
	function parseCss($source = false){
		if(!$source)
			$css = $this->source;
		elseif(is_array($source)){
			$heading = $source[1];
			$css = $source[2];
		}
		$css = $this->parseRawCSS($css);
		if(!$source)
			$this->output = $css;
		else
			return $heading . $css . '</style>';
	}
	function parseJS(){	
		$this->output = $this->source;//NOT IMPLEMENTED YET
	}
	function parseScriptTagURLReloc($url){
		$delim = $url[1];
		$urlA = $url[2];
		$tmp = $this->decodeJSURL($urlA);
		$urlDec = $this->url->getAbsolute($tmp[0]);
		if($this->stdEncoder!=false)
			$urlDec = $this->stdEncoder->encode($urlDec);
		$urlDec = $this->escapeJS($this->url_prefix . $urlDec);
		return 'location.replace(' . $delim . $urlDec . $delim . ')'; 
	}
	function parseScriptStrings($strings){
		$str = $strings[2];
		/*
		if(preg_match('~^https*://~iUs',$str)){
			$tmp = $this->url->getAbsolute($str);
			$urlDec = $this->stdEncoder->encode($tmp);
			return $strings[1] . $this->url_prefix . $urlDec . $strings[1];
		}*/
		$str = preg_replace_callback('~(href|src|codebase|url|action)\s*=\s*([\'\"])?(?(2) (.*?)\\2 | ([^\s\>]+))~isx',array('self','parseExtURL'),$str);
		return $strings[1] . $str . $strings[1];
	}
	function parseScriptTag($matches){
		$tagInner = preg_replace('~#knproxy_script_lt#~iUs','<',$matches[2]);
		$tagInner = preg_replace('~\\\~','#knproxy_script_escape#',$tagInner);//REMOVE ESCAPES
		$tagInner = preg_replace_callback('~([\'\"])(.+)\\1~',Array('self','parseScriptStrings'),$tagInner);
		$tagInner = preg_replace_callback('~location\.replace\(([\'"])(.*)\\1\)~iUs',Array('self','parseScriptTagURLReloc'),$tagInner);
		$tagInner = preg_replace('~#knproxy_script_escape#~','\\',$tagInner);
		return '<script' . $matches[1] . '>' . $tagInner . '</script>';
	}
	function parseScriptPre($matches){
		$tagInner = preg_replace('~<~iUs','#KNPROXY_SCRIPT_LT#',$matches[2]);
		return '<script' . $matches[1] . '>' . $tagInner . '</script>';
	}
	function parseUrlHTML($match){
		$is_pform=false;
		if(preg_match('~^\s*form~iUs',$match[1])){
			//This is a form
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
		$code = preg_replace_callback('~(href|src|codebase|url|action)\s*=\s*([\'\"])?(?(2) (.*?)\\2 | ([^\s\>]+))~isx',array('self','parseExtURL'),$match[1]);
		$code = preg_replace_callback('~(style\s*=\s*)([\'\"])(.*)\2~iUs',Array('self','parseStyle'),$code);
		if($is_pform)
			return '<' . $code . '><input type="hidden" name="knproxy_gettopost" value="true">';
		return '<' . $code . '>';
	}
	function parseHTML(){
		//BY FAR THE MOST DIFFICULT
		//ONLY GET IN TAG ITEMS
		$noJS = false;//FOR PREVENTING ERROR IN JS
		$code = preg_replace_callback('~<script([^>]*)>(.*)</script>~iUs',Array('self','parseScriptPre'),$this->source);
		if(preg_last_error()!=PREG_NO_ERROR){
			$noJS = true;
			$code = $this->source;
		}
		//ABOVE LINE ESCAPES THE < (lesser than) in JS SCRIPTS
		$code = preg_replace_callback('~<([^!].*)>~iUs',Array('self','parseUrlHTML'),$code);
		if(defined('ALLOW_NAVBAR') && ALLOW_NAVBAR=="true")
			$code = preg_replace('~<\s*/\s*head\s*>~iUs','<script type="text/javascript" language="javascript">parent.fixed.document.getElementById(\'url_\').value=parent.fixed.knEncode.unBase64("' . base64_encode($this->url->output($this->url->base)) . '");</script></head>',$code);
		$code = preg_replace_callback('~(<\s*style[^>]*>)(.*)<\s*/style\s*>~iUs',Array('self','parseCSS'),$code);
		if(!$noJS){
			$code = preg_replace_callback('~<script([^>]*)>(.*)<\s*/\s*script>~iUs',Array('self','parseScriptTag'),$code);
		}
		$this->output = $code;
	}
	function parseSimpleURL($matches){
		$url = $matches[3];
		$delimiter = $matches[2];
		$method = $matches[1];
		$url = preg_replace('~^"(.*)"$~iUs','$1',$url);//REMOVE FILTERS
		$url = preg_replace('~^\s*(.*)\s*$~iUs','$1',$url);
		$url = preg_replace('~^\'(.*)\'$~iUs','$1',$url);
		if(strtolower(substr($url,0,5))=='data:')
			return $method . '(' . $url . ')';
		$url = $this->url->getAbsolute($url);
		$encoder = $this->stdEncoder;
		if($encoder!=false){
			$url = $this->url_prefix . $encoder->encode($url);
		}
		if($delimiter =='(' )
			return $method . '(' . $url . ')';
		else
			return $method . $delimiter . $url . $delimiter;
	}
	function decodeJSURL($jsURL){
		$purl = preg_replace('~#knproxy_script_escape#~iUs','',$jsURL);
		if($purl[0]=='"' || $purl[0]=="'")
			$sep = $purl[0];
		else
			$sep = '';
		$purl = preg_replace('~["\']~iUs','',$purl);
		return Array($purl,$sep);
	}
	function escapeJS($text){
		return preg_replace('~([/"\'])~','\\\$1',$text);
	}
	function parseExtURL($matches){
		$method = $matches[1];
		$delim = $matches[2];
		if($delim=='')
			$url = $matches[4];
		else
			$url = $matches[3];
		if(substr($url,0,9)!='#knproxy_'){
			if($url == '' || strtolower(substr($url,0,11)) == 'javascript:' || (isset($url[0]) && $url[0]=='#') || strtolower(substr($url,0,5))=='data:'){
				return $method . '=' . $delim . $url . $delim . ' ';//NO PARSE
			}
		}
		if(substr($url,0,23)=='#knproxy_script_escape#' && $delim==''){
			$tpurl = $this->decodeJSURL($url);
			if($tpurl!='#'){
				$u = $this->url->getAbsolute($tpurl[0]);
				$encoder = $this->stdEncoder;
				if($encoder!=false)
					$u = $this->url_prefix . $encoder->encode($u);
				return $method . '=' . $this->escapeJS($tpurl[1].$u.$tpurl[1]);
			}else{
				return $method . '=' . $this->escapeJS($tpurl[1] . '#' . $tpurl[1]);
			}
		}
		$url = $this->url->getAbsolute($url);
		$encoder = $this->stdEncoder;
		if($encoder!=false){
			$url = $this->url_prefix . $encoder->encode($url);
		}
		$new = $method . '=' . $delim . $url . $delim . ' ';
		return $new;
	}
}

?>