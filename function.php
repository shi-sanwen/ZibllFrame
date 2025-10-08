<?php
/*
plugin name: 子比头像框插件
plugin URI: https://cmxj.song3060.top/
description: 子比头像框插件
author: 是史三问呀
author URI: https://cmxj.song3060.top/
version: 1.1

*/
if ( ! function_exists('avatarbox')) {
/*
不要修改！不要修改！不要修改！
*/
  function avatar( $option = '', $default = null ) {
    $options = get_option('avatarbox'); // Attention: Set your unique id of the framework
    return ( isset( $options[$option] ) ) ? $options[$option] : $default;
  }

}


define('avatarbox_TEMPLATE_DIRECTORY_URI',  plugin_dir_path( __FILE__ ));
define('avatarbox_ROOT_PATH', dirname(__DIR__) . '/');
define('avatarbox', plugin_dir_url( __FILE__ ) );
define('avatarboxpaths', dirname(__DIR__) . '/');
require  plugin_dir_path(__FILE__) .'inc/inc.php';


//版本号获取
function avatarbox_Version($dete)
{
  // 获取插件主文件路径
  	if( ! function_exists('get_plugin_data') ){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	$plugin_data = get_plugin_data( __FILE__ );
    return $plugin_data[$dete];
}

