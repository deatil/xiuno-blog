<?php

/**
 * 博客申请
 *
 * @author deatil
 * @create 2020-4-2
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '申请 - 博客';

$action2 = param(2);
empty($action2) and $action2 = '';

// hook plugin_haya_blog_admin_apply_start.php

if ($action2 == 'add') {
    if ($method == 'GET') {
        // hook plugin_haya_blog_admin_apply_add_get_start.php
        
        include _include($haya_blog_admin_view . '/apply_add.htm');
    } else {
        $data = array();
        
        // hook plugin_haya_blog_admin_apply_add_post_start.php
        
        $data['uid'] = param('uid', '');
        if (empty($data['uid'])) {
            message(-1, '用户ID不能为空');
        }
        
        $user = user__read($data['uid']);
        if (empty($user)) {
            message(-1, '用户不存在');
        }
       
        $data['username'] = param('username', '');
        $data['profession'] = param('profession', '');
        $data['email'] = param('email', '');
        if (empty($data['email'])) {
            message(-1, '申请人邮箱不能为空');
        }
        if (!is_email($data['email'], $err)) {
            message(-1, '申请人邮箱格式错误');
        }
        
        $data['phone'] = param('phone', '');
        if (!empty($data['phone'])) {
            if (!is_mobile($data['phone'], $err)) {
                message(-1, '申请人电话格式错误');
            }
        }
        
        $data['address'] = param('address', '');
        $data['idcard'] = param('idcard', '');
        
        $data['reason'] = param('reason', '');
        if (empty($data['reason'])) {
            message(-1, '申请原因不能为空');
        }
        
        $data['state'] = param('state', 1);
        $data['fail_tip'] = param('fail_tip', '');
        if ($data['state'] == 2 && empty($data['fail_tip'])) {
            message(-1, '申请失败原因不能为空');
        }
        
        $data['type'] = param('type', 1);
        $data['status'] = param('status', 0);
        $data['create_date'] = time();
        $data['create_ip'] = $longip;
        
        // hook plugin_haya_blog_admin_apply_add_post_create_before.php
        
        $add_status = haya_blog_apply__create($data);
        if ($add_status === false) {
            message(-1, '添加申请失败');
        }
        
        // hook plugin_haya_blog_admin_apply_add_post_end.php
        
        message(0, jump('添加申请成功', url('haya_blog-apply')));
    }
} elseif ($action2 == 'edit') {
    if ($method == 'GET') {
        $id = param(3, 0);
        if ($id == 0) {
            message(-1, '申请ID错误');
        }
        
        $haya_blog_apply = haya_blog_apply__read($id);
        if (empty($haya_blog_apply)) {
            message(-1, '申请不存在');
        }
        
        $haya_blog_apply_user = user_read($haya_blog_apply['uid']);
        if (empty($haya_blog_apply_user)) {
            message(-1, '申请用户不存在');
        }
        
        // hook plugin_haya_blog_admin_apply_edit_get_end.php
        
        include _include($haya_blog_admin_view . '/apply_edit.htm');
    } else {
        $data = array();
        
        // hook plugin_haya_blog_admin_apply_edit_post_start.php
        
        $id = param('id', 0);
        if (empty($id)) {
            message(-1, '申请ID不能为空');
        }
        
        $apply = haya_blog_apply__read($data['uid']);
        if (empty($apply)) {
            message(-1, '申请不存在');
        }
        
        $user = user__read($apply['uid']);
        if (empty($user)) {
            message(-1, '申请用户不存在');
        }
        
        $data['username'] = param('username', '');
        $data['profession'] = param('profession', '');
        $data['email'] = param('email', '');
        if (empty($data['email'])) {
            message(-1, '申请人邮箱不能为空');
        }
        if (!is_email($data['email'], $err)) {
            message(-1, $err);
        }
        
        $data['phone'] = param('phone', '');
        if (!empty($data['phone'])) {
            if (!is_mobile($data['phone'], $err)) {
                message(-1, '申请人电话格式错误');
            }
        }
        
        $data['address'] = param('address', '');
        $data['idcard'] = param('idcard', '');
        
        $data['reason'] = param('reason', '');
        if (empty($data['reason'])) {
            message(-1, '申请原因不能为空');
        }
        
        $data['state'] = param('state', 1);
        $data['fail_tip'] = param('fail_tip', '');
        if ($data['state'] == 2 && empty($data['fail_tip'])) {
            message(-1, '申请失败原因不能为空');
        }
        
        $data['type'] = param('type', 1);
        $data['status'] = param('status', 0);
        
        // hook plugin_haya_blog_admin_apply_edit_post_update_before.php
        
        $status = haya_blog_apply__update($id, $data);
        if ($status === false) {
            message(-1, '更新申请失败');
        }
        
        // hook plugin_haya_blog_admin_apply_edit_post_end.php
        
        message(0, jump('更新申请成功', url('haya_blog-apply')));
    }
} elseif ($action2 == 'delete') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    // hook plugin_haya_blog_admin_apply_delete_start.php
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '申请ID不能为空');
    }
    
    $apply = haya_blog_apply__read($id);
    if (empty($apply)) {
        message(-1, '申请不存在');
    }
    
    // hook plugin_haya_blog_admin_apply_delete_delete_before.php
    
    $status = haya_blog_apply__delete($id);
    if ($status === false) {
        message(-1, '删除申请失败');
    }
    
    // hook plugin_haya_blog_admin_apply_delete_end.php
    
    message(0, jump('删除申请成功', url('haya_blog-apply')));
} elseif ($action2 == 'allow') {
    if ($method == 'GET') {
        // hook plugin_haya_blog_admin_apply_allow_get_start.php
    
        $id = param(3, 0);
        if ($id == 0) {
            message(-1, '申请ID错误');
        }
        
        $haya_blog_apply = haya_blog_apply__read($id);
        if (empty($haya_blog_apply)) {
            message(-1, '申请不存在');
        }
        
        $haya_blog_apply_user = user_read($haya_blog_apply['uid']);
        if (empty($haya_blog_apply_user)) {
            message(-1, '申请用户不存在');
        }
        
        // hook plugin_haya_blog_admin_apply_allow_get_end.php
        
        include _include($haya_blog_admin_view . '/apply_allow.htm');
    } else {
        $id = param('id', 0);
        
        // hook plugin_haya_blog_admin_apply_allow_post_start.php
        
        if (empty($id)) {
            message(-1, '申请ID不能为空');
        }
        
        $apply = haya_blog_apply__read($id);
        if (empty($apply)) {
            message(-1, '申请不存在');
        }
        
        if ($apply['state'] != 1) {
            message(-1, '申请已经审核过了');
        }
        
        $apply_user = user__read($apply['uid']);
        if (empty($apply_user)) {
            message(-1, '申请用户不存在');
        }
        
        $data['state'] = param('state', 1);
        $data['fail_tip'] = param('fail_tip', '');
        if ($data['state'] == 3 
            && empty($data['fail_tip'])
        ) {
            message(-1, '申请失败原因不能为空');
        }
        
        // hook plugin_haya_blog_admin_apply_allow_post_update_before.php
        
        $status = haya_blog_apply__update($id, $data);
        if ($status === false) {
            message(-1, '申请批复失败');
        }
        
        if ($data['state'] == 2) {
            $apply_author = haya_blog_author_read_by_uid($apply['uid']);
            if (empty($apply_author)) {
                haya_blog_author__create(array(
                    'uid' => $apply['uid'],
                    'name' => 'blog'.$apply['uid'],
                    'title' => '用户' . $apply['uid'] . '的专栏',
                    'keywords' => '用户' . $apply['uid'] . '的专栏',
                    'description' => '用户' . $apply['uid'] . '的专栏',
                    'article_pagesize' => 10,
                    'status' => 1,
                    'update_date' => time(),
                    'update_ip' => $longip,
                    'create_date' => time(),
                    'create_ip' => $longip,
                ));
            } else {
                haya_blog_author_update_by_uid($apply['uid'], array(
                    'status' => 1,
                    'update_date' => time(),
                    'update_ip' => $longip,
                ));
            }

        }
        
        // hook plugin_haya_blog_admin_apply_allow_post_end.php
        
        message(0, jump('申请批复成功', url('haya_blog-apply')));
    }
} else {
    $pagesize = 10;
    $keyword  = trim(xn_urldecode(param(2)));
    $page     = param(3, 1);
    
    // hook plugin_haya_blog_admin_apply_start.php
    
    $where = array();
    if (!empty($keyword)) {
        $where['name'] = array('LIKE' => $keyword);
    }
    
    $total = haya_blog_apply__count($where);
    $haya_blog_applys = haya_blog_apply_find($where, array(
        'create_date' => -1, 
        'id' => -1,
    ), $page, $pagesize);
    
    $pagination = pagination(url("haya_blog-apply-{$keyword}-{page}"), $total, $page, $pagesize);
    
    // hook plugin_haya_blog_admin_apply_end.php

    include _include($haya_blog_admin_view . '/apply_index.htm');
}    

?>