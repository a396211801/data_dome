<!--页面主体部分-->
<div id="content" class="user_content">
    <div class="data_title">
        <div class="data_fl fl">

            <?php
            $index = false;
            $user =false;
            foreach ($authority as $item){
                if(strtolower($item['controller'])=='system'){
                    if($item['level']==1){
                        $index =true;
                    }
                    if($item['level']==2){
                        $user =true;
                    }
                }
            }
            ?>
            <?php if($index){?>
            <a href="/Admin/System/index">权限管理</a>
            <?php }?>
            <?php if($user){?>
            <a href="/Admin/System/user" class="data_act">用户管理</a>
            <?php } ?>
        </div>
    </div>
    <!--新建客户-->
    <div class="new_client" style="display: ;">
        <span>新建用户</span>
        <div class="new_client_cont">
            <form id="form">
                <label>
                    <span>账户名</span>
                    <input type="text" name="username" id="username" <?php if(isset($res['username'])){ echo 'disabled';}?> value="<?php echo isset($res['username'])?$res['username']:'';?>" placeholder="请填写账户名" />
                </label>
                <label>
                    <span>客户名</span>
                    <input type="text" name="realname" id="realname" value="<?php echo isset($res['realname'])?$res['realname']:'';?>" placeholder="请填写用户名" />
                </label>
                <label>
                    <span>手机</span>
                    <input type="text" name="mobile" id="mobile" value="<?php echo isset($res['mobile']) ? $res['mobile']:'';?>" placeholder="请填写手机号" />
                </label>
                <label>
                    <span>密码</span>
                    <input type="password" name="password" id="password" value="<?php if(isset($res['password'])){ echo $res['password'];}?>" placeholder="请填写密码" />
                </label>
                <label>
                    <span>所属权限</span>
                    <select name="position_id" id="position_id">
                        <?php
                        foreach($positions as $k=>$v) {
                            ?>
                            <option value="<?php echo $v['id'] ?>" <?php if(isset($res['position_id'])){if( $v['id'] == $res['position_id']) {echo ' selected="selected"';}} ?>><?php echo $v['name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </label>
                <p class="btn">
                    <input type="hidden" name="id" id="id" value="<?php if(isset($res['id'])){ echo $res['id'];}?>"/>

                    <input type="button" id="sure" class="sure_add" value="确认" />
                    <input type="button" onclick="canal();" id="cancel" value="取消" />
                </p>
            </form>
        </div>
    </div>
</div>
<script type="application/javascript">
    $(function () {
        $('.sure_add').click(function () {
            var id = $("#id").val();
            var url = '/Admin/System/edit';
            var mobiles = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
            if(id==null || id==undefined || id==''){
                url = '/Admin/System/adduser';
            }
            if($("#username").val()==''){
                layer.msg('账户名必填');
                return false;
            }
            if($("#realname").val()==''){
                layer.msg('用户名必填');
                return false;
            }
            if($("#mobile").val()==''){
                layer.msg('手机号必填');
                return false;
            }
            if($("#mobile").val().length !=11 || !mobiles.test($("#mobile").val())){
                layer.msg('手机号格式不正确');
                return false;
            }
            if($("#position_id").val()==''){
                layer.msg('所属权限必选');
                return false;
            }
            var formData = $("#form").serializeArray();
            var succ_url = '/Admin/System/user';
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function (d) {
                    if (d.ret == 1) {// 成功
                        layer.msg('操作成功！');
                        location.href = succ_url;
                    } else if (d.ret == 1) { //失败一定要 return false
                        layer.msg(d.msg);
                        return false;
                    } else {
                        layer.msg(d.msg);
                        return false;
                    }
                }
            })
        })
    })
    //取消新建
    function canal(){
        location.href='/Admin/System/user';
    }
</script>