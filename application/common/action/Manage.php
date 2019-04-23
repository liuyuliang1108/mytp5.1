{$littleController}小驼峰控制器名
{$name}控制器中文名
{$model}模型名
public function {$littleController}Manage()
{
// $this->isLogin();//判断用户是否登录
return $this->fetch('');
}

/**
* 异步加载树型结构数据
* 1、查询博客分类列表
* 2、封装节点成json格式数据
* */
public function getTreeData()
{   //方法：获得树数据
$nodeArr = {$model}::getzTreeData();

return json_encode($nodeArr);//以json格式输出

}