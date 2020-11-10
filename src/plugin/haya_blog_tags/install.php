<?php

/**
 * 博客标签
 *
 * @create 2020-7-7
 * @author deatil
 */
 
!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_tag;
CREATE TABLE {$tablepre}haya_blog_tag (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) unsigned NOT NULL DEFAULT '' COMMENT '标签名称',
    `keywords` varchar(250) NULL DEFAULT '' COMMENT '关键字',
    `description` varchar(250) NULL DEFAULT '' COMMENT '描述',
    `views` int(11) NULL DEFAULT '0' COMMENT '阅读量',
    `sort` int(11) NULL DEFAULT '100' COMMENT '排序',
    `is_recommend` tinyint(1) NULL DEFAULT '0' COMMENT '1-推荐',
    `status` tinyint(1) NULL DEFAULT '1' COMMENT '状态，1-正常，2-关闭',
    `update_date` int(10) NULL DEFAULT '0' COMMENT '更新时间',
    `update_ip` int(10) NULL DEFAULT '0' COMMENT '更新IP',
    `create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    KEY `id` (`id`),
    KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
db_exec($sql);

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_tag_relationship;
CREATE TABLE {$tablepre}haya_blog_tag_relationship (
    `tag_id` int(11) NOT NULL COMMENT '标签ID',
    `article_id` int(11) NOT NULL COMMENT '文章ID',
    `create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    KEY `article_id` (`article_id`),
    KEY `tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
db_exec($sql);

db_exec("ALTER TABLE {$tablepre}haya_blog_article ADD COLUMN tags text NULL DEFAULT '' COMMENT '标签';");

// 插件配置
$haya_blog_tags_config = array(
    "max_tag_num" => 10,
    "tag_pagesize" => 20,
    "article_pagesize" => 10,
);
kv_set('haya_blog_tags', $haya_blog_tags_config); 

?>