<?php

/**
 * 博客标签
 *
 * @create 2020-7-7
 * @author deatil
 */

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;

db_exec("DROP TABLE IF EXISTS {$tablepre}haya_blog_tag;");
db_exec("DROP TABLE IF EXISTS {$tablepre}haya_blog_tag_relationship;");

db_exec("ALTER TABLE {$tablepre}haya_blog_article DROP COLUMN tags;");

// 删除插件配置
kv_delete('haya_blog_tags'); 

?>