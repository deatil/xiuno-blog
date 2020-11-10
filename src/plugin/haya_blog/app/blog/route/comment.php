<?php

/**
 * 文章评论
 *
 * @author deatil
 * @create 2020-6-21
 */

!defined('DEBUG') and exit('Access Denied.');

$action2 = param(2);
empty($action2) and $action2 = '';

if ($action2 == 'create') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    user_login_check();
    $haya_blog_author = haya_blog_author_read_by_uid($uid);
    
    $header['title'] = '创建文章评论 - 博客';
    
    $open_article_comment = haya_blog_config('open_article_comment');
    if ($open_article_comment != 1) {
        message(-1, '网站当前关闭了文章评论');
    }
    
    $data = array();
    $data['uid'] = $uid;
    
    $data['aid'] = param(3);
    if (empty($data['aid'])) {
        message(-1, '文章ID不能为空');
    }
    
    $haya_blog_article = haya_blog_article__read($data['aid']);
    if (empty($haya_blog_article)) {
        message(-1, jump('文章不存在'));
    }
    
    if ($haya_blog_article['is_reply'] != 1) {
        message(-1, '当前文章关闭了文章评论');
    }
    
    $data['reply'] = param('reply', 0);
    
    $data['content'] = param('content', '');
    if (empty($data['content'])) {
        message(-1, '评论内容不能为空');
    }
    
    $data['is_lock'] = 0;
    $data['status'] = 1;
    
    $data['update_date'] = time();
    $data['update_ip'] = $longip;
    $data['create_date'] = time();
    $data['create_ip'] = $longip;
    
    $add_status = haya_blog_article_comment__create($data);
    if ($add_status === false) {
        message(-1, jump('创建文章评论失败'));
    }
    
    // 增加相关文章回复数量
    haya_blog_article_inc_reply($data['aid']);
    
    // 跟新回复时间
    haya_blog_article__update($data['aid'], array(
        'reply_date' => time(),
        'reply_ip' => $longip,
    ));
    
    $return_html = param('return_html', 0);
    if($return_html) {
        $haya_blog_article_comment_pagination = '';
        $haya_blog_article_comment_list = haya_blog_article_comment_find(array(
            'id' => $add_status,
            'is_lock' => 0,
            'status' => 1,
        ), array(
            'create_date' => -1,
            'id' => -1,
        ), 1, 1);
        
        ob_start();
        include haya_blog_theme_view('article_comment_list.inc.htm');
        $s = ob_get_clean();
        
        message(0, $s);
    } else {
        message(0, jump('创建文章评论成功', haya_blog_url('article-'.$data['aid'])));
    }
} elseif ($action2 == 'lock') {
    if ($method != 'POST') {
        message(-1, '访问错误');
    }
    
    user_login_check();
    $haya_blog_author = haya_blog_author_read_by_uid($uid);
    
    $header['title'] = '锁定文章评论 - 博客';
    
    $id = param('id', 0);
    if (empty($id)) {
        message(-1, '文章评论ID不能为空');
    }
    
    $haya_blog_article_comment = haya_blog_article_comment__read($id);
    if (empty($haya_blog_article_comment)) {
        message(-1, '文章评论不存在');
    }
    
    if ($gid != 1) {
        message(-1, '你不能锁定该评论');
    }
    
    $status = haya_blog_article_comment__update($id, array(
        'is_lock' => 1,
    ));
    
    if ($status === false) {
        message(-1, '锁定文章评论失败');
    }
    
    message(0, jump('锁定文章评论成功', haya_blog_url('article-'.$haya_blog_article_comment['aid'])));
} else {
    message(-1, '访问错误');
}

?>