<?php 

#删除zset列表中的id
$redis->zDelete('article_set',$id);
#删除hash中数据
$redis->hDel('article:id:'.$id);

$data['data'] = null;
$data['meta'] = ['status'=>200,'msg'=>'删除成功！'];

die(json_encode($data));
