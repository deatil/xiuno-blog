<?php

/**
 * 附件
 *
 * @author deatil
 * @create 2020-7-4
 */

!defined('DEBUG') and exit('Access Denied.');

// hook plugin_haya_blog_admin_attach_start.php

$action2 = param(2);
empty($action2) and $action2 = '';

// hook plugin_haya_blog_admin_attach_action_before.php

if ($action2 == 'detail') {
    $header['title'] = '详情 - 附件 - 博客';
    
    // hook plugin_haya_blog_admin_attach_detail_start.php
    
    if ($method != 'GET') {
        message(-1, '访问错误');
    }
    
    $id = param(3, 0);
    if ($id == 0) {
        message(-1, '附件ID错误');
    }
    
    // hook plugin_haya_blog_admin_attach_detail_read_before.php

    $attach = haya_blog_attach__read($id);
    if (empty($attach)) {
        message(-1, '附件不存在');
    }
    
    // hook plugin_haya_blog_admin_attach_detail_user_read_before.php
    
    $attach_user = user__read($attach['uid']);
    
    $filetypes = include APP_PATH.'conf/attach.conf.php';
    
    // hook plugin_haya_blog_admin_attach_detail_end.php
    
    include _include($haya_blog_admin_view . '/attach_detail.htm');
} elseif ($action2 == 'delete') {
    $header['title'] = '删除 - 附件 - 博客';
    
    // hook plugin_haya_blog_admin_attach_delete_start.php
    
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '附件ID不能为空');
    }
    
    // hook plugin_haya_blog_admin_attach_delete_read_before.php
    
    $attach = haya_blog_attach__read($id);
    if (empty($attach)) {
        message(-1, '附件不存在');
    }
    
    // hook plugin_haya_blog_admin_attach_delete_delete_before.php
    
    $status = haya_blog_attach_delete($id);
    if ($status === false) {
        message(-1, '删除附件失败');
    }
    
    // hook plugin_haya_blog_admin_attach_delete_end.php
    
    message(0, jump('删除附件成功', url('haya_blog-attach')));
} else {
    $pagesize = 10;
    $srchtype = param(2);
    $keyword  = trim(xn_urldecode(param(3)));
    $page     = param(4, 1);
    
    // hook plugin_haya_blog_admin_attach_list_start.php
    
    if (!in_array($srchtype, array(
        'filename', 
        'orgfilename', 
        'uid', 
        'use_id', 
    ))) {
        $srchtype = '';
    }
    
    // hook plugin_haya_blog_admin_attach_list_where_before.php
    
    $where = array();
    if (!empty($keyword)) {
        $where[$srchtype] = array('LIKE' => $keyword);
    }
    
    // hook plugin_haya_blog_admin_attach_list_count_before.php
    
    $total = haya_blog_attach__count($where);
    
    // hook plugin_haya_blog_admin_attach_list_find_before.php
    
    $list = haya_blog_attach_find($where, array(
        'create_date' => -1, 
        'id' => -1,
    ), $page, $pagesize);

    // hook plugin_haya_blog_admin_attach_list_pagination_before.php
    
    $pagination = pagination(url("haya_blog-attach-{$srchtype}-{$keyword}-{page}"), $total, $page, $pagesize);
    
    $header['title'] = '附件 - 博客';
    
    // hook plugin_haya_blog_admin_attach_list_end.php
    
    include _include($haya_blog_admin_view . '/attach_index.htm');
}

// hook plugin_haya_blog_admin_attach_end.php

?>