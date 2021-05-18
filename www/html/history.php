<?php

//購入履歴のコントローラー

require_once '../conf/const.php';

require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'details.php';
require_once MODEL_PATH . 'history.php';
session_start();

if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

//トークンの生成
$token = get_csrf_token();

//一般ユーザーだった場合は自分の購入履歴、明細のみを表示
if (is_admin($user) === false) {
    $user_history = user_history($db, $user['user_id']);
}else {
//管理者ユーザーの場合は全ユーザーの情報を取得
    $admin_history = admin_history($db);
}

$order_number = get_post('order_number');

include_once VIEW_PATH. 'history_view.php';