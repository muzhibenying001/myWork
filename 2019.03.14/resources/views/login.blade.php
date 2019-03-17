<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="css/app.css">
</head>
<body>
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
        @csrf
        <div class="form-item">
            <button id="submit">登 陆</button>
        </div>
    </div>
<script src="images/jquery.min.js"></script>
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
/*登陆函数*/
function submit(){
    /*执行表单数据验证*/
    if( yanzheng('username','用户名不能为空！') && yanzheng('password','密码不能为空！') ){
        let username = $('#username').val();
        let password = $('#password').val();
        let token = $('[name="_token"]').val();
        /*发送ajax*/
        $.ajax({
        'url'   : '{{url("login")}}',
        'type'  : 'post',
        'headers': {'X-CSRF-TOKEN': token},
        'data'  : 'username='+username+'&password='+password,
        'dataType'  : 'json',
        'timeout'   : '10000',
        'beforeSend': function(XHR){
            var index = layer.load(2, {time: 10*1000});
            $('#submit').attr('disabled',true);
            $('#submit').html('登陆中...');
        },
        'complete'  :function(){                
            layer.closeAll('loading');
            $('#submit').attr('disabled',false);
            $('#submit').html('登 陆');
        },
        'success'   : function(res){
            if( res.code != 200 ){
                layer.msg(res.msg, {icon: res.num,time:1500});
                return false;
            }

            var FUNC=[
                function() {
                    layer.msg('欢迎回来', {icon: 6,time:2000});
                    aniCB();
                },
                function() {
                    setInterval(function(){
                        $(location).attr('href', 'http://192.168.183.64:8090/');
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


// layer.msg('不开心。。', {icon: 5});

})
</script>
</body>
</html>