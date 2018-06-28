<?php
return array(
	//'配置项'=>'配置值'

    // 路由配置
    //'DEFAULT_MODULE' => 'Admin',    //默认访问模块
    
    // 数据库配置信息
    'DB_TYPE'   => 'mysql',     // 数据库类型
    
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'zd_cmy',    // 数据库名
    'DB_USER'   => 'root',      // 用户名
    'DB_PWD'    => '',          // 密码
    
//     'DB_HOST' => '221.123.178.234', // 数据库服务器地址
    
//     'DB_HOST' => '192.168.1.234', // 数据库服务器地址
//     'DB_NAME' => 'zdwapshop', // 数据库名称
//     'DB_USER' => 'root', // 数据库用户名
//     'DB_PWD' => '123456', // 数据库密码
    
    'DB_PORT'   => 3306,        // 端口
    'DB_PREFIX' => 'ct_',       // 数据库表前缀
    'DB_CHARSET'=> 'utf8',      // 字符集
    'DB_DEBUG'  =>  TRUE,       // 数据库调试模式 开启后可以记录SQL日志

	
    // 缓存配置
    'DATA_CACHE_TYPE'=>'file',  //设置缓存方式为file
    'DATA_CACHE_TIME'=>600,   //缓存周期600秒
    
    'SESSION_AUTO_START' => true, //是否开启session
    'SESSION_OPTIONS' => array('name'=>'retry_count','expire'=>36000),
    
    //'TMPL_EXCEPTION_FILE' => '/Public/Html/exception.tpl'    //异常页面的模板文件
    //'TMPL_ACTION_ERROR' => '/Public/Html/dispatch_jump.tpl', //默认错误跳转对应的模板文件
    //'SHOW_ERROR_MSG' => false,      //不显示错误信息，系统的默认情况下，调试模式是开启错误信息显示的，部署模式则关闭错误信息显示。
    //'ERROR_MESSAGE' => '发生错误！',      //设置错误提示信息
    
    //'ERROR_PAGE' =>'/Public/Html/error.html'     //设置异常和错误都指向一个统一页面
    
    //'LOG_RECORD' => true, // 开启日志记录
    //'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
    
    
    

    'URL_CASE_INSENSITIVE'  =>  true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'             =>  1,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    //'URL_PATHINFO_DEPR'     =>  '/',    // PATHINFO模式下，各参数之间的分割符号
    //'URL_PATHINFO_FETCH'    =>  'ORIG_PATH_INFO,REDIRECT_PATH_INFO,REDIRECT_URL', // 用于兼容判断PATH_INFO 参数的SERVER替代变量列表
    //'URL_REQUEST_URI'       =>  'REQUEST_URI', // 获取当前页面地址的系统变量 默认为REQUEST_URI
    //'URL_HTML_SUFFIX'       =>  'html',  // URL伪静态后缀设置
    //'URL_DENY_SUFFIX'       =>  'ico|png|gif|jpg', // URL禁止访问的后缀设置
    //'URL_PARAMS_BIND'       =>  true, // URL变量绑定到Action方法参数
    //'URL_PARAMS_BIND_TYPE'  =>  0, // URL变量绑定的类型 0 按变量名绑定 1 按变量顺序绑定
    //'URL_404_REDIRECT'      =>  '', // 404 跳转页面 部署模式有效
    //'URL_ROUTER_ON'         =>  false,   // 是否开启URL路由
    //'URL_ROUTE_RULES'       =>  array(), // 默认路由规则 针对模块
    //'URL_MAP_RULES'         =>  array(), // URL映射定义规则
    
    
    
);