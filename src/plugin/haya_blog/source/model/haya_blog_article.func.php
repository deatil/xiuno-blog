<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 文章
 *
 * @author deatil
 * @create 2020-3-31
 */

function haya_blog_article__create($arr) {
    $r = db_create('haya_blog_article', $arr);
    return $r;
}

function haya_blog_article__read($id) {
    $r = db_find_one('haya_blog_article', array(
        'id' => $id,
    ));
    return $r;
}

function haya_blog_article__find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_article', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

function haya_blog_article__update($id, $arr) {
    $r = db_update('haya_blog_article', array(
        'id' => $id,
    ), $arr);
    return $r;
}

function haya_blog_article__delete($id) {
    if (empty($id)) {
        return false;
    }
    
    $r = db_delete('haya_blog_article', array(
        'id' => $id
    ));
    return $r;
}

function haya_blog_article__count($cond = array()) {
    $r = db_count('haya_blog_article', $cond);
    return $r;
}

function haya_blog_article_read($id) {
    $r = db_find_one('haya_blog_article', array(
        'id' => $id,
    ));
    
    if (!empty($r)) {
        $r['category'] = haya_blog_category__read($r['category_id']);
        $r['author_category'] = haya_blog_author_category__read($r['cid']);
    }
    
    return $r;
}

function haya_blog_article_find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_article', $cond, $orderby, $page, $pagesize);
    
    if (!empty($r)) {
        foreach ($r as & $v) {
            $v['author'] = haya_blog_author_read($v['author_id']);
            $v['category'] = haya_blog_category__read($v['category_id']);
            $v['author_category'] = haya_blog_author_category__read($v['cid']);
        }
    }
    
    return $r;
}

function haya_blog_article_delete($id) {
    if (empty($id)) {
        return false;
    }
    
    $r = db_delete('haya_blog_article', array(
        'id' => $id,
    ));
    
    if ($r !== false) {
        haya_blog_article_comment_delete_by_aid($id);
    }
    
    return $r;
}

// reply + 1
function haya_blog_article_inc_reply($id, $n = 1) {
    // hook model_plugin_haya_blog_article_inc_reply_start.php
    global $conf, $db;
    $tablepre = $db->tablepre;
    $sqladd = !in_array($conf['cache']['type'], array('mysql', 'pdo_mysql')) ? '' : ' LOW_PRIORITY';
    $r = db_exec("UPDATE$sqladd `{$tablepre}haya_blog_article` SET reply=reply+$n WHERE id='$id'");
    // hook model_plugin_haya_blog_article_inc_reply_end.php
    return $r;
}

// reply - 1
function haya_blog_article_dec_reply($id, $n = 1) {
    // hook model_plugin_haya_blog_article_dec_reply_start.php
    global $conf, $db;
    
    $article = haya_blog_article__read($id);
    if (empty($article)) {
        return false;
    }
    if ($article['reply'] <= $n) {
        $n = $article['reply'];
    }
    
    $tablepre = $db->tablepre;
    $sqladd = !in_array($conf['cache']['type'], array('mysql', 'pdo_mysql')) ? '' : ' LOW_PRIORITY';
    $r = db_exec("UPDATE$sqladd `{$tablepre}haya_blog_article` SET reply=reply-$n WHERE id='".$article['id']."'");
    // hook model_plugin_haya_blog_article_dec_reply_end.php
    return $r;
}

// hits + 1
function haya_blog_article_inc_views($id, $n = 1) {
    // hook model_plugin_haya_blog_article_inc_views_start.php
    global $conf, $db;
    $tablepre = $db->tablepre;
    $sqladd = !in_array($conf['cache']['type'], array('mysql', 'pdo_mysql')) ? '' : ' LOW_PRIORITY';
    $r = db_exec("UPDATE$sqladd `{$tablepre}haya_blog_article` SET hits=hits+$n WHERE id='$id'");
    // hook model_plugin_haya_blog_article_inc_views_end.php
    return $r;
}

?>