<!--页面主体部分-->
<div id="content" class="finance_content">
    <div class="finance_content_title">
        <div class="finance_title_fl fl">
            <a href="/Finance/index" >计划收款总表</a>
            <a href="/Finance/detail" class="finance_title_act">计划收款明细</a>
        </div>
<!--        <div class="finance_title_rt fr">-->
<!--            <input type="text" placeholder="计划名称"/>-->
<!--            <a href="javascript:;" class="finance_find"></a>-->
<!--        </div>-->
    </div>

    <!--计划收款明细-->
    <div class="data_cont data_cont1">
        <form action="/Finance/detail" method="get" >
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
                <input type="text" name="username" value="<?php if(fn_get_val('username')){ echo fn_get_val('username');}?>" placeholder="计划名"/>
                <input type="submit" style="display: none;" id="search_left"/>
                <a href="javascript:;" id="search_left_btn">查询</a>
            </p>
        </form>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th>收款时间</th>
                <th>计划名称</th>
                <th>收款金额</th>
            </tr>
            <?php if (isset($list['list'])): foreach ($list['list'] as $value): ?>
                <tr>
                    <td><?php echo $value['date']?$value['date']:'-'; ?></td>
                    <td><?php echo $value['plan_name']?$value['plan_name']:'-'; ?></td>
                    <td><?php echo $value['money']?$value['money']:0; ?></td>
                </tr>
            <?php endforeach;endif; ?>
        </table>
        <div class="data_page fr" id="page"></div>
    </div>
</div>
<script>
    $(function () {
         var start = {
          elem: '#start',
          format: 'YYYY-MM-DD',
          min: '2012-01-01', //设定最小日期为当前日期
          max: $("#end").val(), //最大日期
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