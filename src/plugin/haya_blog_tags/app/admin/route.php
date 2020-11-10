<?php

/**
 * 博客标签后台菜单
 * 
 * @author deatil
 * @create 2020-7-9
 */
 
defined('DEBUG') OR exit('Forbidden');

// hook plugin_haya_blog_tags_admin_route_start.php

// 动作
$action = param(1);
empty($action) and $action = 'setting';

$actions = array(
    'setting',
    'tag',
);

// hook plugin_haya_blog_tags_admin_route_actions_after.php

if (!in_array($action, $actions)) {
    message(-1, jump('访问错误'));
}

$action_file = APP_PATH.'plugin/haya_blog_tags/app/admin/route/'.$action.'.php';
if (!file_exists($action_file)) {
    message(-1, jump('路由文件不存在'));
}

// 定义导航
$admin_config = APP_PATH.'plugin/haya_blog_tags/app/admin/config/menu.php';
if (!file_exists($admin_config)) {
    message(-1, jump('插件配置文件不存在', url('plugin')));
}

// hook plugin_haya_blog_tags_admin_route_admin_config_after.php

$admin_menu = include _include($admin_config);

$tablepre = $db->tablepre;
$admin_view = APP_PATH.'plugin/haya_blog_tags/app/admin/view/htm';

// hook plugin_haya_blog_tags_admin_route_end.php

include _include($action_file);

