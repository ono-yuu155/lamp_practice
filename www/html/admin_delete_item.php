<?php

//商品削除の処理

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);


//ユーザータイプが管理者ではない時ログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//postから送られてくる値を変数に格納
$item_id = get_post('item_id');


//POSTから送られてきたトークンがセッションに保存されているトークンと等しいかを調べる関数
//falseだったときにはエラー分を表示しリダイレクトでログインページに戻す
if (is_valid_csrf_token(get_post('csrf_token')) === false) {
  set_error('アクセスが正しくありません');
  redirect_to(LOGIN_URL);
}

//登録した商品を削除する関数
if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
} else {
  set_error('商品削除に失敗しました。');
}



redirect_to(ADMIN_URL);