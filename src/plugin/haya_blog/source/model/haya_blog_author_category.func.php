<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 专栏分类
 *
 * @author deatil
 * @create 2020-3-31
 */

function haya_blog_author_category__create($arr) {
    $r = db_create('haya_blog_author_category', $arr);
    return $r;
}

function haya_blog_author_category__read($id) {
    $r = db_find_one('haya_blog_author_category', array(
        'id' => $id,
    ));
    return $r;
}

function haya_blog_author_category__update($id, $arr) {
    $r = db_update('haya_blog_author_category', array(
        'id' => $id,
    ), $arr);
    return $r;
}

function haya_blog_author_category__delete($id) {
    if (empty($id)) {
        return false;
    }
    
    $r = db_delete('haya_blog_author_category', array(
        'id' => $id
    ));
    return $r;
}

function haya_blog_author_category__count($cond = array()) {
    $r = db_count('haya_blog_author_category', $cond);
    return $r;
}

function haya_blog_author_category__find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_author_category', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

function haya_blog_author_category_read_by_name($name) {
    $r = db_find_one('haya_blog_author_category', array(
        'name' => $name,
    ));
    return $r;
}

function haya_blog_author_category_update_by_author_id($author_id, $arr) {
    $r = db_update('haya_blog_author_category', array(
        'author_id' => $author_id,
    ), $arr);
    return $r;
}

function haya_blog_author_category_find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_author_category', $cond, $orderby, $page, $pagesize);
    
    if (!empty($r)) {
        foreach ($r as & $category) {
            $category['author'] = haya_blog_author__read($category['author_id']);
        }
    }
    
    return $r;
}

function haya_blog_author_category_articles_find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = haya_blog_author_category_find($cond, $orderby, $page, $pagesize);
    
    if (!empty($r)) {
        foreach ($r as & $category) {
            $category['articles'] = haya_blog_article__count(array(
                'cid' => $category['id'],
                'is_lock' => 0,
                'status' => 1,
            ));
        }
    }
    
    return $r;
}

?>