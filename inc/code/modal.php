<?php
//领取条件
function user_add_avatarbox($user_id,$get_type,$id,$ad)
{
    $button = '
<button class="but jb-blue radius btn-block padding-lg wp-ajax-submit" style="overflow: hidden; position: relative;"><i class="fa fa-check" aria-hidden="true"></i>领取
  </button>';
  $count = avatar('avatar')[$id]['pet'][$ad]['get_val'];
    switch ($get_type) {  
        case 'mf':
        return array('msg'=>200,'button'=>$button);
        break;
    case 'vip':
        // 指定会员可领取
       $vip = avatar('avatar')[$id]['pet'][$ad]['vip']['vip_type'];
       return avatarbox_user_vip($user_id,$vip);
        break;
    case 'time':
        // 指定日期
            $date = avatar('avatar')[$id]['pet'][$ad]['date'];
        return avatarbox_user_time($user_id,$date);
        break; 
    case 'pay_zf':
         $pay = avatar('avatar')[$id]['pet'][$ad]['pay']['pay_type'];
         $pay_rmb = avatar('avatar')[$id]['pet'][$ad]['pay'];
         $mate = avatar('avatar')[$id]['pet'][$ad];
        // 【购买】通过消耗积分余额等方式 或 扫码支付获取
    return    avatarbox_user_pay_zf($user_id,$pay,$pay_rmb,$mate,$id,$ad);

        break; 
    case 'registration_time':
        // 注册时间达到X天
      return   avatarbox_user_registration_time($user_id,$count);
        break;
    case 'new_post':
        // 发布文章数量达到X篇
      return  avatarbox_user_new_post($user_id,$count);
        break;
    case 'like':
        // 发布的文章获得X次点赞
        return avatarbox_user_new_like($user_id,$count);
        break;
    case 'comment':
        // 发布评论数量达到X条
      return  avatarbox_user_comment($user_id,$count);
        break;
    case 'comment_like':
        // 发布的评论获得X次点赞
       return  get_comment_like($user_id,$count);
        break;
    case 'followed':
        // 获得X个粉丝
       return avatarbox_user_followed($user_id,$count);
        break;
    case 'favorite':
        // 发布的内容有X被收藏
       return  get_user_comment_favorite($user_id,$count);
        break;
    case 'views':
        // 发布的内容总阅读量达到X(人气值)
        return get_comment_views($user_id,$count);
        break;
    case 'checkin_all_day':
        // 累计签到天数达到X天
       return get_comment_checkin_all_day($user_id,$count);
        break;   
        case 'checkin_continuous_day':
        // 连续签到天数达到X天
       return avatarbox_user_checkin_continuous_day($user_id,$count);
        break;
        
    case 'bbs_post':
        //【论坛】发布帖子数量达到X篇
       return avatarbox_user_bbs_post($user_id,$count);
        break;
    case 'bbs_post_hot':
        // 【论坛】发布的帖子有X篇成为热门
        return  avatarbox_user_bbs_post_hot($user_id,$count);
        break;

    case 'user_auth_s':
        // 【论坛】发布的帖子有X篇成为热门
        return  avatarbox_zib_is_user_auth($user_id,$count);
        break;

    case 'manually_add':
        //  【限定徽章】管理员手动为用户添加
    
        
        break;
    default:
        // 当变量不等于任何一个值时执行的代码
        
        break;              
}
}


function get_avatarbox_ajax_pay(){
    
         $type =  $_REQUEST['pay_type'];
         $pay =  $_REQUEST['pay'];
         $mate =  $_REQUEST['mate'];
         $ad =  $_REQUEST['ad'];
         $id =  $_REQUEST['id'];
        $user_id = get_current_user_id();
        $header = '<div class="mb10 touch"><button class="close" data-dismiss="modal">' . zib_get_svg('close', null, 'ic-close') . '</button><b class="modal-title flex ac">头像框购买</b></div>';
        $hidden = '<input type="hidden" name="order_name" value="头像框:['.$mate['name'].']">';
        $hidden .= '<input type="hidden" name="points_price" value="'.$pay.'">';
        $hidden .= '    <input type="hidden" name="src" value="' . $mate['src'] . '">
    <input type="hidden" name="type" value="' . $mate['type'] . '">
    <input type="hidden" name="get_type" value="' . $mate['get_type'] . '">
    <input type="hidden" name="top" value="' . $mate['top'] . '">
    <input type="hidden" name="desc" value="' . $mate['desc'] . '">
    <input type="hidden" name="name" value="' . $mate['name'] . '">
    <input type="hidden" name="transform" value="' . $mate['transform'] . '">
    <input type="hidden" name="id" value="' . $id . '">
    <input type="hidden" name="ad" value="' . $ad . '">';
 
         $hidden .= '<input type="hidden" name="pay_type" value="' .$type . '">';
        $hidden .= '<input type="hidden" name="order_type" value="3">';
        if ($type == 'points') {
          //我的积分卡片
         $mark      = zib_get_svg('points-color', null, 'em12 mr6');
         $mark      = '<span class="pay-mark px12">' . $mark . '</span>';
         $user_points = zibpay_get_user_points($user_id);
          $con ='';
         $con .= '<div class="mb10 muted-box">';
         $con .= '<div class="flex jsb ab"><span class="muted-2-color">' . zib_get_svg('points-color', null, 'em12 mr6') . '我的积分</span><div><span class="c-yellow">' . $mark . '<span class="em14">' . $user_points . '</span></span></div></div>';
         $con .= '</div>';
        } else {
                //我的积分卡片
         $mark      = zib_get_svg('money-color-2', null, 'em12 mr6');
         $mark      = '<span class="pay-mark px12">' . $mark . '</span>';
         $user_points = zibpay_get_user_balance($user_id);
          $con ='';
         $con .= '<div class="mb10 muted-box">';
         $con .= '<div class="flex jsb ab"><span class="muted-2-color">' . zib_get_svg('money-color-2', null, 'em12 mr6') . '我的余额</span><div><span class="c-blue-2">' . $mark . '<span class="em14">' . $user_points . '</span></span></div></div>';
         $con .= '</div>';
        }
        

         $initiate = '<input type="hidden" name="action" value="avatarbox_points_initiate_pay">';
         $con .= '<button class="mt6 but jb-red wp-ajax-submit btn-block radius">立即支付<span class="pay-price-text">'.$pay.'</span></button>';
         $form = '<form class="balance-charge-form mini-scrollbar scroll-y max-vh7">'  . $hidden . $con .$initiate. '</form>';
    $html = '';
    $html .= $header . $form;
    echo $html;
    exit;
}
add_action('wp_ajax_get_avatarbox_ajax_pay', 'get_avatarbox_ajax_pay');

function avatarbox_points_initiate_pay(){
    
    $user_id = get_current_user_id();
    $points_price =  $_REQUEST['points_price'];
    $order_name =  $_REQUEST['order_name'];
    $order_type =  $_REQUEST['order_type'];
    $pay_type = $_REQUEST['pay_type'];
    $id = $_REQUEST['id'];
    $ad = $_REQUEST['ad'];
    $src = $_REQUEST['src'];
    $name = $_REQUEST['name'];
    $type = $_REQUEST['type'];
    $get_type = $_REQUEST['get_type'];
    $top = $_REQUEST['top'];
    $desc = $_REQUEST['desc'];
    $transform = $_REQUEST['transform'];
          $current_time = current_time("Y-m-d H:i:s");
             //函数节流
    zib_ajax_debounce('avatarbox_points_initiate_pay', $user_id);
            $add_order_data = array(
        'user_id'     => $user_id,
        'product_id'     => $order_name,
        'post_author' => '1',
        'order_price' => $points_price,
        'order_type'  => '3',
        'pay_type'    => 'points',
        'pay_price'   => $points_price,
        'pay_detail'  => array(
            'points' => $points_price,
        ),
        'pay_time'    => $current_time,
    );

    //创建新订单
    $order = ZibPay::add_order($add_order_data);
    if (!$order) {
        zib_send_json_error('订单创建失败');
    }
    if ($pay_type == 'points') {
    //更新用户积分
    $update_points_data = array(
        'order_num' => $order['order_num'], //订单号
        'value'     => -$points_price, //值 整数为加，负数为减去
        'type'      => '积分支付', //类型说明
        'desc'      => '购买头像框'.$order_name, //说明
        'time'      => current_time('Y-m-d H:i'),
        
    );
    $update_points = zibpay_update_user_points($user_id, $update_points_data);
    } else {
    $data      = array(
        'order_num' => $order['order_num'], //订单号
        'value'     => -$points_price, //值 整数为加，负数为减去
        'type'      => '余额支付',
        'desc'      => '购买头像框'.$order_name, //说明
        'time'      => current_time('Y-m-d H:i'),
    );
      $update_points =   zibpay_update_user_balance($user_id, $data);
    }
    

    if (!$update_points) {
        zib_send_json_error('数据更新失败');
    }

     $pay = array(
        'order_num' => $order['order_num'],
        'pay_type'  => $pay_type,
        'pay_price' => $points_price,
        'pay_num'   => $order['order_num'],
    );
    // 更新订单状态
    ZibPay::payment_order($pay);
    $userCode = array(
        'src' => $src,
        'name' => $name,
        'desc' => $desc,
        'type' => $type,
        'get_type' => $get_type,
        'top' => $top,
        'transform' => $transform,
        'id' => $id,
        'ad' => $ad,
        'time' => $current_time
    );
    update_avatarbox_isname($user_id, $userCode);
    $update =    update_user_meta($user_id, 'user_avatarbox', $userCode);
    update_avatarbox_or_add_array($user_id, $userCode);
        $msg_arge = array(
        'send_user'    => 'admin',
        'receive_user' => $user_id,
        'type'         => 'pay',
        'title'        => '恭喜你购买'.$order_name.'成功',
        'content'      => '恭喜你购买成功',
        'meta'         => '',
        'other'        => '',
    );
    //创建新消息
    if (_pz('message_s', true)) {
        ZibMsg::add($msg_arge);
    }
    zib_send_json_success(['reload' => true, 'msg' => '购买成功']);
}

add_action('wp_ajax_avatarbox_points_initiate_pay', 'avatarbox_points_initiate_pay');
function get_avatarbox_info_modal($user_id, $p, $id, $ad)
{

 $name  = $p['name'];
 $get_type =   $p['get_type'];
 $input = '';
 $delete =  delete_avatarbox_user($user_id,$name);
 $button = '';
 $avatarbox = user_add_avatarbox($user_id,$get_type,$id,$ad);
 if ($avatarbox['msg'] == 200) {
     if ($avatarbox['button'] == 1) {
        $button = '
<button class="but jb-blue radius btn-block padding-lg wp-ajax-submit" style="overflow: hidden; position: relative;"><i class="fa fa-check" aria-hidden="true"></i>领取
  </button>';
     } else {
$button = $avatarbox['button'];
     }
     
 } else {
$button =  $avatarbox['button'];
 }
    if ($delete == false) {
        $input ='
    <input type="hidden" name="action" value="avatarbox_user_add_update">
    <input type="hidden" name="id" value="' . $id . '">
    <input type="hidden" name="ad" value="' . $ad . '">
'.$button.'
    ';
    } else {
        $input =' 
    <input type="hidden" name="action" value="delete_user_avatarbox">
    <button class="but jb-red radius btn-block padding-lg wp-ajax-submit" style="overflow: hidden; position: relative;">取消佩戴
    </button>
    ';
    }
            $height = avatar('lheight');
   $lwidth =  avatar('lwidth');
            if ($p['type'] == '0') {
 $style = ' border-radius: 0px;';
} else {
  $style =  '';
}
    if ($height) {
       $h =  'style="height:'.$height.'px;width: '.$lwidth.'px;"';
    }else{
         $h =  'style="height: 80px;width: 90px;"';
    }

    $html = '<button class="close" data-dismiss="modal">' . zib_get_svg('close', null, 'ic-close') . '</button><form>
                <div class="max-vh5 box-body medal-card mb20 mt20 medal-single-card relative">
                    <script type="text/javascript">
    $(function () {
 $(".avatarboxs .avatar").attr("style", "height: 120px; '.$style.'");
})
    </script>
    
                <div class="relative img-icon avatarboxs" >' . zib_get_avatar_avatarbox($user_id, '', $p,true,true,$h) . '</div> 
                </div>    
                <div class="box-body">
                <div class="text-center">
                <div class="mt20">' . $p['name'] . '</div>
                <div class="muted-2-color px12 mt10">' . $p['desc'] . '</div>
                </div></div>
         ' . $input . '
                </form>';
    return $html;
}

function avatarbox_user_add_update()
{
    global $wpdb;
    $user_id = get_current_user_id();
    $id = $_REQUEST['id'];
    $ad = $_REQUEST['ad'];
    
     $mate = avatar('avatar')[$id]['pet'][$ad];
    $src = $mate['src'];
    $name = $mate['name'];
    $type = $mate['type'];
    $get_type = $mate['get_type'];
    $top = $mate['top'];
    $desc = $mate['desc'];
    $transform = $mate['transform'];
    // 记录生成时间
    $generatedTime = date("Y-m-d H:i:s", time());
    $userCode = array(
        'src' => $src,
        'name' => $name,
        'desc' => $desc,
        'type' => $type,
        'get_type' => $get_type,
        'top' => $top,
        'transform' => $transform,
        'id' => $id,
        'ad' => $ad,
        'time' => $generatedTime
    );
    update_avatarbox_isname($user_id, $userCode);
    update_user_meta($user_id, 'user_avatarbox', $userCode);
    update_avatarbox_or_add_array($user_id, $userCode);

    zib_send_json_success(array('msg' =>  '佩戴成功', 'hide_modal' => true, 'reload' => true));
}

add_action('wp_ajax_avatarbox_user_add_update', 'avatarbox_user_add_update');



function user_avatarbox_list($user_id)
{
    $array = get_user_meta($user_id, 'user_avatarbox_list', true);
    $avatarbox = get_user_meta($user_id, 'user_avatarbox', true);
    if ($array) {
        $is_has_html = '';
        $medal_details_html = '';
       $thumbnail = avatar('thumbnail');
        if($thumbnail){
               $thumbnail =  ' src="'.$thumbnail.' " data-';
        }else{
               $thumbnail =  '';
        }
            $is = '';
        foreach ($array  as $v => $key) {
            if ($avatarbox) {
            $is = $avatarbox['name'] == $key['name'] ? 'style="border: 1px solid #2c538f;"' : '';
            $relative = $avatarbox['name'] == $key['name'] ? 'relative-h' : '';
            $badge = $avatarbox['name'] == $key['name'] ? ' <badge class="img-badge badg badg-sm  jb-red msg-icon" style="top: 0px;">佩戴中</badge>' : '';
            
            
            }

            $is_has_html .= '<div class="medal-list '.$relative.'"><a new="new" data-class="modal-mini" mobile-bottom="true" data-height="201" data-remote="' . add_query_arg(['action' => 'user_avatarbox_list_modal', 'id' => $key['id'], 'ad' => $key['ad'],  'v' => $v,'p' => $key], admin_url('admin-ajax.php')) . '" class="single-medal-info" href="javascript:;" data-toggle="RefreshModal">
           '.$badge.'
                <div class="medal-card is-has" ' . $is . '>
                <img class="img-icon lazyload medal-icon" '.$thumbnail.'src="' . $key['src'] . '">
                <div class="muted-color px12 mt6">' . $key['name'] . '</div>
                </div></a></div>';
        }
        $medal_details_html .= '<div class="medal-cat-box muted-box mb20">';
        $medal_details_html .= '<div class="mb6"><b class="medal-cat-name">我的挂件</b></div>';
        $medal_details_html .= '<div class="medal-list-box flex at hh">' . $is_has_html  . '</div>';
        $medal_details_html .= '</div>';
        $tab_content = '<div class="mini-scrollbar scroll-y max-vh5">' . $medal_details_html . '</div>';
    } else {
        $tab_content  = zib_get_null('暂无挂件', 60, 'null.svg', '', 0, 170);
    }
    return $tab_content;
}

// delete_user_meta('17','user_avatarbox_list');
function update_avatarbox_or_add_array($user_id, $new_elements)
{
    $array = get_user_meta($user_id, 'user_avatarbox_list', true);

    if (empty($array)) {
        // 如果数组为空，则直接储存新的数据
        $array = array();
        $array[]  = $new_elements;
    } else {
        // 如果数组不为空，则将新数据追加到现有数据后面
        $array[] = $new_elements;
    }
    $is = update_avatarbox_array_isname($user_id, $new_elements);
    $mate = '';
    if ($is !== false) {
        $mate =  update_user_meta($user_id, 'user_avatarbox_list', $array);
    }
    return $mate;
}

function  update_avatarbox_array_isname($user_id, $new_elements)
{
    $array = get_user_meta($user_id, 'user_avatarbox_list', true);
    if($array){
    foreach ($array  as $v => $key) {
        if ($key['name'] === $new_elements['name']) {
            return false;
            break;  //
        }
    }
    return true;
    }
    
    return true;
}
function  update_avatarbox_array_names($user_id, $name)
{
    $array = get_user_meta($user_id, 'user_avatarbox_list', true);
    if($array){
            foreach ($array  as $v => $key) {
        if ($key['name'] === $new_elements) {
            return false;
            break;  //
        }
    }
    
    return true;
    }else{
        
    return true;
    }

}
//重新当前挂件相同 
function update_avatarbox_isname($user_id, $new_elements)
{
    $array = get_user_meta($user_id, 'user_avatarbox', true);
    if ($array) {
      if ($array['name'] === $new_elements['name']) {
        zib_send_json_error($new_elements['name'] . '已经佩戴了' . $new_elements['name']);
    }
    }
  
}
//判断当前挂件于查看挂件相同
function delete_avatarbox_user($user_id,$name)
{
    
    $array = get_user_meta($user_id,'user_avatarbox', true);
    if ($array) {
        if ($array['name'] === $name) {
            return true;
        }else{
    return false;
    }

}
    
}

//删除当前挂件信息
function delete_user_avatarbox()
{
    $user_id = get_current_user_id();
    $mate =   delete_user_meta($user_id, 'user_avatarbox');
    if ($mate) {
        zib_send_json_success(array('msg' =>  '取消成功', 'hide_modal' => true, 'reload' => true));
    } else {
        zib_send_json_error(array('msg' =>  '取消失败', 'hide_modal' => true, 'reload' => false));
    }
}

add_action('wp_ajax_delete_user_avatarbox', 'delete_user_avatarbox');


function user_avatarbox_list_modal()
{
    $user_id = get_current_user_id();
    $p = $_REQUEST['p'];
    $id = $_REQUEST['id'];
    $ad = $_REQUEST['ad'];
    $v = $_REQUEST['v'];
    echo get_avatarbox_user_modal($user_id,$p, $id, $ad,$v);
    exit;
}

add_action('wp_ajax_user_avatarbox_list_modal', 'user_avatarbox_list_modal');

function get_avatarbox_user_modal($user_id, $p, $id, $ad,$v)
{
 $name  = $p['name'];
 $input = '';
 $delete =  delete_avatarbox_user($user_id,$name);
    $button = '
<button class="but jb-blue radius btn-block padding-lg wp-ajax-submit" style="overflow: hidden; position: relative;"><i class="fa fa-check" aria-hidden="true"></i>佩戴挂件
  </button>';
    if ($delete == false) {
         $array = get_user_meta($user_id, 'user_avatarbox_list', true);
        $input ='
    <input type="hidden" name="action" value="avatarbox_user_add_update">
    <input type="hidden" name="id" value="' . $id . '">
    <input type="hidden" name="ad" value="' . $ad . '">
'.$button.'
    ';
    } else {
        $input =' 
    <input type="hidden" name="action" value="delete_user_avatarbox">
    <button class="but jb-red radius btn-block padding-lg wp-ajax-submit" style="overflow: hidden; position: relative;">取消佩戴
    </button>
    ';
    }
    $height = avatar('height');
   $lwidth =  avatar('width');
    if ($lwidth) {
       $h =  'style="height:'.$height.'px;width: '.$lwidth.'px;"';
    }else{
         $h =  'style="height: 80px;width: 80px;"';
    }
    
    $html = '<button class="close" data-dismiss="modal">' . zib_get_svg('close', null, 'ic-close') . '</button><form>
                <div class="max-vh5 box-body medal-card mb20 mt20 medal-single-card relative">
                <div class="relative img-icon avatarboxs" >' . zib_get_avatar_avatarbox($user_id, '', $p,true,true,$h) . '</div> 
                </div>    
                <div class="box-body">
                <div class="text-center">
                <div class="mt20">' . $p['name'] . '</div>
                <div class="muted-2-color px12 mt10">' . $p['desc'] . '</div>
                </div></div>
         ' . $input . '
                </form>';

    return $html;
}

/**
 * @description: 个人主页展示头像框
 * @param {*} $desc
 * @param {*} $user_id
 * @return {*}
 */
function avatarbox_author_header_identity_filter_medal($desc, $user_id)
{
    $get_id = get_current_user_id();
    global $wp_query;
    $curauth = $wp_query->get_queried_object();

    if (empty($curauth->ID)) {
        return;
    }
    $author_id = $curauth->ID;
    // 确定展示的名称和动作
    if ($author_id === $get_id) {
        $name = '我的挂件';
        $action = 'avatarbox_author_admin';
    } else {
        $name = '他的挂件';
        $action = 'avatarbox_author_list';
    }
    // 检查是否为超级管理员
    if (is_super_admin()) {
        $action = 'avatarbox_author_admin';
    }
    // 创建按钮的 HTML
    $medal = '<button class="jb-cyan but nowave badg badg-sm moderator-bagd mr6" 
                data-class="full-sm"  
                data-height="240" 
                data-remote="' . add_query_arg(['action' => $action, 'user' => $author_id], admin_url('admin-ajax.php')) . '" 
                href="javascript:;" 
                aria-hidden="true"  
                data-toggle="RefreshModal">' . $name . '</button>';

    return $medal.$desc;
}

add_filter('author_header_identity', 'avatarbox_author_header_identity_filter_medal', 10, 2);
    
function avatarbox_author_list_modal($user_id){
    $avatar_img   = zib_get_avatar_box($user_id, 'avatar-img', false, false);
    $header = '<style>
.medal-list {
   border: 1px solid #e5e9ef;
}
.medal-list-box .medal-list:hover {
  border: 1px solid #0400ff;
}
</style>';
    $header     .= avatarbox_get_modal_colorful_header('c-blue', $avatar_img, '');
    
    $array = get_user_meta($user_id, 'user_avatarbox_list', true);
    $avatarbox = get_user_meta($user_id, 'user_avatarbox', true);
    $imgurl = zib_get_avatar_img($user_id);
    if ($array) {
        $is_has_html = '';
        $medal_details_html = '';
       $thumbnail = avatar('thumbnail');
if($thumbnail){
       $thumbnail =  ' src="'.$thumbnail.' " data-';
}else{
       $thumbnail =  '';
}
$is = '';
        foreach ($array  as $v => $key) {
            if ($avatarbox) {
            $is = $avatarbox['name'] == $key['name'] ? 'style="border: 1px solid #2c538f;"' : '';
            
            }
            $is_has_html .= '<div class="medal-list">
                <div class="medal-card is-has" ' . $is . '>
                <img class="img-icon lazyload medal-icon alone-imgbox-img" '.$thumbnail.'src="' . $key['src'] . '"  >  
                <div class="muted-color px12 mt6">' . $key['name'] . '</div>
                </div></div>';
        }
        $medal_details_html .= '<div class="medal-cat-box muted-box mb20">';
        $medal_details_html .= '<div class="mb6"><b class="medal-cat-name">头像框</b></div>';
        $medal_details_html .= '<div class="medal-list-box flex at hh">' . $is_has_html . '</div>';
        $medal_details_html .= '</div>';
         }
    
    else {
           $medal_details_html  = zib_get_null('暂无挂件', 60, 'null.svg', '', 0, 170);
    }
    $avatararray = user_avatarbox_list($user_id);
    $tab_content = '';
    $tab_content .= '<div class="mini-scrollbar scroll-y max-vh5">' . $medal_details_html . '</div>';
    $html = '<div class="padding-w10 nop-sm"><div class="tab-content">' . $tab_content . '</div></div>';
    return $header . $html;
}

    function avatarbox_author_list()
{
   @$id = $_REQUEST['user'];
    return avatarbox::avatarbox_author_list_modal($id);
    exit;
}
add_action('wp_ajax_avatarbox_author_list', 'avatarbox_author_list');

add_action('wp_ajax_nopriv_avatarbox_author_list', 'avatarbox_author_list');

    function avatarbox_author_admin()
{
   @$id = $_REQUEST['user'];
    return avatarbox::avatarbox_author_list_admin($id);
    exit;
}
add_action('wp_ajax_avatarbox_author_admin', 'avatarbox_author_admin');

    
function avatarbox_author_list_admin($user_id){
    $avatar_img   = zib_get_avatar_box($user_id, 'avatar-img', false, false);
    $header = '<style>
.medal-list {
   border: 1px solid #e5e9ef;
}
.medal-list-box .medal-list:hover {
  border: 1px solid #0400ff;
}
</style>';
    $header     .= avatarbox_get_modal_colorful_header('c-blue', $avatar_img, '');
    $list = avatar('avatar');
    $medal_details_html = '';
    $is_has_html = '';
    if ($list) {
$thumbnail = avatar('thumbnail');
if($thumbnail){
       $thumbnail =  ' src="'.$thumbnail.' " data-';
}else{
       $thumbnail =  '';
}

    $array = get_user_meta($user_id, 'user_avatarbox_list', true);
    $avatarbox = get_user_meta($user_id, 'user_avatarbox', true);
    if (!$avatarbox) {
      $relative  == '';
      $badge  == '';
    }
    // var_dump($array);
    foreach ($list  as $i => $v) {
        $k = $v['name'];
         @$is_has_html = '';
          
         if ($v['if']) {
        foreach ($v['pet']  as $s => $p) {
             if (!empty($avatarbox['name'])) {
             $relative = $avatarbox['name'] == $p['name'] ? 'relative-h' : '';
            $badge = $avatarbox['name'] == $p['name'] ? ' <badge class="img-badge badg badg-sm  jb-red msg-icon" style="top: 0px;">佩戴中</badge>' : '';
             }
     $stle = avatarbox_is_get_userlist($p['name'],$user_id);
     $stles = $stle ? $stle:'style="opacity: 0.5; background: rgb(0 0 0 / 10%);"';
     $action = $stle ? '1':'2';
            $is_has_html .= '<div class="medal-list '.$relative.' "><a new="new" data-class="modal-mini" mobile-bottom="true" data-height="201" data-remote="' . add_query_arg(['action' => 'admin_avatarbox_fuyu_modal', 'id' => $i, 'ad' => $s,'user'=>$user_id,'is'=>$action], admin_url('admin-ajax.php')) . '" class="single-medal-info" href="javascript:;" data-toggle="RefreshModal">
            '.$badge.'
                <div class="medal-card is-has " '.$stles.'>
                <img class="img-icon medal-icon" '.$thumbnail.'src="' . $p['src'] . '">
                <div class="muted-color px12 mt6">' . $p['name'] .'</div>
                </div></a></div>';
        }
        $medal_details_html .= '<div class="medal-cat-box muted-box mb20">';
        $medal_details_html .= '<div class="mb6"><b class="medal-cat-name">'.$k . '</b></div>';
        $medal_details_html .= '<div class="medal-list-box flex at hh">' . $is_has_html . '</div>';
        $medal_details_html .= '</div>';
         }
    }
         } else {
           $medal_details_html  = zib_get_null('暂无挂件', 60, 'null.svg', '', 0, 170);
    }
    $avatararray = user_avatarbox_list($user_id);
    $tab_content = '';
    $tab_content .= '<div class="mini-scrollbar scroll-y max-vh5">' . $medal_details_html . '</div>';
    $html = '<div class="padding-w10 nop-sm"><div class="tab-content">' . $tab_content . '</div></div>';
    return $header . $html;
}



function admin_avatarbox_fuyu_modal()
{
    $user_id =  $_REQUEST['user'];
    $id = $_REQUEST['id'];
    $ad = $_REQUEST['ad'];
    $is = $_REQUEST['is'];
    if($is == 1){
    echo Retract_avatarbox_fuyu_modal($user_id,$id,$ad);
    exit;
    }
    echo get_avatarbox_fuyu_modal($user_id,$id,$ad);
    exit;
}

add_action('wp_ajax_admin_avatarbox_fuyu_modal', 'admin_avatarbox_fuyu_modal');


function Retract_avatarbox_fuyu_modal($user_id, $id, $ad)
{

 $mate = avatar('avatar')[$id]['pet'][$ad];
 $name = $mate['name'];
 $input = '';
     $button = '
   <button class="but jb-red radius btn-block padding-lg wp-ajax-submit" style="overflow: hidden; position: relative;"><i class="fa fa-check" aria-hidden="true"></i>回收头像框
    </button>';
        $input ='
    <input type="hidden" name="action" value="avatarbox_admin_add_Retract">
    <input type="hidden" name="user" value="' . $user_id . '">
    <input type="hidden" name="id" value="' . $id . '">
    <input type="hidden" name="ad" value="' . $ad . '">
  '.$button.'
    ';
   $height = avatar('lheight');
   $lwidth =  avatar('lwidth');
        if ($mate['type'] == '0') {
         $style = ' border-radius: 0px;';
        } else {
          $style =  '';
        }
    if ($height) {
       $h =  'style="height:'.$height.'px;width: '.$lwidth.'px;"';
    }else{
         $h =  'style="height: 80px;width: 90px;"';
    }
    $html = '<button class="close" data-dismiss="modal">' . zib_get_svg('close', null, 'ic-close') . '</button><form>
                <div class="max-vh5 box-body medal-card mb20 mt20 medal-single-card relative">
                    <script type="text/javascript">
    $(function () {
 $(".avatarboxs .avatar").attr("style", "height: 120px; '.$style.'");})
    </script>
                <div class="relative img-icon avatarboxs" >' . zib_get_avatar_avatarbox($user_id, '', $mate,true,true,$h) . '</div> 
                </div>    
                <div class="box-body">
                <div class="text-center">
                <div class="mt20">' .$name . '</div>
                <div class="muted-2-color px12 mt10">' . $mate['desc'] . '</div>
                </div></div>
         ' . $input . '
                </form>';
    return $html;
}


function get_avatarbox_fuyu_modal($user_id, $id, $ad)
{

 $mate = avatar('avatar')[$id]['pet'][$ad];
 $name = $mate['name'];
 $input = '';
     $button = '
   <button class="but jb-blue radius btn-block padding-lg wp-ajax-submit" style="overflow: hidden; position: relative;"><i class="fa fa-check" aria-hidden="true"></i>赋予
    </button>';
        $input ='
    <input type="hidden" name="action" value="avatarbox_admin_add_update">
    <input type="hidden" name="user" value="' . $user_id . '">
    <input type="hidden" name="id" value="' . $id . '">
    <input type="hidden" name="ad" value="' . $ad . '">
  '.$button.'
    ';
   $height = avatar('lheight');
   $lwidth =  avatar('lwidth');
        if ($mate['type'] == '0') {
         $style = ' border-radius: 0px;';
        } else {
          $style =  '';
        }
    if ($height) {
       $h =  'style="height:'.$height.'px;width: '.$lwidth.'px;"';
    }else{
         $h =  'style="height: 80px;width: 90px;"';
    }
    $html = '<button class="close" data-dismiss="modal">' . zib_get_svg('close', null, 'ic-close') . '</button><form>
                <div class="max-vh5 box-body medal-card mb20 mt20 medal-single-card relative">
                    <script type="text/javascript">
    $(function () {
 $(".avatarboxs .avatar").attr("style", "height: 120px; '.$style.'");})
    </script>
                <div class="relative img-icon avatarboxs" >' . zib_get_avatar_avatarbox($user_id, '', $mate,true,true,$h) . '</div> 
                </div>    
                <div class="box-body">
                <div class="text-center">
                <div class="mt20">' .$name . '</div>
                <div class="muted-2-color px12 mt10">' . $mate['desc'] . '</div>
                </div></div>
         ' . $input . '
                </form>';
    return $html;
}


function avatarbox_admin_add_Retract()
{
      if (!is_super_admin()) {
     zib_send_json_error('你TM是管理员吗？');
     }
    global $wpdb;
    $user_id = $_REQUEST['user'];
    $id = $_REQUEST['id'];
    $ad = $_REQUEST['ad'];
     $mate = avatar('avatar')[$id]['pet'][$ad];
     $Retract =  unset_Retract_user_list($user_id,$id,$ad);
      if ($Retract) {
    zib_send_json_success(array('msg' =>  '头像框回收成功', 'hide_modal' => true, 'reload' => true));
      } else {
      
    zib_send_json_success(array('msg' =>  '头像框回收失败', 'hide_modal' => true, 'reload' => true));
      }
      
}

add_action('wp_ajax_avatarbox_admin_add_Retract', 'avatarbox_admin_add_Retract');



function avatarbox_admin_add_update()
{
      if (!is_super_admin()) {
     zib_send_json_error('你TM是管理员吗？');
     }
    global $wpdb;
    $user_id = $_REQUEST['user'];
    $id = $_REQUEST['id'];
    $ad = $_REQUEST['ad'];
     $mate = avatar('avatar')[$id]['pet'][$ad];
    $src = $mate['src'];
    $name = $mate['name'];
    $type = $mate['type'];
    $get_type = $mate['get_type'];
    $top = $mate['top'];
    $desc = $mate['desc'];
    $transform = $mate['transform'];
    // 记录生成时间
    $generatedTime = date("Y-m-d H:i:s", time());
    $userCode = array(
        'src' => $src,
        'name' => $name,
        'desc' => $desc,
        'type' => $type,
        'get_type' => $get_type,
        'top' => $top,
        'transform' => $transform,
        'id' => $id,
        'ad' => $ad,
        'time' => $generatedTime
    );
    update_avatarbox_isname($user_id, $userCode);
    update_user_meta($user_id, 'user_avatarbox', $userCode);
    update_avatarbox_or_add_array($user_id, $userCode);
    zib_send_json_success(array('msg' =>  '赋予成功', 'hide_modal' => true, 'reload' => true));
}

add_action('wp_ajax_avatarbox_admin_add_update', 'avatarbox_admin_add_update');


function zib_get_avatar_img($avatar)
{
    $user_id = zib_get_user_id($id_or_email);

    $custom_avatar = $user_id ? get_user_meta($user_id, 'custom_avatar', true) : '';
    $alt           = $user_id ? get_the_author_meta('nickname', $user_id) . '的头像' . zib_get_delimiter_blog_name() : '头像';

    $avatar = $custom_avatar ? $custom_avatar : zib_default_avatar();

    //优化百度头像地址
    $avatar = str_replace('tb.himg.baidu.com', 'himg.bdimg.com', $avatar);
    $avatar = preg_replace("/^(https:|http:)/", "", $avatar);

    return  home_url($avatar);
}


    function load_custom_script() {
    // 检查当前页面是否是指定的作者页面
    if (is_author()) {
        // 加载 'custom-script' 脚本
        wp_enqueue_script('custom-script', avatarbox . 'imge/imgbox.js', array(), '1.0.0', true);
    }
}
add_action( 'wp_enqueue_scripts', 'load_custom_script',999);