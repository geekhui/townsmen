<?php
namespace Admin\Controller;
/**
 * @name    论坛管理 ：交流讨论
 * @author  王辉  20180514
 */
class BbsController extends CommonController
{
    protected $forum_model;
    
    public function __construct()
    {
        parent::__construct();
        
        $forum_model = D('AdminForum');
        $this->forum_model = $forum_model;
    }
    
    /**
     * @name    发帖列表
     * @author  wanghui 20180514
     */
    public function index()
    {
        if(empty(I())){
            $post_list = $this->forum_model->allPostList();
        }else{
            $start_date = isset($_REQUEST['start_date']) ? I('start_date') : date('Y-m-d');
            $end_date = isset($_REQUEST['end_date']) ? I('end_date') : date('Y-m-d');
            $param['start_date'] = $start_date;
            $param['end_date'] = $end_date;
            $where['post_time'] = ['between',[strtotime($start_date.' 00:00:00'), strtotime($end_date.' 23:59:59')]];
            
            if(isset($_REQUEST['type']) && I('type')!='-1'){
                $param['type'] = I('type');
                $where['tid'] = intval(I('type'));
            }
            
            if(isset($_REQUEST['title']) && trim(I('title'))!=''){
                $param['title'] = I('title');
                $where['title'] = ['like', '%'.trim(I('title')).'%'];
            }
            $this->assign('param', $param);
            
            $post_list = $this->forum_model->getPostList($where);
        }
        
        $this->assign('post_info',$post_list['list']);
        $this->assign('page',$post_list['page']);
        $this->display();
    }
    
    /**
     * @name    专题分区列表
     * @author  wanghui 20180514
     */
    public function themeList(){
        
    }
    
    
    
    
    
    
    
}