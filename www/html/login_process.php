<?php

//ログイン判別ページ

require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

/**
 * セッションに保存されている情報があれば商品一覧ページにリダイレクト???
 */
if(is_logined() === true){
  redirect_to(HOME_URL);
}

/**
 * postで送られてきたものを判別して変数に格納
 */
$name = get_post('name');
$password = get_post('password');


$db = get_db_connect();


$user = login_as($db, $name, $password);

//ユーザー情報がfalseの場合エラー文
if( $user === false){
  //エラーメッセージをセッション変数に格納
  set_error('ログインに失敗しました。');
  //ログインページにリダイレクト
  redirect_to(LOGIN_URL);
}
//それ以外のときはログインしましたとメッセージをセッションに格納
set_message('ログインしました。');
//そのユーザーのタイプが１(管理者)だった場合は管理ページにリダイレクト
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}

//POSTから送られてきたトークンがセッションに保存されているトークンと等しいかを調べる関数
//falseだったときにはエラー分を表示しリダイレクトでログインページに戻す
if (is_valid_csrf_token(get_post('csrf_token')) === false) {
  set_error('アクセスが正しくありません');
  redirect_to(LOGIN_URL);
}

//タイプ２(一般ユーザー)だった場合は商品一覧ページにリダイレクト
redirect_to(HOME_URL);