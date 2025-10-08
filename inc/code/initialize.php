<?php
function avatarbox_js_code()
{
?>
    <script type="text/javascript">
        $(function() {
            $('.avatar-set-link').css('z-index', '1');
                   $('.author-header .header-content .header-info .header-avatar')
.css('display', 'flex') .css('align-items', 'center');
            $('.avatarboxs .avatar').css('width', '120px', 'height', '120px');
        })
    </script>
<?php
}
add_action('wp_head', 'avatarbox_js_code');
function avatarbox_styles()
{

?>
    <style>
        .top-user-box-drop .avatar {
            border-radius: 50%;
        }

        .comment .gravatar img {
            border-radius: 50%;
        }

        /*用户中心头像圆形*/
        .author-header .avatar-img {
            --this-size: 95px;
        }

        .author-header .avatar-img .avatar {
            border-radius: 50px;
        }

        .item-meta .avatar-mini {
            transform: translateY(-3px);
            right: -3px;
        }

        .forum-posts {
            --this-padding: 15px 20px;
            padding: var(--this-padding);
            display: flex;
            transition: .3s;
            position: relative;
            margin-left: 6px;
        }

        .user-info {
            margin-left: 10px;
        }
    </style>
<?php

}
add_action('wp_head', 'avatarbox_styles');

function get_avatarbox_user_toshi($user_id,$ims = '')
{

       $meta_value = get_user_meta($user_id, 'user_avatarbox', true);
    if ($meta_value) {
        $datebox = '';
        if($ims){
    $pattern = '/src="(.*?)"/';
    preg_match($pattern, $ims, $matches);
    $dataSrc = @$matches[1];
    $datebox =  "box-src='.$dataSrc.'";

        }
    $img =  '<img src="' . $meta_value['src'] . '" '.$datebox.' class="avatarbox_Miniimgbox" style="
    top: ' . $meta_value['top'] . 'px;
    transform: scale(' . $meta_value['transform'] . ');
    position: absolute;">';
    } else {
        $img =  $meta_value;
    }

    return $img;
}

//输出用户中心页面的头部
function avatarbox_user_page_header()

{

    remove_action('user_center_page_content', 'zib_user_page_header', 8); //替换子比原版头部
      $user    = wp_get_current_user();
    $user_id = isset($user->ID) ? (int) $user->ID : 0;

    $info_class = 'flex header-info relative hh';
    $cover      = get_user_cover_img($user_id);
    $dropup_btn = '';
    $avatar     = zib_get_avatar_box($user_id, 'avatar-img', false, false);

    $gjiang = '<botton class="jb-cyan radius ml10 but nowave" data-class="full-sm"  data-height="240" data-remote="' . add_query_arg(['action' => 'get_user_avatarbox'], admin_url('admin-ajax.php')) . '" href="javascript:;" aria-hidden="true"  data-toggle="RefreshModal" >挂件</botton>';
    $avatar = '<div class="hover-show relative">';
    $avatar .= zib_get_avatar_box($user_id, 'avatar-img', false, false);
    $avatar .= zib_get_user_avatar_set_link('absolute hover-show-con flex jc xx', '<i class="fa fa-camera mb6" aria-hidden="true"></i>修改头像') ?: ($user_id ? zib_get_user_home_link($user_id, 'absolute', '') : '');
    $avatar .= '</div>';

    $desc = '';
    $btns = '';

    if ($user_id) {
        $dropup_btn = '<div class="abs-center right-bottom box-body cover-btns">' . zib_get_user_page_header_dropup_btn($user_id) . '</div>';
        $name       = '<span class="display-name">' . zibpay_get_vip_icon(zib_get_user_vip_level($user_id), 'mr3') . $user->display_name . zib_get_user_auth_badge($user_id, 'ml3') . zib_get_user_level_badge($user_id, 'ml3') . '</span>';

        if (_pz('checkin_s')) {
            $btns = zib_get_user_checkin_btn('but c-blue ml10 pw-1em radius', '<i class="fa fa-calendar-check-o"></i>签到', '<i class="fa fa-calendar-check-o"></i>已签到');
        } else {
            $btns = zib_get_user_home_link($user_id, 'but c-blue ml10 pw-1em radius', '<i class="fa fa-map-marker"></i>我的主页');
        }

        if (_pz('message_s')) {
            $btns .= zibmsg_nav_radius_button($user_id, 'ml10');
        }

        $btns = '<div class="header-btns flex0 flex ac">' .$gjiang. $btns . '</div>';

        $desc = '<span class="but" data-clipboard-tag="用户名" data-clipboard-text="' . $user->user_login . '"><i class="fa fa-user-o"></i>' . $user->user_login . '</span>';
        $desc .= $user->user_email ? '<span class="but" data-clipboard-tag="邮箱" data-clipboard-text="' . $user->user_email . '"><i class="fa fa-envelope-o"></i>' . $user->user_email . '</span>' : '';

        $desc = apply_filters('user_page_header_desc', $desc, $user_id);

        $info_html_flex1 = '<div class="flex1">';
        $info_html_flex1 .= '<div class="em12 name">' . $name . '</div>';
        $info_html_flex1 .= '<div class="desc user-identity flex ac hh">' . $desc . '</div>';
        $info_html_flex1 .= '</div>';
    } else {
        $info_class .= ' signin-loader';
        $info_html_flex1 = '<a href="javascript:;" class="display-name">Hi！请登录</a>';
    }

    $info_html = '<div class="' . $info_class . '">';
    $info_html .= '<div class="flex0 header-avatar">';
    $info_html .= $avatar;
    $info_html .= '</div>';
    $info_html .= $info_html_flex1;
    $info_html .= $btns;
    $info_html .= '</div>';

    $html = '<div class="author-header mb20 radius8 main-shadow main-bg full-widget-sm">';
    $html .= '<div class="page-cover">' . $cover . '<div class="absolute linear-mask"></div>' . $dropup_btn . '</div>';
    $html .= '<div class="header-content">';
    $html .= $info_html;
    $html .= '</div>';
    $html .= '</div>';
    echo $html;
}

add_action('user_center_page_content', 'avatarbox_user_page_header', 1);

function copy_file_to_theme($source_file_path, $destination_file_path) {
    // 检查目标文件的目录是否存在，如果不存在则创建它
    if (!file_exists(dirname($destination_file_path))) {
        if (!mkdir(dirname($destination_file_path), 0755, true)) {
            return false; // 目录创建失败
        }
    }

    // 检查源文件是否存在
    if (!file_exists($source_file_path)) {
        return array('error' => 1, 'msg' => $source_file_path .' 文件不存在');
    }

    // 读取源文件内容
    $file_content = file_get_contents($source_file_path);
    if ($file_content === false) {
        return array('error' => 1, 'msg' => '无法读取源文件: ' . $source_file_path);
    }

    // 尝试打开目标文件进行写入
    $myfile = fopen($destination_file_path, "w");
    if ($myfile === false) {
        return array('error' => 1, 'msg' => '无法打开目标文件: ' . $destination_file_path);
    }

    // 写入内容并关闭文件
    $write_result = fwrite($myfile, $file_content);
    $close_result = fclose($myfile);

    // 检查写入是否成功
    if ($write_result === false || !$close_result) {
        return array('error' => 1, 'msg' => '文件写入失败: ' . $destination_file_path);
    }

    return true; // 复制成功
}


add_action('wp_ajax_avatarbox_zibll_user', 'avatarbox_plugin_zibll_user');
function avatarbox_plugin_zibll_user()
{
    if (!is_super_admin()) {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '操作权限不足')));
        exit();
    } else {


   $my_theme = wp_get_theme();
 if ($my_theme->get( 'Version' ) == '7.7') {
    $theme_user = get_template_directory().'/inc/functions/zib-user.php';
    $myfile = fopen($theme_user, "w+") or die("没有找到相应的文件");
    $file_user = avatarboxpath."code/zibll/zib7/zib-user7.php";
    if (file_exists($file_user)) {
        $str = file_get_contents($file_user, $file_user); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $zibuser = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/zib-theme.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib7.7/zib-theme7.7.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/zib-author.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib7.7/zib-author7.7.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/message/class/private-class.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib7/private-ckass7.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);


 } else if ($my_theme->get( 'Version' ) == '7.8') {
    $theme_user = get_template_directory().'/inc/functions/zib-user.php';
    $myfile = fopen($theme_user, "w+") or die("没有找到相应的文件");
    $file_user = avatarboxpath."code/zibll/zib7.8/zib-user7.8.php";
    if (file_exists($file_user)) {
        $str = file_get_contents($file_user, $file_user); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $zibuser = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/zib-theme.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib7.8/zib-theme7.8.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/zib-author.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib7.8/zib-author7.8.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

    $theme_path = get_template_directory().'/inc/functions/message/class/private-class.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib7.8/private-ckass7.8.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);


 }else  if ($my_theme->get( 'Version' ) == '7.9_beta2') {

    $theme_path = get_template_directory().'/inc/functions/zib-user.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib7.8/zib-user7.8.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/zib-theme.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib7.9/zib-theme7.9.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

    $theme_path = get_template_directory().'/inc/functions/zib-author.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib7.9/zib-author7.9.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/message/class/private-class.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib7.8/private-ckass7.8.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

 } else if ($my_theme->get( 'Version' ) == '8.0') {

    $theme_path = get_template_directory().'/inc/functions/zib-user.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib8.0/zib-user.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/zib-theme.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib8.0/zib-theme.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

    $theme_path = get_template_directory().'/inc/functions/zib-author.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib8.0/zib-author.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/message/class/private-class.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib8.0/private-class.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

 } else if ($my_theme->get( 'Version' ) == '8.1') {

    $theme_path = get_template_directory().'/inc/functions/zib-user.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib8.1/zib-user.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/zib-theme.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib8.1/zib-theme.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

    $theme_path = get_template_directory().'/inc/functions/zib-author.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib8.1/zib-author.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/message/class/private-class.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib8.1/private-class.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

 } else if ($my_theme->get( 'Version' ) == '8.2') {

    $theme_path = get_template_directory().'/inc/functions/zib-user.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib8.2/zib-user.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/zib-theme.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib8.2/zib-theme.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

    $theme_path = get_template_directory().'/inc/functions/zib-author.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib8.2/zib-author.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/message/class/private-class.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib8.2/private-class.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

 } else if ($my_theme->get( 'Version' ) == '8.3') {

    $theme_path = get_template_directory().'/inc/functions/zib-user.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib8.2/zib-user.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/zib-theme.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath . "code/zibll/zib8.2/zib-theme.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

    $theme_path = get_template_directory().'/inc/functions/zib-author.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib8.2/zib-author.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);
    $theme_path = get_template_directory().'/inc/functions/message/class/private-class.php';
    $myfile = fopen($theme_path, "w+") or die("没有找到相应的文件");
    $file_path = avatarboxpath."code/zibll/zib8.2/private-class.php";
    if (file_exists($file_path)) {
        $str = file_get_contents($file_path, $file_path); //将整个文件内容读入到一个字符串中
    }
    fwrite($myfile, $str);
    $MSG = fclose($myfile);

// 获取主题目录
$theme_directory = get_template_directory() . '/inc/functions/';

// 定义源文件与目标文件的对应关系
$files_to_copy = [
    'zib-user.php' => 'code/zibll/zib8.0/zib-user.php',
    'zib-theme.php' => 'code/zibll/zib8.0/zib-theme.php',
    'zib-author.php' => 'code/zibll/zib8.0/zib-author.php',
    'message/class/private-class.php' => 'code/zibll/zib8.0/private-class.php',
];
// 初始化消息状态
$MSG = true; // 假设所有文件都复制成功
// 遍历需要复制的文件，并调用抽象的函数进行操作
foreach ($files_to_copy as $destination_file => $source_file) {
    $destination_path = $theme_directory . $destination_file;
    $source_path = avatarboxpath.$source_file;
     // 复制文件
    $result = copy_file_to_theme($source_path, $destination_path);
    // 检查复制结果
    if (!$result) {
        // 记录失败文件信息
        error_log("Failed to copy: $source_path to $destination_path");
        $MSG = false; // 标记为未成功
    }
}

 }else{
  echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '版本过低 请升级子比主题 > 7.7V ,当前版本'.$my_theme->get( 'Version' ))));
        exit();
 }
    if ($MSG) {
        //刷新所有缓存
        wp_cache_flush();
     /**
     * 刷新固定连接
     */
    flush_rewrite_rules();
        echo (json_encode(array('error' => 0, 'query' => '', 'msg' => '已替换资源 头像框可使用 _当前子比版本'.$my_theme->get( 'Version' ))));
        exit();
    } else {
        echo (json_encode(array('error' => 1, 'ys' => 'danger', 'msg' => '无法替换 存在异常 _当前子比版本'.$my_theme->get( 'Version' ))));
            exit();
    }
    }
}

function avatarbox_get_modal_colorful_header($class = 'jb-blue', $icon = '', $cetent = '', $close_btn = true)
{
    $html = '<div class="modal-colorful-header colorful-bg ' . $class . '">';
    $html .= $close_btn ? '<button class="close" data-dismiss="modal">' . zib_get_svg('close', null, 'ic-close') . '</button>' : '';
    $html .= '<div class="colorful-make"></div>';
    $html .= '<div class="text-center">';
    $html .= $icon ? $icon : '';
    $html .= $cetent ? '<div class="mt10 em12 padding-w10">' . $cetent . '</div>' : '';
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}



function get_user_avatarbox()
{

      @$id = $_REQUEST['user'];
if ($id == true) {
  $user_id = $id;
}else{
    $user_id = get_current_user_id();
}
    return avatarbox::ajaxform_options($user_id);
    exit;
}
add_action('wp_ajax_get_user_avatarbox', 'get_user_avatarbox');

function user_avatarbox($user_id)
{
    $avatar_img = zib_get_avatar_box($user_id, 'avatar-img', false, false);
    $header = '<style>
.medal-list {
   border: 1px solid #e5e9ef;
}
.medal-list-box .medal-list:hover {
  border: 1px solid #0400ff;
}
/* 添加折叠控制样式 */
.medal-cat-header {
    cursor: pointer;
    padding: 8px 12px;
    background-color: #f5f7fa;
    border-radius: 4px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all .3s;
}
.medal-cat-header:hover {
    background-color: #eaeef5;
}
.medal-cat-toggle {
    font-size: 12px;
    color: #8590a6;
    transition: transform .3s;
}
.medal-cat-toggle.open {
    transform: rotate(180deg);
}
.folded {
    display: none;
}
/* 优化布局 */
.medal-list-box {
    flex-wrap: wrap;
    gap: 10px;
}
.medal-list {
    flex: 0 0 calc(25% - 10px);
    max-width: calc(25% - 10px);
    box-sizing: border-box;
}
</style>';

    $header .= avatarbox_get_modal_colorful_header('c-blue', $avatar_img, '');

    $list = avatar('avatar');
    $medal_details_html = '';
    $is_has_html = '';

    if ($list) {
        $thumbnail = avatar('thumbnail');
        if($thumbnail){
            $thumbnail = ' src="'.$thumbnail.' " data-';
        } else {
            $thumbnail = '';
        }

        $array = get_user_meta($user_id, 'user_avatarbox_list', true);

        // 添加折叠状态管理 - 默认全部折叠
        $medal_details_html .= '<script>
        jQuery(document).ready(function($) {
            $(".medal-cat-header").click(function() {
                var $this = $(this);
                var $content = $this.next(".medal-list-box");
                var $toggleIcon = $this.find(".medal-cat-toggle");

                if($content.hasClass("folded")) {
                    $content.removeClass("folded");
                    $toggleIcon.addClass("open");
                } else {
                    $content.addClass("folded");
                    $toggleIcon.removeClass("open");
                }
            });
        });
        </script>';

        $count = 0;
        foreach ($list as $i => $v) {
            $k = $v['name'];
            $is_has_html = '';

            if ($v['if']) {
                foreach ($v['pet'] as $s => $p) {
                    $stle = avatarbox_is_get_userlist($p['name'], $user_id);
                    $stles = $stle ? $stle : 'style="opacity: 0.5; background: rgb(0 0 0 / 10%);"';

                    $is_has_html .= '<div class="medal-list">
                        <a new="new" data-class="modal-mini" mobile-bottom="true" data-height="201"
                           data-remote="' . add_query_arg([
                               'action' => 'user_avatarbox_info_modal',
                               'id' => $i,
                               'ad' => $s,
                               'p' => $p
                           ], admin_url('admin-ajax.php')) . '"
                           class="single-medal-info" href="javascript:;" data-toggle="RefreshModal">
                            <div class="medal-card is-has" '.$stles.'>
                                <img class="img-icon medal-icon" '.$thumbnail.'src="' . $p['src'] . '">
                                <div class="muted-color px12 mt6">' . $p['name'] . '</div>
                            </div>
                        </a>
                    </div>';
                }

                // 添加折叠控制结构
                $medal_details_html .= '<div class="medal-cat-box mb20">
                    <div class="medal-cat-header">
                        <div><b class="medal-cat-name">'.$k.'</b></div>
                        <div class="medal-cat-toggle open">▼</div>
                    </div>
                    <div class="medal-list-box flex at hh folded">' . $is_has_html . '</div>
                </div>';
            }
        }
    } else {
        $medal_details_html = zib_get_null('暂无挂件', 60, 'null.svg', '', 0, 170);
    }

    @$avatararray = user_avatarbox_list($user_id);
    $tab_but = '';
    $tab_content = '';

    $tab_but .= '<li class="active"><a data-toggle="tab" href="#avatar_tab_user">我的挂件</a></li>';
    $tab_but .= '<li class=""><a data-toggle="tab" href="#avatar_tab_listbox">挂件列表</a></li>';

    $tab_content .= '<div class="tab-pane fade active in" id="avatar_tab_user">
        <div class="mini-scrollbar scroll-y max-vh5">' . $avatararray . '</div>
    </div>';

    $tab_content .= '<div class="tab-pane fade" id="avatar_tab_listbox">
        <div class="mini-scrollbar scroll-y max-vh5" style="max-height: 50vh;">' . $medal_details_html . '</div>
    </div>';

    $html = '<div class="padding-w10 nop-sm">
        <ul class="list-inline scroll-x mini-scrollbar tab-nav-theme font-bold">' . $tab_but . '</ul>
        <div class="tab-content">' . $tab_content . '</div>
    </div>';

    return $header . $html;
}
/*
判断是否已获取 头像框
*/
function avatarbox_is_get_userlist($name,$user_id){
    $array = get_user_meta($user_id, 'user_avatarbox_list', true);
             foreach ($array as $ss => $pp) {
           if($pp['name'] === $name){
               return   'style="opacity: 1;background: #ffffff;"';
           }
           }

}

function user_avatarbox_info_modal()
{
    $user_id = get_current_user_id();
    $p = $_REQUEST['p'];
    $id = $_REQUEST['id'];
    $ad = $_REQUEST['ad'];
    echo get_avatarbox_info_modal($user_id, $p, $id, $ad);
    exit;
}

add_action('wp_ajax_user_avatarbox_info_modal', 'user_avatarbox_info_modal');


//获取用户头像
function zib_get_avatar_avatarbox($user_id, $class, $meta_value, $link = true, $vip = true,$style = '')
{
           $thumbnail = avatar('thumbnail');
           $class= 'avatar-img';
if($thumbnail){

       $thumbnail =  ' src="'.$thumbnail.' " data-';
          $avatar_img =  '<img '.$thumbnail.'src="' . $meta_value['src'] . '" style="
    top: ' . $meta_value['top'] . 'px;
    transform: scale(' . $meta_value['transform'] . ');
    position: absolute;
    z-index: 1;
 ">';
}else{
       $thumbnail =  '';
}

    $avatar_img .= zib_get_data_avatar($user_id);
    $vip_icon   = '';
    if ($vip) {
        $vip_icon = zib_get_avatar_badge($user_id);
    }
    $html = '<span class="' . $class . '"  ' . $style . '>' . $avatar_img . $vip_icon . '</span>';
    if ($link && $user_id) {
        $helf = zib_get_user_home_url($user_id);
        $html = $html;
    }
    return $html;
}





// 删除数组元素
function remove_avatarbox($user_id, $element_to_remove)
{
    $array = get_user_meta($user_id, 'user_avatarbox_list', true);

    if (!empty($array)) {
        $updated_array = array_diff($array, array($element_to_remove));
        update_user_meta($user_id, 'user_avatarbox_list', $updated_array);
    }
}


