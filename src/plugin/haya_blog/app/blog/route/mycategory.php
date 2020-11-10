<?php

/**
 * 博客申请
 *
 * @author deatil
 * @create 2020-5-22
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '我的分类 - 博客';

user_login_check();

$haya_blog_check_author = haya_blog_check_author($uid);
if (!$haya_blog_check_author) {
    message(-1, jump('你还没有申请专栏', url('index')));
}

$haya_blog_author = haya_blog_author_read_by_uid($uid);

$action2 = param(2);
empty($action2) and $action2 = '';

if ($action2 == 'create') {
    if ($method == 'GET') {
        include haya_blog_theme_view('mycategory_create.htm');
    } else {
        $category_total = haya_blog_author_category__count(array(
            'author_id' => $haya_blog_author['id'],
            'status' => 1,
        ));
        $category_max_num = haya_blog_config('blog_author_category_max_num');
        if ($category_total >= $category_max_num) {
            message(-1, '你的分类数已达最大，不能再添加了');
        }
        
        $data = array();
        
        $data['author_id'] = $haya_blog_author['id'];
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '分类英文名称不能为空');
        }
        
        $author_category = haya_blog_author_category_read_by_name($data['name']);
        if (!empty($author_category)) {
            message(-1, '分类英文名称已经存在');
        }
        
        $data['title'] = param('title', '');
        if (empty($data['title'])) {
            message(-1, '分类名称不能为空');
        }
        
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        
        $data['sort'] = param('sort', 100);
        $data['is_open'] = param('is_open', 1);
        
        $data['status'] = 1;
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        $data['create_date'] = time();
        $data['create_ip'] = $longip;
        
        $add_status = haya_blog_author_category__create($data);
        if ($add_status === false) {
            message(-1, jump('添加分类失败'));
        }
        
        message(0, jump('添加分类成功', haya_blog_url('mycategory')));
    }
} elseif ($action2 == 'update') {
    if ($method == 'GET') {
        $id = param(3, 0);
        if ($id == 0) {
            message(-1, '分类ID错误');
        }
        
        $author_category = haya_blog_author_category__read($id);
        if (empty($author_category)) {
            message(-1, '分类不存在');
        }
        
        if ($author_category['author_id'] != $haya_blog_author['id']) {
            message(-1, '分类错误');
        }
        
        include haya_blog_theme_view('mycategory_update.htm');
    } else {
        $data = array();
        
        $id = param('id', 0);
        if (empty($id)) {
            message(-1, '分类ID不能为空');
        }
        
        $author_category = haya_blog_author_category__read($id);
        if (empty($author_category)) {
            message(-1, '分类不存在');
        }
        
        if ($author_category['author_id'] != $haya_blog_author['id']) {
            message(-1, '分类错误');
        }
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '分类英文名称不能为空');
        }
        
        $author_category_check = haya_blog_author_category_read_by_name($data['name']);
        if (!empty($author_category_check) && $author_category_check['id'] != $id) {
            message(-1, '分类英文名称已经存在');
        }
        
        $data['title'] = param('title', '');
        if (empty($data['title'])) {
            message(-1, '分类名称不能为空');
        }
        
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        
        $data['sort'] = param('sort', 100);
        $data['is_open'] = param('is_open', 1);
        
        $data['status'] = 1;
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        
        $status = haya_blog_author_category__update($id, $data);
        if ($status === false) {
            message(-1, '分类修改失败');
        }
        
        message(0, jump('分类修改成功', haya_blog_url('mycategory')));
    }
} elseif ($action2 == 'delete') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '分类ID不能为空');
    }
    
    $author_category = haya_blog_author_category__read($id);
    if (empty($author_category)) {
        message(-1, '分类不存在');
    }
    
    if ($author_category['author_id'] != $haya_blog_author['id']) {
        message(-1, '分类错误');
    }
    
    $status = haya_blog_author_category__delete($id);
    
    if ($status === false) {
        message(-1, '删除分类失败');
    }
    
    message(0, jump('删除分类成功', haya_blog_url('mycategory')));
} else {
    $pagesize = 10;
    $page = param(2, 1);
    
    $where = array(
        'author_id' => $haya_blog_author['id'],
        'status' => 1,
    );
    
    $total = haya_blog_author_category__count($where);
    $categorys = haya_blog_author_category_find($where, array(
        'sort' => 1, 
        'create_date' => -1, 
        'id' => 1,
    ), $page, $pagesize);
    
    $pagination = pagination(haya_blog_url("mycategory-{page}"), $total, $page, $pagesize);

    include haya_blog_theme_view('mycategory_index.htm');
}

?>