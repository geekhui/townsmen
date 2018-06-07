<?php
namespace Admin\Controller;
/**
 * @name    会员管理
 * @author  Wanghui 20180524
 */
class MemberController extends CommonController
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * @name    会员列表
     * @author  wanghui 20180524
     */
    public function index()
    {
        $start_date = isset($_REQUEST['start_date']) ? I('start_date') : date('Y-m-d');
        $end_date = isset($_REQUEST['end_date']) ? I('end_date') : date('Y-m-d');
        $param['start_date'] = $start_date;
        $param['end_date'] = $end_date;
        $where['reg_time'] = ['between',[strtotime($start_date.' 00:00:00'), strtotime($end_date.' 23:59:59')]];
        
        if(isset($_REQUEST['member_type']) && I('member_type')!='-1'){ 
            $param['type'] = I('member_type');
            $where['type'] = intval(I('member_type'));
        }
        
        if(isset($_REQUEST['member_name']) && trim(I('member_name'))!=''){
            $param['username'] = I('member_name');
            $where['username'] = ['like', '%'.trim(I('member_name')).'%'];
        }
        $this->assign('param', $param);

        // 分页
        $where['status'] = 1;
        $count = M("user_login")->where($where)->count();
        $page = new \Think\Page($count,25);
        $show = $page->show();
        // 会员列表
        $member_list = M("user_login")->where($where)->limit($page->firstRow.','.$page->listRows)->order("login_time desc")->select();
        
        $this->assign('page',$show);
        $this->assign('list',$member_list);
        $this->display();
    }
    
    
    
    
    
    
    
    
}