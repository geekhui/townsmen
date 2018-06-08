<?php
namespace Home\Controller;

/**
 * @name    论坛资讯控制器
 * @author  wanghui
 */
class ForumController extends CommonController {
    
    public function _initialize() {
        $user = session("zd_login_info.user");
        
    }
    
    // 交流讨论
    public function index() {  
        // 置顶帖
        $top_posts = A("Forum", "Event")->getPostList($istop = 1);
        $this->assign("top_post", $top_posts);
        
        // 普通帖
        $forum_posts = A("Forum", "Event")->getPostList($istop = 0);
        $this->assign("post", $forum_posts);
        
        $this->display();
    }
    
    // 社区活动
    public function activity() {
        $activities = D("Forum")->getActivityData();
        $this->assign('activities', $activities);
        $this->display();
    }
    
    // 掌动爆料站
    public function pubStand() {
        $hotnews = M("hotnews")->where(['status'=>1,'column'=>1])->order('order asc')->select();
        $this->assign('$hotnews', $hotnews);
        $this->display();
    }
    
    // 新游推荐
    public function newGames() {
        $gamenews = M("hotnews")->where(['status'=>1,'column'=>2])->order('order asc')->select();
        $this->assign('gamenews', $gamenews);
        $this->display();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}