<?php
class knUrl{
	var $base = Array();
	function getDetails($url_){
		$URL = Array();
		$tmp = explode('//',$url_);
		if(count($tmp)>1){
			$URL['SCHEME'] = strtoupper($tmp[0]);
			if($URL['SCHEME']=='')
				$URL['SCHEME']='HTTP:';
			unset($tmp[0]);
			$tmp = implode('//',$tmp);
		}
		else{
			$URL['SCHEME'] = 'RELATIVE';
			$tmp = $tmp[0];
		}
		$tmp1 = explode('/',$tmp);
		$URL['HOST'] = $tmp1[0];
		$tmp = explode('/',$tmp);
		$tmp[0]='';
		$tmp = implode('/',$tmp);
		$tmp = explode('?',$tmp);
		if(count($tmp)>1){
			$pos=explode('/',$tmp[0]);
			$tm = $pos[count($pos)-1];
			$pos[count($pos)-1]='';
			$URL['POSITION'] = implode('/',$pos);
			$URL['FILENAME'] = $tm;
			$URL['REQUEST'] = $tmp[1];
		}
		elseif(count($tmp)==1){
			$pos = explode('/',$tmp[0]);
			$file = $pos[count($pos)-1];
			$pos[count($pos)-1]='';
			$URL['POSITION']=implode('/',$pos);
			$URL['FILENAME']=$file;
			$URL['REQUEST'] ='';
		}
		return $URL;
	}
	
	function setBaseurl($url){
		$this->base = $this->getDetails($url);
	}
	
	function formatUrl($url){
		//FORMATS URL FOR ILLEGAL CHARS
		$url = preg_replace('~\\\[\'\"]~','',$url);//REMOVE JS LOGIC
		$url = preg_replace('~\\\/~','/',$url);
		return $url;
	}
	function getAbsolute($add){
		//ADDS URL TOGETHER
		$base = $this->base;
		$add = $this->getDetails($this->formatUrl($add));
		print_r($add);
		$DEFAULT_SCHEME = 'HTTP:';
		if($add['SCHEME']!='RELATIVE')
			return $this->outputUrl($add);
			
		$COMBINE['SCHEME'] = $base['SCHEME'];
		if($COMBINE['SCHEME']=='')
			$COMBINE['SCHEME'] = $DEFAULT_SCHEME;
		$COMBINE['HOST'] = $base['HOST'];
		if($add['HOST']=='')
			$COMBINE['POSITION'] = $add['POSITION'];
		elseif($add['HOST']=='..'){
			$pos = explode('/',$base['POSITION']);
			$i = count($pos)-1;
			$k=false;
			while($i>0 && $k==false){
				if($pos[$i]!=''){
					$pos[$i]='';
					$k=true;
				}else{
					unset($pos[$i]);
				}
				$i--;
			}
			$pos_new = implode('/',$pos);
			$COMBINE['POSITION'] = $pos_new . $add['POSITION'];
		}
		else{
			if(empty($base['POSITION']) || $base['POSITION'][strlen($base['POSITION'])-1]!='/')
				$base['POSITION'].='/';
			$COMBINE['POSITION'] = $base['POSITION'] . $add['HOST'] . $add['POSITION'];
		}
		$COMBINE['FILENAME'] = $add['FILENAME'];
		$COMBINE['REQUEST'] = $add['REQUEST'];
		//SSE FOR WIKIPEDIA
		return $this->outputUrl($COMBINE);
	}
	function outputUrl($URL){
		$U = strtolower($URL['SCHEME']) . '//' . $URL['HOST'] . $URL['POSITION'] . $URL['FILENAME'];
		if($URL['REQUEST']!='')
			$U .= '?' . $URL['REQUEST'];
		return $U;
	}
}
?>