 <?php
	error_reporting(0);
  	if(isset($_REQUEST['url'])&&!empty($_REQUEST['url'])&&isset($_REQUEST['site'])){
		$url = base64_decode($_REQUEST['url']);
		$site = $_REQUEST['site'];
		preg_match("#/([\w]{32}\.ts)\?#",$url,$base);
		$name = empty($base[1]) ? "m3u8.ts":$base[1];
    	header('cache-control:public'); 
    	header('Access-Control-Allow-Origin:*');
    	header('content-type:application/octet-stream;'); 
		header('content-disposition:attachment; filename='.$name);
		switch ($site) {
			case 'iqiyi':
				header("Location: $url");exit;
				break;
			case 'migu':
				header("Location: $url");exit;
				break;
			default:
			    $cookie = "";
			    break;
		}
		$data = curl($url,$cookie);//获取ts文件数据
		echo $data;
	}
	function curl($url,$cookie="")
	{
    	$params["ua"] = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36";
    	$params["cookie"] = $cookie;
      	return nxcurl($url,$params);
	}
  	function nxcurl($url,$params=array())
  	{
      $ip = empty($params["ip"]) ? rand_ip() : $params["ip"]; 
      $header = array('X-FORWARDED-FOR:'.$ip,'CLIENT-IP:'.$ip);
      if(isset($params["httpheader"])){
        $header = array_merge($header,$params["httpheader"]);
      }
      $referer = empty($params["ref"]) ? $url : $params["ref"];
      $user_agent = empty($params["ua"]) ? $_SERVER['HTTP_USER_AGENT'] : $params["ua"] ;
      
      $ch = curl_init();                                                      //初始化 curl
      curl_setopt($ch, CURLOPT_URL, $url);                                    //要访问网页 URL 地址
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                          //伪装来源 IP 地址
      curl_setopt($ch, CURLOPT_REFERER, $referer);                            //伪装网页来源 URL
      curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);                        //模拟用户浏览器信息
      curl_setopt($ch, CURLOPT_NOBODY, false);                                //设定是否输出页面内容
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                         //返回字符串，而非直接输出到屏幕上
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, false);                        //连接超时时间，设置为 0，则无限等待
      curl_setopt($ch, CURLOPT_TIMEOUT, 3600);                                //数据传输的最大允许时间超时,设为一小时
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                       //HTTP验证方法
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                        //不检查 SSL 证书来源
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);                        //不检查 证书中 SSL 加密算法是否存在
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);                         //跟踪爬取重定向页面
      curl_setopt($ch, CURLOPT_AUTOREFERER, true);                            //当Location:重定向时，自动设置header中的Referer:信息
      curl_setopt($ch, CURLOPT_ENCODING, '');                                 //解决网页乱码问题
      curl_setopt($ch, CURLOPT_HEADER, empty($params["header"])?false:true);  //不返回 header 部分
      if(!empty($params["fields"])){
        curl_setopt($ch, CURLOPT_POST, true);                                  //设置为 POST 
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params["fields"]);                //提交数据
      }
      if(!empty($params["cookie"])){
        curl_setopt($ch, CURLOPT_COOKIE, $params["cookie"]);                  //从字符串传参来提交cookies
      }
      if(!empty($params["proxy"])){
        curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);                  //代理认证模式
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);                  //使用http代理模式
        curl_setopt($ch, CURLOPT_PROXY, $params["proxy"]);                    //代理服务器地址 host:post的格式
        if(!empty($params["proxy_userpwd"])){
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $params["proxy_userpwd"]); //http代理认证帐号，username:password的格式
        }
      }
      $data = curl_exec($ch);                                                 //运行 curl，请求网页并返回结果
      curl_close($ch);                                                        //关闭 curl
      return $data;
  	}
	function rand_ip(){
		$ip_long = array(
			array('607649792', '608174079'), //36.56.0.0-36.63.255.255
			array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
			array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
			array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
			array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
			array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
			array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
			array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
			array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
			array('-569376768', '-564133889') //222.16.0.0-222.95.255.255
		);
		$rand_key = mt_rand(0, 9);
		$ip = long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
		return $ip;
	}
 ?>