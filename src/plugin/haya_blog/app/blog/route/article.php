<?php

/**
 * 文章
 *
 * @author deatil
 * @create 2020-5-22
 */

!defined('DEBUG') and exit('Access Denied.');

// hook plugin_haya_blog_route_article_start.php

$id = param(2, 0);
if ($id == 0) {
    message(-1, '文章ID错误');
}

$haya_blog_article = haya_blog_article_read($id);
if (empty($haya_blog_article)) {
    message(-1, '文章已被锁定或者被删除');
}

// hook plugin_haya_blog_route_article_read_after.php

$haya_blog_author = haya_blog_author_read($haya_blog_article['author_id']);

// hook plugin_haya_blog_route_article_author_read_after.php

$articlemod_group = haya_blog_config('articlemod_group');

// hook plugin_haya_blog_route_article_articlemod_group_read_after.php

if (!empty($articlemod_group) && !in_array($gid, $articlemod_group)) {

    // 锁定
    if ($haya_blog_article['is_lock'] == 1 
        && $haya_blog_author['uid'] != $uid
    ) {
        message(-1, '文章已被锁定或者被删除');
    }

    // 私有
    if ($haya_blog_article['is_private'] == 1 
        && $haya_blog_author['uid'] != $uid
    ) {
        message(-1, '文章已被锁定或者被删除');
    }
}

// hook plugin_haya_blog_route_article_articlemod_group_check_after.php

$header['title'] = $haya_blog_article['title'].' - 博客';
$header['keywords'] = $haya_blog_article['keywords'];
$header['description'] = $haya_blog_article['description'];

// hook plugin_haya_blog_route_article_header_set_after.php

// 分区
$category_info = haya_blog_category__read($haya_blog_article['category_id']);

// hook plugin_haya_blog_route_article_category_info_after.php

// 文章作者专栏信息
$author_info = haya_blog_author_info_by_uid($haya_blog_author['uid']);

// hook plugin_haya_blog_route_article_author_info_after.php

$article_count = haya_blog_article__count(array(
    'author_id' => $haya_blog_author['id'],
    'is_lock' => 0,
    'status' => 1,
));

// hook plugin_haya_blog_route_article_article_count_after.php

// 文章分类
$category_max_num = haya_blog_config('blog_author_category_max_num');
$blog_categorys = haya_blog_author_category_articles_find(array(
    'author_id' => $haya_blog_author['id'],
    'status' => 1,
), array(
    'sort' => 1, 
    'create_date' => -1, 
    'id' => 1,
), 1, $category_max_num);

// hook plugin_haya_blog_route_article_blog_categorys_after.php

// 作者最新文章
$blog_new_articles = haya_blog_article_find(array(
    'author_id' => $haya_blog_author['id'],
    'is_lock' => 0,
    'status' => 1,
), array(
    'create_date' => -1, 
    'id' => -1,
), 1, 10);

// hook plugin_haya_blog_route_article_blog_new_articles_after.php

$hide_breadcrumb = false;

// 文章访问记录
haya_blog_article_inc_views($id);

// 专栏访问记录
haya_blog_author_count_update($haya_blog_author['id']);

// hook plugin_haya_blog_route_article_count_update_after.php

include haya_blog_theme_view('article.htm');  

?>