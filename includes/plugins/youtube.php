<?php
class nullEncoder{
	function encode($v){
		return $v;
	}
}
class youtubeParser{
	var $url_prefix = '';
	var $url='';
	var $stdEncoder=false;
	function post($input,$type){
		if($type!='text/html' && $type!='')
			return;
		 // Check we have a video to show and if not, return unchanged
         if ( !defined('file_flv') ) {
            return $input;
         }
         $mediaPlayerUrl = 'swf/player.swf';
         $flvUrl = sprintf('%s',file_flv);
         $imgUrl = sprintf('%s', file_img);
		 $HdflvUrl = sprintf('%s', file_flvhq);
		 if($this->stdEncoder!=false){
			$encoder = $this->stdEncoder;
			$flvUrl = rawurlencode($this->url_prefix . $encoder->encode($flvUrl));
			$imgUrl = rawurlencode($this->url_prefix . $encoder->encode($imgUrl));
			$HdflvUrl = rawurlencode($this->url_prefix . $encoder->encode($HdflvUrl));
		 }else{
			$encoder = new nullEncoder();
		 }
         $html = <<<OUT
<embed src="{$mediaPlayerUrl}"
        width="620"
        height="380"
        bgcolor="000000"
        allowscriptaccess="always"
        allowfullscreen="true"
        type="application/x-shockwave-flash"
        pluginspage="http://www.macromedia.com/go/getflashplayer" 
        flashvars="width=620&amp;height=380&amp;type=video&amp;fullscreen=true&amp;volume=100&amp;file={$flvUrl}&amp;image={$imgUrl}&amp;plugins=hd-1&amp;hd.file={$HdflvUrl}&amp;hd.state=false" />
OUT;

 		 if (file_flv != "file_flv") {
		 $Maps_flv1='<a class="watch-comment-auth" href="'.$this->url_prefix . $encoder->encode($this->url->getAbsolute(sprintf('%s',file_flv))).'" rel="nofollow"><font color="#FF0000">Download FLV</font></a>&nbsp;&nbsp;';
		 }
		 if (file_mp4hd != "file_mp4hd") {
		 $Maps_flv2='<a class="watch-comment-auth" href="'.$this->url_prefix . $encoder->encode($this->url->getAbsolute(sprintf('%s',file_mp4hd))).'" rel="nofollow"><font color="#FF0000">Download MP4HD</font></a>&nbsp;&nbsp;';
		 }
		 if (file_mp4hq != "file_mp4hq") {
		 $Maps_flv3='<a class="watch-comment-auth" href="'.$this->url_prefix . $encoder->encode($this->url->getAbsolute(sprintf('%s',file_mp4hq))).'" rel="nofollow"><font color="#FF0000">Download MP4HQ</font></a>&nbsp;&nbsp;';
		 }
		 if (file_flvh264 != "file_flvh264") {
		 $Maps_flv4='<a class="watch-comment-auth" href="'.$this->url_prefix . $encoder->encode($this->url->getAbsolute(sprintf('%s',file_flvh264))).'" rel="nofollow"><font color="#FF0000">Download FLVH264</font></a>&nbsp;&nbsp;';
		 }
		 if (file_flvhq != "file_flvhq") {
		 $Maps_flv5='<a class="watch-comment-auth" href="'.$this->url_prefix . $encoder->encode($this->url->getAbsolute(sprintf('%s',file_flvhq))).'" rel="nofollow"><font color="#FF0000">Download FLVHQ</font></a>&nbsp;&nbsp;';
		 }
		 if (file_flvHD1 != "file_flvHD") {
		 $Maps_flv6='<a class="watch-comment-auth" href="'.$this->url_prefix . $encoder->encode($this->url->getAbsolute(sprintf('%s',file_flvHD1))).'" rel="nofollow"><font color="#FF0000">Download FLVHD1</font></a>&nbsp;&nbsp;';
		 }
		 if (file_flvHD2 != "file_flvHD2") {
		 $Maps_flv7='<a class="watch-comment-auth" href="'.$this->url_prefix . $encoder->encode($this->url->getAbsolute(sprintf('%s',file_flvHD2))).'" rel="nofollow"><font color="#FF0000">Download FLVHD2</font></a>';
		 }

         // Add our own player into the player div
         $input = preg_replace('#<div(.*?)id="watch-player" class="flash-player">#', '<div id="watch-player" class="flash-player">' . $html .'
		 <div id="watch-video-response" class="watch-video-response-with-quality-settings" align="left" style="font-size:14px">
		 '.$Maps_flv1.$Maps_flv2.$Maps_flv3.$Maps_flv4.$Maps_flv5.$Maps_flv6.$Maps_flv7.'
</div></div>
<div style="display:none"><div>', $input, 1);

		 $input=preg_replace('#http://s.ytimg.com/yt/swf/watch-vfl157150.swf#s','' . $mediaPlayerUrl . '',$input, 1);
		 $input=preg_replace('#http://s.ytimg.com/yt/swf/watch_as3-vfl157163.swf#s','' . $mediaPlayerUrl . '',$input, 1);
		return $input;
	}
	function pre($input,$type){
		if($type!='text/html' && $this->type!='')
			return;//NO GO
		if ( preg_match('#fmt_url_map\=(.*?)&#', $input, $VValue) ) {
			  $VValue[1] = rawurldecode($VValue[1]);
		      //35 convert to 36 for 5 35 error
			  $Vxvalue =preg_replace('#35\|#s','36\|',$VValue[1],1);  
              $findValue=preg_replace('#%2Cexpire%2Cip%2Cipbits%2Citag%2Calgorithm%2Cburst%2Cfactor#s',',expire,ip,ipbits,itag,algorithm,burst,factor',$Vxvalue,1);	
              $findValue=preg_replace('#%2Cexpire%2Cip%2Cipbits%2Citag%2Cratebypass#s',',expire,ip,ipbits,itag,ratebypass',$Vxvalue,1);		  
			  if ( preg_match('#5\|(.*)#', $findValue, $k1Value) ) {
			  $s1Value=rawurldecode($k1Value[1]);
			  define('file_flv', $s1Value);
			  }
			  if ( preg_match('#22\|(.*?)\,#', $findValue, $k2Value) ) {
			  $s2Value=rawurldecode($k2Value[1]);
			  define('file_mp4hd', $s2Value);
			  }
			  if ( preg_match('#18\|(.*?)\,#', $findValue, $k3Value) ) {
			  $s3Value=rawurldecode($k3Value[1]);
			  define('file_mp4hq', $s3Value);
			  }
			  if ( preg_match('#34\|(.*?)\,#', $findValue, $k4Value) ) {
			  $s4Value=rawurldecode($k4Value[1]);
			  define('file_flvh264', $s4Value);
			  }
			  if ( preg_match('#36\|(.*?)\,#', $findValue, $k5Value) ) {
			  $s5Value=rawurldecode($k5Value[1]);
			  define('file_flvhq', $s5Value);
			  }
			  if ( preg_match('#37\|(.*?)\,#', $findValue, $k6Value) ) {
			  $s6Value=rawurldecode($k6Value[1]);
			  define('file_flvHD1', $s6Value);
			  }
			  if ( preg_match('#38\|(.*?)\,#', $findValue, $k7Value) ) {
			  $s7Value=rawurldecode($k7Value[1]);
			  define('file_flvHD2', $s7Value);
			  }
         }
		 
		 // Image
         if ( preg_match('#\"rv.0.thumbnailUrl\"\:\s\"(.*?)\"#', $input, $dValue) ) {
		    $thumb=rawurldecode($dValue[1]);
            define('file_img', $thumb);
         }
		 return $input;
	}
}
?>