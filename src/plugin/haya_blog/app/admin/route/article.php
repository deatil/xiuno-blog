<?php

/**
 * 文章
 *
 * @author deatil
 * @create 2020-6-21
 */

!defined('DEBUG') and exit('Access Denied.');

// hook plugin_haya_blog_admin_article_start.php

$action2 = param(2);
empty($action2) and $action2 = '';

// hook plugin_haya_blog_admin_article_action2_after.php

if ($action2 == 'lock') {
    $header['title'] = '锁定文章 - 博客';
    
    // hook plugin_haya_blog_admin_article_lock_start.php
    
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '文章ID不能为空');
    }
    
    // hook plugin_haya_blog_admin_article_lock_read_before.php
    
    $haya_blog_article = haya_blog_article__read($id);
    if (empty($haya_blog_article)) {
        message(-1, '文章不存在');
    }
    
    // hook plugin_haya_blog_admin_article_lock_update_before.php
    
    $status = haya_blog_article__update($id, array(
        'is_lock' => 1,
    ));
    
    if ($status === false) {
        message(-1, '锁定文章失败');
    }
    
    // hook plugin_haya_blog_admin_article_lock_end.php
    
    message(0, jump('锁定文章成功', url('haya_blog-article')));
} elseif ($action2 == 'unlock') {
    $header['title'] = '解锁锁定文章 - 博客';
    
    // hook plugin_haya_blog_admin_article_unlock_start.php
    
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '文章ID不能为空');
    }
    
    // hook plugin_haya_blog_admin_article_unlock_read_before.php
    
    $haya_blog_article = haya_blog_article__read($id);
    if (empty($haya_blog_article)) {
        message(-1, '文章不存在');
    }
    
    // hook plugin_haya_blog_admin_article_unlock_update_before.php
    
    $status = haya_blog_article__update($id, array(
        'is_lock' => 0,
    ));
    
    if ($status === false) {
        message(-1, '解锁锁定文章失败');
    }
    
    // hook plugin_haya_blog_admin_article_unlock_end.php
    
    message(0, jump('解锁锁定文章成功', url('haya_blog-article')));
} elseif ($action2 == 'unallow') {
    $header['title'] = '禁用文章 - 博客';
    
    // hook plugin_haya_blog_admin_article_unallow_start.php
    
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '文章ID不能为空');
    }
    
    // hook plugin_haya_blog_admin_article_unallow_read_before.php
    
    $haya_blog_article = haya_blog_article__read($id);
    if (empty($haya_blog_article)) {
        message(-1, '文章不存在');
    }
    
    // hook plugin_haya_blog_admin_article_unallow_update_before.php
    
    $status = haya_blog_article__update($id, array(
        'status' => 0,
    ));
    
    if ($status === false) {
        message(-1, '禁用文章失败');
    }
    
    // hook plugin_haya_blog_admin_article_unallow_end.php
    
    message(0, jump('禁用文章成功', url('haya_blog-article')));
} elseif ($action2 == 'allow') {
    $header['title'] = '解禁文章 - 博客';
    
    // hook plugin_haya_blog_admin_article_allow_start.php
    
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '文章ID不能为空');
    }
    
    // hook plugin_haya_blog_admin_article_allow_read_before.php
    
    $haya_blog_article = haya_blog_article__read($id);
    if (empty($haya_blog_article)) {
        message(-1, '文章不存在');
    }
    
    // hook plugin_haya_blog_admin_article_allow_update_before.php
    
    $status = haya_blog_article__update($id, array(
        'status' => 1,
    ));
    
    if ($status === false) {
        message(-1, '解禁文章失败');
    }
    
    // hook plugin_haya_blog_admin_article_allow_end.php
    
    message(0, jump('解禁文章成功', url('haya_blog-article')));
} elseif ($action2 == 'update') {
    $header['title'] = '编辑文章 - 博客';
    
    // hook plugin_haya_blog_admin_article_update_start.php
    
    if ($method == 'GET') {
        // hook plugin_haya_blog_admin_article_update_get_start.php
    
        $id = param(3, 0);
        if ($id == 0) {
            message(-1, '文章ID错误');
        }
        
        // hook plugin_haya_blog_admin_article_update_get_read_before.php
        
        $article = haya_blog_article__read($id);
        if (empty($article)) {
            message(-1, '文章不存在');
        }
        
        $pagesize = 25;
        
        // hook plugin_haya_blog_admin_article_update_get_categorys_before.php
        
        $categorys = haya_blog_category__find(array(
            'status' => 1,
        ), array(
            'sort' => 1,
            'id' => -1,
        ), 1, $pagesize);
        
        $author_category_pagesize = haya_blog_config('blog_author_category_max_num', 10);
        
        // hook plugin_haya_blog_admin_article_update_get_author_category_before.php
        
        $author_category = haya_blog_author_category__find(array(
            'status' => 1,
        ), array(
            'sort' => 1,
            'id' => -1,
        ), 1, $author_category_pagesize);
        
        // hook plugin_haya_blog_admin_article_update_get_end.php
        
        include _include($haya_blog_admin_view . '/article_update.htm');
    } else {
        $data = array();
        
        // hook plugin_haya_blog_admin_article_update_post_start.php
        
        $id = param('id', 0);
        if (empty($id)) {
            message(-1, '文章ID不能为空');
        }
        
        // hook plugin_haya_blog_admin_article_update_post_read_before.php
        
        $article = haya_blog_article__read($id);
        if (empty($article)) {
            message(-1, '文章不存在');
        }
        
        $data['category_id'] = param('category_id', '');
        if (empty($data['category_id'])) {
            message(-1, '分类不能为空');
        }
        
        $data['cid'] = param('cid', '');
        if (empty($data['cid'])) {
            message(-1, '文章分类不能为空');
        }
        
        $data['title'] = param('title', '');
        if (empty($data['title'])) {
            message(-1, '文章名称不能为空');
        }
        
        // $data['content'] = param('content', '');
        
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        
        $data['is_reprinted'] = param('is_reprinted', 0);
        $data['reprinted_from'] = param('reprinted_from', '');
        if ($data['is_reprinted'] == 1) {
            if (empty($data['reprinted_from'])) {
                message(-1, '文章转载来源不能为空');
            }
        }
        
        $data['is_private'] = param('is_private', 0);
        $data['is_author_top'] = param('is_author_top', 0);
        $data['is_reply'] = param('is_reply', 0);
        
        $data['is_top'] = param('is_top', 0);
        $data['is_recommend'] = param('is_recommend', 0);
        $data['is_lock'] = param('is_lock', 0);
        $data['status'] = param('status', 0);
        
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        
        // hook plugin_haya_blog_admin_article_update_post_update_before.php
        
        $add_status = haya_blog_article__update($id, $data);
        if ($add_status === false) {
            message(-1, jump('更新文章失败'));
        }
        
        // hook plugin_haya_blog_admin_article_update_post_end.php
        
        message(0, jump('更新文章成功', url('haya_blog-article')));
    }
} elseif ($action2 == 'delete') {
    $header['title'] = '删除文章 - 博客';
    
    // hook plugin_haya_blog_admin_article_delete_end.php
    
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '文章ID不能为空');
    }
    
    // hook plugin_haya_blog_admin_article_delete_read_before.php
    
    $haya_blog_article = haya_blog_article__read($id);
    if (empty($haya_blog_article)) {
        message(-1, '文章不存在');
    }
    
    // hook plugin_haya_blog_admin_article_delete_delete_before.php
    
    $status = haya_blog_article_delete($id);
    
    if ($status === false) {
        message(-1, '删除文章失败');
    }
    
    // hook plugin_haya_blog_admin_article_delete_end.php
    
    message(0, jump('删除文章成功', url('haya_blog-article')));
} else {
    $pagesize = 10;
    $keyword  = trim(xn_urldecode(param(2)));
    $lock = param(3, '');
    $status = param(4, '');
    $page = param(5, 1);
    
    // hook plugin_haya_blog_admin_article_list_start.php
    
    $where = array();
    if (!empty($keyword)) {
        $where['title'] = array('LIKE' => $keyword);
    }
    
    if ($lock == 'unlock') {
        $where['is_lock'] = 0;
    } elseif ($lock == 'lock') {
        $where['is_lock'] = 1;
    }
    
    if ($status == 'undelete') {
        $where['status'] = 1;
    } elseif ($status == 'delete') {
        $where['status'] = 0;
    }
    
    // hook plugin_haya_blog_admin_article_list_count_before.php
    
    $total = haya_blog_article__count($where);
    
    // hook plugin_haya_blog_admin_article_list_find_before.php
    
    $articles = haya_blog_article_find($where, array(
        'create_date' => -1, 
        'id' => -1,
    ), $page, $pagesize);
    
    // hook plugin_haya_blog_admin_article_list_pagination_before.php

    $pagination = pagination(url("haya_blog-article-{$keyword}-{$lock}-{$status}-{page}"), $total, $page, $pagesize);
    
    $header['title'] = '文章 - 博客';
    
    // hook plugin_haya_blog_admin_article_list_end.php
    
    include _include($haya_blog_admin_view . '/article_index.htm');
}

?>