<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
	public function index(){
		return view('login');
	}


    public function login(Request $request){
    	$username = $request->post('username');
    	$password = $request->post('password');
    	
    	if( !$username ) die (json_encode(['code'=>303,'msg'=>'用户名不能为空','num'=>2]));

    	if( !$password ) die (json_encode(['code'=>303,'msg'=>'密码不能为空','num'=>2]));

    	if( $username != 'admin' ) die (json_encode(['code'=>303,'msg'=>'用户名不存在','num'=>2]));
    	#生成日志文件路径
		$logs = storage_path('login.'.$username);
		# 判断用户登陆日志是否存在
		if( file_exists($logs) ){
			# 判断日志文件创建时间
			if( ( time() - filectime($logs)) <= 180 ){
    			# 打开日志文件
    			$handle = fopen($logs, 'r');
    			# 读取日志文件内容 
    			$contents = fread($handle, filesize ($logs));
    			# 关闭文件
    			fclose($handle);
    			# 判断登陆错误次数
    			if( strlen($contents) >= 3 ) die (json_encode(['code'=>400,'msg'=>'密码错误次数超过3次，请3分钟后再来！','num'=>4]));		
			}else{
				@unlink ($logs);
			}
		}

    	if( $password != 'admin' ){ 
    		# 写入日志 追加记录日志
			file_put_contents($logs,1,FILE_APPEND);

    		die (json_encode(['code'=>303,'msg'=>'密码错误','num'=>2]));
    	}
    	
		die (json_encode(['code'=>200,'msg'=>'登陆成功！']));
    }

    public function welcome(){
		return 'Hello World!';
	}
}
