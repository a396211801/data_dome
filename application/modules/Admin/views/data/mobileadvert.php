<!--页面主体部分-->


<div id="content" class="data_content">
    <div class="data_title">
        <div class="data_fl fl">
            <?php
            $index = false;
            $mobileadvert =false;
            $outbound =false;
            $data =false;
            $other =false;
            $edit =false;
            $del =false;
            foreach ($authority as $item){
                if($item['controller']=='data'){
                    if($item['level']==1){
                        $index =true;
                    }
                    if($item['level']==2){
                        $mobileadvert =true;
                        if(isset($item['operation'])&&in_array('editData',$item['operation'])){
                            $edit = true;
                        }
                        if(isset($item['operation'])&&in_array('delData',$item['operation'])){
                            $del = true;
                        }
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
                <a href="/Admin/data/index" >PC广告</a>
            <?php }?>
            <?php if($mobileadvert){?>
                <a href="/Admin/data/mobileadvert" class="data_act">移动广告</a>
            <?php }?>
            <?php if($outbound){?>
                <a href="/Admin/data/outbound">外呼</a>
            <?php }?>
            <?php if($data){?>
                <a href="/Admin/data/data" >数据</a>
            <?php }?>
            <?php if($other){?>
                <a href="/Admin/data/other">其他</a>
            <?php }?>
        </div>
        <!--        <div class="data_rt fr">-->
        <!--            <input type="text" placeholder="营销计划名称/订单名称"/>-->
        <!--            <a href="javascript:;" class="data_find"></a>-->
        <!--        </div>-->
    </div>
    <div class="order_cont">
        <form action="/Admin/Data/mobileadvert" method="get" >
            <p class="data_cont_title">
                <span>查询：</span>
                <label for="data_date">
                    <input type="text" id="start" name="start_date" id="start_date" value="<?php if(fn_get_val('start_date')){ echo fn_get_val('start_date');}else{ echo (date("Y-m-d",time()));} ?>"/>
                    <a href="javascript:;"></a>
                </label>
                <label for="data_date">
                    <input type="text" id="end" name="end_date" id="end_date" value="<?php if(fn_get_val('end_date')){ echo fn_get_val('end_date');}else{ echo date('Y-m-d',time());} ?>"/>
                    <a href="javascript:;"></a>
                </label>&nbsp;&nbsp;
                <input type="text" name="username" value="<?php if(fn_get_val('username')){ echo fn_get_val('username');}?>" placeholder="用户名/计划名/订单名"/>
                <input type="submit" style="display: none;" id="search_left"/>
                <a href="javascript:;" id="search_left_btn">查询</a>
            </p>
        </form>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th>日期</th>
                <th>客户名称</th>
                <th>计划名称</th>
                <th>订单名称</th>
                <th>展现量</th>
                <th>点击量</th>
                <th>点击率</th>
                <th>操作</th>
            </tr>
            <?php if (isset($list['list'])): foreach ($list['list'] as $value): ?>
                <tr id="<?php echo 'tr_' . $value['id'];?>">
                    <td><?php echo $value['date']?$value['date']:'-'; ?></td>
                    <td><?php echo $value['username']?$value['plan_name']:'-'; ?></td>
                    <td><?php echo $value['plan_name']?$value['plan_name']:'-'; ?></td>
                    <td><?php echo $value['order_name']?$value['order_name']:'-'; ?></td>
                    <td><?php echo $value['pv']?$value['pv']:0; ?></td>
                    <td><?php echo $value['click']?$value['click']:0; ?></td>
                    <td><?php echo $value['click_rate']?$value['click_rate']:'0.00%'; ?></td>
                    <td >
                        <?php if($edit){?>
                        <a href="javascript:editData(<?php echo $value['id'];?>,<?php echo $value['type'];?>);" class="revise">修改</a>
                    <?php }?>
                <?php if($del){?>
                        <a href="javascript:del(<?php echo $value['id'];?>);" class="delete">删除</a>
                    <?php }?>
                    </td>
                </tr>
            <?php endforeach;endif; ?>
        </table>
        <div class="order_num">
            <div class="order_all fl">
                <span class="order_All">共计</span>
                <span>展现量:<i><?php echo $list['sum_pv'] ? $list['sum_pv'] : 0?></i></span>
                <span>点击量:<i><?php echo $list['sum_click'] ? $list['sum_click'] : 0?></i></span>
                <span>总点击率:<i><?php echo $list['click_rate'] ? $list['click_rate'] : 0?></i></span>
            </div>
            <div class="order_page fr">
                <div class="data_page fr" id="page"></div>
            </div>
        </div>

        <!--修改数据-->
        <div class="base_pop edit_pop" style="display: none;">
            <div class="add_money_bg"></div>
            <div class="add_money_cont">
                <form id="edit">
                    <label>
                        <span class="fl">数据日期</span>
                        <input class="fr" type="text" name="" disabled id="edit_day" value=""/>
                    </label>
                    <label>
                        <span class="fl">展现量:</span>
                        <input class="fr" type="text" name="pv" id="pv"/>
                    </label>
                        <label>
                            <span class="fl">点击量</span>
                            <input class="fr" type="text" name="click" id="click"/>
                        </label>
                    <input type="hidden" name="id" id=data" value=""/>
                </form>
                <input type="button" onclick="closeAllPop();" name="cancel"  value="取消" />
                <input type="button" name="subm" id="subm" value="提交" />

            </div>
        </div>

    </div>
</div>
<script>
    //弹窗
    function editData(id,type){
        $.ajax({
            url: '/Admin/Data/editData',
            type: 'GET',
            dataType: 'json',
            data: {'id':id},
            success: function(d) {
                if(d.ret == 1) {// 成功
                    console.log(d);
                    console.log($("#data_id"));
                    $("#edit_day").val(d.data.date);
                    $("#pv").val(d.data.pv);
                    $("input[name='id']").val(d.data.id);
                    if(type ==1 || type ==2){
                        $("#click").val(d.data.click);
                    }
                } else if(d.ret == 0){ //失败一定要 return false
                    layer.msg(d.msg);
                    return false;
                } else {
                    layer.msg(d.msg);
                    return false;
                }
            }
        })
        $(".edit_pop").show();
    }
    //关闭所有弹窗
    function closeAllPop(){
        $(".base_pop").hide();
    }
    //删除
    function del(id){
        var succ_url = '/Admin/Data/mobileadvert';
        var start_date= getQueryString('start_date');
        var end_date= getQueryString('end_date');
        if(start_date== null || end_date== null){
            succ_url = succ_url;
        }else{
            succ_url = succ_url+'?start_date='+start_date+'&end_date='+end_date;
        }
        layer.confirm("确定要删除?",function(index){
            layer.close(index)
            $.ajax({
                url: '/Admin/Data/delData',
                type: 'POST',
                dataType: 'json',
                data: {'id':id},
                success: function(d) {
                    if(d.ret == 1) {// 成功
                        layer.msg('删除成功！');
                        location.href=succ_url;
                    } else if(d.ret == 0){ //失败一定要 return false
                        layer.msg(d.msg);
                        return false;
                    } else {
                        layer.msg(d.msg);
                        return false;
                    }
                }
            })
        },function(){
        })
    }
    //修改
    $(function () {
        $('#subm').click(function () {
            if(isNaN($("#pv").val())){
                layer.msg('必须为数字');
            }
            var formData=$("#edit").serializeArray();
            var succ_url = '/Admin/Data/mobileadvert';
            var start_date= getQueryString('start_date');
            var end_date= getQueryString('end_date');
            if(start_date== null || end_date== null){
                succ_url = succ_url;
            }else{
                succ_url = succ_url+'?start_date='+start_date+'&end_date='+end_date;
            }
            $.ajax({
                url: '/Admin/Data/editData',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(d) {
                    if(d.ret == 1) {// 成功
                        layer.msg('修改成功！');
                        location.href=succ_url;
                    } else if(d.ret == 1){ //失败一定要 return false
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

        var edit_day = {
            elem: '#edit_day',
            format: 'YYYY-MM-DD',
            min: $("#start").val(),
            max: '2099-06-16',
            istime: true,
            istoday: false,
            choose: function(datas){
                start.max = datas; //结束日选好后，重置开始日的最大日期
            }
        };
        laydate(edit_day);


        $("#search_left_btn").click(function(){
            $("#search_left").click();
        })

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
