<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>文章修改</title>
  <link rel="stylesheet" href="{{ asset('layui/css/layui.css')}}">
</head>
<body>

<div class="layui-container"> 
  	<div class="layui-row">
      	<h2>修改文章</h2>
	</div>
	<hr>	
	<div class="layui-form-item">
		<label class="layui-form-label">标题：</label>
		<div class="layui-input-block">
			<input type="text" placeholder="请输入标题" autocomplete="off" class="layui-input" id="title" value="{{ $data->title }}">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">描述：</label>
		<div class="layui-input-block">
			<input type="text" placeholder="请输入描述,不填自动从文章内容中获取" autocomplete="off" class="layui-input" id="desn" value="{{ $data->desn }}">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">内容：</label>
		<div class="layui-input-block">
			<script id="editor" type="text/plain" style="height:200px;"></script>
		</div>
	</div>
	<div class="layui-form-item">
	    <div class="layui-input-block">
	    	<button class="layui-btn" id="submit" onclick="edit()">修改</button>
			<button type="reset" class="layui-btn layui-btn-primary">重置</button>
	    </div>
	</div>
</div>
</body>
<script src="{{ asset('images/jquery.min.js')}}"></script>
<script src="{{ asset('layui/layui.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/ueditor.config.js')}}"></script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/ueditor.all.min.js')}}"> </script>
<script type="text/javascript" charset="utf-8" src="{{ asset('js/lang/zh-cn/zh-cn.js')}}"></script>
<script type="text/javascript">

	var ue = UE.getEditor('editor');
	ue.ready(function() {
	    ue.setContent('{!! $data->info !!}');
	});

	layui.use('layer', function(){
		var layer = layui.layer;		
	});
/*添加*/
function edit(){
	/*获取表单中的值*/
	let title = $('#title').val().trim();
	let desn = $('#desn').val().trim() ? $('#desn').val().trim() : ue.getPlainTxt();
	let info = ue.getContent();
	if( title == '{{ $data->title }}' && desn == '{{ $data->desn }}' && info == '{!! $data->info !!}' ){
		layer.msg('没有修改任何内容',{icon: 4});
		return;
	}

	/*表单验证*/
	if( !title ){
		$('#title').css('border','1px solid red');
		layer.msg('标题不能为空',{icon: 5});
	}else if( !ue.hasContents() && !$('#desn').val().trim() ){
		$('#desn').css('border','1px solid red');
		layer.msg('描述与文章内容必须填写一项',{icon: 5});
	}else{
		$('#desn').css('border','1px solid #e6e6e6');
		$('#title').css('border','1px solid #e6e6e6');
		let data = {title: title, info: info,desn:desn};
		/*验证完成，发送ajax*/
		$.ajax({
			'url'	: '{{ route("art.edit",['id'=>$data->id]) }}',
			'type'	: 'put',
			'data'	: data,
			'dataType'	: 'json',
			'headers'	: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			'timeout'	: '10000',
			'beforeSend': function(XHR){
				var index = layer.load(2, {time: 10*1000})
				$('#submit').attr('disabled',true).val('正在修改...');
	    	},
	    	'complete'	:function(){    			
				$('#submit').attr('disabled',false).val('修改');
				layer.closeAll('loading')
	    	},
	    	'success'	: function(res){
	    		if( res.code != 202 ){
					layer.msg(res.msg, {icon: 2,time:1000});
					return false;
				}

				var index = parent.layer.getFrameIndex(window.name);
				let html = '';
				html += '<td>{{$data->id}}</td>';
				html += '<td>'+ res.data.title +'</td>';
				html += '<td>'+ res.data.desn.slice(0,20) +'... </td>';
				html += '<td>'+ res.data.dtime +'</td>';
				html += '<td><button class="layui-btn layui-btn-xs" onclick="art_show('+ res.data.id +');"><i class="layui-icon">&#xe705;</i> 阅读</button>&nbsp;<button class="layui-btn layui-btn-normal layui-btn-xs" onclick="show_edit('+ res.data.id +');"><i class="layui-icon">&#xe642;</i> 编辑</button>&nbsp;<button class="layui-btn layui-btn-danger layui-btn-xs" onclick="show_edit('+ res.data.id +');"><i class="layui-icon">&#xe640;</i> 删除</button></td>';

				parent.$("a[row-id='{{ $data->id }}']").closest('tr').html( html );
				parent.layer.close(index);
				parent.layer.msg(res.msg, {icon: 1,time:1000});

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
}




    
</script>
</html>
