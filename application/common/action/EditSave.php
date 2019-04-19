/*{$name}编辑保存*/
public function {$littleController}EditSave(Request $request)
{
//获取传入参数
$attr = $request->param();
//将字符串转为索引数组
$attr['file_name'] = explode ( ',', $attr['file_name'] );
$attr['description'] = explode ( '|', $attr['description'] );
$id = $attr['id'];
//使用模型save方法,返回bool值
$model = new {$model};
$data = $model->isUpdate()->save($attr, ['id' => $id]);
return $data;
}