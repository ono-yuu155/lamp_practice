<?php

//購入履歴のコントローラー

require_once '../conf/const.php';

require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';
session_start();

if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

$user_history = user_history($db, $user['user_id']);

$order_number = get_post('order_number');

include_once VIEW_PATH. 'history_view.php';