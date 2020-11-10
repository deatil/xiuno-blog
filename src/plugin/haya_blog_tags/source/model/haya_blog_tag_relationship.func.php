<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 标签与文章关系
 *
 * @author deatil
 * @create 2020-7-7
 */

function haya_blog_tag_relationship__create($arr) {
    $r = db_create('haya_blog_tag_relationship', $arr);
    return $r;
}

function haya_blog_tag_relationship__find(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_tag_relationship', $cond, $orderby, $page, $pagesize);
    
    return $r;
}

function haya_blog_tag_relationship__count($cond = array()) {
    $r = db_count('haya_blog_tag_relationship', $cond);
    return $r;
}

function haya_blog_tag_relationship_read_by_tag_id_and_article_id($tag_id, $article_id) {
    $r = db_find_one('haya_blog_tag_relationship', array(
        'tag_id' => $tag_id,
        'article_id' => $article_id,
    ));
    return $r;
}

function haya_blog_tag_relationship_delete_by_tag_id_and_article_id($tag_id, $article_id) {
    $r = db_delete('haya_blog_tag_relationship', array(
        'tag_id' => $tag_id,
        'article_id' => $article_id,
    ));
    return $r;
}

function haya_blog_tag_relationship_delete_by_article_id($article_id) {
    $r = db_delete('haya_blog_tag_relationship', array(
        'article_id' => $article_id,
    ));
    return $r;
}

function haya_blog_tag_relationship_find_article_ids(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20
) {
    $r = db_find('haya_blog_tag_relationship', $cond, $orderby, $page, $pagesize, 'article_id', 'article_id');
    
    $article_ids = array();
    if (!empty($r)) {
        foreach ($r as $v) {
            $article_ids[] = $v['article_id'];
        }
    }
    
    return $article_ids;
}

function haya_blog_tag_db_cond_to_sqladd($cond) {
    $s = '';
    if(!empty($cond)) {
        $s = ' WHERE ';
        foreach($cond as $k=>$v) {
            $k_arr = explode('.', $k);
            if (count($k_arr) > 0) {
                $k_arr2 = array();
                foreach ($k_arr as $k_arr_v) {
                    $k_arr2[] = "`".$k_arr_v."`";
                }
                $k = implode('.', $k_arr2);
            }
            
            if(!is_array($v)) {
                $v = (is_int($v) || is_float($v)) ? $v : "'".addslashes($v)."'";
                $s .= "$k=$v AND ";
            } elseif(isset($v[0])) {
                $s .= '(';
                foreach ($v as $v1) {
                    $v1 = (is_int($v1) || is_float($v1)) ? $v1 : "'".addslashes($v1)."'";
                    $s .= "$k=$v1 OR ";
                }
                $s = substr($s, 0, -4);
                $s .= ') AND ';
                
                /*
                $ids = implode(',', $v);
                $s .= "$k IN ($ids) AND ";
                */
            } else {
                foreach($v as $k1=>$v1) {
                    if($k1 == 'LIKE') {
                        $k1 = ' LIKE ';
                        $v1="%$v1%";
                    }
                    $v1 = (is_int($v1) || is_float($v1)) ? $v1 : "'".addslashes($v1)."'";
                    $s .= "$k$k1$v1 AND ";
                }
            }
        }
        $s = substr($s, 0, -4);
    }
    return $s;
}

function haya_blog_tag_db_orderby_to_sqladd($orderby) {
    $s = '';
    if(!empty($orderby)) {
        $s .= ' ORDER BY ';
        $comma = '';
        foreach($orderby as $k=>$v) {
            $k_arr = explode('.', $k);
            if (count($k_arr) > 0) {
                $k_arr2 = array();
                foreach ($k_arr as $k_arr_v) {
                    $k_arr2[] = "`".$k_arr_v."`";
                }
                $k = implode('.', $k_arr2);
            }
            
            $s .= $comma."$k ".($v == 1 ? ' ASC ' : ' DESC ');
            $comma = ',';
        }
    }
    return $s;
}


function haya_blog_tag_relationship_find_article(
    $cond = array(), 
    $orderby = array(), 
    $page = 1, 
    $pagesize = 20, 
    $key = '', 
    $col = array()
) {
    global $db;
    $tablepre = $db->tablepre;

    $page = max(1, $page);
    $cond = haya_blog_tag_db_cond_to_sqladd($cond);
    $orderby = haya_blog_tag_db_orderby_to_sqladd($orderby);
    $offset = ($page - 1) * $pagesize;
    $cols = $col ? implode(',', $col) : '*';
    $r = db_sql_find("
        SELECT $cols 
        FROM {$tablepre}haya_blog_tag_relationship tr
        LEFT JOIN {$tablepre}haya_blog_tag t
            ON tr.tag_id = t.id
        LEFT JOIN {$tablepre}haya_blog_article a
            ON tr.article_id = a.id
        $cond
        $orderby 
        LIMIT $offset,$pagesize
    ", $key);
    
    return $r;
}

function haya_blog_tag_relationship_find_articles_count($cond = array()) {
    global $db;
    $tablepre = $db->tablepre;
    
    $cond = haya_blog_tag_db_cond_to_sqladd($cond);
    $arr = db_sql_find_one("
        SELECT COUNT(*) AS num 
        FROM {$tablepre}haya_blog_tag_relationship tr
        LEFT JOIN {$tablepre}haya_blog_tag t
            ON tr.tag_id = t.id
        LEFT JOIN {$tablepre}haya_blog_article a
            ON tr.article_id = a.id
        $cond
    ");
    
    return !empty($arr) ? intval($arr['num']) : $arr;
}

?>