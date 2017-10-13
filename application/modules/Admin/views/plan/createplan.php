<div id="content" class="plan_content" style="margin-bottom: 38px;">
    <!--创建营销计划-->
    <div class="Add_plan" style="display: ;">
        <h6>创建营销计划</h6>
        <div class="add_plan_cont">
            <div id="order_wrap">
                <form id="base_form">
                    <div class="plan_information">
                        <span>基本信息</span>
                        <div class="plan_information_cont">
                            <p class="clearfix short">
                                <span class="fl"><em class="fl"><i>*</i>计划名称:</em>
                                    <input type="text" name="name" id="name" class="fr" value="<?php echo isset($plan['name']) && $plan['name'] ? $plan['name'].'副本' : '' ?>"/>
                                </span>
                                <span class="fr"><em class="fl"><i>*</i>计划金额:</em>
                                    <input type="text" name="price" id="price" class="fr" value="<?php echo isset($plan['price']) && $plan['price'] ? $plan['price'] : '' ?>"/>
                                    </span>
                            </p>
                            <p class="clearfix short">
                                <span class="fl"><em class="fl"><i>*</i>商务/市场:</em>
                                    <input type="text" name="business" id="business" class="fr" value="<?php echo isset($plan['business']) && $plan['business'] ? $plan['business'] : '' ?>"/>
                                </span>
                                <span class="fr" style="width: 324px;"><em class="fl"><i>*</i>计划周期:</em>
								<input type="text" name="start_date" class="" id="start_date" class="fr" value="<?php echo isset($plan['start_date']) && $plan['start_date'] ? date("Y-m-d",$plan['start_date']) : '' ?>" />
								<input type="text" name="end_date" class="" id="end_date" class="fr" value="<?php echo isset($plan['end_date']) && $plan['end_date'] ? date("Y-m-d",$plan['end_date']) : '' ?>" /></span>
                            </p>
                            <p class="long">
                                <span class="fl"><em><i>*</i>计划说明:</em></span>
                                <textarea name="explain" class="fr"><?php echo isset($plan['explain']) && $plan['explain'] ? $plan['explain'] : '' ?></textarea>
                            </p>
                        </div>
                    </div>
                </form>

                <?php
                if(isset($plan['order_list']) && $plan['order_list']) {
                    $num = 1;
                    foreach($plan['order_list'] as $key=>$value) {
                ?>
                <div class="Order_eg">
                    <div class="order_eg">
                        <span>订单<?php echo $num; ?></span>
                        <a href="javascript:;" class="delete_order">删除</a>
                        <p class="clearfix short">
					<span class="fl">
						<em class="fl"><i>*</i>资源类型:</em>
						<select class="fr" name="type">
                            <?php
                            foreach ($order_type as $k=>$v){
                                ?>
                                <option value="<?php echo $k ?>" <?php if($value['type'] == $k) { echo 'selected="selected"'; } ?>><?php echo $v ?></option>
                                <?php
                            }
                            ?>
                        </select>
					</span>
                        </p>
                        <p class="clearfix short">
                            <span class="fl"><em class="fl"><i>*</i>订单名称:</em>
                                <input type="text" name="name" class="fr" value="<?php echo isset($value['name']) && $value['name'] ? $value['name'].'副本' : '' ?>"/>
                                </span>
                            <span class="fr"><em class="fl"><i>*</i>目标cpm:</em>
                                <input type="text" name="target_cpm" class="fr" value="<?php echo isset($value['target_cpm']) && $value['target_cpm'] ? $value['target_cpm'] : '' ?>"/>
                            </span>
                        </p>
                        <p class="long">
                            <span class="fl"><em><i>*</i>订单说明:</em></span>
                            <textarea name="explain"  class="fr"><?php echo isset($value['explain']) && $value['explain'] ? $value['explain'] : '' ?></textarea>
                        </p>
                    </div>
                </div>
                <?php
                    $num++;}}
                ?>
            </div>
            <a href="javascript:;" class="add_order_btn">添加订单</a>
            <p class="Btn">
                <a href="javascript:;" class="keep_Btn">保存并分配</a>
                <a href="/admin/plan/index" class="return_Btn">返回</a>
            </p>
        </div>
    </div>
</div>

<!-- 添加订单模板 -->
<script id="tpl" type="text/html">
    <div class="Order_eg">
        <div class="order_eg">
            <span>订单{num}</span>
            <a href="javascript:;" class="delete_order">删除</a>
            <p class="clearfix short">
					<span class="fl">
						<em class="fl"><i>*</i>资源类型:</em>
						<select class="fr" name="type">
                            <?php
                            foreach ($order_type as $k=>$v){
                                ?>
                                <option value="<?php echo $k ?>"><?php echo $v ?></option>
                                <?php
                            }
                            ?>
                        </select>
					</span>
            </p>
            <p class="clearfix short">
                <span class="fl"><em class="fl"><i>*</i>订单名称:</em><input type="text" name="name" class="fr"/></span>
                <span class="fr"><em class="fl"><i>*</i>目标cpm:</em><input type="text" name="target_cpm" class="fr"/></span>
            </p>
            <p class="long">
                <span class="fl"><em><i>*</i>订单说明:</em></span>
                <textarea name="explain" class="fr"></textarea>
            </p>
        </div>
    </div>
</script>

<script>

    //打开创建营销计划
    function openPlan(){
        $(".Add_plan").show()
    }

    //关闭所有弹窗
    function closeAllPop(){
        $(".base_pop").hide();
    }

    var start_date = {
        elem: '#start_date',
        format: 'YYYY-MM-DD',
        min: '2012-01-01', //设定最小日期为当前日期
        //max: $("#end").val(), //最大日期
        istime: true,
        istoday: false,
        choose: function(datas){
            //end.min = datas; //开始日选好后，重置结束日的最小日期
            //end.start = datas //将结束日的初始值设定为开始日
        }
    };

    laydate(start_date);
    var end_date = {
        elem: '#end_date',
        format: 'YYYY-MM-DD',
        min: '2012-01-01', //设定最小日期为当前日期
        //max: $("#end").val(), //最大日期
        istime: true,
        istoday: false,
        choose: function(datas){
            //end.min = datas; //开始日选好后，重置结束日的最小日期
            //end.start = datas //将结束日的初始值设定为开始日
        }
    };
    laydate(end_date);

    // 返回表单参数
    function params(){
        var p = $("form").serializeArray();
        var data = {};
        for(var k in p){
            data[p[k].name] = p[k].value;
        }
        var arg = [];
        $(".Order_eg").each(function(){
            var obj = {
                type : $(this).find('select option:selected').val(),
                name : $(this).find('input[name=name]').val(),
                target_cpm: $(this).find('input[name=target_cpm]').val(),
                explain:$(this).find('textarea[name=explain]').val(),
            }
            arg.push(obj);
        })
        data['order_list']=arg;
        return data;
    }

    <?php
    if(isset($plan) && $plan) {
    ?>
    var num = <?php echo count($plan['order_list']); ?>;
    <?php
        } else {
    ?>
    var num = 1;
    <?php
    }
    ?>

    $(function(){
        <?php
            if(!isset($plan) || !$plan) {
        ?>
        var html = $("#tpl").html().replace(/{num}/,num);
        $('#order_wrap').append(html)
        <?php
            }
        ?>

        //删除待提交的订单
        $('body').on("click",".delete_order",function(){
            if($(".Order_eg").length == 1){
                layer.msg("至少保留一条订单!");
                return;
            }
            $(this).parent().parent().remove();
        })
        //添加订单
        $(".add_order_btn").on("click",function(){
            num++;
            var html = $("#tpl").html().replace(/{num}/,num);
            $('#order_wrap').append(html)
        })
        //点击营销计划名称显示详情   plan_name
        $(".plan_name").on("click",function(){
            $(this).parent().parent().next().toggle();
        })

        //关闭
        $(".state").on("click",function(){
            $(".change_state").show();
        })

        $(".keep_Btn").on("click",function(){
            var param = params();
            $.ajax({
                "url": "/admin/plan/createplan",
                "data": {
                    params:param
                },
                "dataType": "json",
                type: 'post',
                "success": function (res) {
                    if (res.ret == 1) {
                        window.location.href='/admin/plan/assignCustomer?plan_id='+res.data;
                    } else {
                        layer.msg(res.msg);
                    }
                }
            })
        })
    })
</script>
