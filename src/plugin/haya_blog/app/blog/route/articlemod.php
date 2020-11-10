<?php

/**
 * 文章操作
 *
 * @author deatil
 * @create 2020-6-26
 */

!defined('DEBUG') and exit('Access Denied.');

user_login_check();

$articlemod_group = haya_blog_config('articlemod_group');
if (!in_array($gid, $articlemod_group)) {
    message(-1, '你不能进行该操作');
}

$header['title'] = '文章操作 - 博客';

if($method == 'GET') {
    
    include haya_blog_theme_view('articlemod.htm');  
   
} else {

    $action2 = param(2);
    empty($action2) and $action2 = '';

    if (empty($action2)) {
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

    $action3 = param('option', '');
    empty($action3) and $action3 = 'close';

    if ($action2 == 'lock') {
        if ($action3 == 'open') {
            $status = haya_blog_article__update($id, array(
                'is_lock' => 0,
            ));
        } else {
            $status = haya_blog_article__update($id, array(
                'is_lock' => 1,
            ));
        }
    } elseif ($action2 == 'recommend') {
        if ($action3 == 'open') {
            $status = haya_blog_article__update($id, array(
                'is_recommend' => 1,
            ));
        } else {
            $status = haya_blog_article__update($id, array(
                'is_recommend' => 0,
            ));
        }
    } elseif ($action2 == 'reply') {
        if ($action3 == 'open') {
            $status = haya_blog_article__update($id, array(
                'is_reply' => 1,
            ));
        } else {
            $status = haya_blog_article__update($id, array(
                'is_reply' => 0,
            ));
        }
    }

    if ($status === false) {
        message(-1, '文章操作失败');
    }

    message(0, jump('文章操作成功', haya_blog_url('article')));
    
}

?>