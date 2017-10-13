<!--页面主体部分-->
<div id="content" class="user_content">
    <div class="data_title">
        <div class="data_fl fl">
            <a href="/Admin/Customer/index" class="data_act">用户管理</a>
        </div>
    </div>
    <!--新建客户-->
    <div class="new_client" style="display: ;">
        <span>新建客户</span>
        <div class="new_client_cont">
            <form id="form" autocomplete="off">
            <label>
                <span>账户名</span>
                <input type="text" name="username" id="username" value="<?php echo isset($res['username'])?$res['username']:'';?>" placeholder="请填写账户名" />
            </label>
            <label>
                <span>客户名</span>
                <input type="text" name="realname" id="realname" value="<?php echo isset($res['realname'])?$res['realname']:'';?>" placeholder="请填写用户名" />
            </label>
            <label>
                <span>联系人</span>
                <input type="text" name="contact_name" id="contact_name" value="<?php echo isset($res['contact_name'])?$res['contact_name']:'';?>" placeholder="请填写联系人姓名" />
            </label>
            <label>
                <span>手机</span>
                <input type="text" name="mobile" id="mobile" value="<?php echo isset($res['mobile']) ? $res['mobile']:'';?>" placeholder="请填写手机号" />
            </label>
            <label>
                <span>QQ</span>
                <input type="text" name="qq" id="qq" value="<?php echo isset($res['qq'])?$res['qq']:'';?>" placeholder="请填写QQ" />
            </label>
            <label>
                <span>旺旺</span>
                <input type="text" name="wangwang" id="wangwang" value="<?php echo isset($res['wangwang'])?$res['wangwang']:'';?>" placeholder="请填写旺旺" autocomplete="new-password" />
            </label>
            <label>
                <span>负责人</span>
                <select name="admin_id" id="admin_id">
                    <?php
                    foreach($admin_info as $k=>$v) {
                        ?>
                        <option value="<?php echo $k ?>" <?php if(isset($res['admin_id'])){if( $k == $res['admin_id']) {echo ' selected="selected"';}} ?>><?php echo $v['realname'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </label>
            <label>
                <span>密码</span>
                <input type="password" name="password" id="password" value="<?php if(isset($res['password'])){ echo $res['password'];}?>" placeholder="请填写密码" autocomplete="new-password" />
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
            var url = '/Admin/Customer/edit';
            var mobiles = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
            if(id==null || id==undefined || id==''){
                url = '/Admin/Customer/add';
            }
            if($("#username").val()==''){
                layer.msg('账户名必填');
                return false;
            }
            if($("#realname").val()==''){
                layer.msg('用户名必填');
                return false;
            }
            if($("#contact_name").val()==''){
                layer.msg('联系人必填');
                return false;
            }
            if($("#mobile").val()=='' && $("#qq").val()=='' && $("#wangwang").val()==''){
                layer.msg('手机号,qq,旺旺必填一个');
                return false;
            }
            if($("#mobile").val()!=''){
                if($("#mobile").val().length !=11 || !mobiles.test($("#mobile").val())){
                    layer.msg('手机号格式不正确');
                    return false;
                }
            }
            var formData = $("#form").serializeArray();
            var succ_url = '/Admin/Customer/index';
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
        location.href='/Admin/Customer/index';
    }
</script>