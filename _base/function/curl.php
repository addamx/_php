<?php
// $ch = curl_init("http://www.baidu.com");
// $fp = fopen("test.html", "w");

// curl_setopt($ch, CURLOPT_FILE, $fp);
// curl_setopt($ch, CURLOPT_HEADER, true);

// curl_exec($ch);
// curl_close($ch);
// fclose($fp);

$url = 'http://bbs.hefei.cc/';
$ch  = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); //需要获取的 URL 地址，也可以在curl_init() 初始化会话的时候。
curl_setopt($ch, CURLOPT_TIMEOUT, 5); /* 设置cURL允许执行的最长秒数 */
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //将curl_exec()获取的信息以字符串返回，而不是直接输出
curl_setopt($ch, CURLINFO_HEADER_OUT, true); //追踪句柄的请求字符串。
curl_setopt($ch, CURLOPT_HEADER, true); //启用时会将头文件的信息作为数据流输出。
curl_setopt($ch, CURLOPT_NOBODY, false); //默认true: 将不输出 BODY 部分。同时 Mehtod 变成了 HEAD。修改为 FALSE 时不会变成 GET
$content                        = curl_exec($ch);
$headerStr                      = curl_getinfo($ch, CURLINFO_HEADER_OUT); //获取发出的header
list($responseStr, $contentStr) = explode("\r\n\r\n", $content, 2);
echo "request header:" . $d;
echo '<br/>';
echo 'response header:' . $responseStr;
echo '<br/>';
echo 'response content:' . $contentStr;
