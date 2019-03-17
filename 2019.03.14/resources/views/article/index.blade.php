@extends('article.public')

@section('title','文章列表')

@section('content')
<div class="layui-card">
  <div class="layui-card-body">
	<span class="layui-breadcrumb" lay-separator=">">
	  <a href="">首页</a>
	  <a href="">文章管理</a>
	  <a href="">文章列表</a>
	  <a><cite>正文</cite></a>
	</span>
  </div>
</div>
<div class="layui-card" style="margin-top: 30px;">
  <div class="layui-card-header" style="padding: 20px;text-align: center;">
  	<div class="layui-form-item">
 
	  <div class="layui-inline">
	    <label class="layui-form-label">搜索范围</label>
	    <div class="layui-input-inline" style="width: 150px;">
	      <input type="text" value="{{ $like['datemin'] ?? ''}}" placeholder="请选择日期" autocomplete="off" class="layui-input" id="datemin">
	    </div>
	    <div class="layui-form-mid">-</div>
	    <div class="layui-input-inline" style="width: 150px;">
	      <input type="text" value="{{ $like['datemax'] ?? ''}}" placeholder="请选择日期" autocomplete="off" class="layui-input" id="datemax">
	    </div>
	  </div>
	  
	  <div class="layui-inline">
	    <div class="layui-input-inline" style="width: 200px;">
	      <input type="text" id="search" placeholder="请输入标题"  autocomplete="off" class="layui-input"  value="{{ $like['search'] ?? ''}}" >
	    </div>
	  </div>
	  <div class="layui-inline">
	    <button class="layui-btn" onclick="search()"><i class="layui-icon layui-icon-search" style="font-size: 14px;"></i> 搜 索</button>
	    <button class="layui-btn layui-btn-primary" onclick="research()"><i class="layui-icon layui-icon-refresh" style="font-size: 14px;"></i> 刷 新</button>
	  </div>
	  
	</div>
  </div>
  <div class="layui-card-body">
    <table class="layui-table">
	  <colgroup>
	    <col width="30">
	    <col width="350">
	    <col>
	    <col>
	    <col width="230">
	  </colgroup>
	  <thead>
	  	<tr>
	  		<td colspan="5" style="background: #fff;">
	  			<button class="layui-btn" onclick="show_add();"><i class="layui-icon">&#xe61f;</i> 添加文章</button>
	  		</td>
	  	</tr>
	    <tr>
	      <th>ID</th>
	      <th>标题</th>
	      <th>简介</th>
	      <th>更新日期</th>
	      <th>操作</th>
	    </tr> 
	  </thead>
	  <tbody>
	  	@forelse ($data as $v)	  	
	    <tr>
	      <td>{{ $v->id }}</td>
	      <td><a href="javascript:;"  row-id="{{ $v->id }}" onclick="show_content('{{ route('art.show',['id'=>$v->id]) }}')">{!! mb_strlen($v->title,'utf-8') > 20 ? (mb_substr($v->title, 0, 20, 'utf-8')).' ...' : $v->title !!}</a></td>
	      <td>{!! mb_strlen($v->desn,'utf-8') > 30 ? (mb_substr($v->desn, 0, 30, 'utf-8')).' ...' : $v->desn!!}</td>
	      <td>{{ $v->dtime }}</td>
	      <td>
	      	<button class="layui-btn layui-btn-xs" onclick="show_content('{{ route('art.show',['id'=>$v->id]) }}')"><i class="layui-icon">&#xe705;</i> 阅读</button>
	      	<button class="layui-btn layui-btn-normal layui-btn-xs" onclick="show_edit('{{ route('art.edit',['id'=>$v->id]) }}')"><i class="layui-icon">&#xe642;</i> 编辑</button>
	      	<button class="layui-btn layui-btn-danger layui-btn-xs" onclick="show_del('{{ route('art.del',['id'=>$v->id]) }}',this);"><i class="layui-icon">&#xe640;</i> 删除</button>
	      </td>
	    </tr>
	  	@empty
	  	<tr>
	  		<td colspan="5">暂无数据</td>
	  	</tr>
	  	@endforelse
	    
	  </tbody>
	</table>
		<div id="page1"></div>
  </div>
</div>
@endsection


@section('layui')

	layui.use('laydate', function(){
	  var laydate = layui.laydate;
	  
	  //执行一个laydate实例
	  laydate.render({
	    elem: '#datemin', //指定元素
	    theme: '#393D49',
	    max: 0,
	  });
	  laydate.render({
	    elem: '#datemax', //指定元素
	    theme: '#393D49',
	    max: 0,
	  });
	});


	layui.use('laypage', function(){
	  var laypage = layui.laypage;
	  
	  //执行一个laypage实例
	  laypage.render({
	    elem: 'page1',
	    count: {{ $count }},
	    curr: location.hash.replace('#!page=', ''),
	    hash: 'page',
	    layout: ['count', 'prev', 'page', 'next', 'skip'],
	    jump: function(obj,first){
	    	let url = getUrl();
	    	if(!first) $(location).one().attr('href', url+'page='+obj.curr+'&#!page='+obj.curr);
	    }
	  });
	});
@endsection

@section('script')
<script>
layui.use('layer', function(){
	var layer = layui.layer;		
});

/*添加文章*/
function show_add(){
	layer.open({
		type: 2, 
		title: '添加文章',
		area: ['1000px', '600px'],
		content: '{{ route("art.add") }}'
	});	
}
/*修改文章*/
function show_edit(url){
	layer.open({
		type: 2, 
		title: '修改文章',
		area: ['1000px', '600px'],
		content: url
	});	
}

/*删除文章*/
function show_del(url,obj){
	layer.confirm('确认删除吗?', {icon: 0, title:'提示'}, 
		function(index){
			let page = $(location).attr('href').split('page=')[2] ? $(location).attr('href').split('page=')[2] : 1;
			/*发送ajax*/
			$.ajax({
			'url'	: url+'?page='+page,
			'type'	: 'delete',
			'dataType'	: 'json',
			'headers'	: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
			'timeout'	: '10000',
			'beforeSend': function(XHR){
				var index = layer.load(2, {time: 10*1000})
	    	},
	    	'complete'	:function(){    			
				layer.closeAll('loading')
	    	},
	    	'success'	: function(res){
	    		if( res.code != 204 ){
					layer.msg(res.msg, {icon: 2,time:1000});
					return false;
				}
				$(obj).parents("tr").remove();
				let html = '<tr>';
				html += '<td>'+ res.data.id +'</td>';
				html += '<td>'+ res.data.title +'</td>';
				html += '<td>'+ res.data.desn.slice(0,20) +'... </td>';
				html += '<td>'+ res.data.dtime +'</td>';
				html += '<td><button class="layui-btn layui-btn-xs" onclick="show_content(\''+res.data.show_url+'\')"><i class="layui-icon">&#xe705;</i> 阅读</button>&nbsp;<button class="layui-btn layui-btn-normal layui-btn-xs" onclick="show_edit(\''+ res.data.edit_url +'\')"><i class="layui-icon">&#xe642;</i> 编辑</button>&nbsp;<button class="layui-btn layui-btn-danger layui-btn-xs" onclick="show_del(\''+ res.data.del_url +'\',this)"><i class="layui-icon">&#xe640;</i> 删除</button></td></tr>';
				$("table").append(html);
				layer.msg(res.msg,{icon:1,time:1000});
	    	},
	    	'error'		: function(XMLHttpRequest, textStatus, errorThrown){
	    		switch(textStatus){
	    			case 'timeout':
	    				layer.msg('连接超时，请检查网络', {icon: 2,time:1500});
	    				break;
	    			case 'error':
	    				layer.msg(textStatus, {icon: 2,time:1500});
	    				break;
	    			default:
	    				layer.msg('出错啦，请联系管理员处理！', {icon: 2,time:2000});
	    				break;
	    		}
	    	}

		});
		}
	);
}
/*查看文章*/
function show_content(url){
	layer.open({
		type: 2, 
		title: '文章详情',
		area: ['800px', '600px'],
		content: url
	});	
}
/*搜索框按下回车事件*/
$('#search').keyup(function(event){
    if(event.keyCode ==13){
        let url = getUrl();
		$(location).attr('href',url);
    }
});
/*搜索按钮*/
function search(){
	let url = getUrl();
	$(location).attr('href',url);
}
/*清空按钮*/
function research(){
	$(location).attr('href','{{ route('art.index') }}');
}
/*获取url的函数,参数为需要传递的url参数*/
function getUrl(){
	let datemin = $('#datemin').val() ? $('#datemin').val() : '';
	let datemax = $('#datemax').val() ? $('#datemax').val() : '';
	let search = $('#search').val() ? $('#search').val() : '';

	let url = "{{ route('art.index') }}"+'?';
	
	if( datemin ) url += 'datemin=' + datemin + '&';
	if( datemax ) url += 'datemax=' + datemax + '&';
	if( search ) url += 'search=' + search + '&';

	return url;	
}

</script>
@endsection