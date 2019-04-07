<?php

namespace app\admin\controller;

use app\admin\controller\Base;
use think\captcha\CaptchaController;
use app\admin\model\User as UserModel;
use think\Db;
use think\Request;
use think\facade\Session;

/*继承基类控制器*/

class User extends Base
{
    /*登录界面*/
    public function login()
    {
$this->alreadyLogin();//防止用户重复登录
        /*渲染模板*/
        return $this->fetch();
    }

    /*admin删除*/
    public function adminDelete(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用模型update方法,返回model对象,软删除
        $data=UserModel::destroy(['id'=>$id]);
        return $data;
    }

    /*admin删除恢复*/
    public function unDelete()
    {
        //使用D方法软删除恢复
        $data= Db::table('user')->where('is_delete', 1)->update(['delete_time' => 0]);
        return $data;
    }

    /*admin添加*/
    public function adminAdd()
    {
        /*渲染模板*/
        return $this->fetch();
    }
    /*admin角色增加*/
    public function adminRoleAdd(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
unset($attr['password2']);
        //使用模型save方法,返回bool值
        $object= new UserModel;
        $data=$object->save($attr);
        return $data;
    }
    /*admin编辑*/
    public function adminEdit(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用模型get方法,返回model对象
        $result=UserModel::get(['id'=>$id]);
        //模板赋值
        $this->view->assign('user_info',$result->getData());
        /*渲染模板*/
        return $this->fetch();
    }
    /*admin角色编辑*/
    public function adminRoleEdit(Request $request)
    {
        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用模型save方法,返回bool值
        $object= new UserModel;
        $data=$object->isUpdate()->save($attr,['id'=>$id]);
        return $data;
    }

    /*admin停用*/
    public function adminStop(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用D方法软删除恢复
        $data= Db::table('user')->where(['id'=>$id])->update(['status'=>0]);
        return $data;
    }

    /*admin启用*/
    public function adminStart(Request $request)
    {

        //获取传入参数
        $attr = $request->param();
        $id=$attr['id'];
        //使用D方法软删除恢复
        $data= Db::table('user')->where(['id'=>$id])->update(['status'=>1]);
        return $data;
    }
    /**
     * $this->validate()
     * @param $data $rule $msq
     */
    /*验证登录*/
    public function checkLogin(Request $request)
    {
//初始返回参数
        $status = 0;
        $result = '';
        $data = $request->param();

        //设置验证规则
        $rule = [
            'name' => 'require',
            'password' => 'require',
            'verify|验证码' => 'require|captcha',//必填
        ];

//自定义验证提示信息
        $msq = [
            'name' => ['require' => '姓名不能为空'],
            'password' => ['require' => '密码不能为空'],
            'verify' => ['require' => '验证码不能为空', 'captcha' => '验证码输入错误，请检查！'],
        ];

//进行验证

        $result = $this->validate($data, $rule, $msq);

        //如果验证成功，返回为true,则提交到数据库中进行处理
        if ($result === true) {
            //构造查询条件
            $map = [
                'name' => $data['name'],
                'password' => $data['password'],
            ];
            //查询用户信息
            $user=UserModel::get($map);
            if ($user==null) {
                $result='没有找到该用户';
            }else{
                $status=1;
                $result='验证通过，点击[确定]后进入';

                //用session设置用户登录信息
                Session::set('user_id',$user->id);//用户id
                Session::set('user_info',$user->getdata());//用户所有信息
            }
        }
        return ['status' => $status, 'message' => $result, 'data' => $data];

    }

    /*退出登录*/
    public function logout()
    {
//注销session
        Session::delete('user_id');
        Session::delete('user_info');
        $this->success('已注销登录，正在返回','user/login');

    }

    //验证码
    public function verify()
    {
        //使用扩展内置的方法生成验证码
        $captcha = new CaptchaController();
        $result = $captcha->index();
        return $result;
    }

    /*管理员列表*/
    public function userList()
    {
        $this->view->assign('title','管理员列表');
        $this->view->assign('keywords','管理员列表');
        $this->view->assign('description','管理员列表展示实例');

        $this->view->count=UserModel::count();
        $this->view->assign('count',$this->view->count);
        //判断当前是不是admin用户
        //先通过session获取当前登陆用户名
        $userName=Session::get('user_info.name');
        if ($userName=='admin') {
            $list=UserModel::all();//admin用户可以查看所有用户列表
        }else{
            $list=UserModel::all(['name'=>$userName]);//非admin用户只查看个人信息
        }
        $this->view->assign('list',$list);
        return $this->fetch();

    }
}