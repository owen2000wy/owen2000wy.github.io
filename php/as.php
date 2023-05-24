
<?php
$id = isset($_GET['id'])?$_GET['id']:'cctv1';
$n = array(
  'cctv1' => '0001', //CCTV-1
  'cctv1hd' => '0127', //CCTV-1高清
  'cctv2' => '0002', //CCTV-2
  'cctv2hd' => '0244', //CCTV-2高清
  'cctv3' => '0259', //CCTV-3
  'cctv3hd' => '0245', //CCTV-3高清
  'cctv4' => '0005', //CCTV-4
  'cctv4hd' => '0316', //CCTV-4高清
  'cctv5' => '0260', //CCTV-5
  'cctv5hd' => '0246', //CCTV-5高清
  'cctv5p' => '0128', //CCTV-5+高清
  'cctv6' => '0261', //CCTV-6
  'cctv6hd' => '0247', //CCTV-6高清
  'cctv7' => '0009', //CCTV-7
  'cctv7hd' => '0248', //CCTV-7高清
  'cctv8' => '0262', //CCTV-8
  'cctv8hd' => '0249', //CCTV-8高清
  'cctv9' => '0014', //CCTV-9
  'cctv9hd' => '0250', //CCTV-9高清
  'cctv9hd2' => '0478', //CCTV-9高清2
  'cctv10' => '0015', //CCTV-10
  'cctv10hd' => '0251', //CCTV-10高清
  'cctv11' => '0016', //CCTV-11
  'cctv11hd' => '0328', //CCTV-11高清
  'cctv12' => '0017', //CCTV-12
  'cctv12hd' => '0252', //CCTV-12高清
  'cctv13' => '0126', //CCTV-13
  'cctv13hd' => '0502', //CCTV-13高清
  'cctv14' => '0085', //CCTV-14
  'cctv14hd' => '0253', //CCTV-14高清
  'cctv15' => '0086', //CCTV-15
  'cctv15hd' => '0329', //CCTV-15高清
  'cctv16hd' => '0388', //CCTV-16高清
  'cctv16_4k' => '0506', //CCTV16_4K超高清
  'cctv17' => '0327', //CCTV-17
  'cctv17hd' => '0326', //CCTV-17高清

  'cgtn' => '0021', //CGTN
  'cgtn2' => '0365', //CGTN2
  'cgtna' => '0369', //CGTN 阿拉伯语
  'cgtne' => '0367', //CGTN 西班牙语
  'cgtnf' => '0368', //CGTN 法语
  'cgtnr' => '0370', //CGTN 俄语

  'cetv1' => '0243', //CETV1
  'cetv1hd' => '0302', //CETV1高清
  'cetv2' => '5251', //CETV-2
  'cetv4' => '5252', //CETV-4

  'chcdzdy' => '0324', //CHC动作电影高清
  'chcgqdy' => '0325', //CHC高清电影
  'chcjtyy' => '0323', //CHC家庭影院高清

  'bjws' => '0026', //北京卫视
  'bjwshd' => '0129', //北京卫视高清
  'dfws' => '0011', //东方卫视
  'dfwshd' => '0242', //东方卫视高清
  'tjws' => '0072', //天津卫视
  'tjwshd' => '0136', //天津卫视高清
  'cqws' => '0012', //重庆卫视
  'hljws' => '0042', //黑龙江卫视
  'hljwshd' => '0131', //黑龙江卫视高清
  'jlws' => '0046', //吉林卫视
  'lnws' => '0057', //辽宁卫视
  'lnwshd' => '0255', //辽宁卫视高清
  'nmws' => '0060', //内蒙古卫视
  'nxws' => '0061', //宁夏卫视
  'gsws' => '0034', //甘肃卫视
  'qhws' => '0063', //青海卫视
  'sxws' => '0066', //陕西卫视
  'hbws' => '0040', //河北卫视
  'hbwshd' => '0317', //河北卫视高清
  'sxiws' => '0065', //山西卫视
  'sdws' => '0064', //山东卫视
  'sdwshd' => '0254', //山东卫视高清
  'ahws' => '0024', //安徽卫视
  'ahwshd' => '0256', //安徽卫视高清
  'hnws' => '0041', //河南卫视
  'hubws' => '0043', //湖北卫视
  'hubwshd' => '0135', //湖北卫视高清
  'hunws' => '0008', //湖南卫视
  'hunwshd' => '0130', //湖南卫视高清
  'jxws' => '0049', //江西卫视
  'jsws' => '0048', //江苏卫视
  'jswshd' => '0133', //江苏卫视高清
  'zjws' => '0013', //浙江卫视
  'zjwshd' => '0134', //浙江卫视高清
  'dnws' => '0029', //东南卫视
  'dnwshd' => '0588', //东南卫视高清
  'xmws' => '0159', //厦门卫视
  'gdws' => '0036', //广东卫视
  'gdwshd' => '0137', //广东卫视高清
  'szws' => '0004', //深圳卫视
  'szwshd' => '0132', //深圳卫视高清
  'gxws' => '0037', //广西卫视
  'ynws' => '0082', //云南卫视
  'gzws' => '0038', //贵州卫视
  'gzwshd' => '0318', //贵州卫视高清
  'scws' => '0071', //四川卫视
  'kbws' => '0331', //康巴卫视测试
  'xjws' => '0079', //新疆卫视
  'btws' => '0280', //兵团卫视
  'xzws' => '0076', //西藏卫视
  'hinws' => '0059', //海南卫视
  'ssws' => '0589', //三沙卫视

  'kkse' => '0051', //卡酷少儿

  'tyss' => '0590', //体育赛事高清

  'xdm' => '0321', //新动漫
  'wlqp' => '0319', //网络棋牌
  
  'sdjy' => '0304', //山东教育
  
  'jyjs' => '0303', //金鹰纪实高清
  'jykt' => '0050', //金鹰卡通
  'xfpy' => '0320', //先锋乒羽

  'cftx' => '0148', //财富天下

  'jjkt' => '0087', //嘉佳卡通
);

header('location:'.'http://live.aishang.ctlcdn.com/0000011024'.$n[$id].'_1/playlist.m3u8?CONTENTID=0000011024'.$n[$id].'_1&AUTHINFO=FABqh274XDn8fkurD5614t%2B1RvYajgx%2Ba3PxUJe1SMO4OjrtFitM6ZQbSJEFffaD35hOAhZdTXOrK0W8QvBRom%2BXaXZYzB%2FQfYjeYzGgKhP%2Fdo%2BXpr4quVxlkA%2BubKvbU1XwJFRgrbX%2BnTs60JauQUrav8kLj%2FPH8LxkDFpzvkq75UfeY%2FVNDZygRZLw4j%2BXtwhj%2FIuXf1hJAU0X%2BheT7g%3D%3D&USERTOKEN=eHKuwve%2F35NVIR5qsO5XsuB0O2BhR0KR');
?>
