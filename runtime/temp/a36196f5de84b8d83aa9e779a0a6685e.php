<?php /*a:4:{s:72:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\user\login.html";i:1554214203;s:79:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\base_content.html";i:1553674992;s:72:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_meta.html";i:1553677538;s:74:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_footer.html";i:1553677291;}*/ ?>


<!--base.html是基础模板，可供其他模板继承-->


    <!DOCTYPE HTML>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <link rel="Bookmark" href="http://tp5.1.com/static/admin/favicon.ico" >
        <link rel="Shortcut Icon" href="http://tp5.1.com/static/admin/favicon.ico" />
        <!--[if lt IE 9]>
        <script type="text/javascript" src="http://tp5.1.com/static/admin/lib/html5shiv.js"></script>
        <script type="text/javascript" src="http://tp5.1.com/static/admin/lib/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="http://tp5.1.com/static/admin/static/h-ui/css/H-ui.min.css" />
        <link rel="stylesheet" type="text/css" href="http://tp5.1.com/static/admin/static/h-ui.admin/css/H-ui.admin.css" />
        <link rel="stylesheet" type="text/css" href="http://tp5.1.com/static/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />
        <link rel="stylesheet" type="text/css" href="http://tp5.1.com/static/admin/static/h-ui.admin/skin/default/skin.css" />
        <link rel="stylesheet" type="text/css" href="http://tp5.1.com/static/admin/static/h-ui.admin/css/style.css" />
        <!--[if IE 6]>
        <script type="text/javascript" src="http://tp5.1.com/static/admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
        <script>DD_belatedPNG.fix('*');</script>
        <![endif]-->


<title><?php echo htmlentities((isset($title) && ($title !== '')?$title:'页面标题')); ?></title>
<meta name="keywords" content="<?php echo htmlentities((isset($keywords) && ($keywords !== '')?$keywords:'页面关键字')); ?>">
<meta name="description" content="{description|default='页面描述'}">
<link href="http://tp5.1.com/static/admin/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css"/>
</head>
<body>






<input type="hidden" id="TenantId" name="TenantId" value=""/>
<div class="header"></div>
<div class="loginWraper">
    <div id="loginform" class="loginBox">
        <form class="form form-horizontal" action="index.html" method="post">
            <div class="row cl">
                <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
                <div class="formControls col-xs-8">
                    <input id="nameid" name="name" type="text" placeholder="账户" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
                <div class="formControls col-xs-8">
                    <input id="passwordid" name="password" type="password" placeholder="密码" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <input class="input-text size-L" name="verify" type="text" placeholder="验证码"
                           onblur="if(this.value==''){this.value='验证码:'}"
                           onclick="if(this.value=='验证码:'){this.value='';}" value="" style="width:100px;">
                    <img src="<?php echo captcha_src(); ?>" id="verify_img" alt="captcha"/>
                    <a id="kanbuq" href="javascript:refreshVerify();">看不清，换一张</a>
                </div>
                <!--<?php echo url('admin/user/verify'); ?>-->
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <label for="online">
                        <input type="checkbox" name="online" id="online" value="">
                        使我保持登录状态</label>
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <input name="" type="button" id="loginid" class="btn btn-success radius size-L"
                           value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
                </div>
            </div>
        </form>
    </div>
</div>
<div class="footer">Copyright 你的公司名称 by H-ui.admin v3.1</div>





<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui.admin/js/H-ui.admin.js"></script>
<!--/_footer 作为公共模版分离出去-->





<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui/js/H-ui.min.js"></script>
<!--此乃百度统计代码，请自行删除-->
<script>
    /**
     * ajax脚本
     */
    $(function () {
        //给登陆按钮添加点击事件
        $("#loginid").on('click', function () {

            $.ajax({
                type: 'POST',
                url: "<?php echo url('checkLogin'); ?>",
                data: $('form').serialize(),
                dataType: 'json',
                success: function (data) {
                    if(data.status==1){
                        alert(data.message);
                        window.location.href="<?php echo url('index/index/index'); ?>";
                    }else { //给出错误信息
                        alert(data.message);
                    }
                }

            });
        })

    })


</script>
<script>
    //刷新验证码函数
    function refreshVerify() {
        var ts = Date.parse(new Date()) / 1000;
        $('#verify_img').attr('src', "/captcha?id=" + ts);//刷新验证码
    }
</script>


</body>
</html>

