<!--页面主体部分-->
<div id="content" class="order_content">
    <div class="order_title">
        <div class="order_fl fl">
            <?php
                $index = false;
                $mobileadvert =false;
                $outbound =false;
                $data =false;
                $other =false;
                $input = false;
                $edit = false;
                foreach ($authority as $item){
                    if($item['controller']=='order'){
                        if($item['level']==1){
                            $index =true;
                            if(isset($item['operation'])&&in_array('inputData',$item['operation'])){
                                $input = true;
                            }
                            if(isset($item['operation'])&&in_array('editStatus',$item['operation'])){
                                $edit = true;
                            }
                        }
                        if($item['level']==2){
                            $mobileadvert =true;
                        }
                        if($item['level']==3){
                            $outbound =true;
                        }
                        if($item['level']==4){
                            $data =true;
                        }
                        if($item['level']==5){
                            $other =true;
                        }
                    }
                }
                ?>
            <?php if($index){?>
            <a href="/Admin/order/index" class="order_act">PC广告</a>
            <?php }?>
            <?php if($mobileadvert){?>
            <a href="/Admin/order/mobileadvert">移动广告</a>
            <?php }?>
            <?php if($outbound){?>
            <a href="/Admin/order/outbound">外呼</a>
            <?php }?>
            <?php if($data){?>
            <a href="/Admin/order/data">数据</a>
            <?php }?>
            <?php if($other){?>
            <a href="/Admin/order/other">其他</a>
            <?php }?>
        </div>
        <div class="order_rt fr">
            <form action="/admin/order/index" method="get" >
                <input type="text" name="name" placeholder="营销计划名称/订单名称" value="<?php echo fn_get_val('name')?>"/>
                <input type="submit" style="display: none;" id="search_right"/>
                <a href="javascript:;" class="order_find"></a>
            </form>
        </div>
    </div>
    <div class="order_cont">
        <form action="/admin/order/index" method="get" >
            <p class="order_cont_title">
<!--                <span>查询：</span>-->
<!--                <label for="start_date">-->
<!--                    <input type="text" id="start_date" name="start_date" value="--><?php //if(fn_get_val('start_date')){ echo fn_get_val('start_date');}else{ echo (date("Y-m-d"));} ?><!--"/>-->
<!--                </label>-->
<!--                <label for="end_date">-->
<!--                    <input type="text" id="end_date" name="end_date" value="--><?php //if(fn_get_val('end_date')){ echo fn_get_val('end_date');}else{ echo (date("Y-m-d"));} ?><!--"/>-->
<!--                </label>-->
<!--                <input type="submit" style="display: none;" id="search_left"/>-->
<!--                <a href="javascript:;" id="search_left_btn">查询</a>-->
            </p>
        </form>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th>创建日期</th>
                <th>客户名称</th>
                <th>计划名称</th>
                <th>订单名称</th>
                <th>目标展现量</th>
                <th>累积展现量</th>
                <th>累积点击量</th>
                <th>总点击率</th>
                <th>完成率</th>
                <th>状态</th>
                <th>操作</th>
            </tr>

            <?php
            if($list) {
                foreach($list as $k=>$v) {
            ?>
            <tr>
                <td><?php echo $v['create_date']; ?></td>
                <td><?php echo $v['realname']; ?></td>
                <td><?php echo $v['plan_name']; ?></td>
                <td><?php echo $v['order_name']; ?></td>
                <td><?php echo $v['target_cpm']; ?></td>
                <td><?php echo $v['sum_cpm']; ?></td>
                <td><?php echo $v['sum_click']; ?></td>
                <td><?php echo $v['click_rate']; ?></td>
                <td><?php echo $v['completion_rate']; ?></td>
                <?php
                    if($v['is_del'] == 1) {
                        echo '<td class="deleted">已删除</td>';
                    } else {
                        echo '<td class="normal">正常</td>';
                    }
                ?>

                <td>
                    <?php if($input){ ?>
                    <a href="javascript:openDataPop(<?php echo $v['id']; ?>);">录入数据</a>
                    <?php }?>
                    <?php if($edit){ ?>
                    <a href="javascript:changeState(<?php echo $v['id']?>);" class="add_state">状态</a>
                    <?php }?>
                </td>
            </tr>
            <?php
                }}
            ?>
        </table>
        <div class="order_num">
            <div class="order_all fl">
                <span class="order_All">共计</span>
                <span>展现量:<i><?php echo $all_pv;?></i></span>
                <span>点击量:<i><?php echo $all_click;?></i></span>
                <span>总点击率:<i><?php echo $all_click_rate;?></i></span>
            </div>
            <div class="order_page fr">
                <div class="data_page fr" id="page"></div>
            </div>
        </div>
    </div>
    <!--录入数据弹出窗-->
    <div class="base_pop add_money_win" style="display: none;">
        <div class="add_money_bg"></div>
        <div class="add_money_cont">
            <label>
                <span class="fl">数据日期</span>
                <input class="fr" type="text" name="data_day" id="data_day" value='<?php echo date("Y-m-d"); ?>'/>
            </label>
            <label>
                <span class="fl">展现量</span>
                <input class="fr" type="text" name="show_num" id="show_num"/>
            </label>
            <label>
                <span class="fl">点击量</span>
                <input class="fr" type="text" name="click_num" id="click_num"/>
            </label>
            <input type="hidden" name="order_id" id="order_id" />
            <input type="button" onclick="closeAllPop();" name="cancel" id="cancel" value="取消" />
            <input type="submit" name="subm" id="subm" value="提交" />
        </div>
    </div>
    <!--状态修改弹窗-->
    <div class="base_pop change_state" style="display: none;">
        <div class="change_state_bg"></div>
        <div class="change_state_cont">
            <form id="edit">
                <label>
                    <span class="fl">状态</span>
                    <select name="is_del" id="is_del">
                        <option value="0">正常</option>
                        <option value="1">已删除</option>
                    </select>
                </label>
                <input type="hidden" id="order_id" name="order_id" value=""/>
            </form>
            <input type="button" onclick="closeAllPop();" name="cancel" id="cancel" value="取消" />
            <input type="button" name="subm" id="edit_subm" value="提交" />
        </div>
    </div>
</div>

<script>
    //打开录入数据弹出窗
    function openDataPop(order_id){
        $("#order_id").val(order_id);
        $(".add_money_win").show();
    }

    //录入数据
    $(function(){
        $("#subm").click(function(){

            var data_day = $("#data_day").val();
            var show_num = $("#show_num").val();
            var click_num = $("#click_num").val();
            var order_id = $("#order_id").val();


            if(!order_id) {
                layer.msg('非法操作');
                return false;
            }

            if(!data_day) {
                layer.msg('请输入数据日期!');
                return false;
            }
            if(!show_num) {
                layer.msg('请输入展现量!');
                return false;
            }
            if(!click_num) {
                layer.msg('请输入点击量!');
                return false;
            }

            $.ajax({
                "url": "/admin/order/inputData",
                "data": {
                    "data_day" : data_day,
                    "show_num" : show_num,
                    "click_num" : click_num,
                    'order_id' : order_id,
                },
                "dataType": "json",
                type: 'post',
                "success": function (res) {
                    if (res.ret == 1) {
                        layer.msg(res.msg);
                        setTimeout(
                            "location.reload();"
                            , 1000 )
                        ;
                    } else {
                        layer.msg(res.msg);
                    }
                }
            })
        })
    })

    //关闭所有弹窗
    function closeAllPop(){
        $(".base_pop").hide();
    }

    //状态修改弹窗
    function changeState(id) {
        $.ajax({
            url: '/Admin/Order/editStatus',
            type: 'GET',
            dataType: 'json',
            data: {'order_id':id},
            success: function(d) {
                if(d.ret == 1) {// 成功
                    $("#is_del").val(d.data.is_del);
                    $("input[name='order_id']").val(d.data.id);
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

    $('#edit_subm').click(function () {
        var formData = $("#edit").serializeArray();
        var succ_url = '/Admin/Order/index';
        $.ajax({
            url: '/Admin/Order/editStatus',
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

//    var start_date = {
//        elem: '#start_date',
//        format: 'YYYY-MM-DD',
//        min: '2012-01-01', //设定最小日期为当前日期
//        //max: $("#end").val(), //最大日期
//        istime: true,
//        istoday: false,
//        choose: function(datas){
//            //end.min = datas; //开始日选好后，重置结束日的最小日期
//            //end.start = datas //将结束日的初始值设定为开始日
//        }
//    };
//    laydate(start_date);
//
//    var end_date = {
//        elem: '#end_date',
//        format: 'YYYY-MM-DD',
//        min: '2012-01-01', //设定最小日期为当前日期
//        //max: $("#end").val(), //最大日期
//        istime: true,
//        istoday: false,
//        choose: function(datas){
//            //end.min = datas; //开始日选好后，重置结束日的最小日期
//            //end.start = datas //将结束日的初始值设定为开始日
//        }
//    };
//    laydate(end_date);

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




    $("#search_left_btn").click(function(){
        $("#search_left").click();
    })
    $(".order_find").click(function(){
        $("#search_right").click();
    })


    laypage({
        cont: 'page',
        pages: <?php echo isset($count) ? $count :0;?>,
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

</script>
