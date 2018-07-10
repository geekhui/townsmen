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
   
    /**
     * 用户登录
     * 
     * 首先判断$_SESSION['zd_login_info']是否失效（session默认周期是30分钟），若有效，不必登录
     * 
     * （A）若失效，判断$_GET['zd_token']是否存在，若存在，登录并生成session,更新token和过期时间，但不更新登录时间、ip和登录次数。
     * 
     * （B）若$_SESSION['zd_login_info']和$_GET['zd_token']都不存在时,去获取$_COOKIE['ad_player']的值
     * 若存在，登录生成session值，并更新登录时间、ip、登录次数；
     * 若是要做永久登录，则还应生成新的token值，更新token和过期时间。默认过期时间是20天。
     * 
     * （C）若是SESSION、COOKIE和Token都是无效，则重新登录
     * 
     * session记录用户登录信息，cookie保存身份标识，token为登录标识
     */ 
    public function login() 
    {
        if($_REQUEST['token']){     // A
            $map['token'] = $_REQUEST['token'];
            $user_info = M("user_login")->where($map)->field("uid,status,token,timeout")->find();
            
            if($user_info){
                if($user_info['status'] == 1){
                    if ($_REQUEST['token'] != $user_info['token'])   //永久登录标识错误
                    {
                        $this->ajaxReturn(['code'=>405, 'msg'=>'登录失败，登录标识错误']);
                    }elseif (time() > $user_info['timeout'])      //永久登录时间有效期超时
                    {
                        $this->ajaxReturn(['code'=>406, 'msg'=>'登录失败，登录帐号已过期']);
                        /**
                         * @todo 若是要做永久登录，此处可以重新生成永久登录标识并设定一个新的cookie即可。
                         */
                    }else {
                        $data['token'] = $token = md5(uniqid(rand(), TRUE));
                        $data['timeout'] = $timeout = time() + 60 * 60 * 24 * 15;    //默认15天
                        $userLogin = M("user_login")->where(['uid'=>$user_info['uid']])->save($data);
                        if($userLogin){
                            $userInfo = D("User")->getUserData($user_info['uid']);
                            session('zd_login_info.user',$userInfo);
                            
                            $this->ajaxReturn(['code'=>200, 'msg'=>"登录成功", 'token'=>$token, 'data'=>session('zd_login_info.user')]);
                        }else {
                            $this->ajaxReturn(['code'=>400, 'msg'=>'登录失败']);
                        }    
                    }
                }else{
                    $this->ajaxReturn(['code'=>402, 'msg'=>"帐号已封停，请联系客服"]);
                }
            }else {
                $this->ajaxReturn(['code'=>408, 'msg'=>"账号不存在"]);
            }
        }else {
            if(cookie('zd_player') && ctype_alnum($_COOKIE['zd_player'])){      //B
                $map['identifier'] = $identifier = cookie('zd_player');
                $user_info = M("user_login")->where($map)->field("uid,status,username,token")->find();
                
                if($user_info){
                    if($user_info['status'] == 1){
                        if ($identifier != new_md5($user_info['username'])) //第二身份标识和帐号不匹配
                        {
                            $this->ajaxReturn(['code'=>407, 'msg'=>'登录失败，帐号身份标识错误']);
                        } else {
                            $data['login_time'] = time();
                            $data['login_num'] = array('exp', 'login_num+1');
                            $data['login_ip'] = getRealIp();
                            $userLogin = M("user_login")->where(['uid'=>$user_info['uid']])->save($data);
                            if($userLogin){
                                $userInfo = D("User")->getUserData($user_info['uid']);
                                session('zd_login_info.user',$userInfo);
                
                                $this->ajaxReturn(['code'=>200, 'msg'=>"登录成功", 'token'=>$user_info['token'], 'data'=>session('zd_login_info.user')]);
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
            }else {      // C
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
                $where['password'] = new_md5($login_pwd);
                $user = M("user_login")->where($where)->field('uid,username,status')->find();
                
                if(!empty($user)){
                    if($user['status'] == 1){
                        $data['identifier'] = $identifier = new_md5($user['username']);
                        $data['token'] = $token = md5(uniqid(rand(), TRUE));
                        $data['timeout'] = $timeout = time() + 60 * 60 * 24 * 15;    //默认15天
                        $data['login_time'] = time();
                        $data['login_ip'] = ip2Plus(getRealIp());
                        $data['login_device'] = 1;
                        $data['login_num'] = array('exp', 'login_num+1');
                        $userLogin = M("user_login")->where(['uid'=>$user['uid']])->save($data);
                        if($userLogin){
                            $userInfo = D("User")->getUserData($user['uid']);
                            session('zd_login_info.user',$userInfo);
                            
                            // 是否设置Cookie周期
                            $cookie_time = isset($_REQUEST['cookie_time']) ? intval($_REQUEST['cookie_time']) : -1;        
                            cookie("player", $identifier, $timeout);
                
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