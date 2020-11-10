<?php

/**
 * 博客专栏
 *
 * @author deatil
 * @create 2020-4-20
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '专栏 - 博客';

$action2 = param(2);
empty($action2) and $action2 = '';

if ($action2 == 'add') {
    if ($method == 'GET') {
        include _include($haya_blog_admin_view . '/author_add.htm');
    } else {
        $data = array();
        
        $data['uid'] = param('uid', '');
        if (empty($data['uid'])) {
            message(-1, '用户ID不能为空');
        }
        
        $author_user = user__read($data['uid']);
        if (empty($author_user)) {
            message(-1, '用户不存在');
        }
        
        $author_user = haya_blog_author_read_by_uid($data['uid']);
        if (!empty($author_user)) {
            message(-1, '用户已经有专栏');
        }
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '专栏英文名称不能为空');
        }
        
        $author = haya_blog_author_read_by_name($data['name']);
        if (!empty($author)) {
            message(-1, '专栏英文名称已存在');
        }
        
        $data['title'] = param('title', '');
        if (empty($data['title'])) {
            message(-1, '专栏名称不能为空');
        }
        
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        $data['cover'] = param('cover', '');
        
        $data['article_pagesize'] = param('article_pagesize', 10);
        
        $data['is_recommend'] = param('is_recommend', 0);
        
        $data['status'] = param('status', 0);
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        $data['create_date'] = time();
        $data['create_ip'] = $longip;
        
        $add_status = haya_blog_author__create($data);
        if ($add_status === false) {
            message(-1, '添加专栏失败');
        }
        
        message(0, jump('添加专栏成功', url('haya_blog-author')));
    }
} elseif ($action2 == 'edit') {
    if ($method == 'GET') {
        $id = param(3, 0);
        if ($id == 0) {
            message(-1, '专栏ID错误');
        }
        
        $haya_blog_author = haya_blog_author__read($id);
        if (empty($haya_blog_author)) {
            message(-1, '专栏不存在');
        }
        
        $haya_blog_author_user = user_read($haya_blog_author['uid']);
        if (empty($haya_blog_author_user)) {
            message(-1, '专栏用户不存在');
        }
        
        include _include($haya_blog_admin_view . '/author_edit.htm');
    } else {
        $data = array();
        
        $id = param('id', 0);
        if (empty($id)) {
            message(-1, '专栏ID不能为空');
        }
        
        $author = haya_blog_author__read($id);
        if (empty($author)) {
            message(-1, '专栏不存在');
        }
        
        $data['uid'] = param('uid', '');
        if (empty($data['uid'])) {
            message(-1, '用户ID不能为空');
        }
        
        $author_user = user__read($data['uid']);
        if (empty($author_user)) {
            message(-1, '用户不存在');
        }
        
        $data['name'] = param('name', '');
        if (empty($data['name'])) {
            message(-1, '专栏英文名称不能为空');
        }
        
        $author_check = haya_blog_author_read_by_name($data['name']);
        if (!empty($author_check) && $author_check['id'] != $id) {
            message(-1, '专栏信息已存在');
        }
        
        $data['title'] = param('title', '');
        if (empty($data['title'])) {
            message(-1, '专栏名称不能为空');
        }
        
        $data['keywords'] = param('keywords', '');
        $data['description'] = param('description', '');
        
        if (!empty($_FILES["cover"]["tmp_name"])) {
            $attach = haya_blog_attach_upload($_FILES["cover"]);
            if ($attach === false) {
                message(-1, '专栏logo上传失败');
            }
            
            $cover = $attach['filename'];
            $data['cover'] = $cover;
        }
        
        $data['article_pagesize'] = param('article_pagesize', 1);
        
        $data['is_recommend'] = param('is_recommend', 0);
        
        $data['status'] = param('status', 0);
        $data['update_date'] = time();
        $data['update_ip'] = $longip;
        
        $status = haya_blog_author__update($id, $data);
        if ($status === false) {
            message(-1, '更新专栏失败');
        }
        
        if (isset($attach) && !empty($attach)) {
            haya_blog_attach__update($attach['id'], array(
                'use_id' => md5('haya-blog-author-'.$id),
            ));
        }
        
        message(0, jump('更新专栏成功', url('haya_blog-author')));
    }
} elseif ($action2 == 'delete') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '专栏ID不能为空');
    }
    
    $author = haya_blog_author__read($id);
    if (empty($author)) {
        message(-1, '专栏不存在');
    }
    
    $status = haya_blog_author__delete($id);
    
    if ($status === false) {
        message(-1, '删除专栏失败');
    }
    
    message(0, jump('删除专栏成功', url('haya_blog-author')));
} else {
    $pagesize = 10;
    $keyword  = trim(xn_urldecode(param(2)));
    $page     = param(3, 1);
    
    $where = array();
    if (!empty($keyword)) {
        $where['name'] = array('LIKE' => $keyword);
    }
    
    $total = haya_blog_author__count($where);
    $haya_blog_authors = haya_blog_author_find($where, array(
        'create_date' => -1, 
        'id' => -1,
    ), $page, $pagesize);
    
    $pagination = pagination(url("haya_blog-author-{$keyword}-{page}"), $total, $page, $pagesize);

    include _include($haya_blog_admin_view . '/author_index.htm');
}    

?>