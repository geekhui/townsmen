<?php
namespace Home\Controller;

/**
 * @name    论坛资讯控制器
 * @author  wanghui
 */
class ForumController extends CommonController {
    
    public function _initialize() {
        $user_id = session("zd_user_info.user_id");
    }
    
    // 交流讨论
    public function index() {
        // 置顶帖
        $where['fp.istop'] = 1;
        $where['fp.status'] = 1;
        $top_posts = M("forum_post")
            ->field("fp.pid,fp.tid,fp.uid,fp.title,reply_num,fp.post_time,ft.theme")
            ->join("fp left join ct_forum_theme fm on ft.id = fp.tid and ft.status = 1")
            ->where($where)
            ->order("fp.top_time desc")
            ->select();
        $this->assign("top_post", $top_posts);
        
        // 普通帖
        $condition['fp.istop'] = 0;
        $condition['fp.status'] = 1;
        $forum_posts = M("forum_post")
            ->field("fp.pid,fp.tid,fp.uid,fp.title,reply_num,fp.post_time,ft.theme")
            ->join("fp left join ct_forum_theme fm on ft.id = fp.tid and ft.status = 1")
            ->where($condition)
            ->order("fp.post_time desc")
            ->select();
        $this->assign("post", $forum_posts);
        
        $this->display();
    }
    
    // 社区活动
    public function activity() {
        $activities = M("activity")
            ->field('ac.id,ac.activity,ac.initiator,ac.initiator_type,ac.status')
            ->join("ac left join ct_activity_reply on ar.acid = ac.id")
            ->order('ac.order')
            ->select();
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