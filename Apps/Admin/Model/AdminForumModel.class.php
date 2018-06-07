<?php
namespace Admin\Model;
use Think\Model;

class AdminForumModel extends Model
{
    protected $tableName = 'forum_post';
    
    /**
     * @name    发帖列表
     * @author  wanghui 20180514
     */
    public function allPostList($num = 20){
        $where = ['status' => 1];
        
        $count = $this->where($where)->count();
        $page = new \Think\Page($count,$num);
        $show = $page->show();
        $list = $this
            ->field("fp.pid,fp.title,fp.post_time,ft.theme,ug.username")
            ->join("fp join ct_forum_theme ft on ft.tid = fp.tid and ft.status = 1")
            ->join("join ct_user_login ug on ug.uid = fp.uid")
            ->where(['fp.status' => 1])
            ->order("fp.post_time desc")
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        
        return array('page' => $show , 'list' => $list);
    }
    
    /**
     * @name    发帖列表
     * @author  wanghui 20180524
     */
    public function getPostList($where, $order="post_time desc", $limit = 20){
        $where['status'] = 1;
        foreach ($where as $key=>$val){
            $where_join['fp.'.$key] = $val;
        }
        $count = $this->where($where)->count();
        $page = new \Think\Page($count,$limit);
        $show = $page->show();
        
        $order = "fp." . $order;
        $list = $this
            ->field("fp.pid,fp.title,fp.post_time,ft.theme,ug.username")
            ->join("fp join ct_forum_theme ft on ft.id = fp.tid and ft.status = 1")
            ->join("join ct_user_login ug on ug.uid = fp.uid")
            ->where($where_join)
            ->order($order)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
    
        return array('page' => $show , 'list' => $list);
    }
    
    
    
    
}