<?php

//カートの商品の個数を変更する処理

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

//postで送られきた値を変数に格納
$cart_id = get_post('cart_id');
$amount = get_post('amount');


//POSTから送られてきたトークンがセッションに保存されているトークンと等しいかを調べる関数
//falseだったときにはエラー分を表示しリダイレクトでログインページに戻す
if (is_valid_csrf_token(get_post('csrf_token')) === false) {
  set_error('アクセスが正しくありません');
  redirect_to(LOGIN_URL);
}

//指定した商品の購入数を変更する処理
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
} else {
  set_error('購入数の更新に失敗しました。');
}

redirect_to(CART_URL);