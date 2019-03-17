<!DOCTYPE HTML>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="css/app.css">
    <link rel="stylesheet" href="{{ asset('layui/css/layui.css')}}">
	<script>
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="css/fontawesome-all.css">
</head>

<body>
	<!-- bg effect -->
	<div id="bg">
		<canvas></canvas>
		<canvas></canvas>
		<canvas></canvas>
	</div>

	<div class="dowebok">
        <div class="logo"></div>
        <div class="form-item">
            <input id="username" type="text" autocomplete="off" placeholder="用户名">
            <p class="tip"></p>
        </div>
        <div class="form-item">
            <input id="password" type="password" autocomplete="off" placeholder="登录密码">
            <p class="tip"></p>
        </div>
        <div class="form-item">
            <input id="captcha" type="captcha" autocomplete="off" placeholder="验证码" style="width: 190px;padding-left: 30px;">
            <p class="tip"></p>
        	<input id="captcha_img" 
        	style="
        		width: 60px;
        		background-image: url({!! captcha_src() !!});
				cursor: pointer;
				border: 1px solid transparent;
				background-repeat: no-repeat;
				background-position: center 0;
        		background-size: cover;" 
        	readonly  onclick="this.style.backgroundImage='url({!! captcha_src() !!}&'+Math.random()+')'">
        </div>
        <div class="form-item">
            <button id="submit">登 陆</button>
        </div>
    </div>


<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/canva_moving_effect.js"></script>
<script src="js/layer/layer.js"></script>
<script>
$(function () {
/*登陆按钮点击事件*/
$('#submit').click(function(){
    submit();
});
/*页面按下回车事件*/
$(document).keyup(function(event){
    if(event.keyCode ==13){
        submit();
    }
});
/*username输入框失去焦点事件*/
$('#username').blur(function(){
    yanzheng('username','请输入用户名');
});
/*password输入框失去焦点事件*/
$('#password').blur(function(){
    yanzheng('password','请输入密码');
});
/*captcha输入框失去焦点事件*/
$('#captcha').blur(function(){
    yanzheng('captcha','请输入验证码');
});
/*登陆函数*/
function submit(){
    /*执行表单数据验证*/
    if( yanzheng('username','用户名不能为空！') && yanzheng('password','密码不能为空！') && yanzheng('captcha','验证码不能为空！') ){
        let data = { 
            'username': $('#username').val(),
            'password': $('#password').val(),
            'captcha' : $('#captcha').val()
        }
        /*发送ajax*/
        $.ajax({
        'url'   : '{{route("login.index")}}',
        'type'  : 'post',
        'headers'	: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data'  : data,
        'dataType'  : 'json',
        'timeout'   : '10000',
        'beforeSend': function(XHR){
            var index = layer.load(2, {time: 10*1000});
            $('#submit').attr('disabled',true);
            $('#submit').html('正在登陆...');
        },
        'complete'  :function(){                
            layer.closeAll('loading');
            $('#captcha_img').css({'backgroundImage':'url({!! captcha_src() !!}&'+Math.random()+')','display':'inline'});
        },
        'success'   : function(res){
            if( res.code != 200 ){
                layer.msg(res.msg, {icon: res.num,time:1500});
	            $('#submit').attr('disabled',false);
	            $('#submit').html('登 陆');
                return false;
            }

            var FUNC=[
            	function() {
		            $('#submit').attr('disabled',true);
		            $('#submit').html('正在登陆...');
                    aniCB();
                },
                function() {
                    layer.msg('欢迎回来', {icon: 6,time:2000});
                    aniCB();
                },
                function() {
                    setInterval(function(){
                        $(location).attr('href', '{{ route('art.index') }}');
                    },1000);
                }
            ];  
            var aniCB=function() {  
                $(document).dequeue("myAnimation");  
            }  
            $(document).queue("myAnimation",FUNC);  
            aniCB();

        },
        'error'     : function(XMLHttpRequest, textStatus, errorThrown){
            switch(textStatus){
                case 'timeout':
                    layer.msg('连接超时，请检查网络', {icon: 2,time:1500});
                    $('#submit').attr('disabled',false);
	            	$('#submit').html('登 陆');
                    break;
                case 'error':
                    layer.msg(textStatus, {icon: 2,time:1500});
                    $('#submit').attr('disabled',false);
	            	$('#submit').html('登 陆');
                    break;
                default:
                    layer.msg('出错啦，请联系管理员处理！', {icon: 2,time:2000});
                    $('#submit').attr('disabled',false);
	            	$('#submit').html('登 陆');
                    break;
            }
        }
    });
}

}

function yanzheng(doc,text){
    if( $('#'+doc).val().trim() == '' ){
        $('#'+doc).css('border','1px solid red');
        $('#'+doc).next().show().html(text);
        return false;
    }else{
        $('#'+doc).css('border','1px solid #fff');
        $('#'+doc).next().hide().html('');
        return true;
    }
}

})
</script>
</body>
</html>