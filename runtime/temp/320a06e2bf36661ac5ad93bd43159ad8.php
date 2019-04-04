<?php /*a:4:{s:88:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\blog_category\content_list.html";i:1554373199;s:79:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\base_content.html";i:1554373199;s:72:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_meta.html";i:1554373199;s:74:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_footer.html";i:1554373199;}*/ ?>


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
<link rel="stylesheet" href="http://tp5.1.com/static/admin/lib/layui/css/layui.css" media="all">
</head>
<body>








<div id="content-page"></div><!-- 被分页内容容器 -->


<div id="layer-page"></div> <!--分页容器-->

<div style="margin-left: 30px">
    <button class="btn btn-primary " onclick="add_content()" type="button">新增文章</button>
</div>





<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui.admin/js/H-ui.admin.js"></script>
<!--/_footer 作为公共模版分离出去-->





<script src="http://tp5.1.com/static/admin/lib/layui/layui.js" charset="utf-8"></script>
<script>
    jQuery.Huifold = function (obj, obj_c, speed, obj_type, Event) {
        if (obj_type == 2) {
            $(obj + ":first").find("b").html("-");
            $(obj_c + ":first").show()
        }
        $(obj).bind(Event, function () {
            if ($(this).next().is(":visible")) {
                if (obj_type == 2) {
                    return false
                } else {
                    $(this).next().slideUp(speed).end().removeClass("selected");
                    $(this).find("b").html("+")
                }
            } else {
                if (obj_type == 3) {
                    $(this).next().slideDown(speed).end().addClass("selected");
                    $(this).find("b").html("-")
                } else {
                    $(obj_c).slideUp(speed);
                    $(obj).removeClass("selected");
                    $(obj).find("b").html("+");
                    $(this).next().slideDown(speed).end().addClass("selected");
                    $(this).find("b").html("-")
                }
            }
        })
    }




                    //使用分页模块
                    layui.use('laypage', function () {
                        var laypage = layui.laypage;

                        //laypage初始化分页
                        Ajaxpage();

                        //ajax与后台传送数据，传入当前页码，及文章编号；
                        //传回当前页内容，将其渲染模板，并传回总页数等数据，执行一个laypage实例
                        function Ajaxpage(curr) {
                            $.getJSON("http://tp5.1.com/admin/blog_category/contentList", {
                                page: curr || 1,
                                cId: <?php echo htmlentities($child_id); ?>
                            }, function (data) {


                                //传回当前页内容，将其渲染模板
                                var data = data;

                                var html = '';
                                html = '<ul id="Huifold1" class="Huifold">';

                                data.forEach(function (value) {

                                    html += '<li class="item"  value=" ' + value['url'] + '">';
                                    html += '<h4>' + value['title'] + '<b>+</b></h4>';
                                    html += '<div class="info"> 摘要：<br>' + value['content'] + '</div>';
                                    html += '</li>';

                                });
                                html += '</ul>';


                                $("#content-page").html(html);

                                $.Huifold("#Huifold1 .item h4", "#Huifold1 .item .info", "fast", 3, "mouseover"); /*5个参数顺序不可打乱，分别是：相应区,隐藏显示的内容,速度,类型,事件*/

                                //执行一个laypage实例
                                laypage.render({
                                    elem: 'layer-page' //注意，这里的 test1 是 ID，不用加 # 号
                                    , count: '<?php echo htmlentities($count); ?>', //数据总数，从服务端得到
                                    limit: '<?php echo htmlentities($limits); ?>',//每页显示的数量，从服务端得到
                                    skip: true,//是否开启跳页
                                    skin: '#1AB5B7',//分页组件颜色
                                    curr: curr || 1,
                                    groups: 3,//连续显示分页数
                                    jump: function (obj, first) {
                                        if (!first) {
                                            Ajaxpage(obj.curr)
                                        }
                                        $('#content-page').append('<p style="text-align:center">' + '第' + obj.curr + '页，共' + obj.pages + '页' + '</p>');
                                    }
                                });

                            });

                        }

                    });




        //展示内容
        function contentShow(url) {
            var url = url;
            window.location.href = "http://tp5.1.com/admin/blog_category/contentShow" + url;
        }

        //动态给元素绑定事件
        $("#content-page").on('click', '.item', function () {
            contentShow($(this).attr("value"));
        })

        //添加博客内容
        function add_content() {
            var cId = <?php echo htmlentities($child_id); ?>;

            if (cId == "") {
                layer.alert("请选择一个博客分类");
                return;
            }
            window.location.href = "http://tp5.1.com/admin/blog_category/addContent?cId=" + cId;
        }

</script>


</body>
</html>

