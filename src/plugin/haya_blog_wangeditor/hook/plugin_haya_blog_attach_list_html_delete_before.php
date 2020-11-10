<?php
exit;

$s .= '<span class="text-grey">('.haya_blog_humandate($attach['create_date'], true).')</span>';
$include_delete AND $s .= '<a href="javascript:void(0)" class="ml-3 js-haya-blog-attach-copy" data-img="'.haya_blog_attach_url($attach['filename']).'"><i class="icon-image"></i> 复制图片</a>'."\r\n";

?>