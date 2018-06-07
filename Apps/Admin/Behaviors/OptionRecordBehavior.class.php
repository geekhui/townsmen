<?php
namespace Admin\Behaviors;
use \Think\Behavior;
// use Admin\Model\AdminMenuModel;

class OptionRecordBehavior extends Behavior{

    public function run(&$param){
        $name = CONTROLLER_NAME . '/' . ACTION_NAME;
        
        $admin_menu_model = D('AdminMenu');
        $opt_name = $admin_menu_model->selectMenuInfoByName($name)['title'];
        
        $option_info = array(
            'opt_name' => $opt_name,
            'opt_user' => session('user_info')['user_name'],
            'opt_time' => time(),
        );
//         var_dump($option_info);
    }
}