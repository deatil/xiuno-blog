<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 专栏统计
 *
 * @author deatil
 * @create 2020-6-30
 */

function haya_blog_author_count__create($arr) {
    $r = db_create('haya_blog_author_count', $arr);
    return $r;
}

function haya_blog_author_count__find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_author_count', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

function haya_blog_author_count__count($cond = array()) {
    $r = db_count('haya_blog_author_count', $cond);
    return $r;
}

function haya_blog_author_count_read_by_author_id($author_id) {
    $r = db_find_one('haya_blog_author_count', array(
        'author_id' => $author_id,
    ));
    return $r;
}

function haya_blog_author_count_update_by_author_id($author_id, $arr) {
    $r = db_update('haya_blog_author_count', array(
        'author_id' => $author_id,
    ), $arr);
    return $r;
}

function haya_blog_author_count_delete_by_author_id($author_id) {
    if (empty($author_id)) {
        return false;
    }
    
    $r = db_delete('haya_blog_author_count', array(
        'author_id' => $author_id,
    ));
    return $r;
}

function haya_blog_author_count_find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_author_count', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

?>