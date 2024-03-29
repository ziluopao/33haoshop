<?php
/**
 * 圈子板块初始化文件
 *
 * 圈子板块初始化文件，引用框架初始化文件
 */
define('APP_ID','circle');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

require __DIR__ . '/../33hao.php';

define('APP_SITE_URL', CIRCLE_SITE_URL);
define('TPL_NAME', TPL_CIRCLE_NAME);
define('CIRCLE_TEMPLATES_URL', CIRCLE_SITE_URL.'/templates/'.TPL_NAME);
define('CIRCLE_RESOURCE_SITE_URL',CIRCLE_SITE_URL.'/resource');
require(BASE_PATH.'/framework/function/function.php');

require(BASE_PATH.'/control/control.php');
Base::run();
