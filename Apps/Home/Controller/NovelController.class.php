<?php
namespace Home\Controller;

/**
 * @name    小说控制器
 * @author  wanghui 
 */
class NovelController extends CommonController {
    
    public function _initialize() {

    }
    
    // 小说类别首页
    public function index() {
        // 头部轮播类别
        $novel_ads = A("Novel", "Event")->getHeadNovels();
        $this->assign("ad_list", $novel_ads);
        
        // 小说列表
        $type_num = isset($_REQUEST['typeid']) ? intval(I('typeid')) : 1;
        $novel_list = A("Novel", "Event")->getNovelList($type_num);
        $this->assign("finenovels", $novel_list['fine']);
        $this->assign("usernovels", $novel_list['list']);
        
        $this->display();
    }
    
    // 放入书架
    public function putinBookshelf() {
        if(!session('zd_login_info.user')){
            $this->ajaxReturn(['state'=>'fail','msg'=>'请先登录','url'=>'/Home/Login/login']);
        }else{
            $user = session('zd_login_info.user');
            $novel_id = I('post.nid');
            A("Collection", "Event")->addCollection($user['uid'], 2, $novel_id);
        }
    }
    
    // 移出书架
    public function shiftoutBookshelf() {
        if(!session('zd_login_info.user')){
            $this->ajaxReturn(['state'=>'fail','msg'=>'请先登录','url'=>'/Home/Login/login']);
        }else{
            $user = session('zd_login_info.user');
            $novel_id = I('post.nid');
            A("Collection", "Event")->cancelCollection($user['uid'], 2, $novel_id);
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // 空操作
    public function _empty() {
        A("Index")->index();
    } 
}