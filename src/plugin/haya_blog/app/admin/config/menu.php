<?php

/**
 * 博客后台菜单
 * 
 * @author deatil
 * @create 2020-3-30
 */

return array(
    'haya_blog' => array(
        'url' => 'javascript:;',
        'text' => '博客',
        'class' => 'btn-info mr-1',
        'icon' => 'icon-file',
    ),
    
    'setting' => array(
        'url' => url('haya_blog-setting'),
        'text' => '设置',
    ),
    'apply' => array(
        'url' => url('haya_blog-apply'),
        'text' => '申请',
    ),
    'category' => array(
        'url' => url('haya_blog-category'),
        'text' => '分区',
    ),
    'author' => array(
        'url' => url('haya_blog-author'),
        'text' => '专栏',
    ),
    'author_category' => array(
        'url' => url('haya_blog-author_category'),
        'text' => '分类',
    ),
    'article' => array(
        'url' => url('haya_blog-article'),
        'text' => '文章',
    ),
    'comment' => array(
        'url' => url('haya_blog-comment'),
        'text' => '文章评论',
    ),
    'attach' => array(
        'url' => url('haya_blog-attach'),
        'text' => '附件',
    ),
);

