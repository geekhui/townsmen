<?php
namespace Home\Model;
use Think\Model;

class ForumModel extends Model{
    
    protected $tableName = 'forum_post';

    /**
     * @desc    发帖列表
     * @author  wanghui 20180608
     */
    public function getPostData($where) {
        $where['fp.status'] = 1;
        $where['ft.status'] = 1;
        $post_list = $this
            ->field("fp.pid,fp.tid,fp.uid,fp.title,reply_num,fp.post_time,ft.theme")
            ->join("fp left join ct_forum_theme ft on ft.tid = fp.tid and ft.status = 1")
            ->where($where)
            ->order("fp.top_time desc")
            ->select();
        return $post_list;
    }
   
    /**
     * @desc    社区活动列表
     * @author  wanghui 20180608
     */
    public function getActivityData() {
        $activities = M("activity")
            ->field('ac.id,ac.activity,ac.initiator,ac.initiator_type,ac.status')
            ->join("ac left join ct_activity_reply on ar.acid = ac.id")
            ->order('ac.order')
            ->select();
        return $activities;
    }

}