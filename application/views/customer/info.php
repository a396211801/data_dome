<!--页面主体部分-->
<div id="content" class="user_content">
    <form action="/customer/edit" method="post" id="form1" >
        <div class="user_cont">
            <h4>用户信息</h4>
            <input type="hidden" name="id" value="<?php echo $info['id'] ?>"/>
            <span><strong class="fl">账户名</strong><i class="fr"><?php echo $info['username'] ?></i></span>
            <span><strong class="fl">修改密码</strong>
                            <a href="javascript:;" onclick="$(this).hide().next().show();$('#repwd').show();" class="fr" >修改</a>
                            <input type="password" name='psw' class="user_name fr" value="" style="display:none;" />
                        </span>

            <span id="repwd" style="display: none;">
                <strong class="fl">确认密码</strong>
                <input type="password" name='password' class="user_name fr" value=""/>
            </span>
            <span><em class="fl">*</em><strong class="fl">用户名</strong><input type="text" name='realname' class="user_name fr" value="<?php echo $info['realname'] ?>" /></span>
            <span><em class="fl">*</em><strong class="fl">联系人</strong><input type="text" name='contact_name' class="user_peo fr" value="<?php echo $info['contact_name'] ?>" /></span>
            <span><em class="fl">*</em><strong class="fl">手机</strong><input type="text" name='mobile' class="user_mobile fr" value="<?php echo $info['mobile'] ?>" /></span>
            <span><em  class="fl">*</em><strong class="fl">QQ</strong><input type="text" name='qq' class="user_qq fr" value="<?php echo $info['qq'] ?>" /></span>
            <span><em  class="fl">*</em><strong class="fl">旺旺</strong><input type="text" name='wangwang'  class="user_ww fr" value="<?php echo $info['wangwang'] ?>" /></span>
            <div class="btn fr">
                <a href="javascript:;" class="sure" onclick="submit()">确认</a>
                <a href="javascript:;" class="call_off" onclick="back()">取消</a>
            </div>
        </div>
    </form>
</div>
<script>
    function submit()
    {
        var data = $('#form1').serialize();
        $.ajax({
            "url": "/customer/edit",
            "data": data,
            "dataType": "json",
            type: 'post',
            "success": function (res) {
                if (res.ret == 1) {
                    layer.open({
                        content: res.msg,
                        yes: function(){ location.href='';}
                    });
                } else {
                    layer.open({
                        content: res.msg,
                    });
                }
            }
        })
    }
    function back()
    {
        location.href='';
    }
</script>

