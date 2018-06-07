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
        
        $this->display();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // 空操作
    public function _empty() {
        $this->index();
    }
    
}