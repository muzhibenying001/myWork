<?php 
# 引入自动加载类
include './myAutoUpload.php';
#实例化redis
$redis = new Redis();
#连接redis服务
$redis -> connect('127.0.0.1',6379);
$redis -> select(3);
#判断参数
if( isset($_POST['search']) ){
	$search = trim($_POST['search']);
	setcookie("search",$search);
}else{
	$search = $_COOKIE['search'] ?? '';
}
if( $search != '' ){ 

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

	header('location:search.php');
	die;
}

#实例化分页
$p = new Paging(100,10);

#连接数据库
$pdo = Dao::getDao();
#编写sql语句
$sql = 'select * from thosethings '.$p->limit;
#执行查询
$query = $pdo -> dao_read($sql,false);

if( !$query ) $msg = '没有相关的信息哦！';
#获取redis有序集合中 分数前5的关键词
$arr = $redis -> zRevRange('search_top',0,-1,true);
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>落樱白渡</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="./res/static/css/bootstrap.min.css">
	<link rel="stylesheet" href="./res/layui/css/layui.css">
	<link rel="stylesheet" href="./res/static/css/mian.css">
	<style type="text/css">
		.dropDown-pos{
			position: relative;
		}
		.dropDown-mymenu{
			display: none;
			position: absolute;
			left: 0px;
			top: 50px;
			z-index: 100;
			width: 400px;
			background: #eee;
			font-size: 16px;
		}
	</style>
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
					      <input type="text" name="search" lay-verify="title" autocomplete="off" id="area" class="layui-input" placeholder="输入后按回车键查询">
					      <ul class="dropDown-menu menu radius box-shadow dropDown-mymenu">
							</ul>
					    </div>
					</div>
				</form>
				<div class="blog-nav pull-right">
					<ul class="layui-nav pull-left">
					  <li class="layui-nav-item layui-this"><a href="#">首页</a></li>
					</ul>
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
			<p><span>落 樱 白 渡</span></p>
		</div>

	<script src="./res/layui/layui.js"></script>
	<script>
		layui.config({
		  base: './res/static/js/' 
		}).use('blog');	
	</script>
	<script src="./res/jquery.js"></script>
	<script type="text/javascript">
		var dothing = true;
		$('#area').on({
			compositionstart: function(){ dothing = false },
			compositionend: function(){ dothing = true },
	input :function(){
		var that = this;
        setTimeout(function(){

			if( $(that).val() && dothing ){

				$('.dropDown-mymenu').show();
				/*发送ajax 获取所输入的推荐*/
				$.ajax({
					'url'	: 'ajax.php',
					'type'	: 'post',
					'data'	: 'areaname='+ $(that).val(),
					'dataType': 'json',

					'success':function(res){

						if(res.meta.status != 200){
							return false;
						}else if(res.meta.status == 200){

							let html = '';

							for( i of res.data ){

								html += '<li><a href="javascript:;" style="padding-left:30px;">'+ i +'</a></li>';
							}
							
							$('.dropDown-mymenu').html(html);
						}
					}
				});
			}else{

				$('.dropDown-mymenu').hide();
			}

        },0);
	}
});
		$('#area').bind('blur', function(){
			setTimeout(function(){
				$('.dropDown-mymenu').hide();
			},300);
		});

		$('.dropDown-mymenu').on('click','a',function(){

			$('#area').val( $(this).html() );

			$('.dropDown-mymenu').hide();
		})
	</script>
</body>
</html>