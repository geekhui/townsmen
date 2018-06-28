<?php
namespace Home\Controller;
use Home\Controller\CommonController;
/**
 * @name    社区首页模块控制器
 * @author  wanghui 20180524
 */
class IndexController extends CommonController {
    
    public function _initialize() {
        parent::_initialize();
        
    }
    
    // 首页
    public function index() {
        // 游戏玩家用户跳转至注册页
        $player_user = $_REQUEST['user'];
        if($player_user){
            //重定向到指定的URL地址
            redirect('/Home/Login/register', 1, '页面跳转中...');
            
        }
        // 转盘页
        $this->display();
    }
    
   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // 空操作
    public function _empty() {
        $this->index();
    }
    
}