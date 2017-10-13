<!--点击保存并分配跳转页面-->
<div id="content1" class="plan_content" style="margin-bottom: 40px;">
    <div class="plan_content_title">
        <h4 class="fl">计划管理</h4>
    </div>
    <div class="keep_plan">
        <h6>创建营销计划</h6>
        <div class="keep_plan_cont">
            <p class="p1">
                <i>*</i><span>请输入客户账号:</span>
                <input type="text" id="name" value="<?php echo isset($realname) && $realname ? $realname : '' ?>"/>
                <input type="button" name="yan_btn" id="yan_btn" value="验证" />
            </p>
            <p class="p2 succeed">
                <i style="display: none;" id="notice">验证通过</i>
            </p>
            <p class="p3">
                <input type="button" name="pre_btn" id="pre_btn" value="上一步" />
                <input type="button" name="achieve" id="achieve" value="完成" />
            </p>
        </div>
        <input type="hidden" value="<?php echo $plan_id ?>" id="plan_id" />
    </div>
</div>
<script>

    $(function(){


        //上一步
        $("#pre_btn").click(function(){

            var plan_id = $("#plan_id").val();

            window.location.href='/admin/plan/editPlan?plan_id='+plan_id;
        })

        //验证客户
        $("#yan_btn").click(function(){
            var name = $("#name").val();
            $.ajax({
                "url": "/admin/plan/checkCustomer",
                "data": {
                    name:name
                },
                "dataType": "json",
                type: 'post',
                "success": function (res) {
                    if (res.ret == 1) {
                        $("#notice").text('验证通过');
                        $("#notice").parent().addClass("succeed");
                        $("#notice").show();
                    } else {
                        $("#notice").text('验证失败，请重新输入');
                        $("#notice").parent().removeClass("succeed");
                        $("#notice").show();
                    }
                }
            })
        })

        //分配客户
        $("#achieve").click(function(){
            var plan_id = $("#plan_id").val();
            var name = $("#name").val();

            if(!name) {
                layer.msg('请输入客户名称');
                return false;
            }

            $.ajax({
                "url": "/admin/plan/assignCustomer",
                "data": {
                    plan_id : plan_id,
                    name : name
                },
                "dataType": "json",
                type: 'post',
                "success": function (res) {
                    if (res.ret == 1) {
                        layer.msg(res.msg);
                        setTimeout(
                            "window.location.href='/admin/plan/index'"
                            , 1000)
                        ;
                    } else if (res.ret == 2) {
                        $("#notice").text('验证失败，请重新输入');
                        $("#notice").parent().removeClass("succeed");
                        $("#notice").show();
                    } else {
                        layer.msg(res.msg);
                    }
                }
            })
        })
    })
</script>
