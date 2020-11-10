<?php

/**
 * 标签
 *
 * @author deatil
 * @create 2020-7-12
 */

!defined('DEBUG') and exit('Access Denied.');

if ($method != 'GET') {
    message(-1, jump('访问错误', url('index')));
}

$name = trim(xn_urldecode(param('name')));

$tag_info = haya_blog_tag_read_by_name($name);
if (empty($tag_info)) {
    message(-1, '标签不存在');
}

$header['title'] = $tag_info['name'] . ' - 标签 - 博客';

// 文章列表
$haya_blog_tags_config = kv_get('haya_blog_tags');
$pagesize = $haya_blog_tags_config['article_pagesize'];
$page = param('page', 1);

// 默认条件
$where = array(
    'tr.tag_id' => $tag_info['id'],
    'a.status' => 1,
);
$orderby = array(
    'a.id' => -1,
    'a.create_date' => -1,
);

$total = haya_blog_tag_relationship_find_articles_count($where);
$list = haya_blog_tag_relationship_find_article($where, $orderby, $page, $pagesize, '', array(
    "a.*",
    "t.name as tag_name",
));

if (!empty($list)) {
    foreach ($list as & $v) {
        $v['author'] = haya_blog_author_read($v['author_id']);
        $v['category'] = haya_blog_category__read($v['category_id']);
        $v['author_category'] = haya_blog_author_category__read($v['cid']);
    }
}

$pagination = pagination(url("haya_blog_tags-tag", array(
    'name' => $name,
    'page' => "{page}",
)), $total, $page, $pagesize);

// 最新标签
$new_tags = haya_blog_tag__find(array(
    'status' => 1,
), array(
    'create_date' => -1, 
    'id' => -1,
), 1, 10);

$hide_breadcrumb = false;

include _include($view_path . '/tag.htm');

?>