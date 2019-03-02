<?php 

#接收参数并检测是否为空
if(! $title = isset($title) && !empty($title) ? trim($title) : '' ){
	$data = ['data'=>[],'meta'=>['status'=>400,'msg'=>'标题不能为空！']];
	die(json_encode($data));
}
if(! $content = isset($content) && !empty($content) ? trim($content) : '' ){
	$data = ['data'=>[],'meta'=>['status'=>400,'msg'=>'内容不能为空！']];
	die(json_encode($data));
}

#整合数据
$art = ['title'=>$title,'content'=>$content,'create_time'=>date('Y-m-d H:i:s')];

#修改hash中文章的信息
$info = $redis->hMSet('article:id:'.$id,$art);
#整理信息，返回结果
$data['data'] = $art;
$data['meta'] = ['status'=>200,'msg'=>'修改成功！'];

if( $info === false ) $data['meta'] = ['status'=>500,'msg'=>'修改失败！'];

die(json_encode($data));
