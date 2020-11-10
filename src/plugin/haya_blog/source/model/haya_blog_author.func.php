<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 专栏
 *
 * @author deatil
 * @create 2020-3-31
 */

function haya_blog_author__create($arr) {
    $r = db_create('haya_blog_author', $arr);
    return $r;
}

function haya_blog_author__read($id) {
    $r = db_find_one('haya_blog_author', array(
        'id' => $id,
    ));
    return $r;
}

function haya_blog_author__find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_author', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

function haya_blog_author__update($id, $arr) {
    $r = db_update('haya_blog_author', array(
        'id' => $id,
    ), $arr);
    return $r;
}

function haya_blog_author__delete($id) {
    if (empty($id)) {
        return false;
    }
    
    $r = db_delete('haya_blog_author', array(
        'id' => $id
    ));
    return $r;
}

function haya_blog_author__count($cond = array()) {
    $r = db_count('haya_blog_author', $cond);
    return $r;
}

function haya_blog_author_read($id) {
    $r = db_find_one('haya_blog_author', array(
        'id' => $id,
    ));
    
    $r['user'] = user_read($r['uid']);
    
    return $r;
}

function haya_blog_author_read_by_uid($uid) {
    $r = db_find_one('haya_blog_author', array(
        'uid' => $uid,
    ));
    return $r;
}

function haya_blog_author_read_by_name($name) {
    $r = db_find_one('haya_blog_author', array(
        'name' => $name,
    ));
    return $r;
}

function haya_blog_author_info_by_uid($uid) {
    $r = db_find_one('haya_blog_author', array(
        'uid' => $uid,
    ));
    if (empty($r)) {
        return array(
            'name' => '',
            'title' => '',
            'keywords' => '',
            'description' => '',
            'cover_url' => '',
            'articles' => 0,
            'comments' => 0,
            'create_date' => 0,
        );
    }
    
    $articles = haya_blog_article__count(array(
        'author_id' => $r['id'],
        'is_lock' => 0,
        'status' => 1,
    ));
    $comments = haya_blog_article_comment__count(array(
        'uid' => $r['uid'],
        'is_lock' => 0,
        'status' => 1,
    ));
    
    $info = array(
        'name' => $r['name'],
        'title' => $r['title'],
        'keywords' => $r['keywords'],
        'description' => $r['description'],
        'cover_url' => haya_blog_attach_url($r['cover']),
        'articles' => $articles,
        'comments' => $comments,
        'create_date' => $r['create_date'],
    );
    
    return $info;
}

function haya_blog_author_update_by_uid($uid, $arr) {
    $r = db_update('haya_blog_author', array(
        'uid' => $uid,
    ), $arr);
    return $r;
}

function haya_blog_author_find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $author_list = db_find('haya_blog_author', $cond, $orderby, $page, $pagesize);
    
    if (!empty($author_list)) {
        foreach ($author_list as & $author) {
            $author['user'] = user__read($author['uid']);
        }
    }
    
    return $author_list;
}

?>