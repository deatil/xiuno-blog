<?php

/**
 * 博客编辑器图片上传
 *
 * @author deatil
 * @create 2020-7-3
 */

!defined('DEBUG') and exit('Access Denied.');

user_login_check();

$haya_blog_check_author = haya_blog_check_author($uid);
if (!$haya_blog_check_author) {
    haya_blog_wangeditor_json(-1, '你还没有申请专栏');
}

$action2 = param(1);
empty($action2) and $action2 = '';

// hook plugin_haya_blog_wangeditor_start.php

if ($action2 == 'create') {
    $header['title'] = '上传附件 - 编辑器 - 博客';

    if ($method == 'GET') {
        haya_blog_wangeditor_json(-1, '上传失败');
    }
    
    // hook plugin_haya_blog_wangeditor_create_start.php

    if (empty($_FILES["img"]["tmp_name"])) {
        haya_blog_wangeditor_json(-1, '上传文件没有数据');
    }

    $attach = haya_blog_attach_upload($_FILES["img"]);
    if ($attach === false) {
        haya_blog_wangeditor_json(-1, '上传失败');
    }

    $img = haya_blog_attach_url($attach['filename']);

    empty($_SESSION['haya_blog_wangeditor_files']) AND $_SESSION['haya_blog_wangeditor_files'] = array();
    $n = count($_SESSION['haya_blog_wangeditor_files']);
    $_SESSION['haya_blog_wangeditor_files'][$n] = $attach;
    
    // hook plugin_haya_blog_wangeditor_create_end.php

    haya_blog_wangeditor_json(0, [
        $img,
    ]);
} elseif ($action2 == 'delete') {
    $header['title'] = '附件删除 - 编辑器 - 博客';
    
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '附件ID不能为空');
    }
    
    // hook plugin_haya_blog_wangeditor_delete_start.php
    
    $attach = haya_blog_attach__read($id);
    if (empty($attach)) {
        message(-1, '附件不存在');
    }
    
    if ($attach['uid'] != $uid) {
        message(-1, '删除附件失败');
    }
    
    $status = haya_blog_attach_delete($id);
    if ($status === false) {
        message(-1, '删除附件失败');
    }
    
    // hook plugin_haya_blog_wangeditor_delete_end.php
    
    message(0, '删除附件成功');
}

message(-1, '访问错误');

?>