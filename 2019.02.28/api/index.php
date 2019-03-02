<?php 

header('Access-Control-Allow-Origin: *');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:content-type,token,id');
header("Access-Control-Request-Headers: Origin, X-Requested-With, content-Type, Accept, Authorization");

if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
    header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
    file_put_contents('option.txt',json_encode($_REQUEST));

    exit;
}

#接收请求的类型
$type = isset($_POST['type']) && !empty(($_POST['type'])) ? $_POST['type'] : '';

#实例化redis
$redis = new Redis();
#连接redis服务
$redis -> connect('127.0.0.1',6379);

extract($_POST);
switch ($type) {
	#登陆请求
	case 'login':
		include 'login.php';
		break;
	#添加文章
	case 'artadd':
		include 'artadd.php';
		break;
	#获取文章列表
	case 'art':
		include 'art.php';
		break;
	#修改文章
	case 'artedit':
		include 'artedit.php';
		break;
	#删除文章
	case 'artdel':
		include 'artdel.php';
		break;
	#首页
	case 'info':
		include 'info.php';
		break;
	#用户列表
	case 'user':
		include 'user.php';
		break;
	#用户添加
	case 'useradd':
		include 'useradd.php';
		break;
	default:
		$data = ['data'=>[],'meta'=>['status'=>404,'msg'=>'参数有误，请联系管理员！']];
		echo json_encode($data);
		break;
}