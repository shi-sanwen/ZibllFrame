<?php
class avatarbox
{
    public static function ajaxform_options($user_id)
    {
        echo user_avatarbox($user_id);
        exit;
    }

    public static function avatarbox_author_list_modal($user_id)
    {
        echo avatarbox_author_list_modal($user_id);
        exit;
    }

    public static function avatarbox_author_list_admin($user_id)
    {
        echo avatarbox_author_list_admin($user_id);
        exit;
    }

    public static function update()
    {

        $csf[] = array(
            'title'   => '系统环境',
            'type'    => 'content',
            'content' => '<div style="margin-left:14px;"><li><strong>操作系统</strong>： ' . PHP_OS . ' </li>
            <li><strong>运行环境</strong>： ' . $_SERVER["SERVER_SOFTWARE"] . ' </li>
            <li><strong>PHP版本</strong>： ' . PHP_VERSION . ' </li>
            <li><strong>PHP上传限制</strong>： ' . ini_get('upload_max_filesize') . ' </li>
            <li><strong>WordPress版本</strong>： ' . get_bloginfo('version') . '</li>
            <li><strong>系统信息</strong>： ' . php_uname() . ' </li>
            <li><strong>服务器时间</strong>： ' . current_time('mysql') . '</li></div>
            <a class="but c-yellow" href="' . admin_url('site-health.php?tab=debug') . '">查看更多系统信息</a>',
        );
        $csf[] = array(
            'title'   => '推荐环境',
            'type'    => 'content',
            'content' => '<div style="margin-left:14px;"><li><strong>WordPress</strong>：5.0+，推荐使用最新版</li>
            <li><strong>PHP</strong>：PHP5.6及以上，推荐使用7.0以上</li>
            <li><strong>服务器配置</strong>：无要求，最低配都行</li>
            <li><strong>操作系统</strong>：无要求，不推荐使用Windows系统</li></div>',
        );

        return $csf;
    }
}
