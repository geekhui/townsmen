<?php
namespace Home\Event;
use Think\Controller;

class ForumEvent extends Controller{
    protected $forum_model;
    
    public function __construct()
    {
        $forum_model = D('forum');
        $this->forum_model = $forum_model;
    }

    // 发帖列表
    public function getPostList($istop) {
        $where['fp.istop'] = $istop;
        return $this->forum_model->getPostData($where);
    }
    

    
}