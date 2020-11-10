<?php

/**
 * 返回json
 *
 * @author deatil
 * @create 2020-7-3
 */
function haya_blog_wangeditor_json($errno = 0, $data) {
    $arr = array(
        'errno' => $errno,
        'data' => $data,
    );
    
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($arr, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}

?>