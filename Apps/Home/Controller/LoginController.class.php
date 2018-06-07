<?php
namespace Home\Controller;

/**
 * @name    用户登录控制器
 * @author  wanghui
 */
class LoginController extends CommonController {
    
    public function _initialize() {
        parent::_initialize();
        
    }
    
    // 用户登录
    public function login() {
        
        $login_user = I('post.login_user');
        $login_pwd = I('post.login_pwd');
        $verify_code = I('post.login_code');
        
        if (check_verify($verify_code)) {
            $this->error("验证码不正确", U("login"), 3);
        }
        
        $login_field = A("User","Event")->getLoginField($login_user); //判断用户登录字段
        
        $where[$login_field] = $login_user;
        $where['password'] = md5($login_pwd);
        $user = M("user_login")->where($where)->field('id,status')->find();
        if(!empty($user)){
            if($user['status'] == 1){
                $UserModel = D("User"); // 实例化User对象
                if (!$UserModel->create()){
                    // 如果创建失败 表示验证没有通过 输出错误提示信息
                    exit($UserModel->getError());
                }else{
                    // 验证通过 可以进行其他数据操作
                    $userInfo = $UserModel->getUserData($user['id']);
                }
                session('zd_login_info.user',$userInfo);
                
                $data['login_time'] = time();
                $data['login_ip'] = ipToInter(getRealIp());
                $data['login_device'] = 1;
                $data['login_num'] = array('exp', 'login_num+1');
                $userLogin = M("user_login")->where(['id'=>$user['id']])->save($data);
                if($userLogin){
                    $this->success("登录成功","/Index/index");
                }else{
                    $this->success("登录失败");
                }
            }else {
                $this->error("帐号已封停，请联系客服！");
            }
        }else{
            $this->error("用户名或密码输入不正确", U("login"), 3);
        }
    }
    
    // 新用户注册
    public function register() {
        $verify_code = I('post.regist_code');
        if (check_verify($verify_code)) {   
            $this->error("验证码不正确", U("login"), 3);
        }
        
        $regist_user = I('post.regist_user');
        $login_field = A("User","Event")->getLoginField($regist_user); //用户登录字段
        $data[$login_field] = $login_field;
        
        $regist_pwd = I('post.regist_pwd');
        $verify_pwd = I('post.verify_pwd');
        if($regist_pwd === $verify_pwd){
            $data['password'] = md5($regist_pwd);
        }
        
        $data['reg_time'] = time();
        $data['reg_ip'] = ipToInter(getRealIp());
        $data['reg_device'] = 1;
        $data['status'] = 1;
        M()->startTrans();
        $user_id = M("user_login")->add($data);
        if($user_id){
            if(M("user")->add(['uid'=>$user_id])){
                M()->commit();
                $this->success("用户注册成功",U("login"));
            }else {
                M()->rollback();
                $this->error("用户注册失败");
            }
        }else {
            M()->rollback();
            $this->error("用户注册失败");
        }
    }
    
    // 退出登录
    public function logout() {
        session('zd_login_info.user', null);
        $this->redirect('/');
    }
    
    // 生成验证码
    public function verify() {
        // 验证码参数配置
        $config = array(
            'fontSize'  =>  14,     //验证码字体大小
            'length'    =>  4,      //验证码位数
            'useNoise'  =>  false,  //关闭验证码杂点
            'imageW'    =>  35,     //验证码宽度 设置为0为自动计算
            'imageH'    =>  12,     //验证码高度 设置为0为自动计算
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();  
        /*
            <img src="{:U('Home/verify')}" class="verify" name="verify" title="点击刷新验证码" onclick="this.src=\'' .{:U('Home/verify')}. '?id=\'+Math.random();"> 
         
         */
    }
    
    
    
    
    // 空操作
    public function _empty() {
        $this->index();
    } 
   
}