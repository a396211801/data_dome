<!--页面主体部分-->
<div id="content" class="plan_content">
    <div class="plan_content_title clearfix">
        <div class="plan_state fl">
            <form action="/plan/index" method="get" >
                <span>查询：</span>
                <select name="status">
                    <option value="">计划状态</option>
                    <?php
                        foreach($plan_status as $k=>$v) {
                    ?>
                        <option value="<?php echo $k ?>" <?php if($k == $status) {echo ' selected="selected"';} ?>><?php echo $v ?></option>
                    <?php
                        }
                    ?>
                </select>
                <input type="submit" style="display: none;" id="search_left"/>
                <a href="javascript:;" id="search_left_btn">查询</a>
            </form>
        </div>
        <div class="plan_Find fr">
            <form action="/plan/index" method="get" >
                <input type="text" name="name" placeholder="计划名称" value="<?php echo isset($name) && $name ? $name : '' ?>"/>
                <input type="submit" style="display: none;" id="search_right"/>
                <a href="javascript:;" class="plan_find"></a>
            </form>
        </div>
    </div>
    <div class="plan_content_cont">
        <?php
            if(isset($list) && $list) {
                foreach ($list as $k=>$v) {
        ?>
        <div class="plan">
            <h4><?php echo $v['name']?></h4>
            <?php

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
            echo '<span class="plan_state1 '.$status_class.'">'.$status_name.'</span>';
            ?>
            <p class="plan_p1">
                <span>
                    <i>计划周期：</i>
                    <em>
                        <?php
                        if(isset($v['start_date']) && $v['start_date'] && isset($v['end_date']) && $v['end_date']) {
                            echo date("Y/m/d",$v['start_date'])." — ".date("Y/m/d",$v['end_date']);
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
                    <td><p><?php echo isset($m['explain']) && $m['explain']? $m['explain'] : '无详细说明'; ?></p></td>
                    <td><?php echo $m['target_cpm'] ?>cpm</td>
                    <td><a href="/Data/<?php echo $data_action[$m['type']] ?>?order_id=<?php echo $m['id'] ?>"><?php echo $m['completed'] ?></a></td>
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
        <?php
            }}
        ?>
        <div class="data_page fr" id="page"></div>
    </div>
</div>


<script>
    $(function(){
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
