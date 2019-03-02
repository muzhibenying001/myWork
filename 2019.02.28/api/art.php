<?php 

#接收参数并检测是否为空
$query = isset($query) && !empty($query) ? trim($query) : '';

#获取文章总数量
$total = $redis -> zSize('article_set');
#获取当前页码
$pagenum = isset($pagenum) && !empty($pagenum) ? $pagenum : 1 ;
#获取每页文章数量
$pagesize = isset($pagenum) && !empty($pagenum) ? $pagesize : 5 ;
#计算分页起始位置
$start = ($pagenum - 1) * $pagesize;
$end = ($pagenum - 1) * $pagesize + ($pagesize - 1);
#获取zset中的文章数据
$ids = $redis -> zRange('article_set',$start,$end);
#遍历ids，获取所有文章信息
foreach ($ids as $v) {
	$temp = $redis -> hGetAll('article:id:'.$v);

	if( stripos($temp['title'],$query) !== false ){

		$art[] = $temp;
	}else{
		$art[] = $temp;
	}

	
}

#整理信息，返回结果
$data['data']['article'] = $art;
$data['data']['total'] = $total;
$data['meta'] = ['status'=>200,'msg'=>'查询成功！'];

die(json_encode($data));
