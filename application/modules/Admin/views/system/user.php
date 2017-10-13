<!--页面主体部分-->
<div id="content" class="user_content">
    <div class="data_title">
        <div class="data_fl fl">
            <?php
            $index = false;
            $user = false;
            $add = false;
            $edit = false;
            $frozen = false;
            if($authority){
                foreach ($authority as $item){
                    if(strtolower($item['controller'])=='system'){
                        if($item['level']==1){
                            $index =true;
                        }
                        if($item['level']==2){
                            $user =true;
                            if(isset($item['operation'])&&in_array('adduser',$item['operation'])){
                                $add = true;
                            }
                            if(isset($item['operation'])&&in_array('edit',$item['operation'])){
                                $edit = true;
                            }
                            if(isset($item['operation'])&&in_array('frozen',$item['operation'])){
                                $frozen = true;
                            }
                        }
                    }
                }
            }
            ?>
            <?php if($index){?>
                <a href="/Admin/System/index"  >权限管理</a>
            <?php }?>
            <?php if($user){?>
                <a href="/Admin/System/user" class="data_act">用户管理</a>
            <?php }?>
        </div>
    </div>
    <div class="user_content1" style="display: ;">
        <div class="user_title">
            <?php if($add){?>
            <a href="/Admin/System/adduser" class="add_user fl">新建用户</a>
            <?php }?>
        </div>
        <div class="user_cont">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th>账户名</th>
                    <th>用户名</th>
                    <th>手机</th>
                    <th>权限</th>
                    <th>操作</th>
                </tr>
                <?php if (isset($list['list'])): foreach ($list['list'] as $value): ?>
                    <tr id="<?php echo 'tr_' . $value['id'];?>">
                        <td><?php echo $value['username']?$value['username']:'-'; ?></td>
                        <td><?php echo $value['realname']?$value['realname']:'-'; ?></td>
                        <td><?php echo $value['mobile']?$value['mobile']:'-'; ?></td>
                        <td><?php echo $value['position']?$value['position']:'-'; ?></td>
                        <?php if($value['status'] == 1){?>
                            <td>
                                <?php if($edit){?>
                                <a href="/Admin/system/adduser?id=<?php echo $value['id'];?>" class="edit">编辑</a>
                            <?php }?>
                                <?php if($frozen){?>
                                <a href="javascript:frozen(<?php echo $value['id'];?>,2);" class="freeze">冻结</a>
                            <?php }?>
                            </td>
                        <?php }else{?>
                            <td>
                        <?php if($frozen){?>
                                <a href="javascript:frozen(<?php echo $value['id'];?>,1);" class="freezed">已冻结</a>
                        <?php }?>
                            </td>
                        <?php }?>
                    </tr>
                <?php endforeach;endif; ?>
            </table>
            <div class="User_page">
                <div class="user_page fr" id="page"></div>
            </div>
        </div>
    </div>
</div>


<script>

    //冻结
    function frozen(id,status){
        var info ='冻结';
        if(status == 1){
            info ='解冻';
        }
        var succ_url = '/Admin/System/user';
        layer.confirm("确定要"+info+"吗?",function(index){
            layer.close(index)
            $.ajax({
                url: '/Admin/System/frozen',
                type: 'POST',
                dataType: 'json',
                data: {'id':id,'status':status},
                success: function(d) {
                    if(d.ret == 1) {// 成功
                        layer.msg(d.msg);
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
    $(function () {
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
