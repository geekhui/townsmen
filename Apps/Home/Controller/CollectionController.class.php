<?php
namespace Home\Controller;

/**
 * @name    用户收藏控制器
 * @author  wanghui  20180524
 */
class CollectionController extends CommonController {
    
    public function _initialize() {
        
    }
    
    // 我收藏的商品
    public function collectionGoods() {
        $user = session('zd_login_info.user');
        $collectgoods = A("Collection", "Event")->collectionGoods($user['uid']);
        $this->assign("goods", $collectgoods);
    
        $this->display();
    }
    
    // 我收藏的小说
    public function collectionNovel() {
        $user = session('zd_login_info.user');
        $collectnovals = A("Collection", "Event")->collectionNovels($user['uid']);
        $this->assign("novals", $collectnovals);
        
        $this->display();
    }
    
    // 我收藏的帖子
    public function collectionPosts() {
        $user = session('zd_login_info.user');
        $collectposts = A("Collection", "Event")->collectionPosts($user['uid']);
        $this->assign("posts", $collectposts);
        
        $this->display();
    }
    
    // 加入收藏
    public function addCollection() {
        $user = session('zd_login_info.user');
        $c_id = intval($_REQUEST['cid']);
        $c_type = intval($_REQUEST['ctype']);
        $return_number = A("Collection", "Event")->addCollection($user['uid'], $c_id, $c_type);
        switch ($return_number){
            case 0: $this->error("收藏失败");break;
            case 1: $this->success("收藏成功");break;
            case 2: $this->error("已收藏");break;
        }
    }
    
    // 取消收藏
    public function cancelCollection() { 
        $user = session('zd_login_info.user');
        $c_id = intval($_REQUEST['cid']);
        $c_type = intval($_REQUEST['ctype']);
        $return_number = A("Collection", "Event")->cancelCollection($user['uid'], $c_id, $c_type);
        switch ($return_number){
            case 0: $this->error("取消失败");break;
            case 1: $this->success("取消成功");break;
            case 2: $this->error("已取消收藏");break;
            case 3: $this->error("未曾收藏");break;
        }
    }
    
    
    
    
    
    
    
    
    // 空操作
    public function _empty() {
        $this->index();
    }
    
    
}