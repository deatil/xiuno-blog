<?php
exit;

$data['tags'] = param('tags', '');

haya_blog_tag_relationship_delete_by_article_id($id);
$haya_blog_tags = explode(',', $data['tags']);
$haya_blog_tags_config = kv_get('haya_blog_tags');
if (count($haya_blog_tags) > $haya_blog_tags_config['max_tag_num']) {
    message(-1, '文章标签过多，请删除一些后重试');
}
haya_blog_tags_create($id, $haya_blog_tags);

?>