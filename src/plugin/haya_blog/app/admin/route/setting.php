<?php

defined('DEBUG') OR exit('Forbidden');

/**
 * 博客设置
 *
 * @create 2020-3-25
 * @author deatil
 */
 
$header['title'] = "博客设置";

// hook plugin_haya_blog_admin_setting_start.php

if ($method == 'GET') {
    
    $config = kv_get('haya_blog');
    
    // hook plugin_haya_blog_admin_setting_get_config_after.php
    
    include _include($haya_blog_admin_view . '/setting.htm');
    
} else {
    
    $config = array();
    
    $config['open_blog'] = param('open_blog', 1);
    $config['close_blog_tip'] = param('close_blog_tip', '博客当前维护中~');
    
    $config['blog_name'] = param('blog_name', 'haya_blog');
    $config['blog_article_pagesize'] = param('blog_article_pagesize', 10);
    $config['blog_article_comment_pagesize'] = param('blog_article_comment_pagesize', 10);
    $config['open_article_comment'] = param('open_article_comment', 1);
    
    $config['blog_author_category_max_num'] = param('blog_author_category_max_num', 5);
    $config['blog_author_article_pagesize'] = param('blog_author_article_pagesize', 10);

    $config['articlemod_group'] = param('articlemod_group', array());
    
    $config['open_apply_group'] = param('open_apply_group', 0);
    $config['apply_group'] = param('apply_gid', array());
    $config['apply_group_tip'] = param('apply_group_tip', '');
    
    // hook plugin_haya_blog_admin_setting_get_set_config_before.php
    
    kv_set('haya_blog', $config); 
    
    // hook plugin_haya_blog_admin_setting_get_set_config_after.php
    
    message(0, jump('设置保存成功', url('haya_blog-setting')));
}

// hook plugin_haya_blog_admin_setting_end.php

?>
