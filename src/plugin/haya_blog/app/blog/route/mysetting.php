<?php

/**
 * 博客申请
 *
 * @author deatil
 * @create 2020-5-22
 */

!defined('DEBUG') and exit('Access Denied.');

$header['title'] = '我的设置 - 博客';

user_login_check();

$haya_blog_check_author = haya_blog_check_author($uid);
if (!$haya_blog_check_author) {
    message(-1, jump('你还没有申请专栏', url('index')));
}

if ($method == 'GET') {
    $haya_blog_author = haya_blog_author_read_by_uid($uid);
    
    $article_max_pagesize = haya_blog_config('blog_author_article_pagesize', 10);
    
    include haya_blog_theme_view('mysetting.htm');
} else {
    
    $data = array();
    
    $data['name'] = param('name', '');
    if (empty($data['name'])) {
        message(-1, '专栏英文名称不能为空');
    }
    
    $haya_blog_author_check = haya_blog_author_read_by_name($data['name']);
    if (!empty($haya_blog_author_check) 
        && $haya_blog_author_check['uid'] != $uid
    ) {
        message(-1, '专栏英文名称已被占用');
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
        
        $author = haya_blog_author__read($uid);
        if (empty($author)) {
            message(-1, '专栏不存在');
        }
        
        $cover = $attach['filename'];
        $data['cover'] = $cover;
    }
    
    $data['article_pagesize'] = param('article_pagesize', 10);
    $article_max_pagesize = haya_blog_config('blog_author_article_pagesize', 10);
    if ($data['article_pagesize'] > $article_max_pagesize) {
        message(-1, '每页文章数量不能大于'.$article_max_pagesize);
    }
    
    $data['update_date'] = time();
    $data['update_ip'] = $longip;
    
    $status = haya_blog_author_update_by_uid($uid, $data);
    if ($status === false) {
        message(-1, '设置保存失败');
    }
    
    if (isset($attach) && !empty($attach)) {
        haya_blog_attach__update($attach['id'], array(
            'use_id' => md5('haya-blog-author-'.$haya_blog_author_check['id']),
        ));
    }
    
    message(0, jump('设置保存成功', haya_blog_url('mysetting')));
}

?>