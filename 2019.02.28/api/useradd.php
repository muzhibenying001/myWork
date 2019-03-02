<?php 

#接收参数并检测是否为空
if(! $username = isset($username) && !empty($username) ? trim($username) : '' ){
	$data = ['data'=>[],'meta'=>['status'=>400,'msg'=>'用户名不能为空！']];
	die(json_encode($data));
}
if(! $password = isset($password) && !empty($password) ? trim($password) : '' ){
	$data = ['data'=>[],'meta'=>['status'=>400,'msg'=>'密码不能为空！']];
	die(json_encode($data));
}
#验证用户名是否存在
if( $redis -> sIsMember('user',$username) ){
	$data = ['data'=>[],'meta'=>['status'=>400,'msg'=>'用户名已存在！']];
	die(json_encode($data));
}

#得到用户名主键ID号
$user_id = $redis->incr('user:count');
#存储用户id
$redis->set($username,$user_id);
#整合数据
$user = ['id'=>$user_id,'username'=>$username,'password'=>$password,'create_time'=>date('Y-m-d H:i:s')];
#把用户名放到用户列表中
$redis->sAdd('user',$username);
#把用户的信息存入hash中
$info = $redis->hMSet('user:'.$user_id,$user);
#整理信息，返回结果
$data['data'] = $user;
$data['meta'] = ['status'=>201,'msg'=>'添加成功！'];

if( !$info ) $data['meta'] = ['status'=>500,'msg'=>'添加失败！'];

die(json_encode($data));
