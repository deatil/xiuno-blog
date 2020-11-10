<?php

/**
 * 博客路由
 * 
 * @author deatil
 * @create 2020-5-22
 */
 
defined('DEBUG') OR exit('Forbidden');

$haya_blog_open_blog = haya_blog_config('open_blog');
if ($haya_blog_open_blog != 1) {
    message(-1, haya_blog_config('close_blog_tip', '访问错误'));
}

// 动作
$action = param(1);
empty($action) and $action = 'index';

$actions = array(
    'apply',
    'mysetting',
    'mycategory',
    'myarticle',
    'mycomment',
    
    'index',
    'author',
    'article',
    'comment',
    
    'articlemod',
);

if (!in_array($action, $actions)) {
    message(-1, jump('访问错误'));
}

$haya_blog_action_file = APP_PATH.'plugin/haya_blog/app/blog/route/'.$action.'.php';
if (!file_exists($haya_blog_action_file)) {
    message(-1, jump('页面错误'));
}

$tablepre = $db->tablepre;

include _include($haya_blog_action_file);

