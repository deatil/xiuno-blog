<?php

/**
 * 博客申请
 *
 * @author deatil
 * @create 2020-5-22
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '专栏申请 - 博客';
$header['keywords'] = '专栏申请';
$header['description'] = '专栏申请';

user_login_check();

$haya_blog_check_author = haya_blog_check_author($uid);
if ($haya_blog_check_author) {
    message(-1, jump('你已经是专栏作者了', haya_blog_url('mysetting')));
}

$haya_blog_check_apply = haya_blog_check_apply($gid);
if (!$haya_blog_check_apply) {
    message(-1, jump('页面访问错误', url('index')));
}

$apply = haya_blog_apply_read_by_uid($uid);

if ($method == 'GET') {
    include haya_blog_theme_view('apply.htm');
} else {
    if (!empty($apply) && $apply['state'] == 2) {
        message(-1, jump('你的专栏申请正在审核或者审核已通过，请不要重复提交', haya_blog_url('apply')));
    }
    
    $data = array();
    
    $data['uid'] = $uid;
    
    $data['username'] = param('username', '');
    $data['profession'] = param('profession', '');
    $data['email'] = param('email', '');
    if (empty($data['email'])) {
        message(-1, '你的邮箱不能为空');
    }
    if (!is_email($data['email'], $err)) {
        message(-1, '你的邮箱格式错误');
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
    
    if (!empty($apply) && $apply['state'] == 2) {
        $data['type'] = 2;
    } else {
        $data['type'] = 1;
    }
    $data['status'] = 1;
    $data['create_date'] = time();
    $data['create_ip'] = $longip;
    
    $add_status = haya_blog_apply__create($data);
    if ($add_status === false) {
        message(-1, '专栏申请提交失败');
    }
    
    message(0, jump('专栏申请提交成功，请等待管理员的审核', haya_blog_url('apply')));
}

?>