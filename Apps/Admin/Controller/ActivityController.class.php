<?php
namespace Admin\Controller;
/**
 * @name   活动管理 
 * @author Administrator
 *
 */
class ActivityController extends CommonController
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * @name    活动列表
     * @author  wanghui 20180524
     */
    public function index()
    {
        if(isset($_REQUEST['start_date'])){
            $start_date = I('start_date');
            $param['start_date'] = $start_date;
            $where['end_time'] = ['egt', strtotime($start_date.' 00:00:00')];
        }
        if(isset($_REQUEST['end_date'])){
            $end_date = I('end_date');
            $param['end_date'] = $end_date;
            $where['start_time'] = ['elt', strtotime($end_date.' 23:59:59')];
        }
        
        if(isset($_REQUEST['type']) && I('type')!='-1'){
            $param['type'] = I('type');
            $where['initiator_type'] = intval(I('type'));
        }
    
        if(isset($_REQUEST['aname']) && trim(I('aname'))!=''){
            $param['aname'] = I('aname');
            $where['activity'] = ['like', '%'.trim(I('aname')).'%'];
        }
        $this->assign('param', $param);
    
        $curpage = isset($_REQUEST['p']) ? I('p') : 1;
        $where['status'] = 1;
        $activity_list = M("activity")->where($where)->order("sort")->page($curpage,20)->select();
        $this->assign('list',$activity_list);
        
        $count = M("activity")->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出

        $this->display();
    }
    
    
    
    
    
    
    
    
}