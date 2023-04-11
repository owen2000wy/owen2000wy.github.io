<?php 
error_reporting(0);
	require_once("global.inc.php");
	$host = GlobalBase::is_https().$_SERVER["HTTP_HOST"];
	$api = "ts.php?url=";

	$vid = $_REQUEST['vid'];
	$apikey = $_REQUEST['apikey'];
	header('Access-Control-Allow-Origin:*');
	header('Content-type: application/vnd.apple.mpegurl; charset=utf-8');//播放列表文件使用UTF-8编码
	header('Content-Disposition: attachment; filename='.$vid.".m3u8");
	$data = get_free_url($vid,$apikey);
	$lines = preg_split('/[\r\n]+/s', $data);//按行进行分割字符串
	$durations = array();
	$urls = array();
	$bool = true;
	$targetduration = "";
	foreach ($lines as $value) {
		if(!empty(strstr($value,"#EXT-X-TARGETDURATION:"))){//多码率
			$targetduration = $value;
		}else if(!empty(strstr($value,"#EXTINF:"))){//单码率
			$durations[count($durations)] = $value;
			$bool = true;
		}else if(!empty($value)&&substr($value,0,1)!="#"){	
			put_url($value);
		}
	}
	$m3u8 = "#EXTM3U\n#EXT-X-VERSION:3\n";
	$m3u8 .= empty($targetduration)?"#EXT-X-TARGETDURATION:7200\n" : $targetduration."\n";
	foreach ($durations as $key => $value) {
		$m3u8 .= $value."\n".$api.base64_encode($urls[$key])."&site=m1905\n";
	}
	$m3u8 .="#EXT-X-ENDLIST";
	echo $m3u8;

	function put_url($value){
		global $urls,$bool;
		if($bool){
			$urls[count($urls)] = $value;
		}
	}

	//apikey 从播放页面获取
	function get_free_url($vid,$apikey)
	{
		$time = time();
		$pid = mt_rand(10000000,99999999).mt_rand(1000000,9999999);//长度要大于 5,可随机生成15位数字
		$key = strlen($apikey) > 12 ? substr($apikey,10,8) :substr($pid,3,8);
		$sign = md5($key.$time.$vid.$pid);
		$api = "http://profile.m1905.com/mvod/config.php?k={$key}&t={$time}&i={$vid}&p={$pid}&s={$sign}&v=1";
		$data = str_replace("&amp;","&",_curl($api));
		preg_match('/Set-Cookie:(.*);/iU',$data,$cookie);
	    preg_match('#url="(http://profile.m1905.com/mvod/loader.php?.*)"#iU',$data,$_url);
		//preg_match('#bkurl="(http://profile-bj.m1905.com/mvod/loader.php.*?)"#iU',$data,$_burl);
		return curl($_url[1],$cookie[1]);
	}
	function curl($url,$cookie="")
	{
    	$params["ua"] = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
    	$params["cookie"] = $cookie;
      	return GlobalBase::curl($url,$params);
	}
	function _curl($url,$ckname="")
	{
    	$user_agent = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
  		$ch = curl_init(); 												//初始化 curl
		curl_setopt($ch, CURLOPT_URL, $url);							//要访问网页 URL 地址
		curl_setopt($ch, CURLOPT_HEADER, true); 						//返回 header 部分
		curl_setopt($ch, CURLOPT_NOBODY, false);						//设定是否输出页面内容
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 				//返回字符串，而非直接输出到屏幕上
		curl_setopt($ch, CURLOPT_REFERER, $url);						//伪装网页来源 URL
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, false);				//连接超时时间，设置为 0，则无限等待
		curl_setopt($ch, CURLOPT_TIMEOUT, 3600);					    //数据传输的最大允许时间超时,设为一小时
		curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);				//模拟用户浏览器信息
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);				//HTTP验证方法
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);				//不检查 SSL 证书来源
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);				//不检查 证书中 SSL 加密算法是否存在
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);					//跟踪爬取重定向页面
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);					//当Location:重定向时，自动设置header中的Referer:信息
		curl_setopt($ch, CURLOPT_ENCODING, '');							//解决网页乱码问题
		curl_setopt($ch, CURLOPT_HTTPGET, true);						//设置HTTP的 method为 GET,默认 开启 GET
		curl_setopt($ch, CURLOPT_COOKIE, $ckname);					//从字符串传参来提交cookies
		$data = curl_exec($ch); 										//运行 curl，请求网页并返回结果
		curl_close($ch);												//关闭 curl
		return $data;
	}
 ?>