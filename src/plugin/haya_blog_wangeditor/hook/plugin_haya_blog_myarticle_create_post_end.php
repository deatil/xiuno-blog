<?php
exit;

$sess_haya_blog_wangeditor_files = _SESSION('haya_blog_wangeditor_files');
if (!empty($sess_haya_blog_wangeditor_files)) {
    foreach($sess_haya_blog_wangeditor_files as $attach) {
        if (isset($attach) && !empty($attach)) {
            haya_blog_attach__update($attach['id'], array(
                'use_id' => md5('haya-blog-article-'.$article_id),
            ));
        }
    }
    
    $_SESSION['haya_blog_wangeditor_files'] = array();
}

?>