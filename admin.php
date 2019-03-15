<?php
// 应用入口文件
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_MAIN_FILE', __FILE__);
//define('APP_DEBUG',False);
define('APP_DEBUG',true);
// 定义应用目录
define('APP_PATH','./');
define('BIND_MODULE','Rbac');
define('PROJECT_DIR_NAME',basename(__DIR__));
define('PROJECT_UPLOAD_PATH',__DIR__.'/Public/UploadFile/');
// 引入ThinkPHP入口文件
//require '../tp3.2/run.php';
require 'tp3.2/run.php';

// 亲^_^ 后面不需要任何代码了