<?php
// 程序永不过期
set_time_limit(0);

$title_array = [];
$desn_array = [];
# 获取5页数据
for( $i=2; $i<=6; $i++ ){

	$url = 'http://www.yixieshi.com/page/'.$i;

	$data = http_request($url);

	$preg = '#<div class="article-box clearfix excerpt-\d+">\s*(.*)class="txtcont hidden-xs"><a href=".*"\s+title="(.*)-互联网的一些事">(.*)</a>#iUs';

	preg_match_all($preg, $data, $arr);

	$title_array = array_merge($title_array,$arr[2]);
	$desn_array = array_merge($desn_array,$arr[3]);
}
# 整合数据
$data = [];
foreach ($title_array as $key => $value) {
	$data[$key]['title'] = $value;
	$data[$key]['desn'] = $desn_array[$key];
}
#拼接字符sql串
$values = '';
#处理数组，整合为sql语句
for( $i=0 ; $i<count($data); $i++ ){
	$values .= "('".$data[$i]['title']."','".$data[$i]['desn']."'),";
}
#去掉最后一个逗号
$values = rtrim($values,',');
#连接数据库
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8','root','111111');
#连接sql语句
$sql = "insert into thosethings(title,desn) values " . $values;
#执行入库
$rows = $pdo -> exec($sql);

if(false === $rows){
    //取出错误细信息
    echo 'SQL错误：<br/>';
    echo '错误代码为：' . $pdo->errorCode() . '<br/>';
    echo '错误原因为：' . $pdo->errorInfo()[2];		
    exit;		

}

echo "成功的数量：{$rows}";

#http请求方法
function http_request(string $url,$data = '',string $filepath = ''){
	# $filepath不为空表示有文件上传
	if(!empty($filepath)){
		$data['media'] = new CURLFile($filepath);
	}
	# 1、初始化 相当于打开了浏览器
	$ch = curl_init();
	# 2、相关的设置
	# 请求的URL地址设置
	curl_setopt($ch,CURLOPT_URL,$url);
	# 设置输出的信息不直接输出
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	# 取消https证书验证
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
	# 设置请求的超时时间 单是秒
	curl_setopt($ch,CURLOPT_TIMEOUT,10);
	# 模拟一个浏览器型号
	curl_setopt($ch,CURLOPT_USERAGENT,'MSIE');

	# 表示有数据上传
	if (!empty($data)) {
		# 如果是一个字符串，表示是一个json
		if (is_string($data)) {
			# 如果json类型加一个头信息说明   # 设置头信息
			curl_setopt($ch,CURLOPT_HTTPHEADER,[
				'Content-Type: application/json;charset=utf-8'
			]);
		}
		# 告诉curl你使用了post请求
		curl_setopt($ch,CURLOPT_POST,1);
		# post的数据
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	}
	# 3、执行请求操作
	$data = curl_exec($ch);
	# 得到请求的错误码  0返回请求成功，大于0表示请求有异常
	$errno = curl_errno($ch);
	if (0 < $errno) {
		# 抛出自己的异常
		throw new Exception(curl_error($ch), 1000);
	}
	# 4、关闭资源
	curl_close($ch);

	# 返回数据
	return $data;
}