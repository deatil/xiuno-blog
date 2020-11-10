<?php

/**
 * 博客
 *
 * @author deatil
 * @create 2020-6-30
 */

!defined('DEBUG') and exit('Access Denied.');

$blog_name = param(2, '');
if (empty($blog_name)) {
    message(-1, '专栏不存在');
}

// 专栏信息
$blog_info = haya_blog_author_read_by_name($blog_name);
if (empty($blog_name)) {
    message(-1, '专栏不存在');
}

if ($blog_info['status'] != 1) {
    message(-1, '专栏不存在');
}

// 文章作者专栏信息
$author_info = haya_blog_author_info_by_uid($blog_info['uid']);

// 专栏访问记录
haya_blog_author_count_update($blog_info['id']);

// 专栏统计
$author_count = haya_blog_author_count_read_by_author_id($blog_info['id']);

// 文章分类
$category_max_num = haya_blog_config('blog_author_category_max_num');
$blog_categorys = haya_blog_author_category_articles_find(array(
    'author_id' => $blog_info['id'],
    'status' => 1,
), array(
    'sort' => 1, 
    'create_date' => -1, 
    'id' => 1,
), 1, $category_max_num);

$where = array(
    'author_id' => $blog_info['id'],
    'is_lock' => 0,
    'status' => 1,
);

$extra = array();

// 选中分类
$category_name = param(3, 'all');
if (!empty($category_name)) {
    $select_category = haya_blog_author_category_read_by_name($category_name);
    if (!empty($select_category)) {
        $where['cid'] = $select_category['id'];
    }
}

$order = param('order', 'time');
if ($order == 'hits') {
    $orderby = array(
        'is_author_top' => -1, 
        'hits' => -1, 
        'create_date' => -1, 
        'id' => -1,
    );
} else {
    $orderby = array(
        'is_author_top' => -1, 
        'create_date' => -1, 
        'id' => -1,
    );
}

// 文章
$pagesize = intval($blog_info['article_pagesize']);
$page = param(4, 1);

$total = haya_blog_article__count($where);
$blog_articles = haya_blog_article_find($where, $orderby, $page, $pagesize);

$pagination = pagination(haya_blog_url("author-{$blog_name}-{$category_name}-{page}"), $total, $page, $pagesize);

$hide_breadcrumb = false;

if (empty($category_name) || $category_name == 'all') {
    $header['title'] = $blog_info['title'] . ' - 专栏 - 博客';
    $header['keywords'] = $blog_info['keywords'];
    $header['description'] = $blog_info['description'];
} else {
    $header['title'] = $select_category['title'] . ' - ' . $blog_info['title'] . ' - 专栏 - 博客';
    $header['keywords'] = $select_category['keywords'];
    $header['description'] = $select_category['description'];
}

include haya_blog_theme_view('author.htm');

?>