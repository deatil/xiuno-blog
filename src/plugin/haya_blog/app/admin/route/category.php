<?php

/**
 * 博客分类
 *
 * @author deatil
 * @create 2020-4-2
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '分类 - 博客';

$action2 = param(2);
empty($action2) and $action2 = '';

if ($action2 == 'add') {
    if ($method == 'GET') {
        include _include($haya_blog_admin_view . '/category_add.htm');
    } else {
        $data = array();
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '分类标识不能为空');
        }
        
        $category = haya_blog_category_read_by_name($data['name']);
        if (!empty($category)) {
            message(-1, '分类标识已存在');
        }
        
        $data['title'] = param('title', '');
        if (empty($data['title'])) {
            message(-1, '分类名称不能为空');
        }
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        $data['cover'] = param('cover', '');
        
        $data['status'] = param('status', 0);
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        $data['create_uid'] = $uid;
        $data['create_date'] = time();
        $data['create_ip'] = $longip;
        
        $add_status = haya_blog_category__create($data);
        if ($add_status === false) {
            message(-1, '添加分类失败');
        }
        
        message(0, jump('添加分类成功', url('haya_blog-category')));
    }
} elseif ($action2 == 'edit') {
    if ($method == 'GET') {
        $id = param(3, 0);
        if ($id == 0) {
            message(-1, '分类ID错误');
        }
        
        $haya_blog_category = haya_blog_category__read($id);
        if (empty($haya_blog_category)) {
            message(-1, '分类不存在');
        }
        
        include _include($haya_blog_admin_view . '/category_edit.htm');
    } else {
        $data = array();
        
        $id = param('id', 0);
        if (empty($id)) {
            message(-1, '分类ID不能为空');
        }
        
        $category = haya_blog_category__read($id);
        if (empty($category)) {
            message(-1, '分类不存在');
        }
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '分类标识不能为空');
        }
        
        $category_check = haya_blog_category_read_by_name($data['name']);
        if (!empty($category_check) && $category_check['id'] != $id) {
            message(-1, '分类标识已存在');
        }
        
        $data['title'] = param('title', '');
        if (empty($data['title'])) {
            message(-1, '分类名称不能为空');
        }
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        
        if (!empty($_FILES["cover"]["tmp_name"])) {
            $attach = haya_blog_attach_upload($_FILES["cover"]);
            if ($attach === false) {
                message(-1, '分类logo上传失败');
            }
            
            $cover = $attach['filename'];
            $data['cover'] = $cover;
        }
        
        $data['sort'] = param('sort', 100);
        
        $data['status'] = param('status', 0);
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        
        $status = haya_blog_category__update($id, $data);
        if ($status === false) {
            message(-1, '更新分类失败');
        }
        
        if (isset($attach) && !empty($attach)) {
            haya_blog_attach__update($attach['id'], array(
                'use_id' => md5('haya-blog-category-'.$id),
            ));
        }
        
        message(0, jump('更新分类成功', url('haya_blog-category')));
    }
} elseif ($action2 == 'delete') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '分类ID不能为空');
    }
    
    $category = haya_blog_category__read($id);
    if (empty($category)) {
        message(-1, '分类不存在');
    }
    
    $status = haya_blog_category__delete($id);
    
    if ($status === false) {
        message(-1, '删除分类失败');
    }
    
    message(0, jump('删除分类成功', url('haya_blog-category')));
} else {
    $pagesize = 10;
    $keyword  = trim(xn_urldecode(param(2)));
    $page     = param(3, 1);
    
    $where = array();
    if (!empty($keyword)) {
        $where['title'] = array('LIKE' => $keyword);
    }
    
    $total = haya_blog_category__count($where);
    $haya_blog_categorys = haya_blog_category_find($where, array(
        'sort' => 1, 
        'create_date' => -1, 
        'id' => 1,
    ), $page, $pagesize);
    
    $pagination = pagination(url("haya_blog-category-{$keyword}-{page}"), $total, $page, $pagesize);

    include _include($haya_blog_admin_view . '/category_index.htm');
}    

?>