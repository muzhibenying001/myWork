<?php 
# 引入自动加载类
include './myAutoUpload.php';
#实例化redis
$redis = new Redis();
#连接redis服务
$redis -> connect('127.0.0.1',6379);
#判断参数
if( isset($_POST['search']) ){
	$search = trim($_POST['search']);
	setcookie("search",$search);
}else{
	$search = $_COOKIE['search'] ?? '';
}
if( $search == '' ){ 
	header('location:index.php');
	die;
}
$redis -> select(3);
#验证关键词是否存在
if( $redis -> sIsMember('search',$search) ){
	#关键词存在，使分数增加1
	$redis -> zIncrBy('search_top',1,$search);
}else{
	#不存在，存储关键词
	$redis -> sAdd('search',$search);
	#记录关键词
	$redis -> set($search,$search); 
	#将关键词存入zset
	$redis -> zAdd('search_top', 1, $search);
}
# 实例化sphinx
$sphinx = new SphinxClient();
$sphinx->setServer('localhost',9312);
# 匹配任意分词
$sphinx->SetMatchMode(SPH_MATCH_ANY);
# 执行搜索查询  关键词    索引名  * 表示所有
$ret = $sphinx->query("{$search}","thosethings");
# 获取ID数组
$matches = $ret['matches'];

if (is_null($matches)) {
	$msg = "没有找到相关的信息";
}
// 获取键名
$ids = array_keys($matches);
# 获取总数
$count = count($ids);
# 合并ids
$idstring = implode(',',$ids);
#实例化分页
$p = new Paging($count,10);

#连接数据库
$pdo = Dao::getDao();
#编写sql语句
$sql = 'select * from thosethings where id in ('.$idstring.') '.$p->limit;
#执行查询
$query = $pdo -> dao_read($sql,false);

if( !$query ) return $msg = '没有相关的信息哦！';
#获取redis有序集合中 分数前5的关键词
$arr = $redis -> zRevRange('search_top',0,-1,true);
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>闲言轻博客</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="./res/static/css/bootstrap.min.css">
	<link rel="stylesheet" href="./res/layui/css/layui.css">
	<link rel="stylesheet" href="./res/static/css/mian.css">
</head>
<body class="lay-blog">
		<div class="header">
			<div class="header-wrap">
				<h1 class="logo pull-left" style="line-height: 100px;">
					<a href="#">
						<img src="./res/static/images/logo.png" alt="" class="logo-img">
					</a>
				</h1>
				<form class="layui-form blog-seach pull-left" action="search.php" method="post">
					<div class="layui-form-item blog-sewrap">
					    <div class="layui-input-block blog-sebox">
					      <i class="layui-icon layui-icon-search"></i>
					      <input type="text" name="search" lay-verify="title" autocomplete="off"  class="layui-input" placeholder="输入后按回车键查询">
					    </div>
					</div>
				</form>
				<div class="blog-nav pull-right">
					<a href="#" class="personal pull-left">
						<i class="layui-icon layui-icon-username"></i>
					</a>
				</div>
				<div class="mobile-nav pull-right" id="mobile-nav">
					<a href="javascript:;">
						<i class="layui-icon layui-icon-more"></i>
					</a>
				</div>
			</div>
		</div>

		<div class="layui-row">
			<div class="layui-col-md8">
				<div class="container" style="margin-top: 15px;">
					<nav id="nav_page" style="margin-bottom: 15px;">

					<?php
					
						$class = [	
							'class'=>"class='btn btn-default' ",
							'at_page'=>"class='btn btn-default  active' ",
							'disabled'=>"class='btn btn-default disabled' "
						];
								
						$p -> doJDPage('',$class);
					?>
					</nav>
					<?php foreach( $query as $k => $v ): ?>
					<div class="item">
						<div class="item-box  layer-photos-demo1 layer-photos-demo">
							<h3><a href="#"><?= $v['title'] ?></a></h3>
							<h5>发布于：<span>刚刚</span></h5>
							<p><?= $v['desn'] ?></p>
						</div>
					</div>
					<?php endforeach; ?>
					<nav id="nav_page" style="margin-bottom: 15px;">

					<?php
					
						$class = [	
							'class'=>"class='btn btn-default' ",
							'at_page'=>"class='btn btn-default  active' ",
							'disabled'=>"class='btn btn-default disabled' "
						];
								
						$p -> doJDPage('',$class);
					?>
					</nav>
				</div>
				</div>
			</div>
			<div class="layui-col-md3">
			  	<div class="grid-demo">
					<div class="layui-col-md4" style="position: fixed;top: 200px;right: 50px;">
						<div class="layui-card">
					        <div class="layui-card-header">
					        	<span style="text-align: left;">热搜榜</span>
					        	<span style="float: right;font-size: 12px;">搜索量</span>
					        </div>
					        <div class="layui-card-body">
					        	<ul>
					        		<?php if(!empty($arr)){
					        			$i = 0;
					        			foreach ($arr as $k => $v) {
					        				if($i>=5) break;
					        		?>
					        			<li>
					        				<span style="text-align: left;"><?= $k ?></span>
					        				<span style="float: right;font-size: 12px;"><?= $v ?></span>
					        			</li>					        			
					        		<?php
					        			$i++;
					        			}
					        		}
					        		?>
					        	</ul>
					        </div>
					      </div>
					</div>
			  		
			  	</div>
			</div>
		</div>
		<div class="footer">
			<p>
				<span> </span>
				<span> </span> 
				<span> </span>
			</p>
			<p><span>落 樱 白 度</span></p>
		</div>

	<script src="./res/layui/layui.js"></script>
	<script>
		layui.config({
		  base: './res/static/js/' 
		}).use('blog');	
	</script>
</body>
</html>