<?php

/**
 * 博客标签
 *
 * @author deatil
 * @create 2020-7-9
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '标签 - 博客标签';

$action2 = param(2);
empty($action2) and $action2 = '';

if ($action2 == 'create') {
    if ($method == 'GET') {
        include _include($admin_view . '/tag_create.htm');
    } else {
        $data = array();
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '标签名称不能为空');
        }
        
        $name_info = haya_blog_tag_read_by_name($data['name']);
        if (!empty($name_info)) {
            message(-1, '标签已经存在');
        }
        
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        
        $data['sort'] = 100;
        $data['status'] = param('status', 0);

        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        $data['create_date'] = time();
        $data['create_ip'] = $longip;
        
        $create_id = haya_blog_tag__create($data);
        if ($create_id === false) {
            message(-1, '添加标签失败');
        }
        
        message(0, jump('添加标签成功', url('haya_blog_tags-tag')));
    }
} elseif ($action2 == 'update') {
    if ($method == 'GET') {
        $id = param(3, 0);
        if ($id == 0) {
            message(-1, '标签ID错误');
        }
        
        $tag = haya_blog_tag__read($id);
        if (empty($tag)) {
            message(-1, '标签不存在');
        }
        
        include _include($admin_view . '/tag_update.htm');
    } else {
        $data = array();
        
        $id = param('id', 0);
        if (empty($id)) {
            message(-1, '标签ID不能为空');
        }
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '标签名称不能为空');
        }
        
        $name_info = haya_blog_tag_read_by_name($data['name']);
        if (!empty($name_info) && $name_info['id'] != $id) {
            message(-1, '标签已经存在');
        }
        
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        
        $data['sort'] = param('sort', 100);
        
        $data['is_recommend'] = param('is_recommend', 0);
        
        $data['status'] = param('status', 0);
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        
        $status = haya_blog_tag__update($id, $data);
        if ($status === false) {
            message(-1, '更新标签失败');
        }
        
        message(0, jump('更新标签成功', url('haya_blog_tags-tag')));
    }
} elseif ($action2 == 'delete') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '标签ID不能为空');
    }
    
    $info = haya_blog_tag__read($id);
    if (empty($info)) {
        message(-1, '标签不存在');
    }
    
    $status = haya_blog_tag__delete($id);
    
    if ($status === false) {
        message(-1, '删除标签失败');
    }
    
    message(0, jump('删除标签成功', url('haya_blog_tags-tag')));
} else {
    $pagesize = 10;
    $keyword  = trim(xn_urldecode(param(2)));
    $page     = param(3, 1);
    
    $where = array();
    if (!empty($keyword)) {
        $where['name'] = array('LIKE' => $keyword);
    }
    
    $total = haya_blog_tag__count($where);
    $list = haya_blog_tag__find($where, array(
        'sort' => 1, 
        'create_date' => -1, 
        'id' => 1,
    ), $page, $pagesize);
    
    $pagination = pagination(url("haya_blog_tags-tag-{$keyword}-{page}"), $total, $page, $pagesize);

    include _include($admin_view . '/tag_index.htm');
}    

?>