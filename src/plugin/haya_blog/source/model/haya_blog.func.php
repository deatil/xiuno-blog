<?php

!defined('DEBUG') and exit('Access Denied.');


/**
 * 后台导航
 *
 * @author deatil
 * @create 2020-3-30
 */
function haya_blog_admin_tab_active($arr, $active) {
    $s = '';
    
    foreach ($arr as $k => $v) {
        $class = '';
        if (isset($v['class'])) {
            $class = ' ' . $v['class'];
        }
        
        $icon = '';
        if (isset($v['icon'])) {
            $icon = '<i class="' . $v['icon'] . '"></i> ';
        }
        
        $s .= '<a role="button" class="btn btn-secondary'. $class . ($active == $k ? ' active' : '').'" href="'.$v['url'].'">' . $icon . $v['text'] . '</a>';
    }
    return $s;
}

/**
 * 博客配置
 *
 * @author deatil
 * @create 2020-3-31
 */
function haya_blog_config($key = '', $default = '') {
    static $config;
    
    if (empty($config)) {
        $config = kv_get('haya_blog');
    }
    
    if (empty($key)) {
        return $config;
    }
    
    // hook plugin_haya_blog_config_end.php
    
    if (isset($config[$key])) {
        return !empty($config[$key]) ? $config[$key] : $default;
    }
    
    return $default;
}

/**
 * 博客前台视图
 *
 * @author deatil
 * @create 2020-3-31
 */
function haya_blog_theme_view($view = '') {
    if (empty($view)) {
        return '';
    }
    
    $view_htm = APP_PATH.'plugin/haya_blog/app/blog/view/htm';
    
    // hook plugin_haya_blog_theme_view_htm_end.php
    
    $view_file = $view_htm . '/' . ltrim($view, '/');
    
    return _include($view_file);
}

/**
 * 博客静态文件爱你
 *
 * @author deatil
 * @create 2020-6-13
 */
function haya_blog_static($file, $is_admin = false) {
    global $conf;
    
    // hook plugin_haya_blog_static.php
    
    $static_file = 'plugin/haya_blog/static/' . trim($file, '/');
    
    if ($is_admin) {
        $static_file = '../' . ltrim($static_file, '/');
    }
    
    // hook plugin_haya_blog_static_file_after.php
    
    return $static_file;
}

/**
 * 博客前台链接生成
 *
 * @author deatil
 * @create 2020-4-2
 */
function haya_blog_url($url = '', $extra = array()) {
    // hook plugin_haya_blog_url_start.php
    
    $blog_name = haya_blog_config('blog_name');
    if (empty($blog_name)) {
        return $blog_name = 'haya_blog';
    }
    
    // hook plugin_haya_blog_url_middle.php
    
    if (!empty($url)) {
        $url = $blog_name . '-' . trim($url, '-');
    } else {
        $url = $blog_name;
    }
    
    // hook plugin_haya_blog_url_end.php
    
    return url($url, $extra);
}

/**
 * 博客附件url
 *
 * @author deatil
 * @create 2020-5-17
 */
function haya_blog_attach_url($attach) {
    global $conf;
    
    // hook plugin_haya_blog_attach_url.php
    
    return $conf['upload_url'] . 'haya_blog/' . $attach;
}

/**
 * 博客附件url在后台显示
 *
 * @author deatil
 * @create 2020-5-19
 */
function haya_blog_attach_url_in_admin($attach) {
    global $conf;
    
    // hook plugin_haya_blog_attach_url_in_admin.php
    
    return '../' . $conf['upload_url'] . 'haya_blog/' . $attach;
}

/**
 * 博客附件path
 *
 * @author deatil
 * @create 2020-5-17
 */
function haya_blog_attach_path($attach) {
    global $conf;
    
    // hook plugin_haya_blog_attach_path.php
    
    return $conf['upload_path'] . 'haya_blog/' . $attach;
}

/**
 * 博客上传文件格式化
 *
 * @author deatil
 * @create 2020-7-3
 */
function haya_blog_format_attach_files($files) {
    $fileArray = array();
    $n = 0;
    
    foreach ($files as $key => $file) {
        if (is_array($file['name'])) {
            $keys = array_keys($file);
            $count = count($file['name']);
            for ($i = 0; $i < $count; $i++) {
                $fileArray[$n]['key'] = $key;
                foreach ($keys as $_key) {
                    $fileArray[$n][$_key] = $file[$_key][$i];
                }
                $n++;
            }
        } else {
            $fileArray[$n] = $file;
            $fileArray[$n]['key'] = $key;
            $n++;
        }
    }
    
    return $fileArray;
}

/**
 * 博客附件上传
 *
 * @author deatil
 * @create 2020-5-20
 */
function haya_blog_attach_upload($files) {
    $tmp_file = $files["tmp_name"];
    if (empty($tmp_file)) {
        return false;
    }
    
    if ($files["error"] != 0) {
        return false;
    }
    
    if (!is_uploaded_file($tmp_file)) {
        return false;
    }
    
    // hook plugin_haya_blog_attach_upload_before.php
    
    // 文件原始名
    $orgfilename = $files["name"];
    
    $filemd5 = hash_file('md5', $tmp_file);
    
    $file_path = haya_blog_attach_path(date('Ymd').'/');
    if (!file_exists($file_path)) {
        mkdir($file_path, 0777, true);
    }
    
    $ext = file_ext($orgfilename, 7);
    $filename = date('Ymd').'/'.date('His').'_'.xn_rand(10).'.'.$ext;
    $file = haya_blog_attach_path($filename);
    
    $filetypes = include APP_PATH.'conf/attach.conf.php';
    !in_array($ext, $filetypes['all']) AND $ext = '_'.$ext;
    $filetype = attach_type($orgfilename, $filetypes);
    $filesize = filesize($tmp_file);
    
    if (!move_uploaded_file($tmp_file, $file)) {
        return false;
    }
    
    $data = array(
        'filename' => $filename,
        'orgfilename' => $orgfilename,
        'fileext' => $ext, 
        'filetype' => $filetype,
        'filesize' => $filesize, 
        'filemd5' => $filemd5, 
    );
    
    // hook plugin_haya_blog_attach_upload_create_before.php
    
    $id = haya_blog_attach_create($data);
    if ($id === false) {
        unlink($file);
        return false;
    }
    
    $data['id'] = $id;
    
    // hook plugin_haya_blog_attach_upload_create_after.php
    
    return $data;
}

/**
 * 博客附件删除
 *
 * @author deatil
 * @create 2020-7-4
 */
function haya_blog_attach_delete($id) {
    $attach = haya_blog_attach__read($id);
    if (empty($attach)) {
        return false;
    }
    
    // hook plugin_haya_blog_attach_delete_before.php
    
    $filename = $attach['filename'];
    $file = haya_blog_attach_path($filename);

    $status = haya_blog_attach__delete($id);
    if ($status === false) {
        return false;
    }
    
    if (file_exists($file)) {
        @unlink($file);
    }
    
    // hook plugin_haya_blog_attach_delete_after.php
    
    return true;
}

/**
 * 文章附件
 *
 * @author deatil
 * @create 2020-7-5
 */
function haya_blog_attach_list_html($filelist, $include_delete = FALSE) {
    if (empty($filelist)) {
        return '';
    }
    
    // hook plugin_haya_blog_attach_list_html_start.php
    
    $s = '<fieldset class="fieldset haya-blog-fieldset">'."\r\n";
    $s .= '<legend>附件：</legend>'."\r\n";
    $s .= '<ul class="list-unstyled haya-blog-attachlist">'."\r\n";
    foreach ($filelist as $attach) {
        $s .= '<li class="haya-blog-attach-item haya-blog-attach-'.$attach['id'].'" aid="'.$attach['id'].'">'."\r\n";
        $s .= '     <a href="javascript:;" class="haya-blog-attach-link" data-img="'.haya_blog_attach_url($attach['filename']).'">'."\r\n";
        $s .= '         '.$attach['orgfilename']."\r\n";
        $s .= '     </a>'."\r\n";
        // hook plugin_haya_blog_attach_list_html_delete_before.php
        $include_delete AND $s .= '<a href="javascript:void(0)" class="delete ml-3 js-haya-blog-attach-delete" data-id="'.$attach['id'].'"><i class="icon-remove"></i> '.lang('delete').'</a>'."\r\n";
        // hook plugin_haya_blog_attach_list_html_delete_after.php
        $s .= '</li>'."\r\n";
    };
    $s .= '</ul>'."\r\n";
    $s .= '</fieldset>'."\r\n";
    
    // hook plugin_haya_blog_attach_list_html_end.php
    
    return $s;
}

/**
 * 检测是否可以申请专栏
 *
 * @author deatil
 * @create 2020-5-24
 */
function haya_blog_check_apply($gid = '') {
    if (empty($gid)) {
        return false;
    }
    
    $config = haya_blog_config();
    
    if (!isset($config['open_apply_group']) 
        || !isset($config['apply_group'])
    ) {
        return false;
    }
    
    if (!$config['open_apply_group']) {
        return false;
    }
    
    if (!in_array($gid, $config['apply_group'])) {
        return false;
    }
    
    return true;
}


/**
 * 检测用户是否申请了专栏并且正常使用
 *
 * @author deatil
 * @create 2020-5-24
 */
function haya_blog_check_author($uid = '') {
    if (empty($uid)) {
        return false;
    }
    
    $author = haya_blog_author_read_by_uid($uid);
    if (empty($author)) {
        return false;
    }
    
    if (!$author['status']) {
        return false;
    }
    
    return true;
}


/**
 * 用户访问统计
 *
 * @author deatil
 * @create 2020-7-1
 */
function haya_blog_author_count_update($author_id) {
    global $longip;
    
    if (empty($author_id)) {
        return false;
    }
    
    $author_count = haya_blog_author_count_read_by_author_id($author_id);
    if (empty($author_count)) {
        haya_blog_author_count__create(array(
            'author_id' => $author_id,
            'yesterday' => 0,
            'today' => 1,
            'week' => 1,
            'month' => 1,
            'year' => 1,
            'total' => 1,
            'update_date' => time(),
            'update_ip' => $longip,
            'create_date' => time(),
            'create_ip' => $longip,
        ));
    } else {
        $data = array(
            'update_date' => time(),
            'update_ip' => $longip,
        );
        
        $last_date = $author_count['update_date'];
        if (haya_blog_check_same_day($last_date, time())) {
            $data['today+'] = 1;
        } else {
            $data['yesterday'] = $author_count['today'];
            $data['today'] = 1;
        }
        if (haya_blog_check_same_week($last_date, time())) {
            $data['week+'] = 1;
        } else {
            $data['week'] = 1;
        }
        if (haya_blog_check_same_month($last_date, time())) {
            $data['month+'] = 1;
        } else {
            $data['month'] = 1;
        }
        if (haya_blog_check_same_year($last_date, time())) {
            $data['year+'] = 1;
        } else {
            $data['year'] = 1;
        }
        
        $data['total+'] = 1;
        
        haya_blog_author_count_update_by_author_id($author_id, $data);
    }
}

/**
 * 时间格式化
 *
 * @author deatil
 * @create 2020-6-30
 */
function haya_blog_humandate($timestamp, $show_second = false) {
    $time = $_SERVER['time'];
    
    $lan = array(
        'yesterday' => '昨天 ',
        'today' => '今天 ',
        'hour_ago' => '小时前',
        'minute_ago' => '分钟前',
        'second_ago' => '秒前',
    );
    
    if ($show_second) {
        $time_second = 'H:i:s';
    } else {
        $time_second = 'H:i';
    }
    
    $seconds = $time - $timestamp;
    if ($seconds > 43200) {
        if (date('Y-m-d', $timestamp) == date('Y-m-d')) {
            return $lan['today'].date($time_second, $timestamp);
        } elseif (date('Y-m-d', $timestamp) == date('Y-m-d', strtotime("-1 day"))) {
            return $lan['yesterday'].date($time_second, $timestamp);
        } elseif (date('Y', $timestamp) == date('Y')) {
            return date('m-d '.$time_second, $timestamp);
        } else {
            return date('Y-m-d '.$time_second, $timestamp);
        }
    } elseif($seconds > 3600) {
        return floor($seconds / 3600).$lan['hour_ago'];
    } elseif($seconds > 60) {
        return floor($seconds / 60).$lan['minute_ago'];
    } else {
        return $seconds.$lan['second_ago'];
    }
}

/**
 * 判断两日期是不是同一天
 *
 * @author deatil
 * @create 2020-7-1
 */
function haya_blog_check_same_day($time1, $time2) {
    $m1 = date('Ymd', $time1);
    $m2 = date('Ymd', $time2);
    if ($m1 == $m2) {
        return true;
    }
    
    return false;
}

/**
 * 判断两日期是不是同一周
 * 星期是按周日到周六
 *
 * @author deatil
 * @create 2020-7-1
 */
function haya_blog_check_same_week($pretime, $aftertime) {
    $flag = false;//默认不是同一周
    $afweek = date('w', $aftertime);//当前是星期几
    $mintime = $aftertime - $afweek * 3600*24;//一周开始时间
    $maxtime = $aftertime + (7-$afweek)*3600*24;//一周结束时间
    if ( $pretime >= $mintime && $pretime <= $maxtime) { //同一周
        $flag = true;
    }
    
    return $flag;
}

/**
 * 判断两日期是不是同一月
 *
 * @author deatil
 * @create 2020-7-1
 */
function haya_blog_check_same_month($time1, $time2) {
    $m1 = date('Ym', $time1);
    $m2 = date('Ym', $time2);
    // 必须判断年份，不然2019-8和2020-8会被认为同一月
    if ($m1 == $m2) {
        return true;
    }
    
    return false;
}

/**
 * 判断两日期是不是同一年
 *
 * @author deatil
 * @create 2020-7-1
 */
function haya_blog_check_same_year($time1, $time2) {
    $m1 = date('Y', $time1);
    $m2 = date('Y', $time2);
    if ($m1 == $m2) {
        return true;
    }
    
    return false;
}

/**
 * 友好显示数字
 *
 * @author deatil
 * @create 2020-7-2
 */
function haya_blog_friend_num($num) {
    if (empty($num) || !is_numeric($num)) {
        return 0;  
    }
    
    if ($num >= 100000) {
        $num = round($num / 10000) .'w+';
    } elseif ($num >= 10000) {
        $num = round($num / 10000, 1) .'w+';
    } elseif ($num >= 1000) {
        $num = round($num / 1000, 1) . 'k+';
    }
    
    return $num;
}

/**
 * 文件大小友好显示
 *
 * @author deatil
 * @create 2020-7-5
 */
function haya_blog_humansize($num) {
    if ($num > 1125899906842624) {
        return number_format($num / 1125899906842624, 2, '.', '').'P+';
    } elseif ($num > 1099511627776) {
        return number_format($num / 1099511627776, 2, '.', '').'T+';
    } elseif ($num > 1073741824) {
        return number_format($num / 1073741824, 2, '.', '').'G+';
    } elseif ($num > 1048576) {
        return number_format($num / 1048576, 2, '.', '').'M+';
    } elseif ($num > 1024) {
        return number_format($num / 1024, 2, '.', '').'K+';
    } else {
        return $num.'B';
    }
}