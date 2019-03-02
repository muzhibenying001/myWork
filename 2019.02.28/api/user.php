<?php 

#获取所有用户名
$ids = $redis -> sMembers('user');
#获取每个用户的文章数量
foreach ($ids as $v) {
	$temp = ['username'=>$v,'count'=>$redis -> sCard($v.'art')];
	$art[] = $temp;
}

#整理信息，返回结果
$data['data']['user'] = $art;
$data['meta'] = ['status'=>200,'msg'=>'查询成功！'];

die(json_encode($data));
