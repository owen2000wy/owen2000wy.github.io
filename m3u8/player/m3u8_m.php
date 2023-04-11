<?php 
error_reporting(0);
$host_host = is_https().$_SERVER["HTTP_HOST"];
$ts = "m3u8_m.php?ts=";
$m3u= "m3u8_m.php?url=";
if(isset($_REQUEST['url'])){
	$url = base64_decode($_REQUEST['url']);
	preg_match("#/([\w]{32}\m3u8)\?#",$url,$base);	
	$name = empty($base[1])?"m3u8":$base[1];
	//exit($url);
	header('Access-Control-Allow-Origin:*');
	header('Content-type: application/vnd.apple.mpegurl;');//播放列表文件使用UTF-8编码
	header('Content-Disposition: attachment; filename='.$name);
    $m3u_host=preg_match("#^((http://|https://).*?)/#",$url,$key)?$key[1]:"";
	$m3u_path=preg_match("#^((http://|https://).*)/#",$url,$key)?$key[1]:"";
      $m3u8="";  $purl="";	 
	  $data = curl($url);//获取m3u8文件数据 
	$lines = preg_split('/[\r\n]+/s', $data);//按行进行分割字符串
	foreach ($lines as  $key =>  $value) {		
	  if($value&&substr($value,0,1)!="#"){   //切片信息			    
		 //路径转换为完整路径
		   if(substr($value,0,4)=="http"){
				$purl=$value;			
			}else if(substr($value,0,1)=="/"){
				$purl=$m3u_path.$value;
			}else{
				$purl=$m3u_path."/".$value;
			}		    	
		 //取上行有效信息
		    $i=$key; do {$i--;$front=$lines[$i];}while($front=="");
		   //m3u
		   if(strstr($front,"#EXT-X-STREAM-INF:")){   
		       $m3u8.= $m3u.base64_encode($purl)."\n";	
		   //ts
		   }else if(strstr($front,"#EXTINF:")){        	 
			   $m3u8.= $ts.base64_encode($purl)."\n";
           }
	  }else{
		$m3u8.=$value."\n";	
       }       
    }
echo $m3u8;	
}
//TS文件
if(isset($_REQUEST['ts'])&&!empty($_REQUEST['ts'])){
		$url = base64_decode($_REQUEST['ts']);
		preg_match("#/([\w]{32}\.ts)\?#",$url,$base);		
		$name = empty($base[1]) ? "ts":$base[1]; 
		header('cache-control:public'); 
    	header('Access-Control-Allow-Origin:*');
    	header('content-type:application/octet-stream;'); 
		header('content-disposition:attachment; filename='.$name);		
		$data = curl($url,$cookie);//获取ts文件数据	
	    echo $data; 	
}

function curl($url){
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_REFERER, $url);
	   curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	   curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	   curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	   curl_setopt($ch, CURLOPT_HEADER, 0);
	   curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	   $json = curl_exec($ch);
	   curl_close($ch);
       return $json;
}
function is_https(){
      if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off'){
          return "https://";
      }elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'){
          return "https://";
      }elseif (isset($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off'){
          return "https://";
      }elseif(isset($_SERVER["REQUEST_SCHEME"]) && $_SERVER["REQUEST_SCHEME"] === 'https'){
          return "https://";
      }
      return "http://";
}
 ?>