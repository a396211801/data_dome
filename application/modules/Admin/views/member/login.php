<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <style type="text/css">
        @charset "utf-8";

        html,body{
            margin:0;
            font-size:12px;
            font-family:"微软雅黑",arial;
        }
        h2{
            padding:0;
            margin:0;
        }
        a{
            text-decoration:none;
            outline:none;
            cursor: pointer;
        }
        input{
            font-family:"微软雅黑",arial;
        }
        i,em{
            font-style: normal;
        }
        body{
            min-width: 1080px;
        }
        #box{
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background: url(/img/bg.png) no-repeat;
            background-size: cover;
            min-height: 540px;
        }
        .login_cont{
            width: 340px;
            height: 470px;
            background: #e7edf0;
            border-radius: 5px;
            box-shadow: 0 0 10px 3px rgba(0,0,0,.2);
            margin: 0 auto;
            position: absolute;
            left: 50%;
            margin-left: -170px;
            top: 50%;
            margin-top: -235px;
            text-align: center;
        }
        .login_cont span,
        .login_cont a,
        .login_cont i{
            display: inline-block;
        }
        .login_cont span{
            width: 80px;
            height: 80px;
            background: url(/img/login_logo.png) no-repeat;
            background-size: cover;
            margin: 40px 0;
        }
        .login_cont h2{
            font-size: 20px;
            color: #444;
            margin-bottom: 25px;
            font-weight: normal;
        }
        .login_cont input{
            width: 213px;
            height: 40px;
            border: 1px solid #e8e8e8;
            font-size: 12px;
            color: #4c4c4d;
            padding-left: 15px;
            line-height: 40px;
            margin-bottom: 12px;
        }
        .login_cont a{
            width: 230px;
            height: 42px;
            background: #26adf0;
            text-align: center;
            line-height: 42px;
            color: #fff;
            border-radius: 50px;
            font-size: 12px;
            margin-bottom: 21px;
        }
        .login_cont i{
            color: #f10808;
        }
    </style>
</head>
<body>
<div id="box">
    <form action="/admin/member/login" method='post' id="form1" >
        <div class="login_cont">
            <span></span><br />
            <h2>数据营销平台后台管理</h2><br />
            <input type="text" name="username"  placeholder="用户名"/><br />
            <input type="password" name="password" placeholder="密码"/><br />
            <a href="javascript:;" onclick="login()">登录</a><br />
            <div style="display:none;" id="prompt"><i>用户名或者密码错误，请重新输入</i><br/></div>
        </div>
    </form>
</div>
</body>
</html>
<script src="/js/jquery1.9.0.min.js"></script>
<script src="/js/layer/layer.js"></script>
<script>
    $(function(){
        document.onkeydown = function(e){
            var ev = document.all ? window.event : e;
            if(ev.keyCode==13) {
                login();
            }
        }
    });
    
    function login(){
        var data = $("#form1").serialize();
        $.ajax({
            "url": "/admin/member/login",
            "data": data,
            "dataType": "json",
            type: 'post',
            "success": function (res) {
                if (res.ret == 1) {
                    layer.msg(res.msg);
                    window.location.href='/admin/plan/index';
                } else {
                    $("#prompt").find('i').html(res.msg);
                    $("#prompt").css('display','block');
                }
            }
        })
    }
</script>
