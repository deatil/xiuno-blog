<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 标签
 *
 * @author deatil
 * @create 2020-7-7
 */

function haya_blog_tag__create($arr) {
    $r = db_create('haya_blog_tag', $arr);
    return $r;
}

function haya_blog_tag__read($id) {
    $r = db_find_one('haya_blog_tag', array(
        'id' => $id,
    ));
    return $r;
}

function haya_blog_tag__find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_tag', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

function haya_blog_tag__update($id, $arr) {
    $r = db_update('haya_blog_tag', array(
        'id' => $id,
    ), $arr);
    return $r;
}

function haya_blog_tag__delete($id) {
    if (empty($id)) {
        return false;
    }
    
    $r = db_delete('haya_blog_tag', array(
        'id' => $id
    ));
    return $r;
}

function haya_blog_tag__count($cond = array()) {
    $r = db_count('haya_blog_tag', $cond);
    return $r;
}

function haya_blog_tag_read_by_name($name) {
    $r = db_find_one('haya_blog_tag', array(
        'name' => $name,
    ));
    return $r;
}

?>