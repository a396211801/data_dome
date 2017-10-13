<div id="content" class="plan_content" style="margin-bottom: 38px;">
    <div class="plan_content_title">
        <?php
        //权限按钮
        $index = false;
        $createplan =false;
        $editPlan =false;
        $delPlan =false;
        $changeStatus= false;
        $copy = false;
        foreach ($authority as $item){
            if($item['controller']=='plan'){
                if($item['level']==1){
                    $index =true;
                }
                if($item['operation']){
                    if(isset($item['operation'])&&in_array('createPlan',$item['operation'])){
                        $createplan = true;
                    }
                    if(isset($item['operation'])&&in_array('editPlan',$item['operation'])){
                        $editPlan = true;
                    }
                    if(isset($item['operation'])&&in_array('delPlan',$item['operation'])){
                        $delPlan = true;
                    }
                    if(isset($item['operation'])&&in_array('changeStatus',$item['operation'])){
                        $changeStatus = true;
                    }
                    if(isset($item['operation'])&&in_array('copy',$item['operation'])){
                        $copy = true;
                    }
                }
            }
        }
        ?>
        <?php if($index){?>
            <h4 class="fl">计划管理</h4>
        <?php }?>
        <div class="plan_Find fr">
            <form action="/admin/plan/index" method="get" >
                <input type="text" name="name" placeholder="计划名称" value="<?php echo fn_get_val('name')?>"/>
                <input type="submit" style="display: none;" id="search_right"/>
                <a href="javascript:;" class="plan_find"></a>
            </form>
        </div>
    </div>
    <div class="plan_content_cont">
        <div class="plan_content_cont_title">
            <div class="plan_state fl">
                <form action="/admin/plan/index" method="get" >
                    <span>查询：</span>
                    <select name="status">
                        <option value="">计划状态</option>
                        <?php
                        foreach($plan_status as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if($k == fn_get_val('status')) {echo ' selected="selected"';} ?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="submit" style="display: none;" id="search_left"/>
                    <a href="javascript:;" id="search_left_btn">查询</a>
                </form>
            </div>
            <?php if($createplan){?>
            <a href="/admin/plan/createplan" class="fr add_plan">创建营销计划</a>
            <?php }?>
        </div>
        <ul class="Plan_table">
            <li class="Plan_table_header">
                <ul>
                    <li>创建日期</li>
                    <li>客户名称</li>
                    <li>营销计划名称</li>
                    <li>计划金额</li>
                    <li>投放周期</li>
                    <li>状态</li>
                    <li>市场负责人</li>
                    <li>操作</li>
                </ul>
            </li>

            <?php
                if(isset($list) && $list) {
                    foreach ($list as $k=>$v) {
            ?>
            <li class="Plan_table_list">
                <ul class="clearfix">
                    <li><?php echo date("Y-m-d",$v['create_time']); ?></li>
                    <li><?php echo $v['user_realname']?></li>
                    <li>
                        <a href="javascript:;" class="plan_name"><?php echo $v['name']?></a>
                    </li>
                    <li><?php echo isset($v['price']) && $v['price'] > 0 ? $v['price'] : '不限'; ?></li>
                    <li>
                        <?php
                        if(isset($v['start_date']) && $v['start_date'] && isset($v['end_date']) && $v['end_date']) {
                            echo date("Y/m/d",$v['start_date'])." — ".date("Y/m/d",$v['end_date']);
                        } else {
                            echo "不限";
                        }
                        ?>
                    </li>

                    <?php
                    if($v['is_del'] == 1) {
                        $status_class = '';
                        $status_name = "已删除";
                    } else {
                        switch ($v['status']) {
                            case 1 :
                                $status_class = 'ing';
                                $status_name = $plan_status[$v['status']];
                                break;
                            case 2 :
                                $status_class = 'ing';
                                $status_name = $plan_status[$v['status']];
                                break;
                            case 3 :
                                $status_class = '';
                                $status_name = $plan_status[$v['status']];
                                break;
                            case 4 :
                                $status_class = '';
                                $status_name = $plan_status[$v['status']];
                                break;
                            default:
                                $status_class = '';
                                $status_name = "未知";
                                break;
                        }
                    }
                    echo '<li class="'.$status_class.'">'.$status_name.'</li>';
                    ?>

                    <li><?php echo $v['business']?></li>
                    <li>
                        <?php if($editPlan){?>
                        <a href="/admin/plan/editplan?plan_id=<?php echo $v['id']; ?>" class="edit">编辑</a>
                            <?php }?>
                        <?php if($delPlan){?>
                        <?php if($v['is_del'] != 1){?>
                            <a href="javascript:;" class="delete" data-id="<?php echo $v['id']; ?>">删除</a>
                                <?php }?>
                            <?php }?>
                        <?php if($copy){?>
                        <a href="/admin/plan/createplan?plan_id=<?php echo $v['id']; ?>" class="copy">复制</a>
                        <?php }?>
                        <?php if($changeStatus){?>
                        <a href="javascript:;" class="state" data-id="<?php echo $v['id']; ?>">状态</a>
                        <?php }?>
                    </li>
                </ul>
                <div class="Plan_details" >
                    <div class="plan_details">
                        <h4><?php echo $v['name']?></h4>
                        <?php
                        if($v['is_del'] == 1) {
                            $status_class = 'plan_finish';
                            $status_name = "已删除";
                        } else {
                            switch ($v['status']) {
                                case 1 :
                                    $status_class = 'plan_ing';
                                    $status_name = $plan_status[$v['status']];
                                    break;
                                case 2 :
                                    $status_class = 'plan_ing';
                                    $status_name = $plan_status[$v['status']];
                                    break;
                                case 3 :
                                    $status_class = 'plan_finish';
                                    $status_name = $plan_status[$v['status']];
                                    break;
                                case 4 :
                                    $status_class = 'plan_finish';
                                    $status_name = $plan_status[$v['status']];
                                    break;
                                default:
                                    $status_class = 'plan_finish';
                                    $status_name = "未知";
                                    break;
                            }
                        }
                        echo '<span class="plan_state1 '.$status_class.'">'.$status_name.'</span>';
                        ?>
                        <p class="plan_p1">
                            <span>
                                <i>计划周期：</i>
                                <em>
                                    <?php
                                    if(isset($v['start_date']) && $v['start_date'] && isset($v['end_date']) && $v['end_date']) {
                                        echo date("Y-m-d",$v['start_date'])." -- ".date("Y-m-d",$v['end_date']);
                                    } else {
                                        echo "不限";
                                    }
                                    ?>
                                </em>
                            </span>
                            <span>
                                <i>计划金额：</i>
                                <em><?php echo isset($v['price']) && $v['price'] > 0 ? $v['price'] : '不限'; ?></em>
                            </span>
                            <span>
                                <i>创建时间：</i>
                                <em><?php echo date("Y/m/d",$v['create_time']); ?></em>
                            </span>
                        </p>
                        <p class="plan_p2">
                            <span>
                                <i>投放说明：</i>
                                <em><?php echo isset($v['explain']) && $v['explain']? $v['explain'] : '无投放说明'; ?></em>
                            </span>
                        </p>
                        <?php
                            if(isset($v['order_list']) && $v['order_list']) {
                        ?>
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th>订单名称</th>
                                <th>推广资源</th>
                                <th>详细说明</th>
                                <th>目标量</th>
                                <th>已完成</th>
                                <th>完成率</th>
                            </tr>
                            <?php
                                foreach ($v['order_list'] as $n=>$m) {
                            ?>
                            <tr>
                                <td><?php echo $m['name'] ?></td>
                                <td><?php echo $order_type[$m['type']] ?></td>
                                <td><p><?php echo isset($m['explain']) && $m['explain'] ? $m['explain'] : '无详细说明'; ?></p></td>
                                <td><?php echo $m['target_cpm'] ?>cpm</td>
                                <td><a href="/admin/Data/<?php echo $data_action[$m['type']] ?>?order_id=<?php echo $m['id'] ?>"><?php echo $m['completed'] ?></a></td>
                                <td><?php echo $m['completed_probability'] ?></td>
                            </tr>
                            <?php
                                }
                            ?>
                        </table>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </li>

            <?php
                }}
            ?>
        </ul>
        <div class="Plan_page">
            <div class="data_page fr" id="page"></div>
        </div>
    </div>

    <!--状态修改弹窗-->
    <div class="base_pop change_state" style="display: none;">
        <div class="change_state_bg"></div>
        <div class="change_state_cont">
            <label>
                <span class="fl">状态</span>
                <select name="state" id="status">
                    <option value="">请选择</option>
                    <?php
                        foreach ($plan_status as $k=>$v){
                    ?>
                        <option value="<?php echo $k ?>"><?php echo $v ?></option>
                    <?php
                        }
                    ?>
                </select>
            </label>
            <input type="button" onclick="closeAllPop();" name="cancel" id="cancel" value="取消" />
            <input type="submit" name="subm" id="subm" value="提交" />
        </div>
    </div>
</div>
<script>
    var change_state_plan_id;

    //打开创建营销计划
    function openPlan(){
        $(".Add_plan").show()
    }

    //关闭所有弹窗
    function closeAllPop(){
        $(".base_pop").hide();
    }

    $(function(){

        //点击营销计划名称显示详情   plan_name
        $(".plan_name").on("click",function(){
            $(this).parent().parent().next().toggle();
        })

        //删除订单确认提示
        $(".delete").on("click",function(){
            var del_id = $(this).attr("data-id");
            layer.confirm("确定要删除该计划么？<br/>删除计划后，计划内的订单将全部删除，前台无法看到该计划及计划内订单",
                function(index){

                    $.ajax({
                        "url": "/admin/plan/delPlan",
                        "data": {
                            "plan_id":del_id
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
                    layer.close(index);
                },function(){
                    // do
                    //没有操作
                });
        })
        //关闭
        $(".state").on("click",function(){
            change_state_plan_id = $(this).attr("data-id");
            $(".change_state").show();
        })

        //修改状态
        $("#subm").on("click",function(){
            var status = $("#status").val();
            $.ajax({
                "url": "/admin/plan/changeStatus",
                "data": {
                    "plan_id" : change_state_plan_id,
                    'status' : status
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

        $("#search_left_btn").click(function(){
            $("#search_left").click();
        })
        $(".plan_find").click(function(){
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
    })
</script>
