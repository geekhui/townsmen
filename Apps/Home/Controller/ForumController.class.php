<?php
namespace Home\Controller;

/**
 * @name    论坛资讯控制器
 * @author  wanghui
 */
class ForumController extends CommonController {
    
    public function _initialize() {
        parent::_initialize();
        if(!isset($_SESSION['zd_login_info'])){
            A("Login")->login();
        } 
    }
    
    // 交流讨论
    public function index() {  
        // 置顶帖
        $top_posts = A("Forum", "Event")->getPostList($istop = 1);
		$result_data['top_post'] = $top_posts;
        
        // 普通帖
        $forum_posts = A("Forum", "Event")->getPostList($istop = 0);
		$result_data['post'] = $forum_posts;
        
        $this->ajaxReturn(['code'=>100, 'data'=>$result_data]);
    }
    
    // 社区活动
    public function activity() {
        $activities = D("Forum")->getActivityData();

        $this->ajaxReturn(['code'=>100, 'data'=>$activities]);
    }
    
    // 掌动爆料站
    public function pubStand() {
        $hotnews = M("hotnews")->where(['status'=>1,'column'=>1])->order('order asc')->select();

        $this->ajaxReturn(['code'=>100, 'data'=>$hotnews]);
    }
    
    // 新游推荐
    public function newGames() {
        $gamenews = M("hotnews")->where(['status'=>1,'column'=>2])->order('order asc')->select();

        $this->ajaxReturn(['code'=>100, 'data'=>$gamenews]);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}