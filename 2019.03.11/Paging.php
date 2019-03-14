<?php 

/**
 *  调用doPage方法 直接输出分页标签，a标签，方法参数：
 *  调用doJDPage方法 直接输出只显示7分页样式标签，a标签，方法参数：
 *  @param  $[array] $class 为控制标签的css样式类名
 *      标签说明：
 *        页面为：当前页、第一页、最后一页 为span标签
 *        默认class名为default-page 
 *        当前页class为激活状态default-page active
 *        第一页、最后一页class为禁用状态default-page disabled
 *      可直接使用相应的class进行样式控制，也可传递样式数组
 *        例：$arr = [	
				'class'=>"class='btn btn-default' ",
				'at_page'=>"class='btn btn-default  active' ",
				'disabled'=>"class='btn btn-default disabled' "
				];
 *  @param  $[string] $url 给a标签的href使用的地址，默认为当前页
 */


class Paging{
	
	#属性
	private $offset; #limit语句中的偏移量
	private $limit; #sql中的limit
	private $tot; #总数据
	private $lenth; #每页数量
	private $pages; #总页数
	private $page = 1; #当前页
	#给doPage使用的css样式
	private $class_arr =  [
			'class'=> "class='default-page'",
			'at_page'=>"class='default-page active'",
			'disabled'=>"class='default-page disabled'"
		]; 

	#构造方法初始化，需传进参数：$tot总数据数 $lenth每页数量
	public function __construct($tot,$lenth=6){
		$this->tot = $tot;
		$this->lenth = $lenth;
		$this->pages = $tot>0 ? ceil( $tot/$lenth ) : 1;
		#当前页判定
		if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page']>0){
			$this->page = $_GET['page'];
		}
		if($this->page >= $this->pages){
			$this->page = $this->pages;
		}
		$this->offset = ($this->page-1) * $this->lenth;
		
	}



	/*
		生成数字页码
		@param [array] $class['class'] a标签css样式
					   $class['at_page'] 当前页的样式
					   $class['disabled'] 禁用样式
		@param [string] $url 需要访问的url地址
	 */
	public function doPage($url='',$class_arr=[]){

		#验证是否正确的传递进来参数，如果有误，则转为默认
		
		if( is_array($class_arr) ){
			if( !isset($class_arr['class']) ){
				$class_arr['class'] = $this->class_arr['class'];
			}elseif( !isset($class_arr['at_page']) ){
				$class_arr['at_page'] = $this->class_arr['at_page'];
			}elseif( !isset($class_arr['disabled']) ){
				$class_arr['disabled'] = $this->class_arr['disabled'];
			}
		}else{
			$class_arr = $this->class_arr;
		}

		#生成“上一页按钮”
		$prev = $this->page - 1;
		if($prev<1){
			echo "<span {$class_arr['disabled']}>上一页</span>";			
		}else{
			echo "<a {$class_arr['class']} href={$url}?page={$prev}>上一页</a>";
		}

		#生成“数字页码”
		for($i=1;$i<=$this->pages;$i++){
			if($i==$this->page){
				echo " <span {$class_arr['at_page']}>{$i}</span> ";				
			}else{
				echo " <a {$class_arr['class']} href={$url}?page={$i}>{$i}</a> ";
			}
		}

		#生成“下一页按钮”
		$next = $this->page + 1;
		if($next>$this->pages){
			echo "<span {$class_arr['disabled']}>下一页</span>";		
		}else{
			echo "<a {$class_arr['class']} href={$url}?page={$next}>下一页</a>";
		}

	}

	public function doJDPage($url='',$class_arr=[]){
		#验证是否正确的传递进来参数，如果有误，则转为默认		
		if( is_array($class_arr) ){
			if( !isset($class_arr['class']) ){
				$class_arr['class'] = $this->class_arr['class'];
			}elseif( !isset($class_arr['at_page']) ){
				$class_arr['at_page'] = $this->class_arr['at_page'];
			}elseif( !isset($class_arr['disabled']) ){
				$class_arr['disabled'] = $this->class_arr['disabled'];
			}
		}else{
			$class_arr = $this->class_arr;
		}

		#生成“上一页按钮”
		$prev = $this->page - 1;
		if($prev<1){
			echo "<span {$class_arr['disabled']}>上一页</span>";			
		}else{
			echo "<a {$class_arr['class']} href={$url}?page={$prev}>上一页</a>";
		}

		#生成“数字页码”
		if( $this->pages < 7 ){
			#当总页数小于7页时
			for($i=1;$i<=7;$i++){
				
				if($i==$this->page){
					echo " <span {$class_arr['at_page']}>{$i}</span> ";				
				}else{
					echo " <a {$class_arr['class']} href={$url}?page={$i}>{$i}</a> ";
				}
				if( $i >= $this->pages ) break;
			}

		}else{
			#当总页数大于7页时
			if( $this->page <= 5 ){
			#1.当前页小于5时
				for( $i=1; $i<=7;$i++ ){
					if($i==$this->page){
						echo " <span {$class_arr['at_page']}>{$i}</span> ";				
					}else{
						echo " <a {$class_arr['class']} href={$url}?page={$i}>{$i}</a> ";
					}
				}

				echo " <span>&nbsp;&nbsp; ... &nbsp;&nbsp;</span> ";

			}elseif( $this->page > 5 && $this->page <= $this->pages - 3 ){
			#2.当前页大于5时
				echo " <a {$class_arr['class']} href={$url}?page=1>1</a> ";
				echo " <a {$class_arr['class']} href={$url}?page=2>2</a> ";

				echo " <span>&nbsp;&nbsp; ... &nbsp;&nbsp;</span> ";

				for( $i=$this->page-2; $i<=$this->page + 2; $i++ ){
					if($i==$this->page){
						echo " <span {$class_arr['at_page']}>{$i}</span> ";				
					}else{
						echo " <a {$class_arr['class']} href={$url}?page={$i}>{$i}</a> ";
					}
				}


				echo " <span>&nbsp;&nbsp; ... &nbsp;&nbsp;</span> ";

			}elseif( $this->page >= $this->pages -3 ){

				echo " <a {$class_arr['class']} href={$url}?page=1>1</a> ";
				echo " <a {$class_arr['class']} href={$url}?page=2>2</a> ";

				echo " <span>&nbsp;&nbsp; ... &nbsp;&nbsp;</span> ";

				for( $i= $this->pages - 4 ; $i<=$this->pages ; $i++ ){

					if($i==$this->page){
						echo " <span {$class_arr['at_page']}>{$i}</span> ";				
					}else{
						echo " <a {$class_arr['class']} href={$url}?page={$i}>{$i}</a> ";
					}
				}
			}

			
		}


		#生成“下一页按钮”
		$next = $this->page + 1;
		if($next>$this->pages){
			echo "<span {$class_arr['disabled']}>下一页</span>";		
		}else{
			echo "<a {$class_arr['class']} href={$url}?page={$next}>下一页</a>";
		}
	}

	public function __get($offset){
		// $this->limit = ['skip'=>$this->offset,'limit'=>$this->lenth];
		$this->limit = 'limit '.$this->offset.','.$this->lenth;
		return $this->limit;
	}
}