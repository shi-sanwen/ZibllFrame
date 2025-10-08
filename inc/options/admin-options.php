<?php
/*
Plugin Name: 子比头像框
Plugin URI: https://cmxj.song3060.top/
Description:子比头像框
Author: 是史三问呀
Version:1
Author URI: https://cmxj.song3060.top/
*/

if (! defined('ABSPATH')) {
	die;
}

if (class_exists('CSF')) {

    $prefix    = 'avatarbox';
    
    $imagepath = plugin_dir_path( __FILE__ ) . '/img/';
    $f_imgpath = plugin_dir_path( __FILE__ ) . '/inc/csf-framework/assets/images/';

    //开始构建
    CSF::createOptions($prefix, array(
        'menu_title'         => '子比头像框',
        'menu_slug'          => 'avatarbox',
        'framework_title'    => '子比头像框',
        'show_in_customizer' => true, //在wp-customize中也显示相同的选项
        'footer_text'        => '由史三问开发的子比头像框',
        'footer_credit'      => '<i class="fa fa-fw fa-heart-o" aria-hidden="true"></i> ',
        'theme'              => 'light',
    ));
     CSF::createSection($prefix, array(
        'id'    => 'more',
        'title' => '头像框',
        'icon'  => 'fa fa-cart-plus',
    ));
    
    CSF::createSection($prefix, array(
        'parent'      => 'more',
        'title'       => '头像框列表',
        'icon'        => 'fa fa-drivers-license',
        'description' => '',
        'fields'      => array(
              array(
                'content' => '<p>不允许修改或增加更多的列表不然有可能会报错 最佳配置方式应 修改名称和奖品图片</p>
                <li>预留防止手机端无法切换<code>1</code></li>
                <li>预留防止手机端无法切换<code>2</code></li>
                <li>预留防止手机端无法切换<code>3</code></li>
                <li>预留防止手机端无法切换<code>4</code></li>
                <li>预留防止手机端无法切换<code>5</code></li>
                    <div class="options-notice">
                             <div style="color:#ff2153;"><i class="fa fa-fw fa-info-circle fa-fw"></i>每一次子比更新主题你都需要到此点击 替换文件</div>
        <div class="explain">
        <ajaxform class="ajax-form" ajax-url="' . admin_url("admin-ajax.php") . '">
        <div class="ajax-notice"></div>
        <div><a href="javascript:;" data-confirm="确认修改子比文件启动头像框?  设置后替换子比文件!" class="but jb-yellow ajax-submit"><i class="fa fa-repeat"></i> 一键修改头像框代码</a></div>
        <input type="hidden" ajax-name="action" value="avatarbox_zibll_user">
        </ajaxform>
        </div></div>
                ',
                'style'   => 'warning',
                'type'    => 'submessage',
            ),  
            array(
    'title'    => __('我的头像框高设置'),
    'id'       => 'height',
    'type'     => 'number',
    'default'  => 85,
    'unit'     => 'px',
         ),  
         array(
    'title'    => __('我的头像框宽设置'),
    'id'       => 'width',
    'type'     => 'number',
    'default'  => 85,
    'unit'     => 'px',
         ),
    array(
                'title'    => ' ',
                'subtitle' => '懒加载预载图',
                'id'       => 'thumbnail',
                'class'    => 'compact',
                'desc'     => '懒加载图片',
                'default'  => avatarbox.'imge/load.gif',
                'library'  => 'image',
                'type'     => 'upload',
            ),
            array(
    'title'    => __('列表头像框高设置'),
    'id'       => 'lheight',
    'type'     => 'number',
    'default'  => 85,
    'unit'     => 'px',
         ), 
         array(
    'title'    => __('列表头像框宽设置'),
    'id'       => 'lwidth',
    'type'     => 'number',
    'default'  => 85,
    'unit'     => 'px',
         ),
         array(
  'id'        => 'avatar',
  'type'      => 'group',
  'button_title' => '添加头像框类型',
  'fields'    => array(
    array(
      'id'    => 'name',
      'type'  => 'text',
      'title' => '头像框名称',
    ),
    array(
  'id'    => 'if',
  'type'  => 'switcher',
  'title' => '展示头像框',
   ),
    array(
      'id'        => 'pet',
      'type'      => 'group',
      'title'     => '头像框列表',
      'button_title' => '添加头像框',
      'min'     => '1',
      'fields'    => array(
        array(
          'id'    => 'name',
          'type'  => 'text',
          'title' => '头像框名称',
           'default' => '头像框名称',
        ),      
        array(
        'id'      => 'desc',
        'class'   => 'compact',
        'title'   => '头像框简介',
        'default' => '头像框简介',
        'type'    => 'text',
        ),
       array(
                'title'    => __('头像框图标'),
                'id'       => 'src',
                'desc'     => __('请使用png格式或GIF的透明图片'),
                'preview'  => true,
                'library'  => 'image',
                'type'     => 'upload',
       ), 
                            array(
                                'id'       => 'get_type', //运算符号对比
                                'title'    => '获取方式',
                                'subtitle' => '',
                                'default'  => 'mf',
                                'inline'   => true,
                                'type'     => "select",
                                'options'  => array(
                                    ''                  => '无法获取',
                                    'mf'                => '免费直接领取',
                                    'vip'                => '指定会员可领取',
                                    'time'                => '指定日期可领取',
                                    'pay_zf'        => '【购买】通过消耗积分余额等方式',
                                    'registration_time' => '注册时间达到X天',
                                    'new_post'          => '发布文章数量达到X篇',
                                    'like'              => '发布的文章获得X次点赞',
                                    'comment'           => '发布评论数量达到X条',
                                    'comment_like'      => '发布的评论获得X次点赞',
                                    'followed'          => '获得X个粉丝',
                                    'favorite'          => '发布的内容有X被收藏',
                                    'views'             => '发布的内容总阅读量达到X(人气值)',
                                    'checkin_all_day'   => '累计签到天数达到X天',
                                    'checkin_continuous_day'   => '连续签到天数达到X天',
                                    'bbs_post'          => '【论坛】发布帖子数量达到X篇',
                                    'user_auth_s'          => '认证用户专属',
                                    'manually_add'      => '【限定徽章】管理员手动为用户添加',
                                ),
                            ),   
                            array(
                                'dependency' => array('get_type', 'not-any', '"",manually_add,pay_zf,mf,vip,time,'),
                                'id'         => 'get_val',
                                'class'      => 'compact',
                                'desc'     => __('认证用户 请输入 1'),
                                'title'      => ' ',
                                'subtitle'   => '设定达到的值',
                                'default'    => 0,
                                'type'       => 'number',
                            ),
                                         array(
                'dependency' => array('get_type', '==', 'vip'),
                'title'      => '会员等级',
                'id'         => 'vip',
                'type'       => 'fieldset',
                'fields'     => array(
                                array(
                'id'         => 'vip_type',
                'title'      => '',
                'default'    => 'no',
                'type'       => "radio",
                'inline'     => true,
                'options'    => array(
                    'no' => __('所有会员', 'zib_language'),
                    'vip1'    => __('1级会员', 'zib_language'),
                    'vip2'    => __('2级会员', 'zib_language'),
                ),
            ),
            
                ),
            ),
               array(
                'dependency' => array('get_type', '==', 'time'),
                            'id' => 'date',
                            'type' => 'date',
                            'title' => '指定日期可领取',
                            'default' => current_time('Y-m-d'),
                            'settings' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'changeMonth' => true,
                                'changeYear' => true,
                            ),
                        ),                  
                     array(
                'dependency' => array('get_type', '==', 'pay_zf'),
                'title'      => '支付配置',
                'id'         => 'pay',
                'type'       => 'fieldset',
                'fields'     => array(
                                array(
                'id'         => 'pay_type',
                'title'      => '支付方式',
                'default'    => 'rmb',
                'type'       => "radio",
                'inline'     => true,
                'options'    => array(
                    'points' => __('积分支付', 'zib_language'),
                    'balance'    => __('余额支付', 'zib_language'),
                ),
            ),
             array(
                'dependency' => array('pay_type', '==', 'points'),
                'id'         => 'pay_points',
                'class'      => 'compact',
                'title'      => ' ',
                'subtitle'   => '积分购买',
                'desc'       => '购买头像框需要的积分金额',
                'default'    => 300,
                'type'       => 'number',
                'unit'       => '积分',
                'class'      => 'compact',
            ),
             array(
                'dependency' => array('pay_type', '==', 'balance'),
                'id'         => 'pay_balance',
                'class'      => 'compact',
                'title'      => ' ',
                'subtitle'   => '余额购买',
                'desc'       => '购买头像框需要的余额金额',
                'default'    => 30,
                'type'       => 'number',
                'unit'       => '余额',
                'class'      => 'compact',
            ),
            
                ),
            ),

       array(
                'id'      => 'type',
                'title'   => '头像框方式',
                'default' => '1',
                'type'    => "radio",
                'inline'  => true,
                'desc'    => '如果你的头像框是 圆的就选择圆 方的就选择方',
                'options' => array(
                    '1' => __('圆形', 'zib_language'),
                    '0' => __('方型', 'zib_language'),
                ),
            ), 
            array(
    'title'    => __('设置头像框top px'),
    'desc'     => __('top: ? px 属性设置 新的头像框要自己配置好防止头像框过大！ 每一次调式用1 2递增 支持-1以及更多-'),
    'id'       => 'top',
    'type'     => 'number',
    'default'  => 0,
    'unit'     => 'top',
         ),
   array(
    'title'    => __('设置头像框transform px'),
    'desc'     => __('transform: scale() 属性设置 新的头像框要自己配置好防止头像框过大！ 每一次调式用1.2 1.3递增'),
    'id'       => 'transform',
    'type'     => 'number',
    'default'  => 1.2,
    'unit'     => 'PX',
         ),
         
         
      ),
    ),
  ),
),   
            )));
      
      
              CSF::createSection($prefix, array(
        'parent'      => 'more',
        'title'       => '清理头像',
        'icon'        => 'fa fa-fw fa-gitlab',
        'description' => '',
        'fields'      => array(
              array(
                'content' => '<p>不允许修改或增加更多的列表不然有可能会报错 最佳配置方式应 修改名称和奖品图片</p>
                <li>预留防止手机端无法切换<code>1</code></li>
                <li>预留防止手机端无法切换<code>2</code></li>
                <li>预留防止手机端无法切换<code>3</code></li>
                <li>预留防止手机端无法切换<code>4</code></li>
                <li>预留防止手机端无法切换<code>5</code></li>
                ',
                'style'   => 'warning',
                'type'    => 'submessage',
            ),  
     array( 'content' => '
        <div class="options-notice">
        <div style="color:#ff2153;"><i class="fa fa-fw fa-info-circle fa-fw"></i>清理之后 佩戴中的头像也一并删除</div>
        <div class="explain">
        <ajaxform class="ajax-form" ajax-url="' . admin_url("admin-ajax.php") . '">
        <div class="ajax-notice"></div>
        <div><a href="javascript:;" data-confirm="确认 清理所有用户头像框数据????" class="but jb-yellow ajax-submit"><i class="fa fa-repeat"></i> 清理所有用户头像数据</a></div>
        <input type="hidden" ajax-name="action" value="avatarbox_admin_ajax_delete_user">
        </ajaxform>
        </div></div>
                ',
                'style'   => 'info',
                'type'    => 'submessage',
            ),   
     array( 'content' => '
        <div class="options-notice">
        <div style="color:#ff2153;"><i class="fa fa-fw fa-info-circle fa-fw"></i>删除所有用户指定 头像框数据 【测试】 所有用户中的【测试名字头像框直接删除】</div>
        <div style="color:#ff2153;"><i class="fa fa-fw fa-info-circle fa-fw"></i>用户数量庞大会导致502死机哟~</div>
        <div class="explain">
        <ajaxform class="ajax-form" ajax-url="' . admin_url("admin-ajax.php") . '">
        <div class="ajax-notice"></div>
        <div><div class="csf--wrap"><input type="text" ajax-name="name" value=""><a href="javascript:;" data-confirm="确认 删除所有用户指定头像框????" class="but jb-yellow ajax-submit"><i class="fa fa-repeat"></i> 删除所有用户指定头像框</a></div></div>
        <input type="hidden" ajax-name="action" value="avatarbox_admin_ajax_delete_user_list">
        </ajaxform>
        </div></div>
                ',
                'style'   => 'success',
                'type'    => 'submessage',
            ), 
        ),
    ));

        CSF::createSection($prefix, array(
        'id'    => 'aut',
        'title' => '系统信息',
        'icon'  => 'fa fa-fw fa-align-center',
    ));

        CSF::createSection($prefix, array(
        'parent'      => 'aut',
        'title'       => '系统信息',
        'icon'        => 'fa fa-fw fa-gitlab',
        'description' => '',
        'fields'      => array(
            array(
                'type'    => 'submessage',
                'style'   => 'warning',
                'content' => '<h3 style="color:#fd4c73;"><i class="fa fa-heart fa-fw"></i> 感谢您使用子比头像框插件</h3>
                <div><b>首次使用请在下方进行授权验证</b></div>
                <p>由破解的的子比头像框挂件项目,多功能自定义头像框项目</p>
                <div style="margin:10px 14px;">
                    <li>预留防止手机端无法切换<code>1</code></li>
                    <li>预留防止手机端无法切换<code>2</code></li>
                    <li>预留防止手机端无法切换<code>3</code></li>
                </div>
                <div style="margin-left:14px;"><li><strong>操作系统</strong>： ' . PHP_OS . ' </li>
            <li><strong>运行环境</strong>： ' . $_SERVER["SERVER_SOFTWARE"] . ' </li>
            <li><strong>PHP版本</strong>： ' . PHP_VERSION . ' </li>
            <li><strong>PHP上传限制</strong>： ' . ini_get('upload_max_filesize') . ' </li>
            <li><strong>WordPress版本</strong>： ' . get_bloginfo('version') . '</li>
            <li><strong>系统信息</strong>： ' . php_uname() . ' </li>
            <li><strong>服务器时间</strong>： ' . current_time('mysql') . '</li></div>
            <a class="but c-yellow" href="' . admin_url('site-health.php?tab=debug') . '">查看更多系统信息</a>',
            ),
        ),
    ));
}