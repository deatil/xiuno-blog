<?php

/**
 * 标签首页
 *
 * @author deatil
 * @create 2020-7-13
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '标签 - 博客';

if ($method != 'GET') {
    message(-1, jump('访问错误', url('index')));
}

// 默认条件
$where = array(
    'status' => 1,
);

$extra = array();

$orderby = array(
    'create_date' => -1, 
    'id' => -1,
);

// 标签
$haya_blog_tags_config = kv_get('haya_blog_tags');
$pagesize = $haya_blog_tags_config['tag_pagesize'];
$page = param(3, 1);

$total = haya_blog_tag__count($where);
$list = haya_blog_tag__find($where, $orderby, $page, $pagesize);

$pagination = pagination(url("haya_blog_tags-index-{page}"), $total, $page, $pagesize);

// 最新标签
$new_tags = haya_blog_tag__find(array(
    'status' => 1,
), array(
    'create_date' => -1, 
    'id' => -1,
), 1, 10);

$hide_breadcrumb = false;

include _include($view_path . '/index.htm');

?>