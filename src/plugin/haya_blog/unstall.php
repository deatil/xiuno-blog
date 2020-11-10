<?php

defined('DEBUG') OR exit('Forbidden');

/**
 * 博客卸载
 *
 * @author deatil
 * @create 2020-3-31
 */

$tablepre = $db->tablepre;

db_exec("DROP TABLE IF EXISTS {$tablepre}haya_blog_apply;");
db_exec("DROP TABLE IF EXISTS {$tablepre}haya_blog_category;");
db_exec("DROP TABLE IF EXISTS {$tablepre}haya_blog_author;");
db_exec("DROP TABLE IF EXISTS {$tablepre}haya_blog_author_category;");
db_exec("DROP TABLE IF EXISTS {$tablepre}haya_blog_article;");
db_exec("DROP TABLE IF EXISTS {$tablepre}haya_blog_article_comment;");
db_exec("DROP TABLE IF EXISTS {$tablepre}haya_blog_attach;");

// 删除插件配置
kv_delete('haya_blog'); 

?>
