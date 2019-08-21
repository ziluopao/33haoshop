<?php
/**
 * 菜单
 *
 * @好商城提供技术支持 授权请购买shopnc授权 
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');
$_menu['flea'] = array (
        'name' => $lang['nc_flea'],
        'child'=>array(
                array(
                        'name'=>'设置',
                        'child' => array(
						        'flea_index' => 'SEO设置',
                                'flea_class' => '分类管理',
                                'flea_class_index' => '首页分类管理',
                                'flea' => '闲置管理',
                                'flea_region' => '地区管理',
                                'flea_adv' => '闲置幻灯'
                        )
                )
        )
);