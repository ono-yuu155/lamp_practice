<?php

//商品を購入結果ページ

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

//ユーザーのカート情報を取得
$carts = get_user_carts($db, $user['user_id']);

//関数がTRUEだったら商品が購入されカートから商品が購入されるため削除される
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
  redirect_to(CART_URL);
}

//カートに入っている商品の値段の合計
$total_price = sum_carts($carts);

include_once '../view/finish_view.php';