<?php

/**
 * 博客标签后台菜单
 * 
 * @author deatil
 * @create 2020-7-9
 */

return array(
    'haya_blog_tags' => array(
        'url' => 'javascript:;',
        'text' => '博客标签',
        'class' => 'btn-info mr-1',
        'icon' => 'icon-tags',
    ),
    
    'setting' => array(
        'url' => url('haya_blog_tags-setting'),
        'text' => '设置',
    ),
    'tag' => array(
        'url' => url('haya_blog_tags-tag'),
        'text' => '标签',
    ),
);

