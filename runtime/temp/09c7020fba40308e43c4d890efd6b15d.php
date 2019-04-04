<?php /*a:4:{s:76:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\category\admin.html";i:1554373199;s:79:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\base_content.html";i:1554373199;s:72:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_meta.html";i:1554373199;s:74:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_footer.html";i:1554373199;}*/ ?>


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
<link rel="stylesheet" href="http://tp5.1.com/static/admin/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css" type="text/css">
</head>
<body>






<div class="pos-a"
     style="width:200px;left:0;top:0; bottom:0; height:100%; border-right:1px solid #e5e5e5; background-color:#f5f5f5; overflow:auto;">
    <ul id="bcTree" class="ztree"></ul>
    <div class="row cl" style="text-align: center">
        <button class="btn btn-primary " onclick="category_add()" type="button">新增</button>
        <button class="btn btn-danger " onclick="category_del()" type="button">删除</button>
    </div>
</div>


<div style="margin-left:200px;">

    <!--文字导航-->
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 分类管理 <span
            class="c-gray en">&gt;</span> 分类列表 <a class="btn btn-success radius r"
                                                  style="line-height:1.6em;margin-top:3px"
                                                  href="javascript:location.replace(location.href);" title="刷新"><i
            class="Hui-iconfont">&#xe68f;</i></a></nav>
    <!--文字导航-->

    <iframe ID="cIframe" class="col-md-10" Name="cIframe" FRAMEBORDER=0 SCROLLING=AUTO width=100% height=600px
            SRC="http://tp5.1.com/admin/category/select"></iframe>

</div>





<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui.admin/js/H-ui.admin.js"></script>
<!--/_footer 作为公共模版分离出去-->





<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/zTree/v3/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript">

    var zTreeObj;


    var setting = {
        view: {
            showIcon: true,//是否显示图标
            dblClickExpand: false,//是否双击展开/收起
            selectedMulti: false,//是否允许同时选中多个节点
            expandSpeed: 'fast',//展开速度fast、slow
            showTitle: true, //是否显示节点的title提示信息
            showLine: true,//是否显示节点间的连线
            nameIsHTML: true //设置 name 属性是否支持 HTML 脚本
        },
        data: {
            keep: {
                parent: true
            },
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "pId"
            },


        },

        check: {
            enable: true,
            chkStyle: "radio",
            radioType: "all",
            chkboxType: {"Y": "", "N": ""},
        },

        callback: {
            /* 鼠标单击事件，判断是否是导航栏，然后跳转到展示页面 */
            onClick: function (event, treeId, treeNode, clickFlag) {
                /*获取当前被选中的节点数据集合 返回json格式*/
                var nodes = zTreeObj.getSelectedNodes();
                //这里的逻辑是为修改和查看所预备的hidden
                $("#checkNodeId").val(treeNode.id);
                $("#checkNodePid").val(nodes[0].pId);
                var cId = treeNode.id;
                var pId = treeNode.pId;
                //默认进入查看页面
                category_select(0, cId);
            }
        }


    };

    /*初始化tree*/
    function initTree() {
        jQuery.ajax({
            url: "http://tp5.1.com/admin/category/getTreeData",
            data: {},
            type: "POST",
            dataType: "json",
            cache: false,
            success: function (data) {
                data = eval(data);//ztree只认js对象，而不是字符串，所以eval转换下
                zTreeObj = $.fn.zTree.init($("#bcTree"), setting, data);
                $.fn.zTree.init($("#bcTree"), setting, data);
                var treeObj = $.fn.zTree.getZTreeObj("tree");
            }
        });
    }

    /* DOM元素被加载完成的情况下执行 */
    $(document).ready(function () {
        initTree();
    });


    //查看、编辑分类
    function category_select(flag, cId) {


        var cId = cId;


        if (cId == "") {

            layer.alert("请选择一个博客分类");
            return;
        }

        //修改和完成所有的信息技术
        if (flag == '1' && $("#checkNodePid").val() == "") {

            layer.alert("根博客分类不能修改");
            return;
        }

        $("#cIframe").prop("src", "http://tp5.1.com/admin/category/select?cId=" + cId + "&flag=" + flag);
    }


    //新增分类
    function category_add() {

        var treeObj = $.fn.zTree.getZTreeObj("bcTree");
        nodes = treeObj.getCheckedNodes(true);


        var cId = nodes[0].id;

        var flag = 1;


        if (cId == "") {

            layer.alert("请选择一个分类");
            return;
        }


        $("#cIframe").prop("src", "http://tp5.1.com/admin/category/select?cId=" + cId + "&flag=" + flag);
    }


    //Ajax请求控制器中的方法完成数据的删除
    function category_del() {
        var treeObj = $.fn.zTree.getZTreeObj("bcTree");
        nodes = treeObj.getCheckedNodes(true);

        var id = nodes[0].id;

        var tId = nodes[0].tId;

        $.ajax({
            url: 'http://tp5.1.com/admin/category/delete',
            data: {"id": id},
            type: 'get',
            success: function (data) {

                var data = JSON.parse(data);

                if (data.flag == 1) {
                    //如果php处理成功，调用此方法
                    layer.msg('删除成功!', {icon: 1, time: 1000});
                    $("#bcTree" + tId.substring(6)).remove();      //把这个id的数据在html中自动删除

                } else {
                    layer.msg('删除失败!', {icon: 1, time: 1000});
                }
            }

        });

    }

</script>


</body>
</html>

