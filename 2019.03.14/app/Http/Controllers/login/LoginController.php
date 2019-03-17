<?php

namespace App\Http\Controllers\login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use Validator;

class LoginController extends Controller
{
	#登陆页面显示
    public function index(){
    	return view('login.login');
    }
    #登陆验证
    public function login(Request $request){
    	# 接收参数
    	$data = $request->post();
    	# 验证参数
    	$validator = Validator::make(
    		$data,
    		[
    			'username' => 'required',
    			'password' => 'required',
    			'captcha'  => 'required|captcha|min:4|max:4'
    		],
    		[
    			'username.required' => '用户名不能为空',
    			'password.required' => '密码不能为空',
    			'captcha.required'  => '验证码不能为空',
    			'captcha.min'       => '请输入4位数验证码',
    			'captcha.max'       => '请输入4位数验证码',
    			'captcha.captcha'   => '验证码输入有误',
    		]
    	);
    	# 判断验证是否通过
    	if( $validator -> fails() ){

    		return response()->json([
    			'code' => 300,
    			'msg'  => $validator->errors()->all()[0]
    		]);
    	}
    	# 判断用户名是否存在
    	if( ! $user = User::where('username',$data['username'])->first() ){
    		return response()->json([
    			'code' => 300,
    			'msg'  => '用户名不存在'
    		]);
    	}
    	# 生成用户登陆日志文件
    	$logs = storage_path('login.'.$data['username']);
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
    			if( strlen($contents) >= 3 ){

	    			return response()->json([
		    			'code' => 300,
		    			'msg'  => '密码错误次数过多，请3分钟后再来！',
		    			'num'  => 4
		    		]);	
		    	}
			}else{
				@unlink ($logs);
			}
		}
    	# 判断密码是否正确
    	if( $user->password !== md5($data['password']) ){
    		file_put_contents($logs,1,FILE_APPEND);
    		return response()->json([
		    			'code' => 300,
		    			'msg'  => '密码错误',
		    			'num'  => 2
		    		]);	
    	}
    	# 验证成功，将用户信息写入session
    	session(['admin.user' => $user ]);
    	# 返回结果
    	return response()->json([
			'code' => 200,
			'msg'  => '登陆成功'
		]);	

    }

    public function logout(){
        # 清空session
        session()->flush(); 
        # 跳到登陆页
        return redirect()->route('login.index');
    }
}
