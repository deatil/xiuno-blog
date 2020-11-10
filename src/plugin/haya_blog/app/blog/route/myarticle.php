<?php

/**
 * 文章
 *
 * @author deatil
 * @create 2020-6-21
 */

!defined('DEBUG') and exit('Access Denied.');

user_login_check();
$haya_blog_author = haya_blog_author_read_by_uid($uid);

$action2 = param(2);
empty($action2) and $action2 = '';

if ($action2 == 'create') {
    
    $header['title'] = '创建文章 - 博客';
    
    // hook plugin_haya_blog_myarticle_create_start.php
    
    if ($method == 'GET') {
        // hook plugin_haya_blog_myarticle_create_get_start.php
        
        $haya_blog_category_pagesize = 25;
        $haya_blog_category = haya_blog_category__find(array(
            'status' => 1,
        ), array(
            'sort' => 1,
            'id' => -1,
        ), 1, $haya_blog_category_pagesize);
        
        $haya_blog_author_category_pagesize = haya_blog_config('blog_author_category_max_num', 10);
        $haya_blog_author_category = haya_blog_author_category__find(array(
            'author_id' => $haya_blog_author['id'],
            'status' => 1,
        ), array(
            'sort' => 1,
            'id' => -1,
        ), 1, $haya_blog_author_category_pagesize);
        
        // hook plugin_haya_blog_myarticle_create_get_end.php
        
        include haya_blog_theme_view('myarticle_create.htm');
    } else {
        $data = array();
        $data['author_id'] = $haya_blog_author['id'];
        
        // hook plugin_haya_blog_myarticle_create_post_start.php
        
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
        
        $data['content'] = param('content', '');
        if (empty($data['content'])) {
            message(-1, '文章内容不能为空');
        }
        
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
        $data['is_author_top'] = param('is_top', 0);
        $data['is_reply'] = param('is_reply', 1);
        
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        $data['create_date'] = time();
        $data['create_ip'] = $longip;
        
        // hook plugin_haya_blog_myarticle_create_post_create_before.php
        
        $article_id = haya_blog_article__create($data);
        if ($article_id === false) {
            message(-1, jump('创建文章失败'));
        }
        
        // hook plugin_haya_blog_myarticle_create_post_end.php
        
        message(0, jump('创建文章成功', haya_blog_url('myarticle')));
    }
} elseif ($action2 == 'update') {
    $header['title'] = '编辑文章 - 博客';
    
    // hook plugin_haya_blog_myarticle_update_start.php
    
    if ($method == 'GET') {
        // hook plugin_haya_blog_myarticle_update_get_start.php
        
        $id = param(3, 0);
        if ($id == 0) {
            message(-1, '文章ID错误');
        }
        
        $haya_blog_article = haya_blog_article__read($id);
        if (empty($haya_blog_article)) {
            message(-1, '文章不存在');
        }
        
        if ($haya_blog_article['author_id'] != $haya_blog_author['id']) {
            message(-1, '你不能修改该文章');
        }
        
        $haya_blog_category_pagesize = 25;
        $haya_blog_category = haya_blog_category__find(array(
            'status' => 1,
        ), array(
            'sort' => 1,
            'id' => -1,
        ), 1, $haya_blog_category_pagesize);
        
        $haya_blog_author_category_pagesize = haya_blog_config('blog_author_category_max_num', 10);
        $haya_blog_author_category = haya_blog_author_category__find(array(
            'author_id' => $haya_blog_author['id'],
            'status' => 1,
        ), array(
            'sort' => 1,
            'id' => -1,
        ), 1, $haya_blog_author_category_pagesize);
        
        // hook plugin_haya_blog_myarticle_update_get_end.php
        
        include haya_blog_theme_view('myarticle_update.htm');
    } else {
        $data = array();
        
        // hook plugin_haya_blog_myarticle_update_post_start.php
        
        $id = param('id', 0);
        if (empty($id)) {
            message(-1, '文章ID不能为空');
        }
        
        $haya_blog_article = haya_blog_article__read($id);
        if (empty($haya_blog_article)) {
            message(-1, '文章不存在');
        }
        
        if ($haya_blog_article['author_id'] != $haya_blog_author['id']) {
            message(-1, '你不能修改该文章');
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
        
        $data['content'] = param('content', '');
        if (empty($data['content'])) {
            message(-1, '文章内容不能为空');
        }
        
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
        $data['is_author_top'] = param('is_top', 0);
        $data['is_reply'] = param('is_reply', 1);
        
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        
        // hook plugin_haya_blog_myarticle_update_post_update_before.php
        
        $add_status = haya_blog_article__update($id, $data);
        if ($add_status === false) {
            message(-1, jump('更新文章失败'));
        }
        
        // hook plugin_haya_blog_myarticle_update_post_end.php
        
        message(0, jump('更新文章成功', haya_blog_url('article-'.$id)));
    }
    
} elseif ($action2 == 'delete') {
    $header['title'] = '删除文章 - 博客';
    
    // hook plugin_haya_blog_myarticle_delete_start.php
    
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '文章ID不能为空');
    }
    
    $haya_blog_article = haya_blog_article__read($id);
    if (empty($haya_blog_article)) {
        message(-1, '文章不存在');
    }
    
    if ($haya_blog_article['author_id'] != $haya_blog_author['id']) {
        message(-1, '文章错误');
    }
    
    $status = haya_blog_article_delete($id);
    
    if ($status === false) {
        message(-1, '删除文章失败');
    }
    
    // hook plugin_haya_blog_myarticle_delete_end.php
    
    message(0, jump('删除文章成功', haya_blog_url('myarticle')));
} else {
    $pagesize = 10;
    $keyword  = trim(xn_urldecode(param(3)));
    $page = param(4, 1);
    
    $where = array(
        'author_id' => $haya_blog_author['id'],
        'status' => 1,
    );
    
    if (!empty($keyword)) {
        $where['title'] = array('LIKE' => $keyword);
    }
    
    // hook plugin_haya_blog_myarticle_index_start.php
    
    $total = haya_blog_article__count($where);
    $articles = haya_blog_article_find($where, array(
        'create_date' => -1, 
        'id' => -1,
    ), $page, $pagesize);
    
    $pagination = pagination(haya_blog_url("myarticle-{$keyword}-{page}"), $total, $page, $pagesize);
    
    $header['title'] = '第'.$page.'页 - 我的文章 - 博客';
    
    // hook plugin_haya_blog_myarticle_index_end.php
    
    include haya_blog_theme_view('myarticle_index.htm');
}

?>