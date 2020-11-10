<?php

/**
 * 博客首页
 *
 * @author deatil
 * @create 2020-5-22
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '博客';

if ($method != 'GET') {
    message(-1, jump('访问错误', haya_blog_url()));
}

// 文章分区
$categorys = haya_blog_category_articles_find(array(
    'status' => 1,
), array(
    'sort' => 1, 
    'create_date' => -1, 
    'id' => 1,
), 1, 10);

// 默认条件
$where = array(
    'is_lock' => 0,
    'status' => 1,
);

$extra = array();

// 选中分类
$category_name = param(2, 'all');
if (!empty($category_name)) {
    $select_category = haya_blog_category_read_by_name($category_name);
    if (!empty($select_category)) {
        $where['cid'] = $select_category['id'];
    }
}

$order = param('order', 'time');
if ($order == 'hits') {
    $orderby = array(
        'is_top' => -1, 
        'hits' => -1, 
        'create_date' => -1, 
        'id' => -1,
    );
} else {
    $orderby = array(
        'is_top' => -1, 
        'create_date' => -1, 
        'id' => -1,
    );
}

// 文章
$pagesize = haya_blog_config('blog_article_pagesize', 10);
$page = param(3, 1);

$total = haya_blog_article__count($where);
$articles = haya_blog_article_find($where, $orderby, $page, $pagesize);

$pagination = pagination(haya_blog_url("author-{$category_name}-{page}"), $total, $page, $pagesize);

$hide_breadcrumb = false;

// 推荐文章
$recommend_articles = haya_blog_article_find(array(
    'is_recommend' => 1,
    'is_lock' => 0,
    'status' => 1,
), array(
    'create_date' => -1, 
    'id' => -1,
), 1, 10);

include haya_blog_theme_view('index.htm');

?>