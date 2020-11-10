<?php

/**
 * 标签
 * 
 * @author deatil
 * @create 2020-7-12
 */
 
defined('DEBUG') OR exit('Forbidden');

// 动作
$action = param(1);
empty($action) and $action = 'index';

$actions = array(
    'index',
    'tag',
);

if (!in_array($action, $actions)) {
    message(-1, jump('访问错误'));
}

$action_file = APP_PATH.'plugin/haya_blog_tags/app/tag/route/'.$action.'.php';
if (!file_exists($action_file)) {
    message(-1, jump('页面错误'));
}

$tablepre = $db->tablepre;
$view_path = APP_PATH.'plugin/haya_blog_tags/app/tag/view/htm';

include _include($action_file);

