<?php

/**
 * 文章评论
 *
 * @author deatil
 * @create 2020-6-21
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '评论 - 博客';

$action2 = param(2);
empty($action2) and $action2 = '';

if ($action2 == 'delete') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $header['title'] = '删除评论 - 博客';
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '评论ID不能为空');
    }
    
    $haya_blog_article_comment = haya_blog_article_comment__read($id);
    if (empty($haya_blog_article_comment)) {
        message(-1, '评论不存在');
    }
    
    $status = haya_blog_article_comment__delete($id);
    
    if ($status === false) {
        message(-1, '删除评论失败');
    }
    
    // 减少相关文章回复数量
    haya_blog_article_dec_reply($haya_blog_article_comment['aid']);
    
    message(0, jump('删除评论成功', url('haya_blog-comment')));
} elseif ($action2 == 'lock') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    user_login_check();
    $haya_blog_author = haya_blog_author_read_by_uid($uid);
    
    $header['title'] = '锁定评论 - 博客';
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '评论ID不能为空');
    }
    
    $haya_blog_article_comment = haya_blog_article_comment__read($id);
    if (empty($haya_blog_article_comment)) {
        message(-1, '评论不存在');
    }
    
    $status = haya_blog_article_comment__update($id, array(
        'is_lock' => 1,
    ));
    
    if ($status === false) {
        message(-1, '锁定评论失败');
    }
    
    message(0, jump('锁定评论成功', url('haya_blog-comment')));
} elseif ($action2 == 'unlock') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    user_login_check();
    $haya_blog_author = haya_blog_author_read_by_uid($uid);
    
    $header['title'] = '解锁评论 - 博客';
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '评论ID不能为空');
    }
    
    $haya_blog_article_comment = haya_blog_article_comment__read($id);
    if (empty($haya_blog_article_comment)) {
        message(-1, '评论不存在');
    }
    
    $status = haya_blog_article_comment__update($id, array(
        'is_lock' => 0,
    ));
    
    if ($status === false) {
        message(-1, '解锁评论失败');
    }
    
    message(0, jump('解锁评论成功', url('haya_blog-comment')));
} elseif ($action2 == 'unallow') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    user_login_check();
    $haya_blog_author = haya_blog_author_read_by_uid($uid);
    
    $header['title'] = '禁用评论 - 博客';
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '评论ID不能为空');
    }
    
    $haya_blog_article_comment = haya_blog_article_comment__read($id);
    if (empty($haya_blog_article_comment)) {
        message(-1, '评论不存在');
    }
    
    $status = haya_blog_article_comment__update($id, array(
        'status' => 0,
    ));
    
    if ($status === false) {
        message(-1, '禁用评论失败');
    }
    
    message(0, jump('禁用评论成功', url('haya_blog-comment')));
} elseif ($action2 == 'allow') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    user_login_check();
    $haya_blog_author = haya_blog_author_read_by_uid($uid);
    
    $header['title'] = '解禁评论 - 博客';
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '评论ID不能为空');
    }
    
    $haya_blog_article_comment = haya_blog_article_comment__read($id);
    if (empty($haya_blog_article_comment)) {
        message(-1, '评论不存在');
    }
    
    $status = haya_blog_article_comment__update($id, array(
        'status' => 1,
    ));
    
    if ($status === false) {
        message(-1, '解禁评论失败');
    }
    
    message(0, jump('解禁评论成功', url('haya_blog-comment')));
} else {
    $pagesize = 10;
    $keyword  = trim(xn_urldecode(param(2)));
    $lock = param(3, '');
    $status = param(4, '');
    $page = param(5, 1);
    
    $where = array();
    if (!empty($keyword)) {
        $where['content'] = array('LIKE' => $keyword);
    }
    
    if ($lock == 'unlock') {
        $where['is_lock'] = 0;
    } elseif ($lock == 'lock') {
        $where['is_lock'] = 1;
    }
    
    if ($status == 'undelete') {
        $where['status'] = 1;
    } elseif ($status == 'delete') {
        $where['status'] = 0;
    }
    
    $total = haya_blog_article_comment__count($where);
    $comments = haya_blog_article_comment_find($where, array(
        'create_date' => -1, 
        'id' => -1,
    ), $page, $pagesize);
    
    $pagination = pagination(url("haya_blog-comment-{$keyword}-{page}"), $total, $page, $pagesize);

    $header['title'] = '评论 - 博客';

    include _include($haya_blog_admin_view . '/comment_index.htm');
}

?>