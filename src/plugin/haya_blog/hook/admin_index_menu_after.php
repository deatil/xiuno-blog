<?php
exit;

$haya_blog_config_menu = array(
    'haya_blog' => array(
        'url' => url('haya_blog-setting'), 
        'text' => '博客', 
        'icon' => 'icon-file',
        'tab' => array (
        )
    ),
);

$menu = array_merge($menu, $haya_blog_config_menu);

?>