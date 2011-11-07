<?php
class knUrl{
	var $base = Array();
	function setBaseurl($url){
		$this->base = $this->parseUrl($url);
	}
	function parseUrl($url){
		$result = Array();
		//GET FRAGMENTS
		$frag = explode('#',$url);
		if(is_array($frag) && count($frag)>1){
			$result['FRAGMENT'] = $frag[1];
			$url = $frag[0];
		}
		$tmp = explode('?',$url);
		if(is_array($tmp) && count($tmp)>1){
			//HAS QUERY STRING
			$tmp0 = $tmp[0];
			unset($tmp[0]);
			$result['QUERY'] = implode('?',$tmp);
		}else{
			//NO QUERY STRING
			if(is_array($tmp))
				$tmp0 = $tmp[0];
			else
				$tmp0 = $tmp;
		}
		//FETCH SCHEME IF AVAIL
		$tmp = explode('//',$tmp0);
		if(is_array($tmp) && count($tmp)>1){
			//HAS SCHEME
			$result['SCHEME'] = strtolower($tmp[0]);
			if($result['SCHEME']=='')
				$result['SCHEME']='http:';
			unset($tmp[0]);
			$url = implode('//',$tmp);
		}else{
			//NO SCHEME
			$result['SCHEME'] = ':relative';
			if(is_array($tmp))
				$url = $tmp[0];
			else
				$url = $tmp;
		}
		//NOW TO GET THE HOST
		if($result['SCHEME']!=':relative'){
			//ONLY UNRELATIVE ADDRESSES HAVE HOSTS
			$tmp = explode('/',$url);
			if(is_array($tmp) && count($tmp)>1){
				$result['HOST'] = $tmp[0];
				$result['FILE'] = $tmp[count($tmp)-1];
				$tmp[count($tmp)-1]='';
				$tmp[0]='';
				$url = implode('/',$tmp);
				$result['PATH'] = $url;
			}elseif(is_array($tmp)){
				$result['HOST'] = $tmp[0];
				$result['PATH'] = '';
				$result['FILE'] = '';
			}
		}else{
			$tmp = explode('/',$url);
			$result['FILE'] = $tmp[count($tmp)-1];
			$tmp[count($tmp)-1]='';
			$result['PATH'] = implode('/',$tmp);
		}
		return $result;
	}
	function getAbsolute($addr){
		$add = $this->parseUrl($addr);
		$base = $this->base;
		if($add['SCHEME']!=':relative')
			return $this->output($add);
		if(isset($add['PATH'][0]) && $add['PATH'][0]=='/')
			$base['PATH'] = $add['PATH'];
		else
			$base['PATH'] .= $add['PATH'];
		//FILE
		$base['FILE'] = $add['FILE'];
		if(isset($add['QUERY'])){
			$base['QUERY'] = $add['QUERY'];
		}else{
			if(isset($base['QUERY'])){
				unset($base['QUERY']);
			}
		}
		if(isset($add['FRAGMENT']))
			$base['FRAGMENT'] = $add['FRAGMENT'];
		else
			unset($base['FRAGMENT']);
		return $this->output($base);
	}
	function output($addr){
		if($addr['SCHEME']=='')
			$addr['SCHEME']='http:';/* FOR COMPAT WITH //host.com */
		if(substr($addr['HOST'],strlen($addr['HOST'])-1,1)!='/' && ($addr['PATH']=="" || $addr['PATH'][0]!='/'))
			$addr['PATH'] = '/' . $addr['PATH'];
		$ret = $addr['SCHEME'] . '//' . $addr['HOST'] . $addr['PATH'] . $addr['FILE'];
		if(isset($addr['QUERY'])){
			if(!preg_match('~&[^a]~iUs',$addr['QUERY'])){
				$addr['QUERY'] = preg_replace('~&amp;~iUs','&',$addr['QUERY']);
				//ESCAPE THIS IF NEEDED!
			}
			$ret.='?' . $addr['QUERY'];
		}
		if(isset($addr['FRAGMENT']))
			$ret.="#" . $addr['FRAGMENT'];
		return $ret;
	}
	/** Introduced to help FileSockets **/
	function get_path($addr){
		if(substr($addr['HOST'],strlen($addr['HOST'])-1,1)!='/' && ($addr['PATH']=="" || $addr['PATH'][0]!='/'))
			$addr['PATH'] = '/' . $addr['PATH'];
		$ret=$addr['PATH'] . $addr['FILE'];
		if(isset($addr['QUERY'])){
			if(!preg_match('~&[^a]~iUs',$addr['QUERY'])){
				$addr['QUERY'] = preg_replace('~&amp;~iUs','&',$addr['QUERY']);
				//ESCAPE THIS IF NEEDED!
			}
			$ret.='?' . $addr['QUERY'];
		}
		return $ret;
	}
}
?>