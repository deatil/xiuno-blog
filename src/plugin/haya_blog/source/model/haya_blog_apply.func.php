<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 申请
 *
 * @author deatil
 * @create 2020-3-31
 */

function haya_blog_apply__create($arr) {
    $r = db_create('haya_blog_apply', $arr);
    return $r;
}

function haya_blog_apply__read($id) {
    $r = db_find_one('haya_blog_apply', array(
        'id' => $id,
    ));
    return $r;
}

function haya_blog_apply__find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_apply', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

function haya_blog_apply__update($id, $arr) {
    $r = db_update('haya_blog_apply', array(
        'id' => $id,
    ), $arr);
    return $r;
}

function haya_blog_apply__delete($id) {
    if (empty($id)) {
        return false;
    }
    
    $r = db_delete('haya_blog_apply', array(
        'id' => $id
    ));
    return $r;
}

function haya_blog_apply__count($cond = array()) {
    $r = db_count('haya_blog_apply', $cond);
    return $r;
}

function haya_blog_apply_read_by_name($name) {
    $r = db_find_one('haya_blog_apply', array(
        'name' => $name,
    ));
    return $r;
}

function haya_blog_apply_read_by_uid($uid) {
    $r = db_find_one('haya_blog_apply', array(
        'uid' => $uid,
    ));
    return $r;
}

function haya_blog_apply_read_by_uid_and_name($uid, $name) {
    $r = db_find_one('haya_blog_apply', array(
        'uid' => $uid,
        'name' => $name,
    ));
    return $r;
}

function haya_blog_apply_find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_apply', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

?>