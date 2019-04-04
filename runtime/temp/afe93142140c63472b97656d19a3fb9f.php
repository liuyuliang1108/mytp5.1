<?php /*a:4:{s:77:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\category\select.html";i:1554373199;s:79:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\base_content.html";i:1554373199;s:72:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_meta.html";i:1554373199;s:74:"C:\phpStudy\PHPTutorial\WWW\tp5.1\application\admin\view\base\_footer.html";i:1554373199;}*/ ?>


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
<link href="http://tp5.1.com/admin/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css"/>
</head>
<body>






<div class="page-container">
    <form action="http://tp5.1.com/admin/category/addSave"  method="post" class="form form-horizontal" id="form-category-update">


        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>分类名称name：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['name']); ?>" placeholder="" id="" name="name">
            </div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">所在分类：parent_id：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['parent_id']); ?>" placeholder="" id="" name="parent_id">
            </div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">所在分类中排序值order：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['order']); ?>" placeholder="" id="" name="order">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">分类描述remark：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <textarea name="remark" cols="" rows="" class="textarea" placeholder="<?php echo htmlentities($data['remark']); ?>" datatype="*10-100"
                          dragonfly="true" nullmsg="备注不能为空！" onKeyUp="$.Huitextarealength(this,200)"></textarea>
                <p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>模块名称module：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['module']); ?>" placeholder="" id="" name="module">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>控制器名称名称controller：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['controller']); ?>" placeholder="" id="" name="controller">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>对应模型名称model：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['model']); ?>" placeholder="" id="" name="model">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>方法名称action：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['action']); ?>" placeholder="" id="" name="action">
            </div>
        </div>


        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>视图模板名称view：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['view']); ?>" placeholder="" id="" name="view">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>URL地址：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['url']); ?>" placeholder="" id="" name="url">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>显示状态status：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['status']); ?>" placeholder="" id="" name="status">
            </div>
        </div>

        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>postman地址：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="<?php echo htmlentities($data['postman']); ?>" placeholder="" id="" name="postman">
            </div>
        </div>

        <input type="hidden"  value="" placeholder="" id="input-edit" name="edit">

        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button  type="button" class="btn btn-success" id=""  onclick="submit_update(1)"><i
                        class="icon-save"></i> 修改基本信息
                </button>
                <button type="button"  class="btn btn-success" id=""  onclick="submit_update(0)"><i
                        class="icon-save"></i> 修改节点
                </button>
                <button onClick="layer_close();" class="btn btn-default radius" type="button">
                    &nbsp;&nbsp;取消&nbsp;&nbsp;
                </button>
            </div>
        </div>
    </form>
</div>







<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="http://tp5.1.com/static/admin/static/h-ui.admin/js/H-ui.admin.js"></script>
<!--/_footer 作为公共模版分离出去-->





<!--请在下方写此页面业务相关的脚本-->

<script type="text/javascript">

    //Ajax请求控制器中的方法完成数据的增加
    function submit_update($key) {
        if ($key){
            $('#input-edit').val(1);
        } else {
            $('#input-edit').val(2);
        }

        var formData = $("#form-category-update").serialize();
        $.ajax({
            url: 'http://tp5.1.com/admin/category/addSave',
            data: formData,
            type: 'post',
            success: function (data) {

                var data = JSON.parse(data);


                if (data.flag == -1) {
                    layer.msg('修改失败!', {icon: 2, time: 1000});
                    return;
                } else {
                    layer.msg('修改成功!', {icon: 1, time: 1000});
                }
            }

        });
    }

</script>


</body>
</html>

