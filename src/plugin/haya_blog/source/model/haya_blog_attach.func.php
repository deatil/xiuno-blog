<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 附件
 *
 * @author deatil
 * @create 2020-5-20
 */

function haya_blog_attach__create($arr) {
    $r = db_create('haya_blog_attach', $arr);
    return $r;
}

function haya_blog_attach__read($id) {
    $r = db_find_one('haya_blog_attach', array(
        'id' => $id,
    ));
    return $r;
}

function haya_blog_attach__find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_attach', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

function haya_blog_attach__update($id, $arr) {
    $r = db_update('haya_blog_attach', array(
        'id' => $id,
    ), $arr);
    return $r;
}

function haya_blog_attach__delete($id) {
    if (empty($id)) {
        return false;
    }
    
    $r = db_delete('haya_blog_attach', array(
        'id' => $id
    ));
    return $r;
}

function haya_blog_attach__count($cond = array()) {
    $r = db_count('haya_blog_attach', $cond);
    return $r;
}

function haya_blog_attach_read_by_filemd5($filemd5) {
    $r = db_find_one('haya_blog_attach', array(
        'filemd5' => $filemd5,
    ));
    return $r;
}

function haya_blog_attach_read_by_filename($filename) {
    $r = db_find_one('haya_blog_attach', array(
        'filename' => $filename,
    ));
    return $r;
}

function haya_blog_attach_read_by_use_id($use_id) {
    $r = db_find_one('haya_blog_attach', array(
        'use_id' => $use_id,
    ));
    return $r;
}

function haya_blog_attach_create($arr) {
    global $user, $uid, $longip;
    
    if (empty($user)) {
        return false;
    }
    
    $arr = array_merge(array(
        'uid' => $uid,
        'create_date' => time(),
        'create_ip' => $longip,
    ), $arr);
    
    $r = haya_blog_attach__create($arr);
    return $r;
}

function haya_blog_attach_find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_attach', $cond, $orderby, $page, $pagesize);
    
    if (!empty($r)) {
        foreach ($r as & $v) {
            $v['user'] = user_read_cache($v['uid']);
        }
    }
    
    return $r;
}

?>