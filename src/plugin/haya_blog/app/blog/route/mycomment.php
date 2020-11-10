<?php

/**
 * 文章评论
 *
 * @author deatil
 * @create 2020-6-21
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '我的评论 - 博客';

user_login_check();
$haya_blog_author = haya_blog_author_read_by_uid($uid);

$action2 = param(2);
empty($action2) and $action2 = '';

if ($action2 == 'delete') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    $header['title'] = '删除文章评论 - 博客';
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '文章评论ID不能为空');
    }
    
    $haya_blog_article_comment = haya_blog_article_comment__read($id);
    if (empty($haya_blog_article_comment)) {
        message(-1, '文章评论不存在');
    }
    
    if ($haya_blog_article_comment['uid'] != $uid) {
        message(-1, '你不能删除该评论');
    }
    
    $status = haya_blog_article_comment__delete($id);
    
    if ($status === false) {
        message(-1, '删除文章评论失败');
    }
    
    // 减少相关文章回复数量
    haya_blog_article_dec_reply($haya_blog_article_comment['aid']);
    
    message(0, jump('删除文章评论成功', haya_blog_url('article-'.$haya_blog_article_comment['aid'])));
} else {
    $pagesize = 10;
    $page = param(2, 1);
    
    $where = array(
        'uid' => $uid,
        'status' => 1,
    );
    
    $total = haya_blog_article_comment__count($where);
    $comments = haya_blog_article_comment_find($where, array(
        'create_date' => -1, 
        'id' => -1,
    ), $page, $pagesize);
    
    $pagination = pagination(haya_blog_url("mycomment-{page}"), $total, $page, $pagesize);

    $header['title'] = '第'.$page.'页 - 我的评论 - 博客';

    include haya_blog_theme_view('mycomment_index.htm');
}

?>