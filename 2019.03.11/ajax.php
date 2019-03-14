<?php 

#接收参数并检测是否为空
if( ! $areaname = isset($_POST['areaname']) && !empty($_POST['areaname']) ? trim($_POST['areaname']) : '' ){
	$data = ['data'=>[],'meta'=>['status'=>400,'msg'=>'空字符串']];
	die(json_encode($data));
}

#实例化redis
$redis = new Redis();
#连接redis服务
$redis -> connect('127.0.0.1',6379);
$redis -> select(3);
#获取redis有序集合中
$arr = $redis -> zRevRange('search_top',0,-1,true);

$temp = array();

foreach ($arr as $key => $value) {
	if(strstr($key,$areaname) !== false){
		array_push($temp, $key);
	}
}
#整理信息，返回结果
$data['data'] = $temp;
$data['meta'] = ['status'=>200,'msg'=>'ok'];

// if( !$info ) $data['meta'] = ['status'=>500,'msg'=>'添加失败！'];

die(json_encode($data));
