<?php
namespace Home\Controller;

/**
 * @name    转盘抽奖控制器
 * @author  wanghui 20180529
 */
class IndexController extends CommonController {
    protected $lottery_model;
    
    public function _initialize() {
        parent::_initialize(); 
        if(!isset($_SESSION['zd_login_info'])){
            A("Login")->login();
        }
    
        $lottery_model = D('Lottery');
        $this->lottery_model = $lottery_model;
    }
    
    // 大转盘首页
    public function index() {
        $lottery = A("Lottery", "Event")->allPrizeData();
     
        $this->ajaxReturn(['code'=>100, 'data'=>$lottery]);
    }
    
    /*
     * 每次前端页面的请求，PHP循环奖项设置数组，
     * 通过概率计算函数getRand获取抽中的奖项id。
     * 同样，根据奖项id获取其对应的奖品数组，通过概率计算函数getRand获取抽中的奖品id。
     * 最后输出json个数数据给前端页面。
     */
    public function getAwards(){
        $lottery = A("Lottery", "Event")->allPrizeData();
    
        $awards_data = $lottery['awards'];
        foreach ($awards_data as $val) {
            $arr[$val['id']] = $val['scale'];
        }
    
        $awards_id = getRand($arr);  //根据概率获取奖品id
    
        $prize_data = $lottery['prize'];
        if(count($prize_data[$awards_id]) > 1){
            foreach ($prize_data[$awards_id] as $val) {
                $newarr[$val['id']] = $val['scale'];
                $prize_arr[$val['id']] = $val;
            }
            $prize_id = getRand($newarr);  //根据概率获取奖品id
    
            $data['msg'] = $prize_arr[$prize_id]['type'];
            $data['prize_title'] = $prize_arr[$prize_id]['title'];
            //             echo json_encode($data);exit;   //以json数组返回给前端
            $this->ajaxReturn(['code'=>100, 'data'=>$data]);
        }else{
            $data['msg'] = $prize_data[$awards_id][0]['type'];
            $data['prize_title'] = $prize_data[$awards_id][0]['title']; //中奖奖品
            //             echo json_encode($data);exit;
            $this->ajaxReturn(['code'=>100, 'data'=>$data]);
        }
    }
    
    // 中奖后续处理操作
    public function winLottery(){
        $user = session("zd_login_info.user");
        $prize_id = intval($_REQUEST['pz_id']);
    
        $lottery = D("Lottery")->getPrizeInfo($prize_id);
    
        // 根据奖品类型和有效期等获取其他属性值
        switch ($lottery['type']){
            case 1:     //微信红包
    
                break;
            case 2:     //积分
    
                break;
            case 3:     //VIP小说阅读券
    
                break;
            case 4:     //商城免积分兑换券
    
                break;
            case 5:     //代金券
    
                break;
            case 6:     //任务卷轴
    
                break;
            case 7:     //虚拟鲜花
    
                break;
            case 8:     //超级经验卡
    
                break;
            case 9:     //实物
    
                break;
    
        }
    
    
        $data['uid'] = $user['uid'];
        $data['pz_id'] = $user['pz_id'];
        $data['aw_id'] = $user['aw_id'];
        $data['quantity'] = $user['quantity'];
        $data['win_time'] = $user['win_time'];
        $data['take_time'] = $user['take_time'];
        M("lottery")->add($data);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}