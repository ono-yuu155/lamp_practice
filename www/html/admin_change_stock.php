<?php

//在庫数変更の処理

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

//セッション処理
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

//ユーザータイプが管理者ではない時ログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//postから送られてきた値を変数に格納
$item_id = get_post('item_id');
$stock = get_post('stock');

//在庫数変更の処理
if(update_item_stock($db, $item_id, $stock)){
  print var_dump($stock);
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}

redirect_to(ADMIN_URL);