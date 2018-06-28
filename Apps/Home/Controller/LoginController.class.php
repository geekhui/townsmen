<?php
namespace Home\Controller;

/**
 * @name    用户登录控制器
 * @author  wanghui
 */
class LoginController extends CommonController {
    
    public function _initialize() 
    {
        parent::_initialize();   
    }
    
    // 验证通行证是否已存在
    public function check_passport()
    {
        $passport = $_POST['passport'];
        if(!$passport) $this->ajaxReturn(['code'=>400, 'msg'=> "没有参数"]);
        
        $user = M("user_login")->where(['username'=>$passport/* , 'status'=>1 */])->find();
        if($user){ 
            $this->ajaxReturn(['code'=>401, 'msg'=> "该通行证账号已存在"]);
        }else{
            $this->ajaxReturn(['code'=>200]);
        }
    }
    
    // 验证昵称是否已存在
    public function check_nickname()
    {
        $nickname = $_POST['nickname'];
        if(!$nickname) $this->ajaxReturn(['code'=>400, 'msg'=> "没有参数"]);
        
        $user = M("user_login")->where(['nickname'=>$nickname])->find();
        if($user) {
            $this->ajaxReturn(['code'=>401, 'msg'=> "该昵称已存在"]);
        }else{
            $this->ajaxReturn(['code'=>200]);
        }
    }
    
    // 验证手机号是否已存在
    public function check_mobile()
    {
        $mobile = $_POST['mobile'];
        if(!$mobile) $this->ajaxReturn(['code'=>400, 'msg'=> "没有参数"]);
        
        $user = M("user_login")->where(['mobile'=>$mobile])->find();
        if($user){
            $this->ajaxReturn(['code'=>401, 'msg'=> "该手机号已存在"]);
        }else{
            $this->ajaxReturn(['code'=>200]);
        }
    }
    
    // 生成验证码
    public function verify()
    {
        // 验证码参数配置
        $config = array(
            'fontSize'  =>  25,     //验证码字体大小
            'length'    =>  4,      //验证码位数
            'useNoise'  =>  false,  //关闭验证码杂点
            //             'imageW'    =>  100,     //验证码宽度 设置为0为自动计算
            'imageH'    =>  50,     //验证码高度 设置为0为自动计算
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
        /*
         <img src="{:U('Home/verify')}" class="verify" name="verify" title="点击刷新验证码" onclick="this.src=\'' .{:U('Home/verify')}. '?id=\'+Math.random();">
    
        */
    }
    
    // 新用户注册
    public function user_register()
    {
        $passport = $_POST['passport'];      //自定义通行证
        $password = $_POST['password'];
        $mobile = $_POST['mobile'];
        $nickname = $_POST['nickname'];
        $verify_code = $_POST['verifycode'];

        if(!$passport || !$password || !$mobile || !$nickname || !$verify_code){
            $this->ajaxReturn(['code'=>400, 'msg'=> "参数缺失"]);
        }elseif (!check_verify($verify_code)) {
            $this->ajaxReturn(['code'=>402, 'msg'=> "验证码不正确"]);
        }
        
        $result_check = A("User", "Event")->checkRegisterUser($passport, $password, $mobile, $nickname);
        if($result_check == 200){       //验证通过
            $data['username'] = $passport;  
            $data['password'] = new_md5($password);
//             $data['password'] = md5($password);
            $data['nickname'] = $nickname;
            $data['mobile'] = $mobile;
            $data['reg_time'] = time();
            $data['reg_ip'] = ip2Plus(getRealIp());
            $data['reg_device'] = 1;
            $data['status'] = 1;
            M()->startTrans();
            $user_id = M("user_login")->add($data);
            if($user_id){
                if(M("user_info")->add(['uid'=>$user_id])){
                    M()->commit();
                    $this->ajaxReturn(['code'=>200]);
                }
            }
            M()->rollback();
            $this->ajaxReturn(['code'=>407, 'msg'=> "用户注册失败"]);
        }
    }
    
    // 游戏玩家注册
    public function player_register()
    {
        $passport = $_POST['passport'];      //绑定玩家游戏通行证
        $password = $_POST['password'];
        $mobile = $_POST['mobile'];
        $nickname = $_POST['nickname'];
        $verify_code = $_POST['verifycode'];
        if(!$password || !$mobile || !$nickname || !$verify_code){
            $this->ajaxReturn(['code'=>400, 'msg'=> "参数缺失"]);
        }elseif (check_verify($verify_code)) {
            $this->ajaxReturn(['code'=>402, 'msg'=> "验证码不正确"]);
        }
        
        $result_check = A("User", "Event")->checkRegisterPlayer($password, $mobile, $nickname);
        if($result_check == 200){       //验证通过
//             $data['password'] = md5($password);
            $data['password'] = new_md5($password);
            $data['nickname'] = $nickname;
            $data['mobile'] = $mobile;
            $data['passport'] = $passport;
            $data['reg_time'] = time();
            $data['reg_ip'] = ip2Plus(getRealIp());
            $data['reg_device'] = 1;
            $data['status'] = 1;
            M()->startTrans();
            $user_id = M("user_login")->add($data);
            if($user_id){
                $username = "player_".substr(date('Ymd'), 1).$user_id;
                if (M("user_login")->data(['username'=>$username])->save()){
                    if(M("user_info")->add(['uid'=>$user_id])){
                        M()->commit();
                        $this->ajaxReturn(['code'=>200]);
                    }
                }
            }
            M()->rollback();
            $this->ajaxReturn(['code'=>407, 'msg'=> "用户注册失败"]);
        } 
    }
   
    // 用户登录
    public function login() 
    {
        list($identifier, $token) = explode(':', cookie('auth_zdplayer'));  
        
        if (ctype_alnum($identifier) && ctype_alnum($token)){            
            $map['identifier'] = $identifier; 
            $user_result = M("user_login")->where($map)->field("uid,status,username,token,timeout")->find();

            if($user_result){
                if($user_result['status'] == 1){
                    if ($token != $user_result['token'])   //永久登录标识错误
                    {
                        $this->ajaxReturn(['code'=>405, 'msg'=>'登录失败，登录标识错误']);
                    }elseif (time() > $user_result['timeout'])      //永久登录时间有效期超时
                    {
                        $this->ajaxReturn(['code'=>406, 'msg'=>'登录失败，登录帐号已过期']);
                        /**
                         * @todo 若是要做永久登录，此处可以重新生成永久登录标识并设定一个新的cookie即可。
                         */
                    }elseif ($identifier != new_md5($user_result['username'])) //第二身份标识和帐号不匹配
                    {
                        $this->ajaxReturn(['code'=>407, 'msg'=>'登录失败，帐号身份标识错误']);
                    } else {
                        $data['login_time'] = time();
                        $data['login_num'] = array('exp', 'login_num+1');
                        $userLogin = M("user_login")->where(['uid'=>$user_result['uid']])->save($data);
                        if($userLogin){
                            $userInfo = D("User")->getUserData($user_result['uid']);
                            session('zd_login_info.user',$userInfo);
                            
                            $this->ajaxReturn(['code'=>200, 'msg'=>"登录成功", 'data'=>session('zd_login_info.user')]);
                        }else{
                            $this->ajaxReturn(['code'=>400, 'msg'=>'登录失败']);
                        }
                    }
                }else {
                    $this->ajaxReturn(['code'=>402, 'msg'=>"帐号已封停，请联系客服"]);
                }
            }else {
                $this->ajaxReturn(['code'=>408, 'msg'=>"账号不存在"]);
            }
        }else {
            $login_user = $_POST['login_user'];     //登录名
            $login_pwd = $_POST['login_pwd'];       //登录密码

            if(!$login_user || !$login_pwd) $this->ajaxReturn(['code'=>401, 'msg'=>"参数缺失"]);
            if(I('post.login_code') !== null){
                $verify_code = I('post.login_code');    //验证码
                if (check_verify($verify_code)) {
                    $this->ajaxReturn(['code'=>401, 'msg'=>"验证码不正确"]);
                }
            }
            
            $login_field = A("User","Event")->getLoginField($login_user); //判断用户登录字段
            $where[$login_field] = $login_user;
            //         $where['password'] = md5($login_pwd);
            $where['password'] = new_md5($login_pwd);
            $user = M("user_login")->where($where)->field('uid,username,status')->find();
            
            if(!empty($user)){
                if($user['status'] == 1){
//                     if($is_long) {  //是否记住帐号【永久登录】
    
                        $data['identifier'] = $identifier = new_md5($user['username']);
                        $data['token'] = $token = md5(uniqid(rand(), TRUE));
                        $data['timeout'] = $timeout = time() + 60 * 60 * 24 * 7;    //默认7天
//                     }
                    $data['login_time'] = time();
                    $data['login_ip'] = ip2Plus(getRealIp());
                    $data['login_device'] = 1;
                    $data['login_num'] = array('exp', 'login_num+1');
                    $userLogin = M("user_login")->where(['uid'=>$user['uid']])->save($data);
                    if($userLogin){
                        $userInfo = D("User")->getUserData($user['uid']);
                        
                        session('zd_login_info.user',$userInfo);
//                         if($is_long){
                            cookie("auth_zdplayer", "$identifier:$token", $timeout);
//                         }
                        
                        $this->ajaxReturn(['code'=>200, 'msg'=>"登录成功", 'token'=>$token, 'cookie'=>"$identifier:$token"]);
                    }else{
                        cookie('retry_count', 1, 60);
                        if(session("retry_count") >= 3){
                            $this->ajaxReturn(['code'=>700, 'msg'=>"显示验证码"]);
                        }else{
                            $this->ajaxReturn(['code'=>400, 'msg'=>"登录失败"]);
                        }
                    }
                }else {
                    cookie('retry_count', 1, 60);
                    if(session("retry_count") >= 3){
                        $this->ajaxReturn(['code'=>700, 'msg'=>"显示验证码"]);
                    }else{
                        $this->ajaxReturn(['code'=>402, 'msg'=>"帐号已封停，请联系客服"]);
                    } 
                }
            }else{
                cookie('retry_count', 1, 60);
                if(session("retry_count") >= 3){
                    $this->ajaxReturn(['code'=>700, 'msg'=>"显示验证码"]);
                }else{
                    $this->ajaxReturn(['code'=>401, 'msg'=>"用户名或密码输入不正确"]);
                }
            }
        }
    }
    
//     //登录三次失败，显示验证码
//     public function retry_count(){
        
//         if(!isset($_COOKIE["retry_count"])){
// //             session("retry_count", 1);
            
//         }else {
//             $_SESSION["retry_count"]++;
//         }
//     }
    
    // 退出登录
    public function logout() {
        session('zd_login_info', null);
        session('retry_count', null);
        cookie("auth_zdplayer", null);
        
        $this->ajaxReturn(['code'=>200, 'msg'=>"退出成功"]);
    }
    
    

    
    // 空操作
    public function _empty() {
        $this->index();
    } 
   
}