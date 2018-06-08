<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller {
    // 初始化
    public function _initialize() {
        header("Content-type:text/html;charset=utf-8");
        $login_info = [
            'user' => [
                'uid' => 1,
                'uname' => 'test帐号'
            ]
        ];
        session("zd_login_info",$login_info);
        
    }
    
    // 登录用户信息
    public function loginUserInfo(){
        $user = session("zd_login_info.user");
        
    }
    
    
    
    
    
    
    

    // 空操作
    public function _empty() {
        A("Index")->index();
    }
}