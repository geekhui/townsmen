<?php
namespace Home\Controller;
use Think\Controller;
/**
 * @name    空控制器
 * @author  王辉[Henry]   20180510
 */
class EmptyController extends Controller {
    
    public function index(){
        A("Index")->index();
    }
}