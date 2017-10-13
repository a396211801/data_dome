<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/css/laydate.css"/>
    <link rel="stylesheet" type="text/css" href="/skins/default/laydate.css"/>
    <script src="/js/jquery1.9.0.min.js"></script>
    <script src="/js/pages.js"></script>
    <script type="text/javascript" src="/js/layer/layer.js"></script>
    <script type="text/javascript" src="/js/laypage/1.3/laypage.js"></script>
    <script src="/js/laydate.js"></script>
</head>
<body>
<!--页面头部-->
<div id="header">
    <span class="logo"></span>
    <div class="title_list">
        <a href="/plan/index" <?php if($data=='plan'){ echo 'class="act"';} ?>>计划管理</a>
        <a href="/data/index" <?php if($data=='data'){ echo 'class="act"';} ?>>数据管理</a>
        <a href="/finance/index" <?php if($data=='finance'){ echo 'class="act"';} ?>>财务管理</a>
        <a href="/customer/info" <?php if($data=='customer'){ echo 'class="act"';} ?>>用户信息</a>
    </div>
    <div class="title_rt">
        <span class="date">日期：<i><?php echo date('Y-m-d',time()); ?></i></span>
        <span class="line">|</span>
        <span class="name"><i><?php echo isset($userInfo['realname'])&& $userInfo['realname']?$userInfo['realname']:'未知'; ?></i></span>
        <span class="line">|</span>
        <span style="cursor: pointer;" class="exit"><a style="color: #666;" href="/member/logout">退出</a></span>
    </div>
</div>

<?php echo $_content_; ?>
</body>
</html>
