<?php

define('avatarboxpath', plugin_dir_path(__FILE__));
define('avatarboxurl', plugin_dir_url(__FILE__ ));
if (PHP_VERSION_ID < 70000) {
    wp_die('PHP 版本过低，请先升级php版本到7.0及以上版本，当前php版本为：' . PHP_VERSION);
}
//载入文件
$require_once = array(
    'codestar-framework/codestar-framework.php',
    'options/options.php',
    'code/class.php',
    'code/code.php',
    'code/initialize.php',
    'code/modal.php',
    'options/admin-options.php',
);

foreach ($require_once as $require) {
    require avatarboxpath.$require;
}

