<?php
/*
Plugin Name: 子比头像框
Plugin URI: https://www.scmgzs.top/
Description:子比头像框
Author: 是史三问他
Version:1
Author URI: https://www.scmgzs.top/
*/

//使用Font Awesome 4
add_filter('csf_fa4', '__return_true');
//开启SVG保存
function avatarbox_svg_in_kses($tags) {
    $tags['svg'] = array(
        'xmlns' => true,
        'class' => true,
        'width' => true,
        'height' => true,
        'viewbox' => true,
        'aria-hidden' => true,
        'focusable' => true,
    );
    $tags['g'] = array(
        'fill' => true,
        'transform' => true,
    );
    $tags['path'] = array(
        'd' => true,
        'fill' => true,
    );
    return $tags;
}
add_filter( 'wp_kses_allowed_html', 'avatarbox_svg_in_kses', 10, 2 );


// 获取及设置主题配置参数
function _avatar($name, $default = false, $subname = '')
{
    //声明静态变量，加速获取
    static $options = null;
    if ($options === null) {
        $options = get_option('aut');
    }

    if (isset($options[$name])) {
        if ($subname) {
            return isset($options[$name][$subname]) ? $options[$name][$subname] : $default;
        } else {
            return $options[$name];
        }
    }
    return $default;
}


function _avatarbox($name, $value)
{
    $get_option        = get_option('avatarbox');
    $get_option        = is_array($get_option) ? $get_option : array();
    $get_option[$name] = $value;
    return update_option('avatarbox', $get_option);
}