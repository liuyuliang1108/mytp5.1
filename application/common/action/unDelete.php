/*{$name}删除恢复*/
public function unDelete()
{
//软删除恢复
$list = {$model}::withTrashed()->select(['is_delete' => 1]);
$data=0;
foreach ($list as $config){
$config->restore();
$config->is_delete=0;
$data+=$config->save();
}
return $data;
}