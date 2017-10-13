<!--页面主体部分-->
<div id="content" class="finance_content">
    <div class="finance_content_title">
        <div class="finance_title_fl fl">
            <?php
            $index = false;
            $detail =false;
            $edit =  false;
            $del= false;
            foreach ($authority as $item){
                if($item['controller']=='finance'){
                    if($item['level']==1){
                        $index =true;
                    }
                    if($item['level']==2){
                        $detail =true;
                        if(isset($item['operation'])&&in_array('editDetail',$item['operation'])){
                            $edit = true;
                        }
                        if(isset($item['operation'])&&in_array('delRecord',$item['operation'])){
                            $del = true;
                        }
                    }
                }
            }
            ?>
            <?php if($index){?>
                <a href="/Admin/Finance/index" >计划收款总表</a>
            <?php }?>
            <?php if($detail){?>
                <a href="/Admin/Finance/detail" class="finance_title_act" >计划收款明细</a>
            <?php }?>
        </div>
    </div>

    <!--计划收款明细-->
    <div class="detail_table">
        <form action="/Admin/Finance/detail" method="get" >
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
                <input type="submit" style="display: none;" id="search_left"/>
                <a href="javascript:;" id="search_left_btn">查询</a>
            </p>
        </form>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th>收款时间</th>
                <th>客户名称</th>
                <th>计划名称</th>
                <th>收款金额</th>
                <th>操作</th>
            </tr>
            <?php if (isset($list['list'])): foreach ($list['list'] as $value): ?>
                <tr id="<?php echo 'tr_' . $value['id'];?>">
                    <td><?php echo $value['date']?$value['date']:'-'; ?></td>
                    <td><?php echo $value['username']?$value['username']:'-'; ?></td>
                    <td><?php echo $value['plan_name']?$value['plan_name']:'-'; ?></td>
                    <td><?php echo $value['money']?$value['money']:0; ?></td>
                    <td>
                        <?php if($edit){?>
                        <a href="javascript:edit(<?php echo $value['id'];?>);" class="edit">编辑</a>
                    <?php }?>
                        <?php if($del){?>
                        <a href="javascript:del(<?php echo $value['id'];?>);" class="delete">删除</a>
                    <?php }?>
                    </td>
                </tr>
            <?php endforeach;endif; ?>
        </table>
        <div class="finance_num">
            <div class="finance_page fr" id="page"></div>
        </div>
        <!--编辑计划收款明细-->
        <div class="base_pop edit_pop" style="display: none;">
            <div class="add_money_bg"></div>
            <div class="add_money_cont">
                <form id="edit">
                <label>
                    <span class="fl">数据日期</span>
                    <input class="fr" type="text" name="" id="edit_day" disabled/>
                </label>
                <label>
                    <span class="fl">收款金额</span>
                    <input class="fr" type="text" name="money" id="money"/>
                </label>
                <input type="hidden" id="id" name="id" value=""/>
                </form>
                <input type="button" onclick="closeAllPop();" name="cancel" id="cancel" value="取消" />
                <input type="button" name="subm" id="subm" value="提交" />
            </div>
        </div>

    </div>
</div>

<script>
    //编辑计划收款明细
    function edit(id){
        $.ajax({
            url: '/Admin/Finance/editDetail',
            type: 'GET',
            dataType: 'json',
            data: {'id':id},
            success: function(d) {
                if(d.ret == 1) {// 成功
                    $("#edit_day").val(d.data.date);
                    $("#money").val(d.data.money);
                    $("input[name='id']").val(id);
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
        layer.confirm("确定要删除?",function(index){
            layer.close(index)
            $.ajax({
                url: '/Admin/Finance/delRecord',
                type: 'POST',
                dataType: 'json',
                data: {'id':id},
                success: function(d) {
                    if(d.ret == 1) {// 成功
                        layer.msg('删除成功！');
                        $("#tr_"+id).remove();
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
    $(function () {

        $('#subm').click(function () {
            var formData = $("#edit").serializeArray();
            var succ_url = '/Admin/Finance/detail';
            $.ajax({
                url: '/Admin/Finance/editDetail',
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
            min: '2012-01-01', //设定最小日期为当前日期
            //max: $("#end").val(), //最大日期
            istime: true,
            istoday: false,
            choose: function(datas){
                //end.min = datas; //开始日选好后，重置结束日的最小日期
                //end.start = datas //将结束日的初始值设定为开始日
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