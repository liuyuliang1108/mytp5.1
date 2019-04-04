<?php /*a:3:{s:89:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\blog_category\blog_category.html";i:1554373199;s:72:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_meta.html";i:1554373199;s:74:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_footer.html";i:1554373199;}*/ ?>
﻿
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
<link rel="stylesheet" href="http://tp5.1.com/static/admin/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css" type="text/css">
<title>博客分类</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 博客管理 <span class="c-gray en">&gt;</span> 博客分类 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<container>
<table class="table">

	<tr class="col-md-12">

		<td width="200" class="col-md-2"  >
			<ul id="bcTree" class="ztree" style="display: inline-block">

			</ul>

		</td>


		<td class="col-md-10"style="text-align: center"><iframe ID="cIframe" class="col-md-12" Name="cIframe" FRAMEBORDER=0 SCROLLING=AUTO width=100%  height=390px SRC="http://tp5.1.com/admin/blog_category/contentList"></iframe></td>

	</tr>
</table>
</container>

<


<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui.admin/js/H-ui.admin.js"></script>
<!--/_footer 作为公共模版分离出去-->



<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/zTree/v3/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript">
	var zTreeObj;


	var setting = {
		view : {
			showIcon : true,//是否显示图标
			dblClickExpand : true,//是否双击展开/收起
			selectedMulti : false,//是否允许同时选中多个节点
			expandSpeed : 'fast',//展开速度fast、slow
			showTitle : true, //是否显示节点的title提示信息
			showLine: true,//是否显示节点间的连线
			nameIsHTML : true //设置 name 属性是否支持 HTML 脚本
		},
		data : {
			keep : {
				parent : true
			},
			simpleData : {
				enable : true,
				idKey : "id",
				pIdKey : "pId"
			}
		},

		callback : {
			/* 鼠标单击事件，判断是否是导航栏，然后跳转到展示页面 */
			onClick : function(event, treeId, treeNode, clickFlag) {
				/*获取当前被选中的节点数据集合 返回json格式*/
				var nodes = zTreeObj.getSelectedNodes();
				//这里的逻辑是为修改和查看所预备的hidden
			$("#checkNodeId").val(treeNode.id);
				$("#checkNodePid").val(nodes[0].pId);
				var cId =treeNode.id;

				//默认进入查看页面
				content_list(0,cId);
			}
		}


	};

	/*初始化tree*/
	function initTree() {
		jQuery.ajax({
			url : "http://tp5.1.com/admin/blog_category/getTreeData",
			data : {},
			type : "POST",
			dataType : "json",
			cache : false,
			success : function(data) {
				data = eval(data);//ztree只认js对象，而不是字符串，所以eval转换下
				zTreeObj = $.fn.zTree.init($("#bcTree"), setting, data);
				$.fn.zTree.init($("#bcTree"), setting, data);
				var treeObj = $.fn.zTree.getZTreeObj("tree");
			}
		});
	}

	/* DOM元素被加载完成的情况下执行 */
	$(document).ready(function(){
		initTree();
	});


	//查看、编辑博客分类
	function content_list(flag,cId){

		var cId = cId;



		if(cId == ""){

			layer.alert("请选择一个博客分类");
			return;
		}

		//修改和完成所有的信息技术
		if(flag == '1' && $("#checkNodePid").val() == ""){

			layer.alert("根博客分类不能修改");
			return;
		}
		$("#cIframe").prop("src","http://tp5.1.com/admin/blog_category/contentList?cId=" + cId);
	}


	//删除博客分类
	function category_del(){

		var cId = $("#checkNodeId").val();

		if(cId == ""){
			layer.alert("请选择一个博客分类");
			return;
		}

		if($("#checkNodePid").val() == ""){
			layer.alert("根博客分类不能删除");
			return;
		}
		layer.alert(cId);

		layer.confirm("该操作将删除当前博客分类和所包含的子博客分类，您确定删除吗？",function(){
			$.ajax({
				url:"http://tp5.1.com/admin/blog_category/categoryDel",
				data : {"id":cId},
				type : "POST",
				dataType : "json",
				cache : false,
				success : function(data) {
					if(eval(data)){
						layer.msg('删除成功',{icon:1,time:1000},function(){
							window.location.reload();
						});
					}else{
						layer.alert('删除失败');
					}
				}
			});
		});
	}

</script>
</body>
</html>
