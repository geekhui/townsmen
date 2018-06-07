<?php
namespace Admin\Controller;
/**
 * @name   商城管理 
 * @author Administrator
 *
 */
class MarketController extends CommonController
{
    protected $market_model;
    public function __construct()
    {
        parent::__construct();
        $market_model = D('AdminMarket');
        $this->market_model = $market_model;
    }
    
    /**
     * @name    商品列表
     * @author  wanghui 20180524
     */
    public function index()
    {
        if(isset($_REQUEST['start_date'])){
            $start_date = I('start_date');
            $param['start_date'] = $start_date;
            $where['create_time'] = ['egt', strtotime($start_date.' 00:00:00')];
        }
        if(isset($_REQUEST['end_date'])){
            $end_date = I('end_date');
            $param['end_date'] = $end_date;
            $where['create_time'] = ['elt', strtotime($end_date.' 23:59:59')];
        }
        
        if(isset($_REQUEST['type']) && I('type')!='-1'){
            $param['type'] = I('type');
            $where['type_id'] = intval(I('type'));
        }
    
        if(isset($_REQUEST['gname']) && trim(I('gname'))!=''){
            $param['gname'] = I('gname');
            $where['name'] = ['like', '%'.trim(I('gname')).'%'];
        }
        $this->assign('param', $param);
    
        $novel_data = $this->market_model->getGoodsList($where);
        
        $this->assign('list',$novel_data['list']);
        $this->assign('page',$novel_data['page']);
        $this->display();
    }
    
    
    
    
    
    
    
    
}