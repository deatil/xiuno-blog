<?php

/**
 * 博客后台菜单
 * 
 * @author deatil
 * @create 2020-3-30
 */
 
defined('DEBUG') OR exit('Forbidden');

// hook plugin_haya_blog_admin_route_start.php

// 动作
$action = param(1);
empty($action) and $action = 'setting';

$actions = array(
    'setting',
    'apply',
    'category',
    'author',
    'author_category',
    'article',
    'comment',
    'attach',
);

// hook plugin_haya_blog_admin_route_actions_after.php

if (!in_array($action, $actions)) {
    message(-1, jump('访问错误'));
}

$haya_blog_admin_action_file = APP_PATH.'plugin/haya_blog/app/admin/route/'.$action.'.php';
if (!file_exists($haya_blog_admin_action_file)) {
    message(-1, jump('路由文件不存在'));
}

// 定义导航
$haya_blog_admin_config = APP_PATH.'plugin/haya_blog/app/admin/config/menu.php';
if (!file_exists($haya_blog_admin_config)) {
    message(-1, jump('插件配置文件不存在', url('plugin')));
}

// hook plugin_haya_blog_admin_route_admin_config_after.php

$haya_blog_admin_menu = include _include($haya_blog_admin_config);

$tablepre = $db->tablepre;
$haya_blog_admin_view = APP_PATH.'plugin/haya_blog/app/admin/view/htm';

// hook plugin_haya_blog_admin_route_end.php

include _include($haya_blog_admin_action_file);

