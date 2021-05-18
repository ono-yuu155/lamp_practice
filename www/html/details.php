<?php

//購入明細のコントローラー

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

$user_history = user_history($db, $user['user_id']);

$order_number = get_post('order_number');
$user_details = user_details($db, $order_number);

include_once VIEW_PATH. 'details_view.php';