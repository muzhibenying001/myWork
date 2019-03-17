<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <link rel="stylesheet" href="{{ asset('layui/css/layui.css')}}">
  <style type="text/css">
    .layui-logo{
      background: #20222A;
    }
    .layui-body{
      background: #F6F6F6;
    }
  </style>
  @yield('css')
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo"><img src="{{ asset('images/logo.png') }}" width="120"></div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
      <li class="layui-nav-item"><a href="">控制台</a></li>
      <li class="layui-nav-item"><a href="">文章管理</a></li>
      <li class="layui-nav-item"><a href="">会员管理</a></li>
      <li class="layui-nav-item">
        <a href="javascript:;">其它系统</a>
        <dl class="layui-nav-child">
          <dd><a href="">邮件管理</a></dd>
          <dd><a href="">消息管理</a></dd>
          <dd><a href="">授权管理</a></dd>
        </dl>
      </li>
    </ul>
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item">
        <a href="javascript:;">
          <img src="favicon.ico" class="layui-nav-img">
          {{ session('admin.user')->username }}
        </a>
        <dl class="layui-nav-child">
          <dd><a href="{{ route('login.logout') }}">安全退出</a></dd>
        </dl>
      </li>
      <li class="layui-nav-item"><a href="{{ route('login.logout') }}">退出</a></li>
    </ul>
  </div>
  

  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree"  lay-filter="test">
        <li class="layui-nav-item layui-nav-itemed">
          <a class="" href="javascript:;">文章管理</a>
          <dl class="layui-nav-child">
            <dd><a href="{{ route('art.index') }}"><i class="layui-icon layui-icon-file-b"></i>&emsp;文章列表</a></dd>
            <dd><a href="javascript:;"><i class="layui-icon layui-icon-upload-drag"></i>&emsp;文章回收</a></dd>
            <dd><a href="javascript:;"><i class="layui-icon layui-icon-dialogue"></i>&emsp;评论管理</a></dd>
          </dl>
        </li>
        <li class="layui-nav-item">
          <a href="javascript:;">会员管理</a>
          <dl class="layui-nav-child">
            <dd><a href="javascript:;">列表一</a></dd>
            <dd><a href="javascript:;">列表二</a></dd>
            <dd><a href="">超链接</a></dd>
          </dl>
        </li>
      </ul>
    </div>
  </div>
  
  <div class="layui-body">
    <!-- 内容主体区域 -->
    <div style="padding: 15px;">
      @yield('content')
    </div>
  </div>
</div>
<script src="{{ asset('layui/layui.js')}}"></script>
<script src="{{ asset('images/jquery.min.js')}}"></script>
<script>
//JavaScript代码区域
layui.use('element', function(){
  var element = layui.element;


});
@yield('layui')
</script>
@yield('script')
</body>
</html>