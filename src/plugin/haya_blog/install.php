<?php

defined('DEBUG') OR exit('Forbidden');

/**
 * 博客安装
 *
 * @author deatil
 * @create 2020-3-31
 */

$tablepre = $db->tablepre;

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_apply;
CREATE TABLE {$tablepre}haya_blog_apply (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
    `username` varchar(50) NULL DEFAULT '' COMMENT '申请人姓名',
    `profession` varchar(150) NULL DEFAULT '' COMMENT '申请人职业',
    `email` varchar(250) NOT NULL DEFAULT '' COMMENT '申请人邮箱',
    `phone` varchar(15) NULL DEFAULT '' COMMENT '申请人电话',
    `address` text NULL DEFAULT '' COMMENT '申请人地址',
    `idcard` varchar(50) NULL DEFAULT '' COMMENT '申请人身份证',
    `reason` text NOT NULL DEFAULT '' COMMENT '申请原因',
    `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '申请状态，1-正在申请，2-申请成功，3-申请失败',
    `fail_tip` text NULL DEFAULT '' COMMENT '申请失败原因',
    `type` tinyint(3) NULL DEFAULT '1' COMMENT '类型，1-博客申请，2-博客再次申请',
    `status` tinyint(1) NULL DEFAULT '1' COMMENT '状态，1-正常，0-关闭',
    `finish_date` int(10) NULL DEFAULT '0' COMMENT '申请状态确认时间',
    `finish_ip` int(10) NULL DEFAULT '0' COMMENT '申请状态确认IP',
    `create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    PRIMARY KEY (`id`),
    KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
";
db_exec($sql);

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_category;
CREATE TABLE {$tablepre}haya_blog_category (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类英文名称',
    `title` varchar(150) NOT NULL DEFAULT '' COMMENT '分类名称',
    `keywords` varchar(250) NULL DEFAULT '' COMMENT '关键字',
    `description` varchar(250) NULL DEFAULT '' COMMENT '描述',
    `cover` text NULL DEFAULT '' COMMENT 'cover',
    `sort` int(11) NULL DEFAULT '100' COMMENT '排序',
    `status` tinyint(1) NULL DEFAULT '1' COMMENT '状态，1-正常，2-关闭',
    `update_date` int(10) NULL DEFAULT '0' COMMENT '更新时间',
    `update_ip` int(10) NULL DEFAULT '0' COMMENT '更新IP',
    `create_uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建分类用户ID',
    `create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    PRIMARY KEY (`id`),
    KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
db_exec($sql);

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_author;
CREATE TABLE {$tablepre}haya_blog_author (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
    `name` varchar(25) NOT NULL DEFAULT '' COMMENT '博客英文名称',
    `title` varchar(250) NOT NULL DEFAULT '' COMMENT '博客名称',
    `keywords` varchar(250) NULL DEFAULT '' COMMENT '博客关键字',
    `description` varchar(250) NULL DEFAULT '' COMMENT '博客描述',
    `cover` text NULL DEFAULT '' COMMENT '博客logo',
    `article_pagesize` int(2) NULL DEFAULT '10' COMMENT '博客文章每页数量',
    `is_recommend` tinyint(1) NULL DEFAULT '0' COMMENT '1-推荐',
    `status` tinyint(1) NULL DEFAULT '1' COMMENT '状态，1-正常，2-关闭',
    `update_date` int(10) NULL DEFAULT '0' COMMENT '更新时间',
    `update_ip` int(10) NULL DEFAULT '0' COMMENT '更新IP',
    `create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    KEY `id` (`id`),
    KEY `uid` (`uid`),
    KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
db_exec($sql);

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_author_count;
CREATE TABLE {$tablepre}haya_blog_author_count (
    `author_id` int(11) NOT NULL DEFAULT '0' COMMENT '博客ID',
    `yesterday` int(10) NULL DEFAULT '0' COMMENT '昨天',
    `today` int(10) NULL DEFAULT '0' COMMENT '当天',
    `week` int(10) NULL DEFAULT '0' COMMENT '这周',
    `month` int(10) NULL DEFAULT '0' COMMENT '本月',
    `year` int(10) NULL DEFAULT '0' COMMENT '今年',
    `total` int(10) NULL DEFAULT '0' COMMENT '全部',
    `update_date` int(10) NULL DEFAULT '0' COMMENT '更新时间 上一次时间',
    `update_ip` int(10) NULL DEFAULT '0' COMMENT '更新IP',
    `create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    KEY `author_id` (`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
db_exec($sql);

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_author_category;
CREATE TABLE {$tablepre}haya_blog_author_category (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `author_id` int(11) NOT NULL DEFAULT '0' COMMENT '博客ID',
    `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类英文名称',
    `title` varchar(150) NOT NULL DEFAULT '' COMMENT '分类名称',
    `keywords` varchar(250) NULL DEFAULT '' COMMENT '关键字',
    `description` varchar(250) NULL DEFAULT '' COMMENT '描述',
    `sort` int(11) NULL DEFAULT '100' COMMENT '排序',
    `is_lock` tinyint(1) NULL DEFAULT '0' COMMENT '锁定，1-锁定',
    `is_open` tinyint(1) NULL DEFAULT '1' COMMENT '开启状态，1-启用',
    `status` tinyint(1) NULL DEFAULT '1' COMMENT '状态，1-正常，2-关闭',
    `update_date` int(10) NULL DEFAULT '0' COMMENT '更新时间',
    `update_ip` int(10) NULL DEFAULT '0' COMMENT '更新IP',
    `create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    PRIMARY KEY (`id`),
    KEY `author_id` (`author_id`),
    KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
db_exec($sql);

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_article;
CREATE TABLE {$tablepre}haya_blog_article (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `author_id` int(11) NOT NULL DEFAULT '0' COMMENT '博客ID',
    `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
    `cid` int(11) NOT NULL DEFAULT '0' COMMENT '用户分类ID',
    `title` varchar(250) NOT NULL DEFAULT '' COMMENT '文章名称',
    `keywords` varchar(150) NULL DEFAULT '' COMMENT '文章关键字',
    `description` varchar(255) NULL DEFAULT '' COMMENT '文章描述',
    `content` text NOT NULL DEFAULT '' COMMENT '文章内容',
    `reply` int(11) NULL DEFAULT '0' COMMENT '回复数量',
    `hits` int(11) NULL DEFAULT '0' COMMENT '阅读数量',
    `is_top` tinyint(1) NULL DEFAULT '0' COMMENT '1-置顶',
    `is_recommend` tinyint(1) NULL DEFAULT '0' COMMENT '1-推荐',
    `is_lock` tinyint(1) NULL DEFAULT '0' COMMENT '锁定，1-锁定',
    `is_reprinted` tinyint(1) NULL DEFAULT '0' COMMENT '1-转载',
    `reprinted_from` text NULL DEFAULT '' COMMENT '转载来源',
    `is_private` tinyint(1) NULL DEFAULT '0' COMMENT '1-私有',
    `is_author_top` tinyint(1) NULL DEFAULT '0' COMMENT '1-用户置顶',
    `is_reply` tinyint(1) NULL DEFAULT '1' COMMENT '开启评论，1-开启，0-关闭',
    `status` tinyint(1) NULL DEFAULT '1' COMMENT '状态，1-正常，2-关闭',
    `reply_date` int(10) NULL DEFAULT '0' COMMENT '最后回复时间',
    `reply_ip` int(10) NULL DEFAULT '0' COMMENT '最后回复IP',
    `update_date` int(10) NULL DEFAULT '0' COMMENT '更新时间',
    `update_ip` int(10) NULL DEFAULT '0' COMMENT '更新IP',
    `create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    KEY `id` (`id`),
    KEY `author_id` (`author_id`),
    KEY `category_id` (`category_id`),
    KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
db_exec($sql);

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_article_comment;
CREATE TABLE {$tablepre}haya_blog_article_comment (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
    `aid` int(11) NOT NULL DEFAULT '0' COMMENT '文章ID',
    `reply` int(11) NULL DEFAULT '0' COMMENT '回复评论ID',
    `content` text NOT NULL DEFAULT '' COMMENT '评论内容',
    `is_lock` tinyint(1) NULL DEFAULT '1' COMMENT '锁定，1-锁定',
    `status` tinyint(1) NULL DEFAULT '1' COMMENT '状态，1-正常，2-关闭',
    `update_date` int(10) NULL DEFAULT '0' COMMENT '更新时间',
    `update_ip` int(10) NULL DEFAULT '0' COMMENT '更新IP',
    `create_date` int(10) NULL DEFAULT '0' COMMENT '添加时间',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    PRIMARY KEY (`id`),
    KEY `aid` (`aid`),
    KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
db_exec($sql);

$sql = "
DROP TABLE IF EXISTS {$tablepre}haya_blog_attach;
CREATE TABLE {$tablepre}haya_blog_attach (
    `id` int(11) unsigned NOT NULL auto_increment ,
    `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
    `use_id` varchar(32) NULL default NULL COMMENT '使用对应ID',
    `filename` char(120) NOT NULL default '' '上传后名称',
    `orgfilename` char(120) NOT NULL default '' COMMENT '原始名称',
    `fileext` char(20) NOT NULL default '' COMMENT '文件后缀',
    `filetype` char(7) NOT NULL default '' COMMENT '文件类型',
    `filesize` int(11) NOT NULL default '0' COMMENT '文件大小',
    `filemd5` varchar(32) NOT NULL default '' COMMENT '文件md5值',
    `create_date` int(11) unsigned NOT NULL default '0',
    `create_ip` int(10) NULL DEFAULT '0' COMMENT '添加IP',
    PRIMARY KEY (id),
    KEY filemd5 (filemd5),
    KEY filename (filename),
    KEY use_id (use_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
db_exec($sql);

// 添加插件配置
$haya_blog_config = array(
    "open_blog" => 1,
    "close_blog_tip" => '博客当前维护中~',
    
    "blog_name" => 'haya_blog',
    "blog_article_pagesize" => 10,
    "blog_article_comment_pagesize" => 10,
    "open_article_comment" => 1,
    
    "blog_author_category_max_num" => 5,
    "blog_author_article_pagesize" => 10,
    
    "articlemod_group" => array(),
    
    "open_apply_group" => 0,
    "apply_group" => array(),
    "apply_group_tip" => '当前用户组不能够申请博客！',
);
kv_set('haya_blog', $haya_blog_config); 


?>