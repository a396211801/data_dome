<!--页面主体部分-->
<div id="content" class="user_content">
    <div class="data_title">
        <div class="data_fl fl">
            <?php
            $index = false;
            $user = false;
            if($authority){
                foreach ($authority as $item){
                    if(strtolower($item['controller'])=='system'){
                        if($item['level']==1){
                            $index =true;
                        }
                        if($item['level']==2){
                            $user =true;
                        }
                    }
                }
            }
            ?>
            <?php if($index){?>
                <a href="/Admin/System/index"  class="data_act">权限管理</a>
            <?php }?>
            <?php if($user){?>
            <a href="/Admin/System/user" >用户管理</a>
            <?php }?>
        </div>
    </div>
    <div class="authority_cont">
        <div class="authority_cont1">
            <div class="cont_left fl">
                <h6>权限组</h6>
                <ul>
                    <?php if($position){foreach ($position as $item) {?>
                        <li  rel="<?php echo $item['id'] ?>" style="color:<?php if($item['id'] == $position_id) echo "#57a9fd"; ?>;">
                            <span class="click_task" ><?php echo $item['name'] ?>权限组</span>
                            <a href="javascript:modify(<?php echo $item['id'] ?>);" >修改</a>
                            <a href="javascript:del(<?php echo $item['id'] ?>);" >删除</a>
                            <input type="hidden" p_name="<?php echo $item['name'] ?>" name="position_id" id="position_<?php echo $item['id'] ?>" value="<?php echo $item['id'] ?>" >
                        </li>
                    <?php }}?>
                </ul>
                <input type="button" name="add_new" id="add_new" class="add_new" value="新建权限组" />
            </div>
            <!--权限名称-->
            <div class="cont_right org fr" >
                <div class="cont_right_titile">
                    <span class="span1">权限名称</span>
                    <span><?php
                        if($position){
                            foreach ($position as $item){
                                if($item['id']==$position_id){
                                    echo $item['name'].'权限组';
                                }
                            }
                        }
                        ?></span>
                </div>
                <div class="cont_right_cont" >
                    <?php if($positionList){
                        foreach ($positionList as $item){
                        ?>
                    <div class="cont_right_cont1">
                        <div class="guanli_box">
                            <div class="guanli_box_titile">
                                <span><?php echo $item['name'] ?></span>
                            </div>
                            <?php if($item['child']){
                            foreach ($item['child'] as $t){
                            ?>
                                <ul>
                                    <li>
                                        <span><?php echo $t['name'] ?></span>
                                        <div>
                                            <?php if($t['child']){
                                            foreach ($t['child'] as $k){
                                            ?>
                                            <i><?php echo $k['name'] ?></i>
                                            <?php }}?>
                                        </div>
                                    </li>
                                </ul>
                            <?php }}?>
                        </div>
                    </div>
                    <?php }}?>
                </div>
            </div>

            <!--权限修改-->
            <div class="cont_right edit fr"  style="display: none;">
                <div class="cont_right_titile">
                    <span class="span1">权限名称</span>
                    <input id="show_name" type="text" disabled="disabled" value="管理员权限组"/>
                    <a href="javascript:;" onclick="confirmData()">保存</a>
                    <input type="hidden" name="position_id" id="position_id" value="<?php echo $position_id ?>">
                </div>
                <div class="cont_right_cont">
                    <div class="cont_right_cont1">
                        <div class="guanli_box_titile">
                            <label>
                                        <span><input onclick="toggolAll(this);" type="checkbox"/>全选</span>
                            </label>
                        </div>
                        <?php if($navAll){
                        foreach ($navAll as $item){
                        ?>
                            <div class="guanli_box">
                                <div class="guanli_box_titile">
                                    <label>
                                        <span class="toggolGroup"><input  type="checkbox"
                                                <?php if(in_array($item['id'],$selected)){
                                                    echo 'checked="checked"';
                                                }?>
                                                      value="<?php echo $item['id']?>"/> <?php echo $item['name']?></span>
                                    </label>
                                </div>
                                <?php if($item['child']){
                                    foreach ($item['child'] as $l){
                                ?>
                                <ul>
                                    <li>
                                        <label>
                                            <span><input type="checkbox" <?php if(in_array($l['id'],$selected)){
                                                    echo 'checked="checked"';
                                                }?> value="<?php echo $l['id']?>"/><?php echo $l['name']; ?></span>
                                        </label>
                                        <div>
                                            <?php if($l['child']){
                                            foreach ($l['child'] as $t){
                                            ?>
                                            <label>
                                                <i><input type="checkbox"  <?php if(in_array($t['id'],$selected)){
                                                        echo 'checked="checked"';
                                                    }?> value="<?php echo $t['id']?>"/><?php echo $t['name']; ?></i>
                                            </label>
                                            <?php }}?>
                                        </div>
                                    </li>
                                </ul>
                                <?php }}?>
                            </div>
                        <?php }}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--新建权限组弹窗-->
<div class="base_pop add_new_group" style="display: none;">
    <div class="change_state_bg"></div>
    <div class="change_state_cont">
        <label>
            <span class="fl">权限组名称</span>
            <input type="text" name="name" id="name" class="fr" value=""/>
        </label>
        <input type="button" onclick="closeAllPop();" name="cancel" id="cancel" value="取消" />
        <input type="submit" name="subm" id="subm" value="提交" />
    </div>
</div>
<script>

    $(".click_task").bind('click',function(){
        var position_id = $(this).parent().attr('rel');
        location.href="/Admin/System/index?position_id="+position_id;
    })

    $(".add_new").bind('click',function(){
        $(".add_new_group").show();
    })


    //关闭所有弹窗
    function closeAllPop(){
        $(".base_pop").hide();
    }

    //提交新权限组名称
    $("#subm").on("click",function(){
        var name = $("#name").val();
        if(!name){
            layer.msg('权限名必填');
            return false;
        }
        $.ajax({
            "url": "/admin/System/addOper",
            "data": {
                "name" : name,
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

    function confirmData() {
        var task_id ="";
        var position_id =$("#position_id").val();
        $('input:checkbox[type=checkbox]:checked').each(function(i){
            if(0==i){
                task_id = $(this).val();
            }else{
                task_id += (","+$(this).val());
            }
        });
        $.ajax({
            "url": "/admin/System/editJurisdiction",
            "data": {
                "task_id" : task_id,
                'position_id' : position_id,
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
    }

    function toggolAll(e){
        if($(e).prop("checked")){
            // select all
            $("input[type='checkbox']").prop("checked", true);
        }else{
            // unselect all
            $("input[type='checkbox']").prop("checked", false);
        }
    }

    $(".toggolGroup input[type='checkbox']").click(function(){
        if($(this).prop("checked")){
            // select all
            $(this).prop("checked", true);
            if($(this).parent().parent().parent().next()){
                $(this).parent().parent().parent().nextAll().find("input[type='checkbox']").prop("checked", true);
            }
        }else{
            // unselect all
            $(this).prop("checked", false);
            if($(this).parent().parent().parent().next()){
                $(this).parent().parent().parent().nextAll().find("input[type='checkbox']").prop("checked", false);
            }
        }
    })


    $(".guanli_box li span input[type='checkbox']").click(function(){
        if($(this).prop("checked")){
            // select all
            $(this).prop("checked", true);
            if($(this).parent().parent().next()){
                $(this).parent().parent().next().find("input[type='checkbox']").prop("checked", true);
            }
        }else{
            // unselect all
            $(this).prop("checked", false);
            if($(this).parent().parent().next()){
                $(this).parent().parent().next().find("input[type='checkbox']").prop("checked", false);
            }
        }
    })




    function modify(id){
        var p_name = $("#position_"+id).attr('p_name');
        p_name = p_name+'权限组';
        $("#show_name").val(p_name);
        $("#position_id").val(id);
        $(".org").hide();
        $(".edit").show();
    }

    function del(position_id){
        layer.confirm("该权限在被用户使用，请解除用户与该权限的关系，再来删除该权限",function(index){
            layer.close(index)
            $.ajax({
                "url": "/admin/System/editJurisdiction",
                "data": {
                    'position_id' : position_id,
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
        },function(){
        })
    }


</script>