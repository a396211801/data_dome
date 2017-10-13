<!--页面主体部分-->
<div id="content" class="finance_content">
    <div class="finance_content_title">
        <div class="finance_title_fl fl">
            <a href="/Finance/index" class="finance_title_act">计划收款总表</a>
            <a href="/Finance/detail">计划收款明细</a>
        </div>
        <div class="finance_title_rt fr">
            <form action="/Finance/index" method="get" >
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
                <input type="text" name="username" value="<?php if(fn_get_val('username')){ echo fn_get_val('username');}?>" placeholder="计划名称"/>
                <input type="submit" style="display: none;" id="search_right"/>
                <a href="javascript:;" class="finance_find"></a>
            </form>
        </div>
    </div>
    <!--计划收款总表-->
    <div class="all_table">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th>创建时间</th>
                <th>计划名称</th>
                <th>计划费用</th>
                <th>历史总收款</th>
                <th>状态</th>
            </tr>
            <?php if (isset($list['list'])): foreach ($list['list'] as $value): ?>
                <tr>
                    <td><?php echo $value['date']?$value['date']:'-'; ?></td>
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
                </tr>
            <?php endforeach;endif; ?>
        </table>
        <div class="data_page fr" id="page"></div>
    </div>
</div>

<script>
    $(function () {
            $("#search_left_btn").click(function(){
                $("#search_left").click();
            })
            $(".finance_find").click(function(){
                $("#search_right").click();
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