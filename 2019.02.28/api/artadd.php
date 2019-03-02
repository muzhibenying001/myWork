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

#得到文章主键ID号
$art_id = $redis->incr('article_id');
#整合数据
$art = ['id'=>$art_id,'title'=>$title,'content'=>$content,'user_id'=>$id,'user_name'=>$username,'create_time'=>date('Y-m-d H:i:s')];
#把ID放到文章列表中
$redis->zAdd('article_set', $art_id, $art_id);
#把文章ID放到对应的用户集合中
$redis->sAdd($username.'art',$art_id);
#把文章的信息存入hash中
$info = $redis->hMSet('article:id:'.$art_id,$art);
#整理信息，返回结果
$data['data'] = $art;
$data['meta'] = ['status'=>201,'msg'=>'添加成功！'];

if( !$info ) $data['meta'] = ['status'=>500,'msg'=>'添加失败！'];

die(json_encode($data));
