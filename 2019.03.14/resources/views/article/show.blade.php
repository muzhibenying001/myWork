<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>文章添加</title>
  <link rel="stylesheet" href="{{ asset('layui/css/layui.css')}}">
</head>
<body>

<div class="layui-container"> 
  	<div class="layui-row">
      	<h2>{{ $data->title }}</h2>
	</div>
	<hr>	
	<div>
		<p>于 {{ $data->updated_at }} 最后更新</p>
		<br>
		<p>{!! $data->info !!}</p>
	</div>
</div>
</body>

</html>
