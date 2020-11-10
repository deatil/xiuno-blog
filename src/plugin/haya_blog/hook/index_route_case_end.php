<?php
exit;

case haya_blog_config('blog_name'): 
    include _include(APP_PATH.'plugin/haya_blog/app/blog/route.php'); 
    break;
    
?>