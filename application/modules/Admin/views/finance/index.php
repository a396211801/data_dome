<div id="content" class="finance_content">
    <div class="finance_content_title">
        <div class="finance_title_fl fl">

            <?php
            $index = false;
            $detail =false;
            $add = false;
            $edit = false;
            foreach ($authority as $item){
                if($item['controller']=='finance'){
                    if($item['level']==1){
                        $index =true;
                        if(isset($item['operation'])&&in_array('addReceivables',$item['operation'])){
                            $add = true;
                        }
                        if(isset($item['operation'])&&in_array('editStatus',$item['operation'])){
                            $edit = true;
                        }
                    }
                    if($item['level']==2){
                        $detail =true;
                    }
                }
            }
            ?>
            <?php if($index){?>
            <a href="/Admin/Finance/index" class="finance_title_act">计划收款总表</a>
            <?php }?>
            <?php if($detail){?>
            <a href="/Admin/Finance/detail">计划收款明细</a>
            <?php }?>
        </div>
    </div>
    <!--计划收款总表-->
    <div class="all_table" >
        <form action="/Admin/Finance/index" method="get" >
            <p class="data_cont_title">
                <span>查询：</span>
                <label for="data_date">
                    <input type="text" id="start" name="start_date" id="start_date" value="<?php if(fn_get_val('start_date')){ echo fn_get_val('start_date');}else{ echo (date("Y-m-01",time()));} ?>"/>
                    <a href="javascript:;"></a>
                </label>
                <label for="data_date">
                    <input type="text" id="end" name="end_date" id="end_date" value="<?php if(fn_get_val('end_date')){ echo fn_get_val('end_date');}else{ echo date('Y-m-t',time());} ?>"/>
                    <a href="javascript:;"></a>
                </label>&nbsp;&nbsp;
                <input type="text" name="username" value="<?php if(fn_get_val('username')){ echo fn_get_val('username');}?>" placeholder="客户名/营销计划名"/>
                <select name="collection_status">
                    <option value="">收款状态</option>
                    <?php
                    foreach($collection_status as $k=>$v) {
                        ?>
                        <option value="<?php echo $k ?>" <?php if($k == fn_get_val('collection_status')) {echo ' selected="selected"';} ?>><?php echo $v ?></option>
                        <?php
                    }
                    ?>
                </select>
                <input type="submit" style="display: none;" id="search_left"/>
                <a href="javascript:;" id="search_left_btn">查询</a>
            </p>
        </form>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th>创建时间</th>
                <th>客户名称</th>
                <th>计划名称</th>
                <th>计划费用</th>
                <th>历史总收款</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <?php if (isset($list['list'])): foreach ($list['list'] as $value): ?>
                <tr>
                    <td><?php echo $value['date']?$value['date']:'-'; ?></td>
                    <td><?php echo $value['username']?$value['username']:'-'; ?></td>
                    <td><?php echo $value['name']?$value['name']:'-'; ?></td>
                    <td><?php echo $value['price']?$value['price']:0; ?></td>
                    <td><?php echo $value['money']?$value['money']:0; ?></td>
                    <td>
                        <?php if($value['collection_status'] == 1){?>
                            <a href="javascript:;" class="money_zero">待收款</a>
                        <?php } else if($value['collection_status'] == 2){?>
                            <a href="javascript:;" class="money_part">部分收款</a>
                        <?php } else{?>
                            <a href="javascript:;" class="money_all">完全收款</a>
                        <?php }?>
                    </td>
                    <td>
                        <?php if($add){?>
                        <a href="javascript:addMoneyWin(<?php echo $value['id']?>);" class="add_money">添加收款</a>
                    <?php }?>
                        <?php if($edit){?>
                        <a href="javascript:changeState(<?php echo $value['id']?>);" class="add_state">状态</a>
                    <?php }?>
                    </td>
                </tr>
            <?php endforeach;endif; ?>
        </table>
        <div class="finance_num">
            <div class="finance_page fr" id="page"></div>
        </div>
        <!--添加收款弹窗-->
        <div class="base_pop add_money_win" style="display: none;">
            <div class="add_money_bg"></div>
            <div class="add_money_cont">
                <form id="add">
                <label>
                    <span class="fl">数据日期</span>
                    <input class="fr" type="text" name="date" id="data_day" value="<?php echo date('Y-m-d',time());?>"/>
                </label>
                <label>
                    <span class="fl">收款金额</span>
                    <input class="fr" type="text" name="money" id="money"/>
                </label>
                <input type="hidden" id="pid" name="pid" value=""/>
                </form>
                <input type="button" onclick="closeAllPop();" name="cancel" id="cancel" value="取消" />
                <input type="button" name="subm" id="subm" value="提交" />
            </div>
        </div>
        <!--状态修改弹窗-->
        <div class="base_pop change_state" style="display: none;">
            <div class="change_state_bg"></div>
            <div class="change_state_cont">
                <form id="edit">
                <label>
                    <span class="fl">状态</span>
                    <select name="collection_status" id="collection_status">
                        <option value="0">请选择</option>
                        <option value="1">待收款</option>
                        <option value="2">部分收款</option>
                        <option value="3">完全收款</option>
                    </select>
                </label>
                    <input type="hidden" id="plan_id" name="plan_id" value=""/>
                </form>
                <input type="button" onclick="closeAllPop();" name="cancel" id="cancel" value="取消" />
                <input type="button" name="subm" id="edit_subm" value="提交" />
            </div>
        </div>
    </div>
</div>

<script>

    $("#search_left_btn").click(function(){
        $("#search_left").click();
    })
    $(".finance_find").click(function(){
        $("#search_right").click();
    })

    //添加收款弹窗
    function addMoneyWin(id) {
        $("input[name='pid']").val(id);
        $(".add_money_win").show();
    }
    //状态修改弹窗
    function changeState(id) {
        $.ajax({
            url: '/Admin/Finance/editStatus',
            type: 'GET',
            dataType: 'json',
            data: {'plan_id':id},
            success: function(d) {
                if(d.ret == 1) {// 成功
                    $("#collection_status").val(d.data.collection_status);
                    $("input[name='plan_id']").val(d.data.id);
                } else if(d.ret == 0){ //失败一定要 return false
                    layer.msg(d.msg);
                    return false;
                } else {
                    layer.msg(d.msg);
                    return false;
                }
            }
        })
        $(".change_state").show();
    }

    //关闭所有弹窗
    function closeAllPop(){
        $(".base_pop").hide();
    }

    //编辑计划收款明细
    function edit(){
        $(".edit_pop").show();
    }
    //删除计划收款明细
    function del(){
        $(".del_pop").show();
    }

    $(function () {
        $('#edit_subm').click(function () {
            var formData = $("#edit").serializeArray();
            var succ_url = '/Admin/Finance/index';
            $.ajax({
                url: '/Admin/Finance/editStatus',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function (d) {
                    if (d.ret == 1) {// 成功
                        layer.msg('修改成功！');
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

        $('#subm').click(function () {
            var formData = $("#add").serializeArray();
            var succ_url = '/Admin/Finance/index';
            $.ajax({
                url: '/Admin/Finance/addReceivables',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function (d) {
                    if (d.ret == 1) {// 成功
                        layer.msg('添加成功！');
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


        var start = {
            elem: '#start',
            format: 'YYYY-MM-DD',
            min: '2012-01-01', //设定最小日期为当前日期
            max: $("#end").val(),
            istime: true,
            istoday: false,
            choose: function(datas){
                end.min = datas; //开始日选好后，重置结束日的最小日期
                end.start = datas //将结束日的初始值设定为开始日
            }
        };
        var end = {
            elem: '#end',
            format: 'YYYY-MM-DD',
            min: $("#start").val(),
            max: '2099-06-16',
            istime: true,
            istoday: false,
            choose: function(datas){
                start.max = datas; //结束日选好后，重置开始日的最大日期
            }
        };
        laydate(start);
        laydate(end);


        var data_day = {
            elem: '#data_day',
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


        laydate(data_day);

        laypage({
            cont: 'page',
            pages: <?php echo isset($list['count']) ? $list['count'] :0;?>,
            skin: '#1E9FFF',
            curr: function () {
                var page = location.search.match(/p=(\d+)/);
                return page ? page[1] : 1;
            }(),
            jump: function (e, first) { //触发分页后的回调
                if (!first) { //一定要加此判断，否则初始时会无限刷新
                    var param = parseQueryString(location.href);
                    param.p = e.curr;
                    var ary = [];
                    for (var i in param) {
                        ary.push(i + "=" + param[i]);
                    }
                    location.href = "?" + ary.join("&");
                }
            }
        });
    })

</script>