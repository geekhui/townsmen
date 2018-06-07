<?php
namespace Admin\Controller;
/**
 * @name   订单管理 
 * @author wanghui 20180525
 *
 */
class OrderController extends CommonController
{
    protected $order_model;
    public function __construct()
    {
        parent::__construct();
        $order_model = D('AdminOrder');
        $this->order_model = $order_model;
    }
    
    /**
     * @name    订单列表
     * @author  wanghui 20180524
     */
    public function index()
    {
        if(isset($_REQUEST['start_date'])){
            $start_date = I('start_date');
            $param['start_date'] = $start_date;
            $where['success_time'] = ['egt', strtotime($start_date.' 00:00:00')];
        }
        if(isset($_REQUEST['end_date'])){
            $end_date = I('end_date');
            $param['end_date'] = $end_date;
            $where['order_time'] = ['elt', strtotime($end_date.' 23:59:59')];
        }
        
        if(isset($_REQUEST['type']) && I('type')!='-1'){
            $param['type'] = I('type');
            $where['order_status'] = intval(I('type'));
        }
    
        if(isset($_REQUEST['ordernum']) && trim(I('ordernum'))!=''){
            $param['ordernum'] = I('ordernum');
            $where['order_number'] = ['like', '%'.trim(I('ordernum')).'%'];
        }
        $this->assign('param', $param);
    
        $novel_data = $this->order_model->getOrderList($where);
        
        $this->assign('list',$novel_data['list']);
        $this->assign('page',$novel_data['page']);
        $this->display();
    }
    
    
    
    
    
    
    
    
}