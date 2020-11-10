<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 文章评论
 *
 * @author deatil
 * @create 2020-3-31
 */

function haya_blog_article_comment__create($arr) {
    $r = db_create('haya_blog_article_comment', $arr);
    return $r;
}

function haya_blog_article_comment__read($id) {
    $r = db_find_one('haya_blog_article_comment', array(
        'id' => $id,
    ));
    return $r;
}

function haya_blog_article_comment__find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20, 
    $key = ''
) {
    $r = db_find('haya_blog_article_comment', $cond, $orderby, $page, $pagesize, $key);
    
    return $r;
}

function haya_blog_article_comment__update($id, $arr) {
    $r = db_update('haya_blog_article_comment', array(
        'id' => $id,
    ), $arr);
    return $r;
}

function haya_blog_article_comment__delete($id) {
    if (empty($id)) {
        return false;
    }
    
    $r = db_delete('haya_blog_article_comment', array(
        'id' => $id
    ));
    return $r;
}

function haya_blog_article_comment__count($cond = array()) {
    $r = db_count('haya_blog_article_comment', $cond);
    return $r;
}

function haya_blog_article_comment_delete_by_aid($aid) {
    if (empty($aid)) {
        return false;
    }
    
    $r = db_delete('haya_blog_article_comment', array(
        'aid' => $aid,
    ));
    return $r;
}

function haya_blog_article_comment_delete_by_uid($uid) {
    if (empty($uid)) {
        return false;
    }
    
    $r = db_delete('haya_blog_article_comment', array(
        'uid' => $uid,
    ));
    return $r;
}

function haya_blog_article_comment_find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_article_comment', $cond, $orderby, $page, $pagesize);
    
    if (!empty($r)) {
        $reply_cids = array();
        foreach ($r as & $v) {
            $v['user'] = user_read_cache($v['uid']);
            
            if ($v['reply'] > 0 && !in_array($v['reply'], $reply_cids)) {
                $reply_cids[] = $v['reply'];
            }
        }
        
        if (!empty($reply_cids)) {
            $reply_comments = db_find('haya_blog_article_comment', array_merge($cond, array('id' => $reply_cids)), $orderby, 1, $pagesize, 'id');
            if (!empty($reply_comments)) {
                foreach ($reply_comments as & $rc) {
                    $rc['user'] = user_read_cache($rc['uid']);
                }
            }
            
            foreach ($r as & $v2) {
                if (isset($reply_comments[$v2['reply']])) {
                    $v2['reply_comment'] = $reply_comments[$v2['reply']];
                }
            }
        }
    }
    
    return $r;
}

?>