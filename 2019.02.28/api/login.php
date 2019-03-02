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
if( ! $redis -> sIsMember('user',$username) ){
	$data = ['data'=>[],'meta'=>['status'=>400,'msg'=>'用户名不存在！']];
	die(json_encode($data));
}

#获取用户id
$user_id = $redis -> get($username);

#跟据获取用户id 获取密码
$user_password = $redis->hget("user:{$user_id}",'password');

#判断用户输入的密码
if( $password != $user_password ){
	#判断set中是否有输入错误的情况
	if(!$redis->exists($username.'login')){
        // 用户名对应的KEY不存在，进行设置，且还带有效期
        $redis->setEx($username.'login', 3600, 1);

    }else{
        // 判断是否已经超过了3次限制则拒绝
        if ($redis->get($username.'login') >= 3){
            $data = ['data'=>[],'meta'=>['status'=>402,'msg'=>'密码输入错误次数过多，请1小时后再来！']];
			die(json_encode($data));
        }else{
            $redis->incr($username.'login');
        }
    }
	$data = ['data'=>[],'meta'=>['status'=>400,'msg'=>'密码有误，请重新输入！']];
	die(json_encode($data));
}

if ($redis->get($username.'login') >= 3){
	$data = ['data'=>[],'meta'=>['status'=>402,'msg'=>'密码输入错误次数过多，请1小时后再来！']];
}else{
	#整理信息，返回结果
	$data['data'] = ['id'=>$user_id,'username'=>$username];
	$data['meta'] = ['status'=>200,'msg'=>'欢迎回来！'];
	#计录今天登陆人数
	$redis->sAdd(date('Ymd'),$user_id);
}



die(json_encode($data));
