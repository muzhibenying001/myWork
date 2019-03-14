<?php 

// header("Content-type: text/html; charset=gb2312");
#设置请求地址
$url = "http://www.haha56.net/main/youmo/201704/15843.html";
#初始化CURL
$ch = curl_init();
#CURL配置地址
curl_setopt($ch, CURLOPT_URL, $url);
#配置输入的信息不直接输出
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
#执行请求，获取到页上的html代码
$html = curl_exec($ch);
#先去掉html中所有换行符/n
$html = preg_replace('/\\n*/', '', $html);
#匹配所有P标签中的内容
preg_match_all('/<p.*?>.*?<\/p>/', $html, $li);
#将得到的二维数组进行处理
foreach ($li[0] as $key => &$value) {
	# 去掉P标签
	$data[$key] = preg_replace('/<p>/', '', $value);
	$data[$key] = preg_replace('/<\/p>/', '', $data[$key]);
	# 去掉br
	$data[$key] = preg_replace('/<br \/>/', '', $data[$key]);
}
// INSERT INTO joke_100(content) values ('11'),('22');
$values = '';
#处理数组，整合为sql语句
for( $i=0 ; $i<count($data)-1 ; $i++ ){
	$values .= "('".$data[$i]."'),";
}
#去掉最后一个逗号
$values = rtrim($values,',');
#字符集转换
$values = iconv('gbk','utf-8',$values);
#连接数据库
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8','root','111111');
#连接sql语句
$sql = "insert into joke_100(content) values " . $values;
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





