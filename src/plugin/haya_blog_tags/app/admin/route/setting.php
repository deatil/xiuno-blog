<?php

defined('DEBUG') OR exit('Forbidden');

/**
 * 博客标签设置
 *
 * @create 2020-7-9
 * @author deatil
 */
 
$header['title'] = "设置 - 博客标签";

if ($method == 'GET') {
    
    $config = kv_get('haya_blog_tags');
    
    include _include($admin_view . '/setting.htm');
    
} else {
    
    $config = array();
    
    $config['max_tag_num'] = param('max_tag_num', 10);
    $config['tag_pagesize'] = param('tag_pagesize', 20);
    $config['article_pagesize'] = param('article_pagesize', 10);
    
    kv_set('haya_blog_tags', $config); 
    
    message(0, jump('设置保存成功', url('haya_blog_tags-setting')));
}


?>
