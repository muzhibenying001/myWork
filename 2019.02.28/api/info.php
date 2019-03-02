<?php 

#获取今天登陆人数
$count = $redis -> sCard(date('Ymd'));
#获取昨天登陆人数
$last = $redis -> sCard(date('Ymd')-1);
#获取文章总数量
$total = $redis -> zSize('article_set');
#获取用户总数量
$person = $redis -> sCard('user');
#组装七天内的登陆人数图表信息
$xAxis = '';
$series = '';
$t_month = mktime( 0,0,0,date('m'),date('d'),date('y') );
for ($i=6; $i >= 0; $i--) { 
	$temp = mktime( 0,0,0,date('m'),date('d')-$i,date('y') );
	$xAxis .= date('Y-m-d',$temp) . ',';
	$series .= $redis -> sCard(date('Ymd')-$i) . ',';
}

// $xAxis['data'] = array_reverse($xAxis['data']);
#整理信息，返回结果
$data['data']['logincount'] = $count;
$data['data']['artcount'] = $total;
$data['data']['last'] = $last;
$data['data']['person'] = $person;
$data['data']['charts']['xAxis'] = rtrim($xAxis,',');
$data['data']['charts']['series'] = rtrim($series,',');
$data['meta'] = ['status'=>200,'msg'=>'查询成功！'];

die(json_encode($data));
