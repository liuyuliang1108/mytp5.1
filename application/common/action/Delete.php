/*{$name}删除*/
public function {$littleController}Delete(Request $request)
{

//获取传入参数
$attr = $request->param();
$id = $attr['id'];
//使用模型update方法,返回model对象,软删除
$data= {$model}::destroy(['id' => $id]);
//使用删除事件同时将is_delete字段修改为1
return $data;
}