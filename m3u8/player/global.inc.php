<?php
define('PATH',str_replace(basename($_SERVER['REQUEST_URI']),'',$_SERVER['REQUEST_URI']));
header('Cache-Control:no-cache,must-revalidate');
header('Pragma:no-cache');
header("Expires:-1"); 

class GlobalBase
{
  public static function curl($url,$params=array())
  {
      $ip = empty($params["ip"]) ? self::rand_ip() : $params["ip"]; 
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
  public static function http_curl($url){
    $curl = curl_init();
    $header[] = 'Referer:'.$url;
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
    curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,30);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36');
    $response=curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  public static function https_url()
  {
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http_type . $_SERVER['HTTP_HOST'];
  }

  public static function report($url,$ids=array())
  {
    $ids['ua'] = isset($ids['ua']) ? $ids['ua'] : 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4';
    $curl = curl_init();
    $header = array('CLIENT-IP:0.0.0.0','X-FORWARDED-FOR:0.0.0.0');
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST,0);
    curl_setopt($curl, CURLOPT_HEADER, 0);  //0表示不输出Header，1表示输出
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_USERAGENT, $ids['ua']);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($curl, CURLOPT_REFERER,$ids['ref']); 
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    $html = curl_exec($curl);   
    curl_close($curl);
    return $html; 
  }
	public static function rand_ip(){
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
  public static function get_ip()
  { 
    if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
      $ip = explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"])[0]; //浏览当前页面的用户计算机的网关
    else if (@$_SERVER["HTTP_CLIENT_IP"]) 
      $ip = $_SERVER["HTTP_CLIENT_IP"]; 						//客户端的ip
    else if (@$_SERVER["REMOTE_ADDR"]) 
      $ip = $_SERVER["REMOTE_ADDR"]; 							//浏览当前页面的用户计算机的ip地址
    else if (@getenv("HTTP_X_FORWARDED_FOR"))
      $ip = explode(",",getenv("HTTP_X_FORWARDED_FOR"))[0];
    else if (@getenv("HTTP_CLIENT_IP")) 
      $ip = getenv("HTTP_CLIENT_IP"); 
    else if (@getenv("REMOTE_ADDR")) 
       $ip = getenv("REMOTE_ADDR"); 
    else 
      $ip = "Unknown IP"; 
    return $ip; 
  }

  public static function is_https()
  {
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
}
?>