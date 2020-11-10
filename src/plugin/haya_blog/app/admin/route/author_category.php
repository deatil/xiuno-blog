<?php

/**
 * 博客专栏分类
 *
 * @author_category deatil
 * @create 2020-5-1
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '专栏分类 - 博客';

$action2 = param(2);
empty($action2) and $action2 = '';

if ($action2 == 'add') {
    if ($method == 'GET') {
        include _include($haya_blog_admin_view . '/author_category_add.htm');
    } else {
        $data = array();
        
        $data['author_id'] = param('author_id', '');
        if (empty($data['author_id'])) {
            message(-1, '专栏ID不能为空');
        }
        
        $blog_author = haya_blog_author__read($data['author_id']);
        if (empty($blog_author)) {
            message(-1, '专栏不存在');
        }
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '专栏分类英文名称不能为空');
        }
        
        $author_category = haya_blog_author_category_read_by_name($data['name']);
        if (!empty($author_category)) {
            message(-1, '专栏分类英文名称已经存在');
        }
        
        $data['title'] = param('title', '');
        if (empty($data['title'])) {
            message(-1, '专栏分类名称不能为空');
        }
        
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        $data['cover'] = param('cover', '');
        
        $data['is_lock'] = param('is_lock', 0);
        $data['is_open'] = param('is_open', 1);
        
        $data['status'] = param('status', 0);
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        $data['create_date'] = time();
        $data['create_ip'] = $longip;
        
        $add_status = haya_blog_author_category__create($data);
        if ($add_status === false) {
            message(-1, jump('添加专栏分类失败'));
        }
        
        message(0, jump('添加专栏分类成功', url('haya_blog-author_category')));
    }
} elseif ($action2 == 'edit') {
    if ($method == 'GET') {
        $id = param(3, 0);
        if ($id == 0) {
            message(-1, '专栏分类ID错误');
        }
        
        $haya_blog_author_category = haya_blog_author_category__read($id);
        if (empty($haya_blog_author_category)) {
            message(-1, '专栏分类不存在');
        }
        
        $haya_blog_author = haya_blog_author__read($haya_blog_author_category['author_id']);
        if (empty($haya_blog_author)) {
            message(-1, '专栏不存在');
        }
        
        $haya_blog_author_user = user_read($haya_blog_author['uid']);
        if (empty($haya_blog_author_user)) {
            message(-1, '专栏用户不存在');
        }
        
        include _include($haya_blog_admin_view . '/author_category_edit.htm');
    } else {
        $data = array();
        
        $id = param('id', 0);
        if (empty($id)) {
            message(-1, '专栏ID不能为空');
        }
        
        $author_category = haya_blog_author_category__read($id);
        if (empty($author_category)) {
            message(-1, '专栏分类不存在');
        }
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '专栏分类英文名称不能为空');
        }
        
        $author_category_check = haya_blog_author_category_read_by_name($data['name']);
        if (!empty($author_category_check) && $author_category_check['id'] != $id) {
            message(-1, '专栏分类英文名称已经存在');
        }
        
        $data['title'] = param('title', '');
        if (empty($data['title'])) {
            message(-1, '专栏分类名称不能为空');
        }
        
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        $data['sort'] = param('sort', 100);
        
        $data['is_lock'] = param('is_lock', 0);
        $data['is_open'] = param('is_open', 1);
        
        $data['status'] = param('status', 0);
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        
        $status = haya_blog_author_category__update($id, $data);
        if ($status === false) {
            message(-1, '更新专栏分类失败');
        }
        
        message(0, jump('更新专栏分类成功', url('haya_blog-author_category')));
    }
} elseif ($action2 == 'delete') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '专栏分类ID不能为空');
    }
    
    $author_category = haya_blog_author_category__read($id);
    if (empty($author_category)) {
        message(-1, '专栏分类不存在');
    }
    
    $status = haya_blog_author_category__delete($id);
    
    if ($status === false) {
        message(-1, '删除专栏分类失败');
    }
    
    message(0, jump('删除专栏分类成功', url('haya_blog-author_category')));
} else {
    $pagesize = 10;
    $srchtype = param(2);
    $keyword  = trim(xn_urldecode(param(3)));
    $page     = param(4, 1);
    
    if (!in_array($srchtype, array(
        'author_id', 
        'name', 
        'title', 
    ))) {
        $srchtype = '';
    }
    
    $where = array();
    if (!empty($keyword)) {
        $where[$srchtype] = array('LIKE' => $keyword);
    }
    
    $total = haya_blog_author_category__count($where);
    $haya_blog_author_categorys = haya_blog_author_category_find($where, array(
        'sort' => 1, 
        'create_date' => -1, 
        'id' => 1,
    ), $page, $pagesize);
    
    $pagination = pagination(url("haya_blog-author_category-{$srchtype}-{$keyword}-{page}"), $total, $page, $pagesize);

    include _include($haya_blog_admin_view . '/author_category_index.htm');
}    

?>