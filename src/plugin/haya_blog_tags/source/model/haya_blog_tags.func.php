<?php

!defined('DEBUG') and exit('Access Denied.');

/**
 * 后台导航
 *
 * @author deatil
 * @create 2020-7-9
 */
function haya_blog_tags_admin_tab_active($arr, $active) {
    $s = '';
    
    foreach ($arr as $k => $v) {
        $class = '';
        if (isset($v['class'])) {
            $class = ' ' . $v['class'];
        }
        
        $icon = '';
        if (isset($v['icon'])) {
            $icon = '<i class="' . $v['icon'] . '"></i> ';
        }
        
        $s .= '<a role="button" class="btn btn-secondary'. $class . ($active == $k ? ' active' : '').'" href="'.$v['url'].'">' . $icon . $v['text'] . '</a>';
    }
    return $s;
}

/**
 * 标签
 *
 * @author deatil
 * @create 2020-7-7
 */

function haya_blog_tags_create($article_id, $tags) {
    if (empty($article_id) || empty($tags)) {
        return false;
    }
    if (!is_array($tags)) {
        return false;
    }
    
    foreach ($tags as $tag) {
        haya_blog_tags_article_add_tag($article_id, $tag);
    }

    return true;
}

function haya_blog_tags_article_add_tag($article_id, $tag) {
    global $longip;
    
    $tag1 = haya_blog_tag_read_by_name($tag);
    if (empty($tag1)) {
        $tag_id = haya_blog_tag__create(array(
            'name' => $tag,
            'status' => 1,
            'update_date' => time(),
            'update_ip' => $longip,
            'create_date' => time(),
            'create_ip' => $longip,
        ));
        
        if ($tag_id === false) {
            return false;
        }
    } else {
        $tag_id = $tag1['id'];
    }
    
    $relationship = haya_blog_tag_relationship_read_by_tag_id_and_article_id($tag_id, $article_id);
    if (empty($relationship)) {
        haya_blog_tag_relationship__create(array(
            'tag_id' => $tag_id,
            'article_id' => $article_id,
            'create_date' => time(),
            'create_ip' => $longip,
        ));
    }

    return true;
}

/**
 * 时间格式化
 *
 * @author deatil
 * @create 2020-7-13
 */
function haya_blog_tags_humandate($timestamp, $show_second = false) {
    $time = $_SERVER['time'];
    
    $lan = array(
        'yesterday' => '昨天 ',
        'today' => '今天 ',
        'hour_ago' => '小时前',
        'minute_ago' => '分钟前',
        'second_ago' => '秒前',
    );
    
    if ($show_second) {
        $time_second = 'H:i:s';
    } else {
        $time_second = 'H:i';
    }
    
    $seconds = $time - $timestamp;
    if ($seconds > 43200) {
        if (date('Y-m-d', $timestamp) == date('Y-m-d')) {
            return $lan['today'].date($time_second, $timestamp);
        } elseif (date('Y-m-d', $timestamp) == date('Y-m-d', strtotime("-1 day"))) {
            return $lan['yesterday'].date($time_second, $timestamp);
        } elseif (date('Y', $timestamp) == date('Y')) {
            return date('m-d '.$time_second, $timestamp);
        } else {
            return date('Y-m-d '.$time_second, $timestamp);
        }
    } elseif($seconds > 3600) {
        return floor($seconds / 3600).$lan['hour_ago'];
    } elseif($seconds > 60) {
        return floor($seconds / 60).$lan['minute_ago'];
    } else {
        return $seconds.$lan['second_ago'];
    }
}


?>